<?php
namespace app\index\controller;

use app\model\Altplay;
use app\model\Product;
use app\model\News;
use app\model\Cate;
use app\model\Sys;
use app\model\Message;

class Index
{
    public function index()
    {
        redirect('/index');
        //return '企业官网前台首页';
    }

    public function altplay()
    {
        $list = Altplay::where('ctype', 0)->order('ord', 'asc')->select();
        return jsonSuccess($list);
    }

    public function about()
    {
        $row = Sys::where('webkey', 'AboutUs')->find();
        return jsonSuccess($row ? $row->webcon : '');
    }

    public function contact()
    {
        $row = Sys::where('webkey', 'ContactUs')->find();
        return jsonSuccess($row ? $row->webcon : '');
    }

    public function productList()
    {
        $cate = input('cate', 0, 'intval');
        $page = input('page', 1);
        $rows = input('rows', 10);
        $query = Product::order('ord', 'asc')->order('id', 'desc');
        if ($cate > 0) {
            $query->where('cate', $cate);
        }
        $list = $query->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess($list);
    }

    public function productDetail()
    {
        $id  = input('id', 0, 'intval');
        $row = Product::find($id);
        if (!$row) {
            return jsonError('产品不存在');
        }
        return jsonSuccess($row);
    }

    public function newsList()
    {
        $cate = input('cate', 0, 'intval');
        $page = input('page', 1);
        $rows = input('rows', 10);
        $query = News::order('ord', 'asc')->order('id', 'desc');
        if ($cate > 0) {
            $query->where('cate', $cate);
        }
        $list = $query->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess($list);
    }

    public function newsDetail()
    {
        $id  = input('id', 0, 'intval');
        $row = News::find($id);
        if (!$row) {
            return jsonError('消息不存在');
        }
        return jsonSuccess($row);
    }

    public function cateList()
    {
        $ctype = input('ctype', 1, 'intval');
        $list  = Cate::where('ctype', $ctype)->order('ord', 'asc')->select();
        return jsonSuccess($list);
    }

    public function messageAdd()
    {
        $code = input('code', '');
        if (!captcha_check($code)) {
            return jsonError('验证码错误，请重新输入');
        }

        $data = [
            'username' => input('username', ''),
            'phone'    => input('phone', ''),
            'email'    => input('email', ''),
            'msgcon'   => input('msgcon', ''),
            'addtime'  => time(),
        ];
        if (empty($data['username']) || empty($data['msgcon'])) {
            return jsonError('姓名和留言内容不能为空');
        }
        Message::create($data);
        return jsonSuccess(null, '留言成功，感谢您的留言！');
    }
}