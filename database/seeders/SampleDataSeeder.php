<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "\n\nSeeding sample inspection data...\n";

        // Get the department ID
        $deptId = DB::table('sys_dept')->first()->id ?? 1;

        // Insert sample vehicles
        echo "[1/3] Creating sample vehicles...\n";
        for ($i = 1; $i <= 10; $i++) {
            DB::table('i_vehicle_base')->insert([
                'id' => $i,
                'plateno' => 'ABC-' . sprintf('%03d', $i),
                'vehicletype' => '01',
                'engineno' => 'ENG' . sprintf('%05d', $i),
                'chassisno' => 'CHR' . sprintf('%05d', $i),
                'makeofvehicle' => 'Toyota',
                'model' => 'Camry 2020',
                'licencetype' => 'A',
                'owner' => 'Owner ' . $i,
                'address' => 'Lagos, Nigeria',
                'phoneno' => '+234-XXX-' . sprintf('%04d', $i),
                'netweight' => 1500.00,
                'grossweight' => 1800.00,
                'personstocarry' => 5.0,
                'fueltype' => 'P',
                'headlampsystem' => 'H',
                'drivemethod' => 'M',
                'axisnumber' => 2,
                'createDate' => Carbon::now(),
            ]);
        }
        echo "   10 vehicles created\n\n";

        // Insert sample inspections
        echo "[2/3] Creating sample inspections...\n";
        for ($i = 1; $i <= 50; $i++) {
            $seriesNo = 'SER-' . sprintf('%05d', $i);
            $testResult = rand(0, 1) ? '1' : '0';
            
            DB::table('i_data_base')->insert([
                'id' => $i,
                'seriesno' => $seriesNo,
                'plateno' => 'ABC-' . sprintf('%03d', rand(1, 10)),
                'vehicletype' => '01',
                'licencetype' => 'A',
                'inspectdate' => Carbon::now()->subDays(rand(0, 90))->toDateString(),
                'inspecttimes' => '1',
                'inspecttype' => '01',
                'starttime' => Carbon::now()->subHours(rand(1, 48))->toDateTimeString(),
                'endTime' => Carbon::now()->subHours(rand(1, 48))->toDateTimeString(),
                'inspector' => 'Inspector ' . rand(1, 5),
                'testresult' => $testResult,
                'createDate' => Carbon::now()->subDays(rand(0, 90)),
                'dept_id' => $deptId,
                'owner' => 'Owner ' . rand(1, 10),
                'isupload' => '1',
            ]);
        }
        echo "   50 inspections created\n\n";

        // Insert sample brake data
        echo "[3/3] Creating sample brake data...\n";
        for ($i = 1; $i <= 50; $i++) {
            DB::table('i_data_brake_front')->insert([
                'id' => $i,
                'seriesno' => 'SER-' . sprintf('%05d', $i),
                'inspecttimes' => 1,
                'lftaxleload' => '800',
                'rgtaxleload' => '800',
                'axleload' => '1600',
                'lftbrakeforce' => '450',
                'rgtbrakeforce' => '450',
                'brakeeff' => '85',
                'stsbrakeeff' => '1',
                'dept_id' => $deptId,
            ]);
        }
        echo "   50 brake records created\n\n";

        echo "================================================\n";
        echo "========  SAMPLE DATA CREATED SUCCESSFULLY!\n";
        echo "================================================\n\n";
    }
}
