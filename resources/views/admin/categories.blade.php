@extends('layouts.admin_layout')

@section('content')

{{-- TIÊU ĐỀ --}}
<div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 m-0">Danh mục sản phẩm</h3>
        <span class="text-gray-500 text-sm">Quản lý phân loại hiển thị trên website</span>
    </div>
</div>

{{-- KHU VỰC THÔNG BÁO (ALERTS) --}}
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center animate-fade-in-down">
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <div><span class="font-bold">Thành công!</span> {{ session('success') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg animate-fade-in-down">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- CỘT TRÁI: FORM THÊM MỚI --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-24 z-10 overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 p-4">
                <h5 class="m-0 font-bold text-blue-600 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Thêm Mục Mới
                </h5>
            </div>
            <div class="p-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    
                    {{-- Tên danh mục --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Tên hiển thị <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm" placeholder="VD: Camera Wifi" value="{{ old('name') }}">
                    </div>
                    
                    {{-- Chọn cha --}}
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
                        <p class="text-[10px] text-gray-400 mt-1 italic">Để trống nếu là danh mục lớn nhất</p>
                    </div>
                    
                    {{-- Icon --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Icon (Font Awesome)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                <i class="fas fa-icons"></i>
                            </span>
                            <input type="text" name="icon" class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm" placeholder="VD: fas fa-camera">
                        </div>
                    </div>

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition transform active:scale-95 text-sm uppercase flex justify-center items-center">
                        <i class="fas fa-save mr-2"></i> Lưu Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- CỘT PHẢI: DANH SÁCH DANH MỤC --}}
    <div class="lg:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
            @forelse($categories as $parent)
            
            {{-- Chỉ hiện danh mục cha, con sẽ hiện bên trong --}}
            @if($parent->parent_id !== null) @continue @endif

            <div class="col-span-1">
                <div class="bg-white rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.05)] hover:shadow-[0_5px_15px_rgba(0,0,0,0.1)] hover:-translate-y-0.5 transition duration-200 border-none overflow-hidden h-full flex flex-col group/card">
                    
                    {{-- Card Header (Parent) --}}
                    <div class="bg-slate-800 text-white px-4 py-3 font-semibold text-sm flex items-center justify-between">
                        <div class="flex items-center truncate max-w-[70%]">
                            <i class="{{ $parent->icon ?? 'fas fa-folder' }} mr-2 text-yellow-400"></i>
                            <span class="truncate uppercase tracking-wide" title="{{ $parent->name }}">{{ $parent->name }}</span>
                        </div>
                        <div class="flex gap-1 opacity-100 transition-opacity">
                            {{-- Nút xem sản phẩm của danh mục cha --}}
                            <a href="{{ route('admin.category.products', $parent->id) }}" class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-white transition" title="Xem danh sách sản phẩm">
                                <i class="fas fa-list-ul fa-xs"></i>
                            </a>
                            {{-- Nút sửa --}}
                            <button onclick="openEditModal({{ $parent->id }}, '{{ $parent->name }}', '{{ $parent->icon }}', '')" class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-yellow-400 transition" title="Sửa tên/icon">
                                <i class="fas fa-pen fa-xs"></i>
                            </button>
                            {{-- Nút xóa --}}
                            <form action="{{ route('categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa danh mục cha sẽ xóa tất cả danh mục con bên trong!\nBạn có chắc chắn không?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 flex items-center justify-center rounded text-slate-300 hover:bg-slate-700 hover:text-red-400 transition" title="Xóa">
                                    <i class="fas fa-times fa-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Children List --}}
                    <div class="flex flex-col flex-grow bg-white">
                        @forelse($parent->children as $child)
                            <div class="relative border-b border-slate-100 last:border-0 hover:bg-blue-50/30 transition duration-200 flex justify-between items-center group/item px-4 py-3">
                                
                                {{-- Link click vào xem sản phẩm --}}
                                <a href="{{ route('admin.category.products', $child->id) }}" 
                                   class="flex-grow cursor-pointer text-slate-600 font-medium text-sm flex items-center hover:text-blue-700 transition decoration-0">
                                    <i class="fas fa-caret-right text-slate-300 mr-2.5 transition group-hover/item:text-blue-600"></i>
                                    <span>{{ $child->name }}</span>
                                    <span class="ml-2 text-[10px] bg-gray-100 text-gray-500 px-1.5 rounded border border-gray-200 group-hover/item:border-blue-200 group-hover/item:text-blue-600 transition">
                                        {{-- Giả sử bạn có relationship products_count, nếu không thì bỏ dòng này --}}
                                        {{ $child->products->count() }} sp
                                    </span>
                                </a>

                                <div class="flex gap-2">
                                    <button onclick="openEditModal({{ $child->id }}, '{{ $child->name }}', '{{ $child->icon }}', '{{ $parent->id }}')" class="text-slate-400 hover:text-yellow-500 transition" title="Sửa">
                                        <i class="fas fa-pen fa-xs"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa mục này?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-400 hover:text-red-500 transition" title="Xóa">
                                            <i class="fas fa-trash-alt fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-400 text-xs italic">
                                <i class="fas fa-folder-open mb-1 block text-lg"></i>
                                Chưa có danh mục con
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10 bg-white rounded-xl border border-dashed border-gray-300 text-gray-500">
                <i class="fas fa-layer-group text-4xl mb-3 text-gray-300"></i>
                <p>Chưa có danh mục nào. Hãy thêm mới ở cột bên trái.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL SỬA (Tailwind) --}}
<div id="editModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeEditModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
            
            {{-- Modal Header --}}
            <div class="bg-yellow-400 px-4 py-3 sm:px-6 flex justify-between items-center shadow-sm">
                <h3 class="text-base font-bold leading-6 text-gray-900 flex items-center" id="modal-title">
                    <i class="fas fa-edit mr-2"></i> Cập nhật Danh mục
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-800 hover:text-white hover:bg-yellow-500 rounded-full w-8 h-8 flex items-center justify-center transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-6">
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
                                @if($cat->parent_id == null) 
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option> 
                                @endif
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
                    
                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition text-sm">Hủy bỏ</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold rounded-lg shadow-sm transition text-sm flex items-center">
                            <i class="fas fa-save mr-2"></i> Lưu thay đổi
                        </button>
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
        
        // Sử dụng Route JS trick của Laravel để tránh hardcode URL
        let url = "{{ route('categories.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('editForm').action = url;
        
        // Show Modal Tailwind
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

@endsection