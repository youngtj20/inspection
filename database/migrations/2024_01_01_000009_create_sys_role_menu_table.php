<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_role_menu', function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('menu_id');
            
            $table->primary(['role_id', 'menu_id']);
            $table->index('menu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_role_menu');
    }
};
