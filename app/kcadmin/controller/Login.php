<?php
namespace app\kcadmin\controller;

use app\model\Admin;
use app\validate\AdminValidate;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Login
{
    public function index()
    {
        if (!request()->isAjax() && request()->isGet()) {
            return View::fetch('login/index');
        }
        $username = input('username', '');
        $userpwd  = input('userpwd', '');
        $validate = new AdminValidate();
        if (!$validate->scene('login')->check(['username' => $username, 'userpwd' => $userpwd])) {
            return jsonError($validate->getError());
        }
        $admin = Admin::where('username', $username)->find();
        if (!$admin) {
            return jsonError('用户名不存在');
        }
        if ($admin->userpwd !== md5($userpwd)) {
            return jsonError('密码错误'.$userpwd);
        }
        $token = generateToken();
        $admin->token = $token;
        $admin->save();
        Session::set('token', $token);
        Session::set('username', $admin->username);
        return jsonSuccess([
            'token'    => $token,
            'username' => $admin->username,
        ], '登录成功');
    }

    public function logout()
    {
        //$token = input('token', '') ?: request()->header('token', '');
        $token = Session::get('token');
        if ($token) {
            $admin = Admin::where('token', $token)->find();
            if ($admin) {
                $admin->token = '';
                $admin->save();
            }
        }
        Session::clear();
        return redirect('/kcadmin/login/index');
        //return jsonSuccess(null, '退出成功');
    }
}