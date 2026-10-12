<?php

namespace app\kcindexapi\controller;

use app\model\Cate as CateModel;

class Cate
{
    protected $middleware = [\app\middleware\ApiSign::class];

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

    public function getList()
    {
        $catelist1 = [];
        $catelist2 = [];
        $list = CateModel::whereBetween('ctype', [1, 2])->order('ctype', 'asc')->order('ord', 'asc')->select();
        foreach ($list as $v) {
            if ($v['ctype'] == 1) {
                $catelist1[] = $v;
            } else {
                $catelist2[] = $v;
            }
        }
        return jsonSuccess([
            'catedata1' => $catelist1,
            'catedata2' => $catelist2,
        ]);
    }
}