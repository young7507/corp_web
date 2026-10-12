<?php
namespace app\kcadminapi\controller;

use app\model\Altplay;
use app\validate\AltplayValidate;

class Altpic
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class, \app\middleware\ApiSign::class];

    public function indexlist()
    {
        $page  = input('page', 1);
        $rows  = input('rows', 20);
        $list  = Altplay::order('ord', 'asc')->order('id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '/' : '/public/';
        foreach ($list->items() as $item) {
            $item->thumb = $item->thumb ? $pubpath . $item->thumb : '';
        }
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function insert()
    {
        $data = [
            'thumb' => '',
            'ord'   => input('ord', 0, 'intval'),
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
        if (file_exists(public_path() . $row->thumb)) {
            unlink(public_path() . $row->thumb);
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