<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin GPM</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Font Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border border-gray-100">
        
        {{-- Logo / Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4 text-2xl">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Quản Trị Hệ Thống</h2>
            <p class="text-sm text-slate-400 mt-1">Vui lòng đăng nhập để tiếp tục</p>
        </div>
        
        <form action="/login" method="POST">
            @csrf 
            
            {{-- Thông báo lỗi --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm flex items-center mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Input Email --}}
            <div class="mb-5">
                <label class="block text-slate-600 text-sm font-bold mb-2 ml-1">Email truy cập</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm text-gray-700 placeholder-gray-400" 
                           placeholder="admin@gmail.com" required autofocus>
                </div>
            </div>
            
            {{-- Input Password --}}
            <div class="mb-8">
                <label class="block text-slate-600 text-sm font-bold mb-2 ml-1">Mật khẩu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm text-gray-700 placeholder-gray-400" 
                           placeholder="••••••••" required>
                </div>
            </div>

            {{-- Button --}}
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition duration-200 shadow-lg shadow-blue-500/30 transform active:scale-95 uppercase text-sm tracking-wider">
                Đăng nhập
            </button>
            
            <div class="text-center mt-6">
                <a href="/" class="text-sm text-slate-400 hover:text-blue-600 transition flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-1"></i> Quay về trang chủ
                </a>
            </div>
        </form>
    </div>

</body>
</html>