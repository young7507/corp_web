<?php
namespace app\kcadminapi\controller;

use app\model\Admin;
use app\model\Sys as SysModel;
use app\model\HistoryModel;
use app\validate\AdminValidate;

class Sys
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class, \app\middleware\ApiSign::class];

    public function getmenu()
    {
        $menu = get_sys_config();
        return jsonSuccess(['sys' => $menu]);
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
        $token = request()->header('token', '') ?: input('token', '');
        $admin = Admin::where('token', $token)->find();
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

    public function aboutinfo()
    {
        $ispublic = config('app.ispublic');
        $row = SysModel::where('webkey', 'AboutUs')->find();
        if (!$row) {
            return jsonError('关于我们内容不存在');
        }
        if (!$ispublic) {
            $row->webcon = str_replace('/uploads', '/public/uploads', $row->webcon);
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

    public function contactinfo()
    {
        $ispublic = config('app.ispublic');
        $row = SysModel::where('webkey', 'ContactUs')->find();
        if (!$row) {
            return jsonError('联络我们内容不存在');
        }
        if (!$ispublic) {
            $row->webcon = str_replace('/uploads', '/public/uploads', $row->webcon);
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

    public function hislist()
    {
        $list = HistoryModel::order('hisord', 'asc')->order('id', 'desc')
            ->select();
        return jsonSuccess([
            'rows' => $list->items(),
        ]);
    }

    public function getextlist()
    {
        $list = SysModel::where('webkey', 'ABExt')
            ->whereOr('webkey', 'ProExt')
            ->whereOr('webkey', 'NewsExt')
            ->whereOr('webkey', 'CTExt')
            ->whereOr('webkey', 'MSGExt')
            ->whereOr('webkey', 'DevlpExt')
            ->order('id', 'asc')
            ->select();
        foreach ($list as $item) {
            if ($item->webkey == 'ABExt') {
                $item->name = '关于我们';
            } elseif ($item->webkey == 'ProExt') {
                $item->name = '产品展示';
            } elseif ($item->webkey == 'NewsExt') {
                $item->name = '新闻中心';
            } elseif ($item->webkey == 'CTExt') {
                $item->name = '联络我们';
            } elseif ($item->webkey == 'MSGExt') {
                $item->name = '在线留言';
            } elseif ($item->webkey == 'DevlpExt') {
                $item->name = '发展历程';
            }
        }
        return jsonSuccess([
            'rows' => $list,
        ]);
    }

    public function extupdate()
    {
        $id = intval(input('id', 0));
        $content = input('content', '');
        if (!$id) {
            return jsonError('参数错误');
        }
        if (!$content) {
            return jsonError('内容不能为空');
        }
        $row = SysModel::where('id', $id)->find();
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->webcon = $content;
        $row->save();
        return jsonSuccess('更新成功');
    }
}