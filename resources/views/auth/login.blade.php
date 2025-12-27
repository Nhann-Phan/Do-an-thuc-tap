<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Đăng Nhập Quản Trị</h2>
        
        <form action="/login" method="POST">
            @csrf <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded focus:outline-none focus:border-indigo-500" placeholder="admin@gmail.com" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                <input type="password" name="password" class="w-full border p-2 rounded focus:outline-none focus:border-indigo-500" placeholder="******" required>
            </div>

            @if($errors->any())
                <p class="text-red-500 text-sm italic mb-4 text-center">{{ $errors->first() }}</p>
            @endif

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded hover:bg-indigo-700 transition">
                ĐĂNG NHẬP
            </button>
        </form>
    </div>

</body>
</html>