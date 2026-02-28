<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('i_vehicle_base', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plateno', 30)->nullable();
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
            $table->string('odmeter', 20)->nullable();
            $table->dateTime('createDate')->nullable();
            $table->double('authorized_to_carry')->nullable();
            $table->integer('axis_number')->nullable();
            $table->string('chassis_no', 255)->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('drive_method', 255)->nullable();
            $table->string('engine_no', 255)->nullable();
            $table->string('fuel_type', 255)->nullable();
            $table->double('gross_weight')->nullable();
            $table->string('hand_brake', 255)->nullable();
            $table->string('head_lamp_system', 255)->nullable();
            $table->string('heavy_or_light', 255)->nullable();
            $table->string('identification_mark', 255)->nullable();
            $table->string('licence_type', 255)->nullable();
            $table->string('make_of_vehicle', 255)->nullable();
            $table->double('net_weight')->nullable();
            $table->double('persons_to_carry')->nullable();
            $table->string('phone_no', 255)->nullable();
            $table->string('plate_no', 255)->nullable();
            $table->string('product_date', 255)->nullable();
            $table->string('register_date', 255)->nullable();
            $table->string('vehicle_type', 255)->nullable();
            
            $table->index(['plateno', 'vehicletype']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_vehicle_base');
    }
};
