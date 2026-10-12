<?php
namespace app\kcadmin\controller;

use app\model\Admin;
use app\model\Sys as SysModel;
use app\validate\AdminValidate;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Sys
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function password()
    {
        return View::fetch('sys/password');
    }

    public function updatePassword()
    {
        $psdpwd = input('psdpwd', '');
        $newpwd = input('newpwd', '');
        $cfmpwd = input('cfmpwd', '');
        $validate = new AdminValidate();
        if (!$validate->scene('password')->check([
            'psdpwd' => $psdpwd,
            'newpwd' => $newpwd,
            'cfmpwd' => $cfmpwd,
        ])) {
            return jsonError($validate->getError());
        }
        if ($newpwd !== $cfmpwd) {
            return jsonError('新密码与确认密码不一致');
        }
        $admin = Admin::where('token', Session::get('token'))->find();
        if (!$admin) {
            return jsonError('用户未登录');
        }
        if ($admin->userpwd !== md5($psdpwd)) {
            return jsonError('原密码错误');
        }
        $admin->userpwd = md5($newpwd);
        $admin->token   = '';
        $admin->save();
        return jsonSuccess(null, '密码修改成功，请重新登录');
    }

    public function about()
    {
        return View::fetch('sys/about');
    }

    public function aboutinfo()
    {
        $row = SysModel::where('webkey', 'AboutUs')->find();
        if (!$row) {
            return jsonError('关于我们内容不存在');
        }
        return jsonSuccess(['content' => $row->webcon]);
    }

    public function aboutsave()
    {
        $content = input('content', '');
        if (empty($content)) {
            return jsonError('内容不能为空');
        }
        $row = SysModel::where('webkey', 'AboutUs')->find();
        if (!$row) {
            SysModel::create(['webkey' => 'AboutUs', 'webcon' => $content]);
        } else {
            $row->webcon = $content;
            $row->save();
        }
        return jsonSuccess(null, '保存成功');
    }

    public function contact()
    {
        return View::fetch('sys/contact');
    }

    public function contactinfo()
    {
        $row = SysModel::where('webkey', 'ContactUs')->find();
        if (!$row) {
            return jsonError('联络我们内容不存在');
        }
        return jsonSuccess(['content' => $row->webcon]);
    }

    public function contactsave()
    {
        $content = input('content', '');
        if (empty($content)) {
            return jsonError('内容不能为空');
        }
        $row = SysModel::where('webkey', 'ContactUs')->find();
        if (!$row) {
            SysModel::create(['webkey' => 'ContactUs', 'webcon' => $content]);
        } else {
            $row->webcon = $content;
            $row->save();
        }
        return jsonSuccess(null, '保存成功');
    }
}