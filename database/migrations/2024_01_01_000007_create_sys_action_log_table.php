<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sys_action_log', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键ID');
            $table->string('name', 255)->nullable()->comment('日志名称');
            $table->tinyInteger('type')->nullable()->comment('日志类型');
            $table->string('ipaddr', 255)->nullable()->comment('操作IP地址');
            $table->string('clazz', 255)->nullable()->comment('产生日志的类');
            $table->string('method', 255)->nullable()->comment('产生日志的方法');
            $table->string('model', 255)->nullable()->comment('产生日志的表');
            $table->bigInteger('record_id')->nullable()->comment('产生日志的数据id');
            $table->text('message')->nullable()->comment('日志消息');
            $table->dateTime('create_date')->nullable()->comment('记录时间');
            $table->string('oper_name', 255)->nullable()->comment('产生日志的用户昵称');
            $table->bigInteger('oper_by')->nullable()->comment('产生日志的用户');
            $table->dateTime('createDate')->nullable();
            $table->string('operName', 255)->nullable();
            $table->bigInteger('recordId')->nullable();
            
            $table->index('oper_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_action_log');
    }
};
