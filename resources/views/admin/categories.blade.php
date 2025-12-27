@extends('layouts.admin_layout')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .tw-reset a { text-decoration: none; }
    .tw-reset ul { padding-left: 0; margin-bottom: 0; }
    /* Hiệu ứng Modal */
    #editModal { transition: opacity 0.3s ease; }
    #editModal.hidden { opacity: 0; pointer-events: none; }
    #editModal:not(.hidden) { opacity: 1; pointer-events: auto; }
</style>

<div class="tw-reset font-sans relative">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-700">Cấu hình Danh mục</h3>
            <p class="text-sm text-gray-500">Quản lý Menu hiển thị trên website</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-blue-600">
                <div class="p-4 bg-gray-50 border-b">
                    <h4 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-plus-circle mr-2 text-blue-600"></i> Thêm Mục Mới
                    </h4>
                </div>
                <div class="p-5">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tên hiển thị</label>
                            <input type="text" name="name" required class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="VD: Camera Wifi">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Thuộc nhóm (Cha)</label>
                            <select name="parent_id" class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">-- Danh mục gốc --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Icon (Font Awesome)</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fas fa-icons"></i>
                                </span>
                                <input type="text" name="icon" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-r-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="VD: fas fa-camera">
                            </div>
                        </div>
                        
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow transition">
                            THÊM VÀO MENU
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded-lg border border-blue-200 mb-4 flex items-center shadow-sm">
                <i class="fas fa-info-circle mr-2 text-xl"></i> 
                <span class="font-medium">Danh sách danh mục đang hiển thị trên Website</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($categories as $parent)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="bg-gray-800 text-white px-3 py-3 flex justify-between items-center">
                        <span class="font-bold text-sm uppercase flex items-center truncate max-w-[60%]">
                            <i class="{{ $parent->icon ?? 'fas fa-folder' }} mr-2 text-yellow-400"></i> {{ $parent->name }}
                        </span>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('admin.category.products', $parent->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="Xem danh sách sản phẩm">
                                <i class="fas fa-list-ul"></i>
                            </a>
                            
                            <button onclick="openEditModal({{ $parent->id }}, '{{ $parent->name }}', '{{ $parent->icon }}', '')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Sửa danh mục">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Xóa mục này sẽ xóa tất cả danh mục con?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Xóa"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    
                    <ul class="divide-y divide-gray-100">
                        @forelse($parent->children as $child)
                            <li class="px-3 py-2 flex justify-between items-center hover:bg-gray-50 text-sm">
                                <span class="flex items-center truncate max-w-[60%]">
                                    <i class="fas fa-angle-right text-gray-300 mr-2"></i> {{ $child->name }}
                                </span>
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('product.create', ['category_id' => $child->id]) }}" class="text-green-600 border border-green-200 px-1 rounded text-xs font-bold hover:bg-green-50" title="Thêm sản phẩm">+SP</a>
                                    
                                    <button onclick="openEditModal({{ $child->id }}, '{{ $child->name }}', '{{ $child->icon }}', '{{ $parent->id }}')" class="text-yellow-500 hover:text-yellow-600 px-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Xóa mục này?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-300 hover:text-red-500 px-1" title="Xóa"><i class="fas fa-times"></i></button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-2 text-center text-gray-400 text-xs italic bg-gray-50">Chưa có mục con</li>
                        @endforelse
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all scale-100">
            <div class="bg-yellow-500 px-4 py-3 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg"><i class="fas fa-edit mr-2"></i> Cập nhật Danh mục</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 text-xl">&times;</button>
            </div>
            
            <div class="p-6">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tên hiển thị</label>
                        <input type="text" id="editName" name="name" required class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nhóm cha (Nếu muốn đổi)</label>
                        <select name="parent_id" id="editParentId" class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                            <option value="">-- LÀ DANH MỤC GỐC --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Icon</label>
                        <input type="text" id="editIcon" name="icon" class="w-full border border-gray-300 px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-bold">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded font-bold">Lưu thay đổi</button>
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
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection