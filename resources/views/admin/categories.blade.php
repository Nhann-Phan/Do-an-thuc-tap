@extends('layouts.admin_layout')

@section('content')

{{-- TIÊU ĐỀ --}}
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800 m-0">Danh mục sản phẩm</h3>
    <span class="text-gray-500 text-sm">Quản lý Menu hiển thị trên website</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- CỘT TRÁI: FORM THÊM MỚI --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24 z-10 overflow-hidden">
            <div class="bg-white border-b border-gray-100 p-4">
                <h5 class="m-0 font-bold text-blue-600 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm Mục Mới
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Tên hiển thị</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm" placeholder="VD: Camera Wifi">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Thuộc nhóm (Cha)</label>
                        <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white">
                            <option value="">-- Danh mục gốc --</option>
                            @foreach($categories as $cat)
                                @if($cat->parent_id == null)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Icon (Font Awesome)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fas fa-icons"></i>
                            </span>
                            <input type="text" name="icon" class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm" placeholder="VD: fas fa-camera">
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition transform active:scale-95 text-sm uppercase">
                        THÊM VÀO MENU
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- CỘT PHẢI: DANH SÁCH DANH MỤC --}}
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
            @foreach($categories as $parent)
            
            @if($parent->parent_id !== null) @continue @endif

            <div class="col-span-1">
                <div class="bg-white rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.05)] hover:shadow-[0_5px_15px_rgba(0,0,0,0.1)] hover:-translate-y-0.5 transition duration-200 border-none overflow-hidden h-full flex flex-col group/card">
                    
                    {{-- Card Header --}}
                    <div class="bg-slate-800 text-white px-4 py-3 font-semibold text-sm flex items-center justify-between">
                        <div class="flex items-center truncate max-w-[70%]">
                            <i class="{{ $parent->icon ?? 'fas fa-folder' }} mr-2 text-yellow-400"></i>
                            <span class="truncate" title="{{ $parent->name }}">{{ $parent->name }}</span>
                        </div>
                        <div class="flex gap-1 opacity-100 transition-opacity">
                            <a href="{{ route('admin.category.products', $parent->id) }}" class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-white transition" title="Xem danh sách">
                                <i class="fas fa-list-ul fa-xs"></i>
                            </a>
                            <button onclick="openEditModal({{ $parent->id }}, '{{ $parent->name }}', '{{ $parent->icon }}', '')" class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-yellow-400 transition" title="Sửa">
                                <i class="fas fa-pen fa-xs"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Xóa mục này sẽ xóa tất cả danh mục con?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-red-400 transition" title="Xóa">
                                    <i class="fas fa-times fa-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Children List --}}
                    <div class="flex flex-col">
                        @forelse($parent->children as $child)
                            <div class="relative border-b border-slate-100 last:border-0 hover:bg-green-50/50 transition duration-200 flex justify-between items-center group/item">
                                
                                <a href="{{ route('admin.category.products', $child->id) }}" 
                                   class="flex-grow py-3 px-4 cursor-pointer text-slate-600 font-medium text-sm flex items-center hover:text-green-700 transition decoration-0"
                                   title="Xem danh sách sản phẩm trong danh mục {{ $child->name }}">
                                    <i class="fas fa-caret-right text-slate-300 mr-2.5 transition group-hover/item:text-green-600"></i>
                                    <span>{{ $child->name }}</span>
                                    <i class="fas fa-plus ml-2.5 opacity-0 group-hover/item:opacity-100 transition text-green-600 text-xs"></i> 
                                </a>

                                <div class="pr-2.5 flex gap-1">
                                    <button onclick="openEditModal({{ $child->id }}, '{{ $child->name }}', '{{ $child->icon }}', '{{ $parent->id }}')" class="w-7 h-7 flex items-center justify-center rounded text-slate-400 hover:bg-slate-100 hover:text-yellow-500 transition" title="Sửa tên">
                                        <i class="fas fa-pen fa-xs"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa mục này?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="w-7 h-7 flex items-center justify-center rounded text-slate-400 hover:bg-slate-100 hover:text-red-500 transition" title="Xóa mục này">
                                            <i class="fas fa-trash-alt fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-400 text-xs italic bg-gray-50/50">Chưa có mục con</div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MODAL SỬA (Tailwind) --}}
<div id="editModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            
            {{-- Modal Header --}}
            <div class="bg-yellow-400 px-4 py-3 sm:px-6 flex justify-between items-center">
                <h3 class="text-base font-bold leading-6 text-gray-900 flex items-center" id="modal-title">
                    <i class="fas fa-edit mr-2"></i> Cập nhật Danh mục
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-800 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-4 py-5 sm:p-6">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Tên hiển thị</label>
                        <input type="text" id="editName" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Nhóm cha</label>
                        <select name="parent_id" id="editParentId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition text-sm bg-white">
                            <option value="">-- LÀ DANH MỤC GỐC --</option>
                            @foreach($categories as $cat)
                                @if($cat->parent_id == null) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Icon</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fas fa-icons"></i>
                            </span>
                            <input type="text" id="editIcon" name="icon" class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition text-sm">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition text-sm">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold rounded-lg shadow-sm transition text-sm">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, name, icon, parentId) {
        document.getElementById('editName').value = name;
        document.getElementById('editIcon').value = icon;
        document.getElementById('editParentId').value = parentId || "";
        document.getElementById('editForm').action = "/admin/categories/" + id;
        
        // Show Modal Tailwind
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

@endsection