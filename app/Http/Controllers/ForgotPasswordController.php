<?php

namespace App\Http\Controllers; // Đã bỏ \Auth ở đây

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Show form để người dùng nhập email
    public function showLinkRequestForm() {
        return view('auth.forgot_password');
    }

    // 2. Xử lý gửi mã OTP qua Mail
    public function sendResetCodeEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'Email này không tồn tại trong hệ thống!'
        ]);

        $otp = rand(100000, 999999);

        // Lưu hoặc cập nhật mã OTP vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp, 
                'created_at' => Carbon::now()
            ]
        );

        // Gửi Mail
        Mail::raw("Mã OTP lấy lại mật khẩu của mày là: $otp. Mã có hiệu lực trong 15 phút.", function ($message) use ($request) {
            $message->to($request->email)->subject('Mã xác nhận đổi mật khẩu');
        });

        // Gửi xong thì trả về view nhập OTP kèm theo email để xử lý bước sau
        return view('auth.reset-password', ['email' => $request->email]);
    }

    // 3. Xử lý kiểm tra OTP và cập nhật mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Kiểm tra OTP
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData || Carbon::parse($resetData->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['token' => 'Mã OTP không đúng hoặc đã hết hạn!']);
        }

        // Đổi pass trong bảng users
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Xóa mã OTP sau khi dùng xong để bảo mật
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Mày đã đổi mật khẩu thành công!');
    }
}