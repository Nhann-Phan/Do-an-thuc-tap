@extends('layouts.admin_layout')

@section('content')

    {{-- HEADER --}}
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800 m-0">Thư viện ảnh dự án</h3>
        <span class="text-gray-500 text-sm">Quản lý các hình ảnh thực tế hiển thị trên trang chủ</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- CỘT TRÁI: FORM UPLOAD (Sticky) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24 z-10 overflow-hidden">
                {{-- Card Header --}}
                <div class="bg-blue-600 px-6 py-4 border-b border-blue-500">
                    <h5 class="font-bold text-white flex items-center m-0 text-base uppercase">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Đăng ảnh mới
                    </h5>
                </div>
                
                {{-- Card Body --}}
                <div class="p-6">
                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Chọn ảnh</label>
                            <input type="file" name="image" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer border border-gray-300 rounded-lg p-1 bg-white" accept="image/*">
                            <p class="text-xs text-gray-400 mt-1 italic">Định dạng: jpg, png. Tối đa 2MB.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Mô tả / Tên dự án</label>
                            <input type="text" name="caption" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm" placeholder="Ví dụ: Lắp đặt Camera tại Châu Đốc...">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition transform active:scale-95 text-sm uppercase flex items-center justify-center">
                            <i class="fas fa-upload mr-2"></i> TẢI LÊN NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: DANH SÁCH ẢNH --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h5 class="font-bold text-gray-700 flex items-center m-0 text-base uppercase">
                        <i class="fas fa-images mr-2 text-blue-500"></i> Danh sách ảnh đã đăng
                    </h5>
                </div>
                
                <div class="p-6">
                    @if(isset($images) && count($images) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($images as $img)
                            <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition duration-300">
                                
                                {{-- Image Container --}}
                                <div class="aspect-square bg-gray-100 overflow-hidden relative">
                                    <img src="{{ asset($img->image_path) }}" 
                                         class="w-full h-full object-cover transition duration-700 group-hover:scale-110" 
                                         alt="Gallery Image">
                                    
                                    {{-- Overlay Actions (Hiện khi hover) --}}
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                                        <button onclick="openEditModal({{ $img->id }}, '{{ $img->caption }}')" 
                                                class="w-9 h-9 flex items-center justify-center bg-yellow-400 hover:bg-yellow-500 text-white rounded-full transition transform hover:scale-110" title="Sửa">
                                            <i class="fas fa-pen fa-xs"></i>
                                        </button>
                                        <a href="{{ route('gallery.delete', $img->id) }}" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này?')" 
                                           class="w-9 h-9 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full transition transform hover:scale-110" title="Xóa">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                {{-- Caption --}}
                                <div class="p-3 bg-white border-t border-gray-100">
                                    <p class="text-sm font-medium text-gray-700 truncate text-center" title="{{ $img->caption }}">
                                        {{ $img->caption ?? 'Không có mô tả' }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <div class="text-gray-300 mb-4 text-6xl"><i class="fas fa-image"></i></div>
                            <p class="text-gray-500 font-medium">Chưa có hình ảnh nào trong thư viện.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL SỬA (Hidden by default) --}}
    <div id="editModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeEditModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    
                    {{-- Modal Header --}}
                    <div class="bg-yellow-500 px-4 py-3 sm:px-6 flex justify-between items-center">
                        <h3 class="text-base font-bold leading-6 text-white uppercase flex items-center" id="modal-title">
                            <i class="fas fa-edit mr-2"></i> Cập nhật Ảnh
                        </h3>
                        <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-100 focus:outline-none text-xl leading-none">
                            &times;
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-6">
                        <form id="editForm" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT') 
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Mô tả / Tên dự án</label>
                                <input type="text" id="editCaption" name="caption" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition text-sm">
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Đổi ảnh khác (Tùy chọn)</label>
                                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition cursor-pointer border border-gray-300 rounded-lg p-1 bg-white" accept="image/*">
                                <p class="text-xs text-gray-400 mt-1 italic">Bỏ trống nếu chỉ muốn sửa tên.</p>
                            </div>

                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition text-sm">Hủy bỏ</button>
                                <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-bold text-sm shadow-sm transition">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
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