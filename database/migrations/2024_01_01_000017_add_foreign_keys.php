<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Foreign keys for sys_dept
        Schema::table('sys_dept', function (Blueprint $table) {
            $table->foreign('create_by')->references('id')->on('sys_user')->onDelete('set null');
            $table->foreign('update_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        // Foreign keys for sys_user
        Schema::table('sys_user', function (Blueprint $table) {
            $table->foreign('dept_id')->references('id')->on('sys_dept')->onDelete('set null');
        });

        // Foreign keys for sys_role, sys_menu, sys_dict, sys_file
        Schema::table('sys_role', function (Blueprint $table) {
            $table->foreign('create_by')->references('id')->on('sys_user')->onDelete('set null');
            $table->foreign('update_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        Schema::table('sys_menu', function (Blueprint $table) {
            $table->foreign('create_by')->references('id')->on('sys_user')->onDelete('set null');
            $table->foreign('update_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        Schema::table('sys_dict', function (Blueprint $table) {
            $table->foreign('create_by')->references('id')->on('sys_user')->onDelete('set null');
            $table->foreign('update_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        Schema::table('sys_file', function (Blueprint $table) {
            $table->foreign('create_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        Schema::table('sys_action_log', function (Blueprint $table) {
            $table->foreign('oper_by')->references('id')->on('sys_user')->onDelete('set null');
        });

        // Foreign keys for role and menu relationships
        Schema::table('sys_user_role', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('sys_user')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('sys_role')->onDelete('cascade');
        });

        Schema::table('sys_role_menu', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('sys_role')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('sys_menu')->onDelete('cascade');
        });

        // Foreign keys for files
        Schema::table('f_equipment_files', function (Blueprint $table) {
            $table->foreign('dept_id')->references('id')->on('sys_dept')->onDelete('set null');
        });

        Schema::table('f_personnel_files', function (Blueprint $table) {
            $table->foreign('dept_id')->references('id')->on('sys_dept')->onDelete('set null');
        });

        // Foreign keys for inspection data
        Schema::table('i_data_base', function (Blueprint $table) {
            $table->foreign('dept_id')->references('id')->on('sys_dept')->onDelete('set null');
        });

        Schema::table('i_vehicle_register', function (Blueprint $table) {
            $table->foreign('dept_id')->references('id')->on('sys_dept')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Drop all foreign keys in reverse order
        Schema::table('i_vehicle_register', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('i_data_base', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('f_personnel_files', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('f_equipment_files', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('sys_role_menu', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['menu_id']);
        });

        Schema::table('sys_user_role', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['role_id']);
        });

        Schema::table('sys_action_log', function (Blueprint $table) {
            $table->dropForeign(['oper_by']);
        });

        Schema::table('sys_file', function (Blueprint $table) {
            $table->dropForeign(['create_by']);
        });

        Schema::table('sys_dict', function (Blueprint $table) {
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::table('sys_menu', function (Blueprint $table) {
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::table('sys_role', function (Blueprint $table) {
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });

        Schema::table('sys_user', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
        });

        Schema::table('sys_dept', function (Blueprint $table) {
            $table->dropForeign(['create_by']);
            $table->dropForeign(['update_by']);
        });
    }
};
