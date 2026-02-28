<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_user', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键ID');
            $table->string('username', 255)->nullable()->comment('用户名');
            $table->string('nickname', 255)->nullable()->comment('用户昵称');
            $table->char('password', 64)->nullable()->comment('密码');
            $table->string('salt', 255)->nullable()->comment('密码盐');
            $table->bigInteger('dept_id')->nullable()->comment('部门ID');
            $table->string('picture', 255)->nullable()->comment('头像');
            $table->tinyInteger('sex')->nullable()->comment('性别（1:男,2:女）');
            $table->string('email', 255)->nullable()->comment('邮箱');
            $table->string('phone', 255)->nullable()->comment('电话号码');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->dateTime('create_date')->nullable()->comment('创建时间');
            $table->dateTime('update_date')->nullable()->comment('更新时间');
            $table->tinyInteger('status')->nullable()->comment('状态（1:正常,2:冻结,3:删除）');
            $table->dateTime('createDate')->nullable();
            $table->dateTime('updateDate')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_user');
    }
};
