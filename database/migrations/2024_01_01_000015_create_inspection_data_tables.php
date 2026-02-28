<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Brake Front
        Schema::create('i_data_brake_front', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
            $table->string('stsfrictioneff', 2)->nullable();
            $table->string('stsbrakeforce', 2)->nullable();
            $table->string('stshandbrakeforce', 2)->nullable();
            $table->string('stsaxleload', 2)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stsbrakediff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->string('stshandbrakediff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Brake Rear
        Schema::create('i_data_brake_rear', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
            $table->string('stsfrictioneff', 2)->nullable();
            $table->string('stsbrakeforce', 2)->nullable();
            $table->string('stshandbrakeforce', 2)->nullable();
            $table->string('stsaxleload', 2)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stsbrakediff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->string('stshandbrakediff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Brake Rear 02
        Schema::create('i_data_brake_rear02', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
            $table->string('stsfrictioneff', 2)->nullable();
            $table->string('stsbrakeforce', 2)->nullable();
            $table->string('stshandbrakeforce', 2)->nullable();
            $table->string('stsaxleload', 2)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stsbrakediff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->string('stshandbrakediff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Brake Rear 03, 04, 05 similar structure
        Schema::create('i_data_brake_rear03', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
            $table->string('stsfrictioneff', 2)->nullable();
            $table->string('stsbrakeforce', 2)->nullable();
            $table->string('stshandbrakeforce', 2)->nullable();
            $table->string('stsaxleload', 2)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stsbrakediff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->string('stshandbrakediff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        Schema::create('i_data_brake_rear04', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
            $table->string('stsfrictioneff', 2)->nullable();
            $table->string('stsbrakeforce', 2)->nullable();
            $table->string('stshandbrakeforce', 2)->nullable();
            $table->string('stsaxleload', 2)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stsbrakediff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->string('stshandbrakediff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        Schema::create('i_data_brake_rear05', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftaxleload', 10)->nullable();
            $table->string('rgtaxleload', 10)->nullable();
            $table->string('axleload', 10)->nullable();
            $table->string('lftbrakeforce', 10)->nullable();
            $table->string('rgtbrakeforce', 10)->nullable();
            $table->string('lfthandbrake', 10)->nullable();
            $table->string('rgthandbrake', 10)->nullable();
            $table->string('lftfrictioneff', 10)->nullable();
            $table->string('rgtfrictioneff', 10)->nullable();
            $table->string('lftbrakeeff', 10)->nullable();
            $table->string('rgtbrakeeff', 10)->nullable();
            $table->string('brakeeff', 10)->nullable();
            $table->string('lftbrakediff', 10)->nullable();
            $table->string('rgtbrakediff', 10)->nullable();
            $table->string('brakediff', 10)->nullable();
            $table->string('lfthandbrakeeff', 10)->nullable();
            $table->string('rgthandbrakeeff', 10)->nullable();
            $table->string('handbrakeeff', 10)->nullable();
            $table->string('lfthandbrakediff', 10)->nullable();
            $table->string('rgthandbrakediff', 10)->nullable();
            $table->string('handbrakediff', 10)->nullable();
        });

        // Brake Rear 06 with InnoDB
        Schema::create('i_data_brake_rear06', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('axleload', 255)->nullable();
            $table->string('brakediff', 255)->nullable();
            $table->string('brakeeff', 255)->nullable();
            $table->string('handbrakediff', 255)->nullable();
            $table->string('handbrakeeff', 255)->nullable();
            $table->integer('inspectTimes')->nullable();
            $table->string('lftaxleload', 255)->nullable();
            $table->string('lftbrakediff', 255)->nullable();
            $table->string('lftbrakeeff', 255)->nullable();
            $table->string('lftbrakeforce', 255)->nullable();
            $table->string('lftfrictioneff', 255)->nullable();
            $table->string('lfthandbrake', 255)->nullable();
            $table->string('lfthandbrakediff', 255)->nullable();
            $table->string('lfthandbrakeeff', 255)->nullable();
            $table->string('rgtaxleload', 255)->nullable();
            $table->string('rgtbrakediff', 255)->nullable();
            $table->string('rgtbrakeeff', 255)->nullable();
            $table->string('rgtbrakeforce', 255)->nullable();
            $table->string('rgtfrictioneff', 255)->nullable();
            $table->string('rgthandbrake', 255)->nullable();
            $table->string('rgthandbrakediff', 255)->nullable();
            $table->string('rgthandbrakeeff', 255)->nullable();
            $table->string('seriesNo', 255)->nullable();
            $table->string('stsbrakediff', 255)->nullable();
            $table->string('stsbrakeeff', 255)->nullable();
            $table->string('stsfrictioneff', 255)->nullable();
            $table->string('stshandbrakediff', 255)->nullable();
            $table->string('stshandbrakeeff', 255)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_data_brake_front');
        Schema::dropIfExists('i_data_brake_rear');
        Schema::dropIfExists('i_data_brake_rear02');
        Schema::dropIfExists('i_data_brake_rear03');
        Schema::dropIfExists('i_data_brake_rear04');
        Schema::dropIfExists('i_data_brake_rear05');
        Schema::dropIfExists('i_data_brake_rear06');
    }
};
