<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('i_vehicle_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('stationno', 10)->nullable();
            $table->string('inspectdate', 21)->nullable();
            $table->string('registertime', 10)->nullable();
            $table->string('plateno', 30)->nullable();
            $table->string('inspecttype', 2)->nullable();
            $table->string('vehicletype', 4)->nullable();
            $table->string('engineno', 50)->nullable();
            $table->string('makeofvehicle', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('licencetype', 1)->nullable();
            $table->string('owner', 200)->nullable();
            $table->string('identificationmark', 50)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('phoneno', 30)->nullable();
            $table->double('netweight')->nullable();
            $table->double('authorizedtocarry')->nullable();
            $table->double('grossweight')->nullable();
            $table->double('personstocarry')->nullable();
            $table->string('fueltype', 1)->nullable();
            $table->string('headlampsystem', 1)->nullable();
            $table->string('drivemethod', 1)->nullable();
            $table->integer('axisnumber')->nullable();
            $table->string('handbrake', 16)->nullable();
            $table->string('registerdate', 22)->nullable();
            $table->string('productdate', 22)->nullable();
            $table->string('heavyorlight', 1)->nullable();
            $table->string('chassisno', 50)->nullable();
            $table->string('acceptmember', 50)->nullable();
            $table->string('odmeter', 20)->nullable();
            $table->string('inspectitems', 100)->nullable();
            $table->string('presentor', 50)->nullable();
            $table->string('invoiceno', 30)->nullable();
            $table->dateTime('createDate')->nullable();
            $table->string('position', 255)->nullable();
            $table->bigInteger('dept_id')->nullable();
            
            $table->index(['plateno', 'vehicletype']);
            $table->index(['seriesno', 'inspecttimes']);
            $table->index('inspectdate');
            $table->index('inspecttype');
            $table->index('dept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_vehicle_register');
    }
};
