<?php

namespace app\kcindexapi\controller;

use app\model\Altplay;
use app\model\Product;
use app\model\News;
use app\model\Sys as SysModel;

class Index
{
    protected $middleware = [\app\middleware\ApiSign::class];

    public function protuilist()
    {
        $list = Product::where('tui', 1)->field('id,proname,subtitle,thumb,ord')->order('ord', 'asc')->order('id', 'desc')->select();

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        foreach ($list as $item) {
            if (file_exists(public_path() . $pubpath . '/' . $item->thumb)) {
                $item->thumb = $item->thumb ? $pubpath . '/' . $item->thumb : '';
            } else {
                $item->thumb = $pubpath . '/uploads/noimages.gif';
            }
        }

        $row = SysModel::where('webkey', 'ProExt')->find();
        return jsonSuccess(['rows' => $list, 'subtitle' => $row ? $row->webcon : '']);
    }

    public function newstuilist()
    {
        $list = News::where('tui', 1)->field('id,title,subtitle,thumb,ord')->order('ord', 'asc')->order('id', 'desc')->select();

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        foreach ($list as $item) {
            if (file_exists(public_path() . $pubpath . '/' . $item->thumb)) {
                $item->thumb = $item->thumb ? $pubpath . '/' . $item->thumb : '';
            } else {
                $item->thumb = $pubpath . '/uploads/noimages.gif';
            }
        }
        $row = SysModel::where('webkey', 'NewsExt')->find();
        return jsonSuccess(['rows' => $list, 'subtitle' => $row ? $row->webcon : '']);
    }

    public function altlist()
    {
        $list = Altplay::order('ord', 'asc')->order('id', 'desc')->select();

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        foreach ($list as $item) {
            $item->thumb = $item->thumb ? $pubpath . '/' . $item->thumb : '';
        }
        return jsonSuccess(['rows' => $list]);
    }

    public function getextkey()
    {
        $key = input('key');
        $row = SysModel::where('webkey', $key)->find();
        return jsonSuccess(['subtitle' => $row ? $row->webcon : '']);
    }
}