<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    // Hiển thị danh sách các Section của 1 Page + Form thêm mới
    public function index(Page $page)
    {
        $sections = $page->sections; // Lấy danh sách section đã có
        return view('admin.page_sections.index', compact('page', 'sections'));
    }

    // Lưu Section mới
    public function store(Request $request, Page $page)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'nullable|max:255',
        ]);

        $data = [];
        $input = $request->all();

        // Xử lý dữ liệu dựa theo TYPE
        if ($request->type == 'text_image') {
            $data['content'] = $input['content_text'] ?? '';
            $data['layout'] = $input['layout'] ?? 'image_right';
            
            // Upload ảnh
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/sections'), $filename);
                $data['image'] = '/uploads/sections/' . $filename;
            }
        } 
        elseif ($request->type == 'cta') {
            $data['subtext'] = $input['cta_subtext'] ?? '';
            $data['button_text'] = $input['cta_btn_text'] ?? '';
            $data['button_link'] = $input['cta_btn_link'] ?? '';
        }
        elseif ($request->type == 'stats') {
            // Xử lý mảng thống kê (giả sử nhập 4 ô cố định từ form)
            $stats = [];
            if(isset($input['stat_number'])) {
                foreach($input['stat_number'] as $key => $val) {
                    if($val) {
                        $stats[] = [
                            'number' => $val,
                            'label' => $input['stat_label'][$key] ?? ''
                        ];
                    }
                }
            }
            $data['stats'] = $stats;
        }

        // Tạo mới Section
        PageSection::create([
            'page_id' => $page->id,
            'title' => $request->title,
            'type' => $request->type,
            'position' => $request->position ?? 0,
            'data' => $data // Laravel sẽ tự cast mảng này sang JSON nhờ Model
        ]);

        return redirect()->back()->with('success', 'Đã thêm khối nội dung thành công!');
    }

    // Xóa Section
    public function destroy($id)
    {
        $section = PageSection::findOrFail($id);
        $section->delete();
        return redirect()->back()->with('success', 'Đã xóa khối nội dung.');
    }

    // ... các hàm index, store, destroy cũ giữ nguyên ...

    // 1. Hàm hiển thị form sửa
    public function edit($id)
    {
        $section = PageSection::findOrFail($id);
        return view('admin.page_sections.edit', compact('section'));
    }

    // 2. Hàm thực hiện cập nhật
    public function update(Request $request, $id)
    {
        $section = PageSection::findOrFail($id);
        
        $request->validate([
            'title' => 'nullable|max:255',
        ]);

        // Lấy dữ liệu cũ ra
        $data = $section->data ?? []; 
        $input = $request->all();

        // Xử lý cập nhật theo từng loại
        if ($section->type == 'text_image') {
            $data['content'] = $input['content_text'] ?? $data['content'] ?? '';
            $data['layout'] = $input['layout'] ?? $data['layout'] ?? 'image_right';
            
            // Upload ảnh mới nếu có
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/sections'), $filename);
                $data['image'] = '/uploads/sections/' . $filename;
            }
        } 
        elseif ($section->type == 'cta') {
            $data['subtext'] = $input['cta_subtext'] ?? '';
            $data['button_text'] = $input['cta_btn_text'] ?? '';
            $data['button_link'] = $input['cta_btn_link'] ?? '';
        }
        elseif ($section->type == 'stats') {
            $stats = [];
            if(isset($input['stat_number'])) {
                foreach($input['stat_number'] as $key => $val) {
                    if($val) {
                        $stats[] = [
                            'number' => $val,
                            'label' => $input['stat_label'][$key] ?? ''
                        ];
                    }
                }
            }
            $data['stats'] = $stats;
        }

        // Lưu vào Database
        $section->update([
            'title' => $request->title,
            'position' => $request->position ?? 0,
            'data' => $data
        ]);

        return redirect()->route('page_sections.index', $section->page_id)
                         ->with('success', 'Đã cập nhật khối thành công!');
    }
}