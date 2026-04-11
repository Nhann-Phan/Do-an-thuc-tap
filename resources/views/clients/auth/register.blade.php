@extends('layouts.client_layout')
@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-2">Đăng Ký Tài Khoản</h1>
        <p class="text-center text-gray-500 mb-8 text-sm">Trở thành thành viên để mua sắm dễ dàng hơn</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            {{-- NÂNG CẤP Ô EMAIL: CÓ NÚT GỬI OTP --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <div class="flex gap-2">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <button type="button" id="btn-send-otp" class="whitespace-nowrap px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 text-sm font-semibold transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Gửi mã
                    </button>
                </div>
                <p id="otp-message" class="text-xs font-medium mt-1 hidden"></p>
            </div>

            {{-- Ô NHẬP MÃ OTP (Mặc định ẩn, gửi mail xong mới hiện) --}}
            <div id="otp-group" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mã xác nhận (OTP) *</label>
                <input type="text" name="otp" maxlength="6" class="w-full px-4 py-2 border border-blue-300 bg-blue-50 rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Nhập 6 số gửi về email...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu *</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu *</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border rounded-xl outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition mt-4">Tạo Tài Khoản</button>
        </form>
        <div class="text-center mt-6 text-sm">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập</a>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ NÚT GỬI MÃ OTP BẰNG AJAX --}}
<script>
    document.getElementById('btn-send-otp').addEventListener('click', function() {
        let emailInput = document.getElementById('email');
        let email = emailInput.value;
        let btn = this;
        let msg = document.getElementById('otp-message');
        let otpGroup = document.getElementById('otp-group');

        // Kiểm tra xem đã nhập email chưa
        if(!email) {
            alert('Vui lòng nhập email trước!');
            emailInput.focus();
            return;
        }

        // Vô hiệu hóa nút để tránh khách bấm liên tục spam mail
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';

        // Gọi API lên Route send.otp
        fetch('{{ route("send.otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Hiện ô nhập OTP và báo màu xanh lá
                otpGroup.classList.remove('hidden');
                msg.classList.remove('hidden');
                msg.className = 'text-xs font-medium mt-1 text-green-600';
                msg.innerText = data.message;
                
                // Đếm ngược 60s
                let timeLeft = 60;
                let timer = setInterval(() => {
                    btn.innerText = `Chờ (${timeLeft}s)`;
                    timeLeft--;
                    if(timeLeft < 0) {
                        clearInterval(timer);
                        btn.disabled = false;
                        btn.innerText = 'Gửi lại mã';
                    }
                }, 1000);
            } else {
                // Lỗi (VD: Email bị trùng, nhập sai định dạng) báo màu đỏ
                btn.disabled = false;
                btn.innerText = 'Gửi mã';
                msg.classList.remove('hidden');
                msg.className = 'text-xs font-medium mt-1 text-red-600';
                msg.innerText = data.message || 'Lỗi: Email không hợp lệ hoặc đã tồn tại!';
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerText = 'Gửi mã';
            alert('Có lỗi xảy ra, vui lòng kiểm tra lại kết nối!');
        });
    });
</script>
@endsection