<?php

namespace app\kcindexapi\controller;

use app\model\Product as ProductModel;
use app\model\Propic;
use app\model\Cate;
use app\model\Sys as SysModel;

class Pro
{
    protected $middleware = [\app\middleware\ApiSign::class];

    public function indexlist()
    {
        $cate = input('cate', 1);
        $page = input('page', 1);
        $rows = input('rows', 20);
        $list = ProductModel::alias('p')
            ->leftJoin('cate c', 'p.cate=c.id')
            ->field('p.*, c.catename')
            ->where('p.cate', $cate)
            ->order('p.ord', 'asc')
            ->order('p.id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        foreach ($list->items() as $item) {
            $item->thumb = $item->thumb ? $pubpath . '/' . $item->thumb : '';
        }

        $row = SysModel::where('webkey', 'ProExt')->find();
        $subtitle = $row ? $row->webcon : '';
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
            'subtitle' => $subtitle,
        ]);
    }

    public function getinfo()
    {
        $id  = input('id', 0, 'intval');
        $row = ProductModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $allPic = Propic::where('proid', $id)->order('ord', 'asc')->select();
        if ($allPic) {
            $row->pic = $allPic;
        }
        return jsonSuccess($row);
    }

    public function getcate()
    {
        $cateList = Cate::where('ctype', 2)->order('ord', 'asc')->select();
        return jsonSuccess($cateList);
    }
}