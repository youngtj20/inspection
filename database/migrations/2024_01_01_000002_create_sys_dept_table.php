<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_dept', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键ID');
            $table->string('title', 255)->nullable()->comment('部门名称');
            $table->bigInteger('pid')->nullable()->comment('父级ID');
            $table->string('pids', 255)->nullable()->comment('所有父级编号');
            $table->integer('sort')->nullable()->comment('排序');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->dateTime('create_date')->nullable()->comment('创建时间');
            $table->dateTime('update_date')->nullable()->comment('更新时间');
            $table->bigInteger('create_by')->nullable()->comment('创建用户');
            $table->bigInteger('update_by')->nullable()->comment('更新用户');
            $table->tinyInteger('status')->nullable()->comment('状态（1:正常,2:冻结,3:删除）');
            $table->string('address', 300)->nullable();
            $table->double('area')->nullable();
            $table->string('contactnumber', 30)->nullable();
            $table->string('contacts', 50)->nullable();
            $table->integer('employees')->nullable();
            $table->string('state', 100)->nullable();
            $table->dateTime('createDate')->nullable();
            $table->dateTime('updateDate')->nullable();
            $table->string('deptno', 10)->nullable()->comment('组织机构编号');
            
            $table->index('create_by');
            $table->index('update_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_dept');
    }
};
