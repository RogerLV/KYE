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
        $this->seedRoles();
        $this->seedPages();
        $this->seedUserRoles();
        $this->seedParameters();
        $this->seedTestData();

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
            1 => [
                'StaffList',
                'Staff List', 
                true, 
                'glyphicon glyphicon-list-alt', 
                'StaffController', 
                'listAll',
            ],
            2 => [
                'OccupationalRiskList',
                'Occupational Risk', 
                true, 
                'glyphicon glyphicon-stats', 
                'OccupationalRiskController', 
                'listAll',
            ],
            3 => [
                'RoleList',
                'User List',
                true, 
                'glyphicon glyphicon-user', 
                'RoleController', 
                'listAll',
            ],
            4 => [
                'ReviewPeriodView',
                'Review Period Settings',
                true,
                'glyphicon glyphicon-calendar',
                'ReviewPeriodController',
                'view',
            ],
            5 => [
                'StaffInfo',
                'Staff Info',
                false,
                null,
                'StaffController',
                'view',
            ],
            6 => [
                'KYECaseCreate',
                'Create KYE Case',
                false,
                null,
                'KYECaseController',
                'create',
            ],
            7 => [
                'KYECaseView',
                'View KYE Case',
                false,
                null,
                'KYECaseController',
                'view',
            ],
            8 => [
                'OccupationalRiskChecker',
                'Occupational Risk Checker Approve Page',
                false,
                null,
                'OccupationalRiskController',
                'checkerPage',
            ],
            9 => [
                'KYECaseListPending',
                'Pending KYE Cases',
                true,
                'glyphicon glyphicon-search',
                'KYECaseController',
                'listPending',
            ],
            10 => [
                'StaffViewEx',
                'View Ex-Staff',
                false,
                null,
                'ExStaffController',
                'listAll',
            ],
            11 => [
                'OccupationalRiskMaker',
                'Occupational Risk Maker Edit Page',
                false,
                null,
                'OccupationalRiskController',
                'makerPage',
            ],
            12 => [
                'KYECaseChecker',
                'KYE Case Checker Page',
                false,
                null,
                'KYECaseController',
                'checkerPage',
            ],
            13 => [
                'ReviewPeriodEdit',
                'Review Period Settings Edit Page',
                false,
                null,
                'ReviewPeriodController',
                'edit',
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
            4 => [1, 2, 3, 4],
            5 => [1, 2, 3, 4],
            6 => [1],
            7 => [1, 2, 3, 4],
            8 => [2],
            9 => [1, 2],
            10 => [1, 2, 3, 4],
            11 => [1],
            12 => [2],
            13 => [3],
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
            ['RelationshipRisk', 'high', 'Half Yearly'],
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

    private function seedTestData()
    {
        // seed staff
        $staff = [
            ['12390', 'Info Technology', 'Lu Chao', 'IT_Research and Innovation', '2015-04-27', 1],
            ['12417', 'Info Technology', 'Jin Shuanghai', 'IT_Data and Reporting', '2015-06-08', 2],
        ];

        foreach ($staff as $entry) {
            $ins = new \App\Models\Staff();
            $ins->employNo = $entry[0];
            $ins->department = $entry[1];
            $ins->uEngName = $entry[2];
            $ins->section = $entry[3];
            $ins->joinDate = $entry[4];
            $ins->pendingCaseID = $entry[5];

            $ins->save();
        }

        // seed occupational risk
        $occupationalRisks = [
            ['Info Technology', 'IT_Research and Innovation', 'low'],
            ['Info Technology', 'IT_Data and Reporting', 'low'],
        ];

        foreach ($occupationalRisks as $entry) {
            $ins = new \App\Models\OccupationalRisk();
            $ins->department = $entry[0];
            $ins->section = $entry[1];
            $ins->riskLevel = $entry[2];

            $ins->save();
        }
        
        // seed pending KYE case
        $documents = [
            ['DowJones', 'Screen Shot 2017-05-03 at 10.41.35 PM.png', '/DowJones/12390/thepbQmQMju14lg6p.png'],
            ['Questnet', 'Screen Shot 2017-05-03 at 10.41.35 PM.png', '/Questnet/12390/tR3dWjTobIEcSI0R3.png'],
            ['CreditBureau', 'Screen Shot 2017-05-03 at 10.09.42 PM.png', '/CreditBureau/12390/tNVFPi9NHDdMC9QN1.png'],
        ];

        foreach ($documents as $doc) {
            $ins = new \App\Models\Document();
            $ins->type = $doc[0];
            $ins->origName = $doc[1];
            $ins->subAddr = $doc[2];

            $ins->save();
        }

        $operationLogs = [
            ['KYECases', 'insert', [
                'employNo' => '12390',
                'name' => 'Lu Chao',
                'department' => 'Info Technology',
                'section' => 'IT_Research and Innovation',
                'DowJonesFileID' => 1,
                'QuestnetFileID' => 2,
                'CreditBureauFileID' => 3,
                'occupationalRisk' => 'low',
                'relationshipRisk' => 'low',
                'specialFactors' => 'low',
                'overallRisk' => 'low',
            ], 'LHI1',
            ],
            ['KYECases', 'insert', [
                'employNo' => '12417',
                'name' => 'Jin Shuanghai',
                'department' => 'Info Technology',
                'section' => 'IT_Data and Reporting',
                'DowJonesFileID' => 1,
                'QuestnetFileID' => 2,
                'CreditBureauFileID' => 3,
                'occupationalRisk' => 'low',
                'relationshipRisk' => 'low',
                'specialFactors' => 'low',
                'overallRisk' => 'low',
            ], 'LHI1',
            ]
        ];

        foreach ($operationLogs as $entry) {
            $ins = new \App\Models\OperationLog();
            $ins->tableName = $entry[0];
            $ins->type = $entry[1];
            $ins->to = json_encode($entry[2]);
            $ins->madeBy = $entry[3];
            $ins->save();
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
