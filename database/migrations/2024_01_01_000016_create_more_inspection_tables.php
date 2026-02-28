<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Brake Summary
        Schema::create('i_data_brake_summary', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('tolbrakeeff', 10)->nullable();
            $table->string('tolhandbrakeeff', 10)->nullable();
            $table->string('tolload', 10)->nullable();
            $table->string('stsbrakeeff', 2)->nullable();
            $table->string('stshandbrakeeff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Gas Data
        Schema::create('i_data_gas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('idlhcmax', 10)->nullable();
            $table->string('idlhcmin', 10)->nullable();
            $table->string('idlhcaverage', 10)->nullable();
            $table->string('stsidlhc', 2)->nullable();
            $table->string('hghhcmax', 10)->nullable();
            $table->string('hghhcmin', 10)->nullable();
            $table->string('hghhcaverage', 10)->nullable();
            $table->string('stshghhc', 2)->nullable();
            $table->string('idlcomax', 10)->nullable();
            $table->string('idlcomin', 10)->nullable();
            $table->string('idlcoaverage', 10)->nullable();
            $table->string('stsidlco', 2)->nullable();
            $table->string('hghcomax', 10)->nullable();
            $table->string('hghcomin', 10)->nullable();
            $table->string('hghcoaverage', 10)->nullable();
            $table->string('stshghco', 2)->nullable();
            $table->string('idllambdamax', 10)->nullable();
            $table->string('idllambdamin', 10)->nullable();
            $table->string('idllambdaaverage', 10)->nullable();
            $table->string('stsidllambda', 2)->nullable();
            $table->string('hghlambdamax', 10)->nullable();
            $table->string('hghlambdamin', 10)->nullable();
            $table->string('hghlambdaaverage', 10)->nullable();
            $table->string('stshghlambda', 2)->nullable();
            $table->string('idlco2max', 10)->nullable();
            $table->string('idlco2min', 10)->nullable();
            $table->string('idlco2average', 10)->nullable();
            $table->string('stsidlco2', 2)->nullable();
            $table->string('hghco2max', 10)->nullable();
            $table->string('hghco2min', 10)->nullable();
            $table->string('hghco2average', 10)->nullable();
            $table->string('stshghco2', 2)->nullable();
            $table->string('idlo2max', 10)->nullable();
            $table->string('idlo2min', 10)->nullable();
            $table->string('idlo2average', 10)->nullable();
            $table->string('stsidlo2', 2)->nullable();
            $table->string('hgho2max', 10)->nullable();
            $table->string('hgho2min', 10)->nullable();
            $table->string('hgho2average', 10)->nullable();
            $table->string('stshgho2', 2)->nullable();
            $table->string('idlnomax', 10)->nullable();
            $table->string('idlnomin', 10)->nullable();
            $table->string('idlnoaverage', 10)->nullable();
            $table->string('stsidlno', 2)->nullable();
            $table->string('hghnomax', 10)->nullable();
            $table->string('hghnomin', 10)->nullable();
            $table->string('hghnoaverage', 10)->nullable();
            $table->string('stshghno', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Headlamp Left
        Schema::create('i_data_headlamp_left', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('height', 10)->nullable();
            $table->string('lightintensity', 10)->nullable();
            $table->string('offsetlrfar', 10)->nullable();
            $table->string('offsetlrnear', 10)->nullable();
            $table->string('offsetudfar', 10)->nullable();
            $table->string('offsetudnear', 10)->nullable();
            $table->string('stslightintensity', 2)->nullable();
            $table->string('stsoffsetlrfar', 2)->nullable();
            $table->string('stsoffsetlrnear', 2)->nullable();
            $table->string('stsoffsetudfar', 2)->nullable();
            $table->string('stsoffsetudnear', 2)->nullable();
            $table->string('stsheight', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Headlamp Right
        Schema::create('i_data_headlamp_right', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('height', 10)->nullable();
            $table->string('lightintensity', 10)->nullable();
            $table->string('offsetlrfar', 10)->nullable();
            $table->string('offsetlrnear', 10)->nullable();
            $table->string('offsetudfar', 10)->nullable();
            $table->string('offsetudnear', 10)->nullable();
            $table->string('stslightintensity', 2)->nullable();
            $table->string('stsoffsetlrfar', 2)->nullable();
            $table->string('stsoffsetlrnear', 2)->nullable();
            $table->string('stsoffsetudfar', 2)->nullable();
            $table->string('stsoffsetudnear', 2)->nullable();
            $table->string('stsheight', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Pit
        Schema::create('i_data_pit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('defectcode', 30)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('description', 800)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Sideslip
        Schema::create('i_data_sideslip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('slide', 10)->nullable();
            $table->string('stsslide', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Smoke
        Schema::create('i_data_smoke', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('n1', 10)->nullable();
            $table->string('n2', 10)->nullable();
            $table->string('n3', 10)->nullable();
            $table->string('n4', 10)->nullable();
            $table->string('naverage', 10)->nullable();
            $table->string('stsn', 2)->nullable();
            $table->string('k1', 10)->nullable();
            $table->string('k2', 10)->nullable();
            $table->string('k3', 10)->nullable();
            $table->string('k4', 10)->nullable();
            $table->string('kaverage', 10)->nullable();
            $table->string('stsk', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Speedometer
        Schema::create('i_data_speedometer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inspectTimes')->nullable();
            $table->string('seriesNo', 30)->nullable();
            $table->string('speed', 10)->nullable();
            $table->string('stsspeed', 2)->nullable();
            $table->string('deptId', 255)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
        });

        // Suspension Front
        Schema::create('i_data_suspension_front', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftweight', 10)->nullable();
            $table->string('rgtweight', 10)->nullable();
            $table->string('lftsuspension', 10)->nullable();
            $table->string('rgtsuspension', 10)->nullable();
            $table->string('suspensiondiff', 10)->nullable();
            $table->string('suspensioneff', 10)->nullable();
            $table->string('stssuspension', 2)->nullable();
            $table->string('stssuspensiondiff', 2)->nullable();
            $table->string('stssuspensioneff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Suspension Rear
        Schema::create('i_data_suspension_rear', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('lftweight', 10)->nullable();
            $table->string('rgtweight', 10)->nullable();
            $table->string('lftsuspension', 10)->nullable();
            $table->string('rgtsuspension', 10)->nullable();
            $table->string('suspensiondiff', 10)->nullable();
            $table->string('suspensioneff', 10)->nullable();
            $table->string('stssuspension', 2)->nullable();
            $table->string('stssuspensiondiff', 2)->nullable();
            $table->string('stssuspensioneff', 2)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });

        // Visual
        Schema::create('i_data_visual', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seriesno', 30)->nullable();
            $table->integer('inspecttimes')->nullable();
            $table->string('defectcode', 30)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('description', 800)->nullable();
            $table->bigInteger('dept_id')->nullable();
            $table->integer('inspect_times')->nullable();
            $table->string('series_no', 255)->nullable();
            
            $table->index('dept_id');
            $table->index(['seriesno', 'inspecttimes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('i_data_brake_summary');
        Schema::dropIfExists('i_data_gas');
        Schema::dropIfExists('i_data_headlamp_left');
        Schema::dropIfExists('i_data_headlamp_right');
        Schema::dropIfExists('i_data_pit');
        Schema::dropIfExists('i_data_sideslip');
        Schema::dropIfExists('i_data_smoke');
        Schema::dropIfExists('i_data_speedometer');
        Schema::dropIfExists('i_data_suspension_front');
        Schema::dropIfExists('i_data_suspension_rear');
        Schema::dropIfExists('i_data_visual');
    }
};
