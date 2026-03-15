@extends('layouts.admin_layout')

@section('content')

<style>
    .cke_notification_warning { display: none !important; }
    /* Toggle Switch Custom CSS */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #2563eb;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #2563eb;
    }
</style>

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
    <h3 class="text-2xl font-bold text-gray-800">
        @if($selectedCategoryId)
            @php $catName = $categories->find($selectedCategoryId)->name ?? 'Mới'; @endphp
            Bảng nhập liệu: <span class="text-blue-600">{{ $catName }}</span>
        @else
            Thêm sản phẩm mới
        @endif
    </h3>
    
    <a href="{{ $selectedCategoryId ? route('admin.category.products', $selectedCategoryId) : route('product.index_admin') }}" 
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition">
        <i class="fas fa-list mr-2"></i> Xem danh sách đã nhập
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6">
        
        {{-- ALERTS --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <div><span class="font-bold">Thành công!</span> {{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- CỘT TRÁI (Thông tin chính) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required placeholder="Nhập tên sản phẩm tiếp theo..." value="{{ old('name') }}" autofocus>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                    </div>

                    {{-- KHU VỰC NHẬP THÔNG SỐ KỸ THUẬT --}}
                    <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100">
                        <h4 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-cogs mr-2"></i> Thông số kỹ thuật
                        </h4>
                        
                        <div id="specs_container">
                            <p id="no_specs_msg" class="text-gray-500 italic text-sm">Vui lòng chọn danh mục bên phải để hiển thị ô nhập thông số.</p>

                            {{-- 1. Form CAMERA --}}
                            <div id="form_camera" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Độ phân giải]" class="px-3 py-2 border rounded" placeholder="Độ phân giải (VD: 4.0MP)">
                                <input type="text" name="specs[Tầm xa hồng ngoại]" class="px-3 py-2 border rounded" placeholder="Hồng ngoại (VD: 30m)">
                                <input type="text" name="specs[Ống kính]" class="px-3 py-2 border rounded" placeholder="Ống kính (VD: 2.8mm)">
                                <input type="text" name="specs[Chuẩn nén]" class="px-3 py-2 border rounded" placeholder="Chuẩn nén (VD: H.265+)">
                                <input type="text" name="specs[Chống nước]" class="px-3 py-2 border rounded" placeholder="Chống nước (VD: IP67)">
                                <input type="text" name="specs[Nguồn điện]" class="px-3 py-2 border rounded" placeholder="Nguồn (VD: 12V/PoE)">
                                <div class="col-span-2">
                                    <input type="text" name="specs[Tính năng]" class="w-full px-3 py-2 border rounded" placeholder="Tính năng khác (VD: Có Mic, Báo động đèn)">
                                </div>
                            </div>

                            {{-- 2. Form SWITCH --}}
                            <div id="form_switch" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Số cổng]" class="px-3 py-2 border rounded" placeholder="Số cổng (VD: 8 Port)">
                                <input type="text" name="specs[Tốc độ]" class="px-3 py-2 border rounded" placeholder="Tốc độ (VD: Gigabit 1000Mbps)">
                                <input type="text" name="specs[PoE]" class="px-3 py-2 border rounded" placeholder="PoE (VD: 8 Port PoE 120W)">
                                <input type="text" name="specs[Loại Switch]" class="px-3 py-2 border rounded" placeholder="Loại (VD: Managed / Unmanaged)">
                                <input type="text" name="specs[Vỏ]" class="px-3 py-2 border rounded" placeholder="Vỏ (VD: Kim loại)">
                                <input type="text" name="specs[Nguồn điện]" class="px-3 py-2 border rounded" placeholder="Nguồn (VD: 220V)">
                            </div>

                            {{-- 3. Form WIFI / ACCESS POINT --}}
                            <div id="form_wifi" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Chuẩn Wifi]" class="px-3 py-2 border rounded" placeholder="Chuẩn Wifi (VD: Wifi 6 AX1800)">
                                <input type="text" name="specs[Băng tần]" class="px-3 py-2 border rounded" placeholder="Băng tần (VD: Kép 2.4 & 5GHz)">
                                <input type="text" name="specs[Chịu tải]" class="px-3 py-2 border rounded" placeholder="Chịu tải (VD: 100 User)">
                                <input type="text" name="specs[Tốc độ]" class="px-3 py-2 border rounded" placeholder="Tốc độ (VD: 1800Mbps)">
                                <input type="text" name="specs[Cổng kết nối]" class="px-3 py-2 border rounded" placeholder="Cổng (VD: 1 Gigabit WAN/LAN)">
                                <input type="text" name="specs[Tính năng]" class="px-3 py-2 border rounded" placeholder="Tính năng (VD: Mesh, Roaming)">
                            </div>

                            {{-- 4. Form ĐIỆN MẶT TRỜI --}}
                            <div id="form_solar" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Công suất Pmax]" class="px-3 py-2 border rounded" placeholder="Công suất (VD: 450W)">
                                <input type="text" name="specs[Hiệu suất]" class="px-3 py-2 border rounded" placeholder="Hiệu suất (VD: 21.3%)">
                                <input type="text" name="specs[Loại Cell]" class="px-3 py-2 border rounded" placeholder="Loại Cell (VD: Mono Half-cut)">
                                <input type="text" name="specs[Kích thước]" class="px-3 py-2 border rounded" placeholder="Kích thước (VD: 2m x 1m)">
                                <input type="text" name="specs[Cân nặng]" class="px-3 py-2 border rounded" placeholder="Cân nặng (VD: 24kg)">
                                <input type="text" name="specs[Bảo hành]" class="px-3 py-2 border rounded" placeholder="Bảo hành (VD: 12 Năm)">
                            </div>

                            {{-- 5. Form ĐẦU GHI (Recorder) --}}
                            <div id="form_recorder" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Số kênh]" class="px-3 py-2 border rounded" placeholder="Số kênh (VD: 8 kênh IP)">
                                <input type="text" name="specs[Băng thông]" class="px-3 py-2 border rounded" placeholder="Băng thông (VD: 80Mbps)">
                                <input type="text" name="specs[Hỗ trợ ổ cứng]" class="px-3 py-2 border rounded" placeholder="Ổ cứng (VD: 1 SATA max 10TB)">
                                <input type="text" name="specs[Chuẩn nén]" class="px-3 py-2 border rounded" placeholder="Chuẩn nén (VD: H.265+)">
                                <input type="text" name="specs[Cổng xuất hình]" class="px-3 py-2 border rounded" placeholder="Xuất hình (VD: HDMI 4K, VGA)">
                                <input type="text" name="specs[Tính năng]" class="px-3 py-2 border rounded" placeholder="Tính năng (VD: AI Phát hiện người)">
                            </div>

                             {{-- 6. Form ROUTER (Cân bằng tải) --}}
                             <div id="form_router" class="specs-group hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="specs[Chịu tải]" class="px-3 py-2 border rounded" placeholder="Chịu tải (VD: 200 thiết bị)">
                                <input type="text" name="specs[Cổng WAN]" class="px-3 py-2 border rounded" placeholder="Cổng WAN (VD: 4 WAN Gigabit)">
                                <input type="text" name="specs[Thông lượng NAT]" class="px-3 py-2 border rounded" placeholder="Thông lượng (VD: 900Mbps)">
                                <input type="text" name="specs[RAM]" class="px-3 py-2 border rounded" placeholder="RAM (VD: 512MB DDR3)">
                                <input type="text" name="specs[VPN]" class="px-3 py-2 border rounded" placeholder="VPN (VD: IPsec, OpenVPN)">
                                <input type="text" name="specs[Tính năng]" class="px-3 py-2 border rounded" placeholder="Khác (VD: Load Balancing)">
                            </div>

                        </div>
                    </div>

                </div>
                
                {{-- CỘT PHẢI (Thông tin phụ) --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 mb-6">
                        
                        {{-- Danh mục --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_select" onchange="toggleSpecsForm()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                        {{ (isset($selectedCategoryId) && $selectedCategoryId == $cat->id) || old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Thương hiệu</label>
                            <input type="text" name="brand" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ví dụ: Dell, HP..." value="{{ old('brand') }}">
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mô tả ngắn</label>
                            <textarea name="short_description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" rows="3">{{ old('short_description') }}</textarea>
                        </div>

                        {{-- Giá bán --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Giá bán chính (VNĐ)</label>
                            <input type="number" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-700" placeholder="0" value="{{ old('price') }}">
                        </div>

                        {{-- Phiên bản giá --}}
                        <div class="bg-white border border-blue-200 rounded-lg mb-4 overflow-hidden">
                            <div class="bg-blue-50 px-3 py-2 border-b border-blue-100 flex justify-between items-center">
                                <small class="font-bold text-blue-700 uppercase flex items-center">
                                    <i class="fas fa-tags mr-1.5"></i> Các phiên bản & Tồn kho
                                </small>
                            </div>
                            <div class="p-3">
                                <div class="grid grid-cols-12 gap-2 mb-2 text-xs font-bold text-gray-500 uppercase text-center">
                                    <div class="col-span-4 text-left">Tên</div>
                                    <div class="col-span-4">Giá</div>
                                    <div class="col-span-3">Số lượng</div>
                                    <div class="col-span-1">Xóa</div>
                                </div>
                                
                                <div id="variants-container" class="space-y-2"></div>
                                
                                <button type="button" onclick="addVariant()" class="mt-2 w-full py-2 border-2 border-dashed border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 hover:border-blue-400 transition text-sm font-bold flex items-center justify-center">
                                    <i class="fas fa-plus mr-1"></i> Thêm phiên bản
                                </button>
                            </div>
                        </div>

                        {{-- Ảnh đại diện --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ảnh đại diện</label>
                            <input type="file" name="image" id="imageInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 mb-2" accept="image/*" onchange="previewImage(this)">
                            
                            <div class="p-2 border border-gray-200 bg-white rounded-lg flex items-center justify-center min-h-[150px]">
                                <img id="preview" src="#" class="max-h-[200px] rounded hidden">
                                <span id="placeholder-text" class="text-gray-400 text-xs italic">Chưa chọn ảnh</span>
                            </div>
                        </div>

                        <hr class="border-gray-200 my-4">

                        {{-- Switches --}}
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-bold text-gray-700 cursor-pointer" for="activeSwitch">Hiển thị ngay</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="activeSwitch" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-bold text-red-500 cursor-pointer" for="hotSwitch">Sản phẩm HOT</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_hot" id="hotSwitch" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </div>

                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 uppercase flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i> Lưu & Nhập tiếp
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'description', { height: 400, language: 'vi' });
    
    function previewImage(input) {
        var preview = document.getElementById('preview');
        var placeholder = document.getElementById('placeholder-text');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }

    function addVariant() {
        const container = document.getElementById('variants-container');
        const index = container.children.length; 
        
        const html = `
            <div class="grid grid-cols-12 gap-2 mb-2 items-center variant-item border-b border-gray-100 pb-2">
                <div class="col-span-4">
                    <input type="text" name="variants[${index}][name]" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Tên (VD: Đỏ)" required>
                </div>
                <div class="col-span-4">
                    <input type="number" name="variants[${index}][price]" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Giá" required>
                </div>
                <div class="col-span-3">
                    <input type="number" name="variants[${index}][quantity]" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none text-center" placeholder="SL" value="0">
                </div>
                <div class="col-span-1 text-right">
                    <button type="button" class="text-gray-400 hover:text-red-500 transition p-1" onclick="this.closest('.variant-item').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    // 🔥 SCRIPT TỰ ĐỘNG HIỂN THỊ FORM NHẬP LIỆU THEO DANH MỤC 🔥
    function toggleSpecsForm() {
        // Lấy text của option đang chọn (Để so sánh từ khóa)
        let select = document.getElementById('category_select');
        let selectedText = select.options[select.selectedIndex].text.toLowerCase();

        // Ẩn tất cả form trước
        document.querySelectorAll('.specs-group').forEach(el => el.classList.add('hidden'));
        document.getElementById('no_specs_msg').classList.add('hidden');

        // Logic check từ khóa để hiện form tương ứng
        let found = false;

        if (selectedText.includes('camera')) {
            document.getElementById('form_camera').classList.remove('hidden'); found = true;
        } 
        else if (selectedText.includes('switch')) {
            document.getElementById('form_switch').classList.remove('hidden'); found = true;
        } 
        else if (selectedText.includes('wifi') || selectedText.includes('access point') || selectedText.includes('ap')) {
            document.getElementById('form_wifi').classList.remove('hidden'); found = true;
        }
        else if (selectedText.includes('mặt trời') || selectedText.includes('solar') || selectedText.includes('pin')) {
            document.getElementById('form_solar').classList.remove('hidden'); found = true;
        }
        else if (selectedText.includes('đầu ghi') || selectedText.includes('ghi hình') || selectedText.includes('nvr') || selectedText.includes('dvr')) {
            document.getElementById('form_recorder').classList.remove('hidden'); found = true;
        }
        else if (selectedText.includes('router') || selectedText.includes('cân bằng tải')) {
            document.getElementById('form_router').classList.remove('hidden'); found = true;
        }

        // Nếu không khớp từ khóa nào thì hiện thông báo
        if (!found) {
            document.getElementById('no_specs_msg').classList.remove('hidden');
            document.getElementById('no_specs_msg').innerText = "Danh mục này không yêu cầu thông số kỹ thuật đặc biệt.";
        }
    }

    // Chạy 1 lần khi load trang (để lỡ form đang edit thì nó hiện sẵn)
    document.addEventListener("DOMContentLoaded", function() {
        toggleSpecsForm();
    });
</script>
@endsection