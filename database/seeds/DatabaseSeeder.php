<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->seedRoleList();
        $this->seedParameters();
    }

    private function seedRoleList()
    {
        $roleList = [
            ['LUC1', 4, 'ITD'],
            ['LUC1', 3, 'ITD'],
        ];

        foreach ($roleList as $entry) {
            $roleIns = new \App\Model\RoleList();
            $roleIns->lanID = $entry[0];
            $roleIns->roleID = $entry[1];
            $roleIns->dept = $entry[2];

            $roleIns->save();
        }
    }

    private function seedParameters()
    {
        $paras = [
            ['OccupationalRisk', 'low', 'Biennial'],
            ['OccupationalRisk', 'high', 'Annual'],
            ['RelationshipRisk', 'low', 'Biennial'],
            ['RelationshipRisk', 'high', 'HalfYearly'],
            ['SpecialFactorRisk', 'low', 'Biennial'],
            ['SpecialFactorRisk', 'high', 'Annual'],
        ];

        foreach ($paras as $entry) {
            $paraIns = new \App\Model\Parameter();
            $paraIns->key1 = $entry[0];
            $paraIns->key2 = $entry[1];
            $paraIns->value = $entry[2];
            $paraIns->save();
        }
    }
}
