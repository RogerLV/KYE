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

        $this->createFolders();
    }

    private function seedRoles()
    {
        $roleList = [
            [1, 'Maker', false],
            [2, 'Checker', false],
            [3, 'App Admin', false],
            [4, 'Sys Admin', true],
        ];

        foreach ($roleList as $roleInfo) {
            $roleIns = new \App\Models\Role();
            $roleIns->id = $roleInfo[0];
            $roleIns->enName = $roleInfo[1];
            $roleIns->hide = $roleInfo[2];

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
                'User List',
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
            [
                'StaffViewEx',
                'View Ex-Staff',
                false,
                null,
                'ExStaffController',
                'listAll',
            ],
            [
                'OccupationalRiskMaker',
                'Occupational Risk Maker Edit Page',
                false,
                null,
                'OccupationalRiskController',
                'makerPage',
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
            1 => [1, 2, 3, 4],
            2 => [1, 2, 3, 4],
            3 => [1, 2, 3, 4],
            10 => [1, 2, 3, 4],
            11 => [1],
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
            ['LHI1', 3, 'HRD'],
            ['LHI1', 2, 'HRD'],
            ['LUC1', 2, 'ITD'],
            ['LUC1', 1, 'ITD'],
            ['TYI1', 1, 'HRD'],
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

    private function createFolders()
    {
        $folders = [
            'StaffList',
            'OccupationalRisk',
        ];

        foreach ($folders as $folder) {
            $dir = STORAGE_PATH.$folder;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }
}
