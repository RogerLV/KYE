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
        $this->seedRoles();
        $this->seedPages();
        $this->seedUserRoles();
        $this->seedParameters();
    }

    private function seedRoles()
    {
        $roleList = [
            1 => 'Maker',
            2 => 'Checker',
            3 => 'App Admin',
            4 => 'Sys Admin',
        ];

        foreach ($roleList as $roleID => $roleName) {
            $roleIns = new \App\Models\Role();
            $roleIns->id = $roleID;
            $roleIns->enName = $roleName;

            $roleIns->save();
        }
    }

    private function seedPages()
    {
        $pages = [
            [
                'StaffList',
                'Staff List', 
                true, 
                'glyphicon glyphicon-list-alt', 
                'StaffController', 
                'listAll',
            ],
            [
                'OccupationalRiskList',
                'Occupational Risk', 
                true, 
                'glyphicon glyphicon-stats', 
                'OccupationalRiskController', 
                'listAll',
            ],
            [
                'RoleList',
                'Role List', 
                true, 
                'glyphicon glyphicon-user', 
                'RoleController', 
                'listAll',
            ],
            [
                'ReviewPeriodSetting',
                'Review Period Setting',
                true,
                'glyphicon glyphicon-calendar',
                'ReviewPeriodController',
                'setting',
            ],
            [
                'StaffView',
                'Staff Info',
                false,
                null,
                'StaffController',
                'view',
            ],
            [
                'KYECaseCreate',
                'Create KYE Case',
                false,
                null,
                'KYECaseController',
                'new',
            ],
            [
                'KYECaseView',
                'View KYE Case',
                false,
                null,
                'KYECaseController',
                'view',
            ],
            [
                'OccupationalRiskCheck',
                'Occupational Risk Check',
                false,
                null,
                'OccupationalRiskController',
                'check',
            ],
            [
                'KYECaseCheck',
                'KYE Case Check',
                false,
                null,
                'KYECaseController',
                'check',
            ],
        ];

        foreach ($pages as $pageInfo) {
            $pageIns = new \App\Models\Page();
            $pageIns->name = $pageInfo[0];
            $pageIns->title = $pageInfo[1];
            $pageIns->showInEntrance = $pageInfo[2];
            $pageIns->icon = $pageInfo[3];
            $pageIns->controller = $pageInfo[4];
            $pageIns->action = $pageInfo[5];

            $pageIns->save();
        }

        $pageControl = [
            3 => [1, 2, 3, 4],
        ];

        foreach ($pageControl as $pageID => $roleIDs) {
            foreach ($roleIDs as $roleID) {
                $rolePageIns = new \App\Models\RolePage();
                $rolePageIns->roleID = $roleID;
                $rolePageIns->pageID = $pageID;

                $rolePageIns->save();
            }
        }
    }

    private function seedUserRoles()
    {
        $roleList = [
            ['LUC1', 4, 'ITD'],
            ['LUC1', 3, 'ITD'],
        ];

        foreach ($roleList as $entry) {
            $roleIns = new \App\Models\UserRole();
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
            $paraIns = new \App\Models\Parameter();
            $paraIns->key1 = $entry[0];
            $paraIns->key2 = $entry[1];
            $paraIns->value = $entry[2];
            $paraIns->save();
        }
    }
}
