<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('i_data_base', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plateno', 30)->nullable();
            $table->string('vehicletype', 4)->nullable();
            $table->string('licencetype', 2)->nullable();
            $table->string('seriesno', 30)->nullable();
            $table->string('inspectdate', 21)->nullable();
            $table->string('inspecttimes', 2)->nullable();
            $table->string('inspecttype', 2)->nullable();
            $table->string('starttime', 22)->nullable();
            $table->string('endTime', 22)->nullable();
            $table->string('conclusion', 200)->nullable();
            $table->string('workerline', 20)->nullable();
            $table->string('register', 50)->nullable();
            $table->string('inspector', 50)->nullable();
            $table->string('appearanceinspector', 50)->nullable();
            $table->string('pitinspector', 50)->nullable();
            $table->string('owner', 200)->nullable();
            $table->string('testresult', 2)->nullable();
            $table->dateTime('createDate')->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->dateTime('updateDate')->nullable();
            $table->string('isupload', 2)->nullable();
            
            $table->index(['plateno', 'vehicletype']);
            $table->index(['seriesno', 'inspecttimes']);
            $table->index('inspectdate');
            $table->index('dept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_data_base');
    }
};
