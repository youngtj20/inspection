<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('f_personnel_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('age')->nullable();
            $table->dateTime('createDate')->nullable();
            $table->string('education', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('gender', 255)->nullable();
            $table->string('jobTitle', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->dateTime('updateDate')->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('job_title', 255)->nullable();
            $table->dateTime('update_date')->nullable();
            
            $table->index('dept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('f_personnel_files');
    }
};
