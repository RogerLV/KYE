<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function __construct()
    {
        // As of start of the application, override parent
        // constructor preventing user initialization.
    }

    public function welcome()
    {
        // get user and role info
        $loginUser = \App\Logic\LoginUser\LoginUserKeeper::initUser();
        $userInfo = \App\Models\User::where('lanID', $loginUser->lanID)->first();
        $roleList = \App\Models\UserRole::with('role')->where('lanID', $loginUser->lanID)
                        ->orderBy('roleID', 'DESC')
                        ->get();

        $pages = \App\Models\Page::join('RolePages', 'Pages.id', '=', 'RolePages.pageID')
                    ->where('Pages.showInEntrance', '=', true)
                    ->where('RolePages.roleID', '=', $loginUser->roleID)
                    ->get();

        return view('welcome')
                ->with('userInfo', $userInfo)
                ->with('roleList', $roleList)
                ->with('selectedMapID', $loginUser->id)
                ->with('pages', $pages);
    }
}
