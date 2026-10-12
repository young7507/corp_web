<?php
namespace app\kcadmin\controller;

use app\model\Altplay;
use app\validate\AltplayValidate;
use think\facade\Db;
use think\facade\View;

class Altpic
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];
    /*
    public function _init()
    {
        parent::_init();
    }
        */

    public function index()
    {
        return View::fetch('altpic/index');
    }

    public function indexlist()
    {
        $page  = input('page', 1);
        $rows  = input('rows', 20);
        $list  = Altplay::order('ord', 'asc')->order('id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function insert()
    {
        $data = [
            'thumb' => '',
            'ord' => input('ord', 0, 'intval'),
        ];
        $file = null;
        if (!empty($_FILES['altpic']['tmp_name']) && $_FILES['altpic']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('altpic');
        }
        if ($file) {
            $result = uploadImage($file, 'uploads/altplay');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['thumb'] = $result['data'];
        } else {
            return jsonError('请上传轮播图片');
        }
        $validate = new AltplayValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        Altplay::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function update()
    {
        $id   = input('id', 0, 'intval');
        $row  = Altplay::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $data = [
            'ord' => input('ord', 0, 'intval'),
        ];
        $file = null;
        if (!empty($_FILES['altpic']['tmp_name']) && $_FILES['altpic']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('altpic');
        }
        if ($file) {
            $result = uploadImage($file, 'uploads/altplay');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['thumb'] = $result['data'];
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function delete()
    {
        $id  = input('id', 0, 'intval');
        $row = Altplay::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }

    public function updateStatus()
    {
        $id     = input('id', 0, 'intval');
        $ctype  = input('ctype', 0, 'intval');
        $row    = Altplay::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->ctype = $ctype;
        $row->save();
        return jsonSuccess(null, '状态更新成功');
    }
}