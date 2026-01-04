@extends('layouts.admin_layout')

@section('content')

<style>
    .category-card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-radius: 12px; overflow: hidden; height: 100%; transition: transform 0.2s; background: white; }
    .category-card:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .card-header-custom { background-color: #1e293b; color: white; padding: 12px 15px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: space-between; }
    .child-item-wrapper { position: relative; border-bottom: 1px solid #f1f5f9; transition: background 0.2s; display: flex; justify-content: space-between; align-items: center; }
    .child-item-wrapper:last-child { border-bottom: none; }
    .child-item-wrapper:hover { background-color: #f0fdf4; }
    .child-action-area { flex-grow: 1; padding: 12px 15px; cursor: pointer; color: #334155; font-weight: 500; text-decoration: none; display: flex; align-items: center; }
    .child-action-area:hover { color: #15803d; }
    .child-action-area i.fa-plus { opacity: 0; margin-left: 10px; transition: opacity 0.2s; color: #15803d; }
    .child-item-wrapper:hover .child-action-area i.fa-plus { opacity: 1; }
    .child-icon { color: #cbd5e1; margin-right: 10px; transition: color 0.2s; }
    .child-item-wrapper:hover .child-icon { color: #15803d; }
    .action-buttons-mini { padding-right: 10px; display: flex; gap: 5px; }
    .btn-mini { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: none; background: transparent; transition: all 0.2s; color: #94a3b8; }
    .btn-mini:hover { background-color: #f1f5f9; }
    .btn-mini.edit:hover { color: #eab308; }
    .btn-mini.delete:hover { color: #ef4444; }
</style>

<div class="row g-4">
    <div class="col-12 mb-2">
        <h3 class="fw-bold text-dark m-0">Danh mục sản phẩm</h3>
        <span class="text-muted small">Quản lý Menu hiển thị trên website</span>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px; z-index: 1;">
            <div class="card-header bg-white border-bottom p-3">
                <h5 class="m-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Thêm Mục Mới</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tên hiển thị</label>
                        <input type="text" name="name" required class="form-control form-control-lg fs-6" placeholder="VD: Camera Wifi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Thuộc nhóm (Cha)</label>
                        <select name="parent_id" class="form-select form-select-lg fs-6">
                            <option value="">-- Danh mục gốc --</option>
                            @foreach($categories as $cat)
                                @if($cat->parent_id == null)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Icon (Font Awesome)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-icons"></i></span>
                            <input type="text" name="icon" class="form-control" placeholder="VD: fas fa-camera">
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 py-2 fw-bold shadow-sm">THÊM VÀO MENU</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="row g-3 align-items-start">
            @foreach($categories as $parent)
            
            @if($parent->parent_id !== null) @continue @endif

            <div class="col-md-6">
                <div class="category-card d-flex flex-column">
                    <div class="card-header-custom">
                        <div class="d-flex align-items-center text-truncate" style="max-width: 70%;">
                            <i class="{{ $parent->icon ?? 'fas fa-folder' }} me-2 text-warning"></i>
                            <span class="text-truncate" title="{{ $parent->name }}">{{ $parent->name }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.category.products', $parent->id) }}" class="btn btn-sm btn-outline-light border-0 py-0" title="Xem danh sách"><i class="fas fa-list-ul"></i></a>
                            <button onclick="openEditModal({{ $parent->id }}, '{{ $parent->name }}', '{{ $parent->icon }}', '')" class="btn btn-sm btn-outline-light border-0 py-0" title="Sửa"><i class="fas fa-pen"></i></button>
                            <form action="{{ route('categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Xóa mục này sẽ xóa tất cả danh mục con?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger border-0 py-0 text-white" title="Xóa"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column">
                        @forelse($parent->children as $child)
                            <div class="child-item-wrapper">
                                
                                <a href="{{ route('admin.category.products', $child->id) }}" 
                                   class="child-action-area"
                                   title="Xem danh sách sản phẩm trong danh mục {{ $child->name }}">
                                    <i class="fas fa-caret-right child-icon"></i>
                                    <span>{{ $child->name }}</span>
                                    <i class="fas fa-plus small"></i> 
                                </a>

                                <div class="action-buttons-mini">
                                    <button onclick="openEditModal({{ $child->id }}, '{{ $child->name }}', '{{ $child->icon }}', '{{ $parent->id }}')" class="btn-mini edit" title="Sửa tên"><i class="fas fa-pen fa-xs"></i></button>
                                    <form action="{{ route('categories.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa mục này?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn-mini delete" title="Xóa mục này"><i class="fas fa-trash-alt fa-xs"></i></button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted small fst-italic bg-light">Chưa có mục con</div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Cập nhật Danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Tên hiển thị</label>
                        <input type="text" id="editName" name="name" required class="form-control form-control-lg fs-6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">Nhóm cha</label>
                        <select name="parent_id" id="editParentId" class="form-select form-select-lg fs-6">
                            <option value="">-- LÀ DANH MỤC GỐC --</option>
                            @foreach($categories as $cat)
                                @if($cat->parent_id == null) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small">Icon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-icons"></i></span>
                            <input type="text" id="editIcon" name="icon" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning fw-bold px-4">Lưu thay đổi</button>
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
        var myModal = new bootstrap.Modal(document.getElementById('editModal'));
        myModal.show();
    }
</script>
@endsection