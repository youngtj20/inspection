<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_file', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键ID');
            $table->string('name', 255)->nullable()->comment('文件名');
            $table->string('path', 255)->nullable()->comment('文件路径');
            $table->string('mime', 255)->nullable()->comment('MIME文件类型');
            $table->bigInteger('size')->nullable()->comment('文件大小');
            $table->string('md5', 255)->nullable()->comment('MD5值');
            $table->string('sha1', 255)->nullable()->comment('SHA1值');
            $table->bigInteger('create_by')->nullable()->comment('上传者');
            $table->dateTime('create_date')->nullable()->comment('创建时间');
            $table->dateTime('createDate')->nullable();
            
            $table->index('create_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_file');
    }
};
