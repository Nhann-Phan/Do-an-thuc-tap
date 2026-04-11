<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Cart;
use Illuminate\Support\Facades\Cache; // Required for OTP caching
use Illuminate\Support\Facades\Mail;  // Required for sending Mail
use App\Mail\RegisterOtpMail;         // Required to use the Mailable

class AuthController extends Controller
{
    // 0. Gửi mã OTP (AJAX gọi vào đây)
    public function sendOtp(Request $request)
    {
        // Kiểm tra xem user có nhập email chưa, và email có bị trùng trong DB không
        $request->validate([
            'email' => 'required|email|unique:users,email'
        ]);

        // 1. Tạo mã 6 số ngẫu nhiên
        $otp = rand(100000, 999999);
        
        // 2. Lưu vào Cache 5 phút (khóa theo email)
        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(5));

        // 3. Nhét OTP vào Bức Thư và Gửi đi
        Mail::to($request->email)->send(new RegisterOtpMail($otp));

        return response()->json(['success' => true, 'message' => 'Đã gửi mã OTP thành công!']);
    }

    // 1. Hiển thị form đăng nhập
    public function showLogin() 
    {
        if (Auth::check()) {
            return Auth::user()->role == 2 ? redirect()->route('home') : redirect()->intended('admin');
        }
        
        return view('clients.auth.login'); 
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.'])->withInput();
            }

            // ================================================================
            // BẮT ĐẦU ĐỒNG BỘ GIỎ HÀNG TỪ SESSION VÀO DATABASE (KHI LOGIN)
            // ================================================================
            $sessionCart = session()->get('cart', []);
            if (count($sessionCart) > 0) {
                foreach ($sessionCart as $item) {
                    $existingCart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $item['product_id'])
                        ->where('variant_id', $item['variant_id'] ?? null)
                        ->first();

                    if ($existingCart) {
                        // Nếu đã có trong DB thì cộng dồn số lượng
                        $existingCart->quantity += $item['quantity'];
                        $existingCart->save();
                    } else {
                        // Nếu chưa có thì tạo mới
                        Cart::create([
                            'user_id' => Auth::id(),
                            'product_id' => $item['product_id'],
                            'variant_id' => $item['variant_id'] ?? null,
                            'quantity' => $item['quantity'],
                        ]);
                    }
                }
                // Đồng bộ xong thì xóa sạch Session cũ đi
                session()->forget('cart');
            }
            // ================================================================

            // PHÂN LUỒNG TÀI KHOẢN
            if ($user->role == 0 || $user->role == 1) {
                return redirect()->intended('admin');
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng!'])->withInput();
    }

    // 3. Hiển thị form đăng ký
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('clients.auth.register'); 
    }

    // 4. Xử lý đăng ký Khách hàng
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed', 
            'otp' => 'required' 
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được đăng ký, vui lòng dùng email khác.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'otp.required' => 'Vui lòng nhập mã xác nhận (OTP).'
        ]);

        // KIỂM TRA OTP TỪ CACHE
        $cachedOtp = Cache::get('otp_' . $request->email);
        
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'Mã xác nhận (OTP) không đúng hoặc đã hết hạn!'])->withInput();
        }

        // Tạo User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
            'role' => 2, 
            'is_active' => 1,
        ]);

        // Xóa OTP khỏi Cache sau khi đăng ký thành công
        Cache::forget('otp_' . $request->email);

        Auth::login($user);

        // ================================================================
        // BẮT ĐẦU ĐỒNG BỘ GIỎ HÀNG (KHI VỪA ĐĂNG KÝ XONG)
        // ================================================================
        $sessionCart = session()->get('cart', []);
        if (count($sessionCart) > 0) {
            foreach ($sessionCart as $item) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                ]);
            }
            session()->forget('cart'); 
        }
        // ================================================================

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');
    }

    // 5. Đăng xuất
    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}