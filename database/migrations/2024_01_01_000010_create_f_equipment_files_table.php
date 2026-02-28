<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('f_equipment_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('manufacturer', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('producerCountry', 255)->nullable();
            $table->string('productDate', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->dateTime('createDate')->nullable();
            $table->dateTime('updateDate')->nullable();
            $table->string('certificationDate', 255)->nullable();
            $table->string('certification_date', 255)->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('producer_country', 255)->nullable();
            $table->string('product_date', 255)->nullable();
            $table->dateTime('update_date')->nullable();
            
            $table->index('dept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('f_equipment_files');
    }
};
