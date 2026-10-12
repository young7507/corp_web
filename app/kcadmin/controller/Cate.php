<?php
namespace app\kcadmin\controller;

use app\model\Cate as CateModel;
use app\model\Product;
use app\model\News;
use app\validate\CateValidate;
use think\facade\Db;
use think\facade\View;

class Cate
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function news()
    {
        return View::fetch('cate/news');
    }

    public function product()
    {
        return View::fetch('cate/product');
    }

    public function index()
    {
        $ctype = input('ctype', 1, 'intval');
        $page  = input('page', 1);
        $rows  = input('rows', 20);
        $list  = CateModel::where('ctype', $ctype)->order('ord', 'asc')->order('id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function insert()
    {
        $ctype = input('ctype', 1, 'intval');//echo $ctype;exit;
        $data  = [
            'catename' => input('cate', ''),
            'ctype'=> $ctype,
            'ord'  => input('ord', 0, 'intval'),
        ];
        $validate = new CateValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        CateModel::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function update()
    {
        $id   = input('id', 0, 'intval');
        $row  = CateModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $data = [
            'catename' => input('cate', ''),
            'ord'  => input('ord', 0, 'intval'),
        ];
        $validate = new CateValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function delete()
    {
        $id  = input('id', 0, 'intval');
        $row = CateModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $prow = Product::where('cate', $id)->select();
        $nrow = News::where('cate', $id)->select();
        if (!$prow->isEmpty()) {
            return jsonError('该分类下存在产品，无法删除');
        }
        if (!$nrow->isEmpty()) {
            return jsonError('该分类下存在新闻，无法删除');
        }

        $row->delete();
        return jsonSuccess(null, '删除成功');
    }

    public function getList()
    {
        $ctype = input('ctype', 1, 'intval');
        $list  = CateModel::where('ctype', $ctype)->order('ord', 'asc')->select();
        return jsonSuccess($list);
    }
}