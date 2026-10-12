<?php
namespace app\kcadmin\controller;

use app\model\Admin;
use app\model\Sys as SysModel;
use app\validate\AdminValidate;
use think\facade\Db;
use think\facade\Session;

class Base
{
    //protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function _init()
    {
        $token = Session::get('token');
        $username = Session::get('username');
        if (empty($token) || empty($username)) {
            $token = request()->param('token', '');
            if (request()->isAjax()) {
                return json(['code' => 401, 'data' => null, 'msg' => '未登录，请先登录']);
            } else {
                return redirect('/kcadmin/login/index');
            }
        }
    }
}