<?php
namespace app\kcadmin\controller;

use app\model\News as NewsModel;
use app\model\Cate;
use app\validate\NewsValidate;
use think\facade\Db;
use think\facade\View;

class News
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function index()
    {
        return View::fetch('news/index');
    }

    public function indexlist()
    {
        
        $page = input('page', 1);
        $rows = input('rows', 20);
        $list = NewsModel::alias('p')
            ->leftJoin('cate c', 'p.cate=c.id')
            ->field('p.*, c.catename')
            ->order('p.ord', 'asc')
            ->order('p.id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function add()
    {
        return View::fetch('news/add');
    }

    public function edit()
    {
        return View::fetch('news/edit');
    }

    public function getinfo()
    {
        $id  = input('id', 0, 'intval');
        $row = NewsModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        return jsonSuccess($row);
    }

    public function getcate()
    {
        $cateList = Cate::where('ctype', 2)->order('ord', 'asc')->select();
        return jsonSuccess($cateList);
    }

    public function insert()
    {
        $data = [
            'title'    => input('title', ''),
            'subtitle' => input('subtitle', ''),
            'cate'     => input('category', 0, 'intval'),
            'content'   => input('content', ''),
            'tui'      => input('istui', 0, 'intval'),
            'ord'      => input('ord', 0, 'intval'),
            'pubtime'  => time(),
        ];
        $file = null;
        if (!empty($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('thumb');
        }
        if ($file) {
            $result = uploadImage($file, 'uploads/news');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['thumb'] = $result['data'];
        }
        $validate = new NewsValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        NewsModel::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function update()
    {
        $id  = input('id', 0, 'intval');
        $row = NewsModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $data = [
            'title'    => input('title', ''),
            'subtitle' => input('subtitle', ''),
            'cate'     => input('category', 0, 'intval'),
            'content'   => input('content', ''),
            'tui'      => input('istui', 0, 'intval'),
            'ord'      => input('ord', 0, 'intval'),
        ];
        $file = null;
        if (!empty($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('thumb');
        }
        if ($file && $file->isValid()) {
            $result = uploadImage($file, 'uploads/news');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['thumb'] = $result['data'];
        }
        $validate = new NewsValidate();
        if (!$validate->check($data)) {
            return jsonError($validate->getError());
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function del()
    {
        $id  = input('id', 0, 'intval');
        $row = NewsModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }

    public function updateStatus()
    {
        $id   = input('id', 0, 'intval');
        $tui  = input('tui', 0, 'intval');
        $row  = NewsModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->tui = $tui;
        $row->save();
        return jsonSuccess(null, '状态更新成功');
    }
}