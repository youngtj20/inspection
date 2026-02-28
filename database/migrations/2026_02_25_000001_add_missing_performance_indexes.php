<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // i_data_base - most queried table
        $this->addIndexIfMissing('i_data_base', 'idx_idb_testresult', ['testresult']);
        $this->addIndexIfMissing('i_data_base', 'idx_idb_createdate', ['createDate']);
        $this->addIndexIfMissing('i_data_base', 'idx_idb_dept_inspectdate', ['dept_id', 'inspectdate']);
        $this->addIndexIfMissing('i_data_base', 'idx_idb_dept_testresult', ['dept_id', 'testresult']);
        $this->addIndexIfMissing('i_data_base', 'idx_idb_inspector', ['inspector']);

        // i_vehicle_register - used heavily for vehicle listing
        $this->addIndexIfMissing('i_vehicle_register', 'idx_ivr_createdate', ['createDate']);
        $this->addIndexIfMissing('i_vehicle_register', 'idx_ivr_dept_id', ['dept_id']);
        $this->addIndexIfMissing('i_vehicle_register', 'idx_ivr_owner', ['owner']);

        // sys_action_log - used for activity log sorted by create_date
        $this->addIndexIfMissing('sys_action_log', 'idx_sal_create_date', ['create_date']);

        // sys_user - role lookups
        $this->addIndexIfMissing('sys_user', 'idx_su_dept_status', ['dept_id', 'status']);
    }

    public function down(): void
    {
        $drops = [
            ['i_data_base',        'idx_idb_testresult'],
            ['i_data_base',        'idx_idb_createdate'],
            ['i_data_base',        'idx_idb_dept_inspectdate'],
            ['i_data_base',        'idx_idb_dept_testresult'],
            ['i_data_base',        'idx_idb_inspector'],
            ['i_vehicle_register', 'idx_ivr_createdate'],
            ['i_vehicle_register', 'idx_ivr_dept_id'],
            ['i_vehicle_register', 'idx_ivr_owner'],
            ['sys_action_log',     'idx_sal_create_date'],
            ['sys_user',           'idx_su_dept_status'],
        ];

        foreach ($drops as [$table, $index]) {
            $this->dropIndexIfExists($table, $index);
        }
    }

    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();

        if (!$exists) {
            Schema::table($table, function (Blueprint $t) use ($indexName, $columns) {
                $t->index($columns, $indexName);
            });
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();

        if ($exists) {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        }
    }
};
