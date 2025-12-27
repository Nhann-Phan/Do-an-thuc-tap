@extends('layouts.admin_layout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    /* Hiệu ứng Modal */
    #editModal { transition: opacity 0.3s ease; }
    #editModal.hidden { opacity: 0; pointer-events: none; }
    #editModal:not(.hidden) { opacity: 1; pointer-events: auto; }
</style>

<div class="font-sans relative">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Thư viện ảnh dự án</h3>
            <p class="text-sm text-gray-500">Quản lý các hình ảnh thực tế hiển thị trên trang chủ</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden sticky top-4">
                <div class="bg-blue-600 px-4 py-3 border-b border-blue-500">
                    <h4 class="font-bold text-white flex items-center">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Đăng ảnh mới
                    </h4>
                </div>
                <div class="p-5">
                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Chọn ảnh</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition" required accept="image/*">
                            <p class="text-xs text-gray-400 mt-1">Định dạng: jpg, png. Tối đa 2MB.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả / Tên dự án</label>
                            <input type="text" name="caption" class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 transition" placeholder="Ví dụ: Lắp đặt Camera tại Châu Đốc...">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded shadow transition flex items-center justify-center transform active:scale-95">
                            <i class="fas fa-upload mr-2"></i> TẢI LÊN NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h4 class="font-bold text-gray-700 flex items-center">
                        <i class="fas fa-images mr-2 text-blue-500"></i> Danh sách ảnh đã đăng
                    </h4>
                </div>
                
                <div class="p-5">
                    @if(isset($images) && count($images) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($images as $img)
                            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition bg-white flex flex-col">
                                <div class="h-40 overflow-hidden bg-gray-100 relative group">
                                    <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Gallery">
                                </div>
                                
                                <div class="p-3 border-t flex flex-col justify-between flex-grow">
                                    <p class="text-sm font-medium text-gray-800 truncate mb-3" title="{{ $img->caption }}">
                                        {{ $img->caption ?? 'Không có mô tả' }}
                                    </p>
                                    
                                    <div class="flex justify-between items-center mt-auto">
                                        <button onclick="openEditModal({{ $img->id }}, '{{ $img->caption }}')" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-1.5 rounded mr-2 transition shadow-sm flex items-center justify-center text-xs font-bold">
                                            <i class="fas fa-pen mr-1"></i> Sửa
                                        </button>
                                        
                                        <a href="{{ route('gallery.delete', $img->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-1.5 rounded transition shadow-sm flex items-center justify-center text-xs font-bold">
                                            <i class="fas fa-trash mr-1"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded border border-dashed border-gray-300">
                            <div class="text-gray-300 mb-3 text-5xl"><i class="fas fa-box-open"></i></div>
                            <p class="text-gray-500 font-medium">Chưa có hình ảnh nào trong thư viện.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all scale-100">
            <div class="bg-yellow-500 px-4 py-3 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-edit mr-2"></i> Cập nhật Ảnh</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 text-2xl leading-none">&times;</button>
            </div>
            
            <div class="p-6">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả / Tên dự án</label>
                        <input type="text" id="editCaption" name="caption" class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-1 focus:ring-yellow-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Đổi ảnh khác (Tùy chọn)</label>
                        <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition" accept="image/*">
                        <p class="text-xs text-gray-400 mt-1">Bỏ trống nếu chỉ muốn sửa tên.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded font-bold text-sm transition">Hủy bỏ</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded font-bold text-sm shadow transition">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    function openEditModal(id, caption) {
        document.getElementById('editCaption').value = caption || '';
        // Route cập nhật: /admin/gallery/{id}
        document.getElementById('editForm').action = "/admin/gallery/" + id;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection