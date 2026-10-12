<?php
namespace app\kcadmin\controller;

use app\model\Message as MessageModel;
use app\validate\MessageValidate;
use think\facade\Db;
use think\facade\View;

class Message
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function index()
    {
        return View::fetch('message/index');
    }

    public function indexlist()
    {
        $page = input('page', 1);
        $rows = input('rows', 20);
        $list = MessageModel::order('id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function update()
    {
        $id    = input('id', 0, 'intval');
        $reply = input('reply', '');
        $row   = MessageModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $validate = new MessageValidate();
        if (!$validate->check(['reply' => $reply])) {
            return jsonError($validate->getError());
        }
        $row->reply = $reply;
        $row->save();
        return jsonSuccess(null, '回复成功');
    }

    public function delete()
    {
        $id  = input('id', 0, 'intval');
        $row = MessageModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }
}