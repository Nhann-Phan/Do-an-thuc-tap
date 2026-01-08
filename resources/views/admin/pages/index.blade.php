@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Trang Giới Thiệu</h2>
        <a href="{{ route('pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Thêm trang mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">STT</th>
                    <th class="py-3 px-6 text-left">Tiêu đề</th>
                    {{-- ĐÃ XÓA CỘT 'HIỆN MENU' Ở ĐÂY CHO GỌN --}}
                    <th class="py-3 px-6 text-center">Thứ tự</th>
                    <th class="py-3 px-6 text-center">Trạng thái</th>
                    <th class="py-3 px-6 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($pages as $key => $page)
                
                <tr class="border-b border-gray-200 hover:bg-blue-50 cursor-pointer transition" 
                    onclick="window.location='{{ route('pages.edit', $page->id) }}'" 
                    title="Click để chỉnh sửa">
                    
                    <td class="py-3 px-6 text-left whitespace-nowrap font-bold">{{ $key + 1 }}</td>
                    
                    <td class="py-3 px-6 text-left">
                        <div class="font-bold text-gray-800">{{ $page->title }}</div>
                        <div class="text-xs italic text-gray-500">{{ $page->slug }}</div>
                    </td>

                    <td class="py-3 px-6 text-center">{{ $page->position }}</td>
                    
                    <td class="py-3 px-6 text-center">
                        @if($page->is_active)
                            <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs font-bold">Active</span>
                        @else
                            <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs font-bold">Inactive</span>
                        @endif
                    </td>
                    
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center space-x-2">
                            
                            {{-- 1. NÚT ẨN/HIỆN MENU (MỚI) --}}
                            <button type="button"
                                    onclick="event.stopPropagation(); toggleMenuStatus(this, {{ $page->id }})"
                                    data-status="{{ $page->show_in_menu }}"
                                    class="w-8 h-8 rounded flex items-center justify-center transition focus:outline-none 
                                    {{ $page->show_in_menu ? 'bg-blue-100 text-blue-600 hover:bg-blue-200' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}" 
                                    title="{{ $page->show_in_menu ? 'Đang hiện trên menu (Click để ẩn)' : 'Đang ẩn (Click để hiện)' }}">
                                <i class="fas {{ $page->show_in_menu ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            </button>

                            {{-- 2. Nút Sections --}}
                            <a href="{{ route('page_sections.index', $page->id) }}" 
                               onclick="event.stopPropagation()"
                               class="w-8 h-8 rounded bg-purple-100 text-purple-600 flex items-center justify-center hover:bg-purple-200 transition" 
                               title="Quản lý nội dung chi tiết">
                                <i class="fas fa-layer-group"></i>
                            </a>

                            {{-- 3. Nút Xóa --}}
                            <form action="{{ route('pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');" onclick="event.stopPropagation()">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleMenuStatus(btn, pageId) {
        // Lấy trạng thái hiện tại (1 hoặc 0) từ attribute data-status
        let currentStatus = parseInt(btn.getAttribute('data-status'));
        let newStatus = currentStatus === 1 ? 0 : 1;
        
        // Icon element bên trong button
        let icon = btn.querySelector('i');

        // Hiệu ứng loading nhẹ (optional)
        btn.style.opacity = '0.5';

        fetch(`/admin/pages/${pageId}/toggle-menu`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ show_in_menu: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            btn.style.opacity = '1'; // Trả lại độ sáng
            
            if(data.success) {
                // Cập nhật trạng thái mới cho nút
                btn.setAttribute('data-status', newStatus);

                if (newStatus === 1) {
                    // Đang Mở (Hiện)
                    btn.className = "w-8 h-8 rounded flex items-center justify-center transition focus:outline-none bg-blue-100 text-blue-600 hover:bg-blue-200";
                    icon.className = "fas fa-eye";
                    btn.title = "Đang hiện trên menu (Click để ẩn)";
                } else {
                    // Đang Đóng (Ẩn)
                    btn.className = "w-8 h-8 rounded flex items-center justify-center transition focus:outline-none bg-gray-200 text-gray-500 hover:bg-gray-300";
                    icon.className = "fas fa-eye-slash";
                    btn.title = "Đang ẩn (Click để hiện)";
                }
            } else {
                alert('Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.style.opacity = '1';
            alert('Lỗi kết nối!');
        });
    }
</script>
@endsection