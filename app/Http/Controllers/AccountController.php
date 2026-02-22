<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    // 1. Hiển thị danh sách tài khoản
    public function index()
    {
        // Đổi auth()->id() thành Auth::id()
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
        return view('admin.accounts.index', compact('users'));
    }

    // 2. Hiển thị form thêm mới
    public function create()
    {
        return view('admin.accounts.create');
    }

    // 3. Xử lý lưu tài khoản mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:0,1,2',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Phải mã hóa mật khẩu trước khi lưu
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Đã cấp tài khoản thành công!');
    }

    // 4. Hiển thị form sửa thông tin
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.accounts.edit', compact('user'));
    }

    // 5. Xử lý cập nhật thông tin
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Bỏ qua check trùng email với chính id của tài khoản đang sửa
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:0,1,2',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        // Nếu Admin có gõ mật khẩu mới vào ô thì mới tiến hành đổi mật khẩu
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.accounts.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    // 6. Xóa tài khoản
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.accounts.index')->with('success', 'Đã xóa tài khoản!');
    }
    
    // ====================================================
    // 7. XỬ LÝ ĐỔI MẬT KHẨU CÁ NHÂN (TỪ MENU AVATAR)
    // ====================================================
    
    // Hiển thị form đổi mật khẩu
    public function changePasswordForm()
    {
        return view('admin.profile.change_password');
    }

    // Xử lý lưu mật khẩu mới
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // 'confirmed' tự động check khớp với ô new_password_confirmation
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác!');
        }

        // Cập nhật mật khẩu mới
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}