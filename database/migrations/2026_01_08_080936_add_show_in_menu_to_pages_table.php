<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up()
{
    // Kiểm tra xem cột 'show_in_menu' đã có trong bảng 'pages' chưa
    if (!Schema::hasColumn('pages', 'show_in_menu')) {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_menu')->default(true)->after('slug'); 
        });
    }
}

public function down()
{
    Schema::table('pages', function (Blueprint $table) {
        $table->dropColumn('show_in_menu');
    });
}
};
