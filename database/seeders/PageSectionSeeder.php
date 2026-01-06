<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;

class PageSectionSeeder extends Seeder
{
    public function run()
    {
        $page = Page::firstOrCreate(
            ['slug' => 've-chung-toi'],
            ['title' => 'Về chúng tôi', 'is_active' => 1]
        );

        PageSection::where('page_id', $page->id)->delete();

        // Section 1
        PageSection::create([
            'page_id' => $page->id,
            'title' => 'Câu chuyện khởi nghiệp',
            'type' => 'text_image',
            'position' => 1,
            // SỬA: Thêm json_encode
            'data' => json_encode([
                'layout' => 'image_right',
                'content' => '<p>Được thành lập vào năm 2020, GPM Technology bắt đầu với sứ mệnh đơn giản...</p>',
                'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
            ])
        ]);

        // Section 2
        PageSection::create([
            'page_id' => $page->id,
            'title' => 'Con số biết nói',
            'type' => 'stats',
            'position' => 2,
            // SỬA: Thêm json_encode
            'data' => json_encode([
                'background' => 'blue',
                'stats' => [
                    ['number' => '5+', 'label' => 'Năm kinh nghiệm'],
                    ['number' => '200+', 'label' => 'Dự án hoàn thành'],
                    ['number' => '50+', 'label' => 'Nhân sự tài năng'],
                    ['number' => '98%', 'label' => 'Khách hàng hài lòng'],
                ]
            ])
        ]);

        // Section 3
        PageSection::create([
            'page_id' => $page->id,
            'title' => 'Tầm nhìn & Sứ mệnh',
            'type' => 'text_image',
            'position' => 3,
            // SỬA: Thêm json_encode
            'data' => json_encode([
                'layout' => 'image_left',
                'content' => '<ul><li><strong>Tầm nhìn:</strong> Trở thành đơn vị...</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
            ])
        ]);

        // Section 4
        PageSection::create([
            'page_id' => $page->id,
            'title' => 'Sẵn sàng đồng hành cùng bạn',
            'type' => 'cta',
            'position' => 4,
            // SỬA: Thêm json_encode
            'data' => json_encode([
                'subtext' => 'Liên hệ ngay hôm nay để nhận tư vấn miễn phí...',
                'button_text' => 'Gửi yêu cầu tư vấn',
                'button_link' => '#footer'
            ])
        ]);
    }
}