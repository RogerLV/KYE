<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use App\Exceptions\AppException;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $loginUser;

    public function __construct()
    {
        $this->loginUser = \App\Logic\LoginUser\LoginUserKeeper::getUser();
    }

    protected function pageAccessible($class, $action)
    {
        $accessible = \App\Models\RolePage::join('Pages', 'Pages.id', '=', 'RolePages.pageID')
                        ->where('Pages.controller', last(explode('\\', $class)))
                        ->where('Pages.action', $action)
                        ->where('RolePages.roleID', $this->loginUser->roleID)
                        ->get();

        if ($accessible->isEmpty()) {
            throw new AppException('CTR001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        return true;
    }

    protected function checkParameters($paras, $optionalParas = [])
    {
        $paras = is_array($paras) ? $paras : [$paras];

        $rtn = [];
        foreach ($paras as $para) {

            if (empty(trim(request()->input($para)))) {
                throw new AppException('CTR002', ERROR_MESSAGE_DATA_ERROR);
            }

            $rtn[$para] = request()->input($para);
        }

        $paras = is_array($optionalParas) ? $optionalParas : [$optionalParas];
        foreach ($paras as $para) {
            $rtn[$para] = request()->input($para);
        }

        return $rtn;
    }
}
