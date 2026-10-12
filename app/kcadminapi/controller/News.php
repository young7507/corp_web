<?php
namespace app\kcadminapi\controller;

use app\model\News as NewsModel;
use app\model\Cate;
use app\validate\NewsValidate;

class News
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class, \app\middleware\ApiSign::class];

    public function indexlist()
    {
        $cateid = input('cateid', 0, 'intval');
        $page = input('page', 1);
        $rows = input('rows', 20);
        $catename = '';
        if(!$cateid) {
            $list = NewsModel::alias('p')
                ->leftJoin('cate c', 'p.cate=c.id')
                ->field('p.*, c.catename')
                ->order('p.ord', 'asc')
                ->order('p.id', 'desc')
                ->paginate(['page' => $page, 'list_rows' => $rows]);
        } else {
            $cate = Cate::find($cateid);
            if (!$cate) {
                return jsonError('分类不存在');
            }
            $catename = $cate->catename;
            $list = NewsModel::alias('p')
            ->leftJoin('cate c', 'p.cate=c.id')
            ->where('p.cate', $cateid)
            ->field('p.*, c.catename')
            ->order('p.ord', 'asc')
            ->order('p.id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        }

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        foreach ($list->items() as $item) {
            $item->thumb = $item->thumb ? $pubpath . '/' . $item->thumb : '';
        }
        return jsonSuccess([
            'catename' => $catename,
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function getinfo()
    {
        $id  = input('id', 0, 'intval');
        $row = NewsModel::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        $row->thumb = $row->thumb ? $pubpath . '/' . $row->thumb : '';
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
            'content'  => input('content', ''),
            'tui'      => input('istui', 0, 'intval'),
            'ord'      => input('ord', 0, 'intval'),
            'pubtime'  => time(),
        ];
        $file = null;
        if (!empty($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('thumb');
        }
        if (!$file) {
            return jsonError('缩略图不能为空');
        }
        $data['thumb'] = $file;
        $validate = new NewsValidate();
        if (!$validate->scene('add')->check($data)) {
            return jsonError($validate->getError());
        }
        $result = uploadImage($file, 'uploads/news');
        if ($result['code'] !== 200) {
            return jsonError($result['msg']);
        }
        $data['thumb'] = $result['data'];
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
            'content'  => input('content', ''),
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
        if (!$validate->scene('edit')->check($data)) {
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
        if (file_exists(public_path() . $row->thumb)) {
            unlink(public_path() . $row->thumb);
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