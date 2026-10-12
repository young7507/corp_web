<?php

namespace app\kcindexapi\controller;

use app\model\Admin;
use app\validate\AdminValidate;

class Login
{
    public function index()
    {
        $username = input('username', '');
        $userpwd  = input('userpwd', '');
        $validate = new AdminValidate();
        if (!$validate->scene('login')->check(['username' => $username, 'userpwd' => $userpwd])) {
            return jsonError($validate->getError());
        }
        $admin = Admin::where('username', $username)->find();
        if (!$admin) {
            return jsonError('用户名或密码错误！');
        }
        if ($admin->userpwd !== md5($userpwd)) {
            return jsonError('用户名或密码错误！');
        }
        $token = generateToken();
        $admin->token = $token;
        $admin->save();
        return jsonSuccess([
            'token'    => $token,
            'username' => $admin->username,
        ], '登录成功');
    }

    public function logout()
    {
        $token = input('token', '') ?: request()->header('token', '');
        if ($token) {
            $admin = Admin::where('token', $token)->find();
            if ($admin) {
                $admin->token = '';
                $admin->save();
            }
        }
        return jsonSuccess(null, '退出成功');
    }
}