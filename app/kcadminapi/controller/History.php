<?php
namespace app\kcadminapi\controller;

use app\model\HistoryModel;
use app\validate\HistoryValidate;

class History
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class, \app\middleware\ApiSign::class];

    public function indexlist()
    {
        $page = input('page', 1);
        $rows = input('rows', 20);
        $list = HistoryModel::order('hisord', 'asc')->order('id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function insert()
    {
        $data = [
            'hisdate'  => input('hisdate', '', 'trim'),
            'histitle' => input('histitle', '', 'trim'),
            'hiscon'   => input('hiscon', '', 'trim'),
            'hisord'   => input('hisord', 0, 'intval'),
        ];
        $validate = new HistoryValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        HistoryModel::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function update()
    {
        $id  = input('id', 0, 'intval');
        $row = HistoryModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }

        $data = [
            'hisdate'  => input('hisdate', '', 'trim'),
            'histitle' => input('histitle', '', 'trim'),
            'hiscon'   => input('hiscon', '', 'trim'),
            'istui'    => input('istui', 0, 'intval'),
            'hisord'   => input('hisord', 0, 'intval'),
        ];
        $validate = new HistoryValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function updateStatus()
    {
        $id   = input('id', 0, 'intval');
        $istui = input('istui', 0, 'intval');
        $row  = HistoryModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->istui = $istui;
        $row->save();
        return jsonSuccess(null, '状态更新成功');
    }

    public function delete()
    {
        $id  = input('id', 0, 'intval');
        $row = HistoryModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }
}