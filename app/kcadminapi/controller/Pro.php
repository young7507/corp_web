<?php
namespace app\kcadminapi\controller;

use app\model\Product;
use app\model\Propic;
use app\model\Cate as CateModel;
use app\validate\ProductValidate;

class Pro
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class, \app\middleware\ApiSign::class];

    public function indexlist()
    {
        $cateid = input('cateid', 0, 'intval');
        $keyword = input('keyword', '');
        $page = input('page', 1);
        $rows = input('rows', 20);
        $catename = '';
        $query = Product::alias('p')
            ->leftJoin('cate c', 'p.cate=c.id')
            ->field('p.*, c.catename');
        if ($cateid) {
            $cate = CateModel::find($cateid);
            if (!$cate) {
                return jsonError('分类不存在');
            }
            $catename = $cate->catename;
            if ($keyword) {
                $query = $query->where('p.cate', $cateid)->whereLike('p.proname', "%$keyword%");
            } else {
                $query = $query->where('p.cate', $cateid);
            }
        } else {
            if ($keyword) {
                $query = $query->whereLike('p.proname', "%$keyword%");
            }
        }
        $list = $query->order('p.ord', 'asc')
            ->order('p.id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);
        
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
        $row = Product::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        $row->thumb = $row->thumb ? $pubpath . '/' . $row->thumb : '';
        return jsonSuccess($row);
    }

    public function insert()
    {
        $data = [
            'proname'  => input('title', ''),
            'subtitle' => input('subtitle', ''),
            'cate'     => input('category', 0, 'intval'),
            'intro'    => input('intro', ''),
            'content'  => input('content', ''),
            'tui'      => input('istui', 0, 'intval'),
            'ord'      => input('ord', 0, 'intval'),
            'addtime'  => time(),
        ];
        $file = null;
        if (!empty($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('thumb');
        }
        if (!$file) {
            return jsonError('缩略图不能为空');
        }
        $data['thumb'] = $file;
        $validate = new ProductValidate();
        if (!$validate->scene('add')->check($data)) {
            return jsonError($validate->getError());
        }

        $result = uploadImage($file, 'uploads/product');
        if ($result['code'] !== 200) {
            return jsonError($result['msg']);
        }
        $data['thumb'] = $result['data'];
        Product::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function update()
    {
        $id  = input('id', 0, 'intval');
        $row = Product::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $data = [
            'proname'  => input('title', ''),
            'subtitle' => input('subtitle', ''),
            'cate'     => input('category', 0, 'intval'),
            'intro'    => input('intro', ''),
            'content'  => input('content', ''),
            'tui'      => input('istui', 0, 'intval'),
            'ord'      => input('ord', 0, 'intval'),
        ];
        $file = null;
        if (!empty($_FILES['thumb']['tmp_name']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('thumb');
        }

        if ($file && $file->isValid()) {
            $result = uploadImage($file, 'uploads/product');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['thumb'] = $result['data'];
        }
        $validate = new ProductValidate();
        if (!$validate->scene('edit')->check($data)) {
            return jsonError($validate->getError());
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function del()
    {
        $id  = input('id', 0, 'intval');
        $row = Product::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        } else {
            $list = Propic::where('proid', $id)->select();
            foreach ($list as $one) {
                if (file_exists(public_path() . $one->pic)) {
                    unlink(public_path() . $one->pic);
                }
            }
            if ($row->thumb) {
                if (file_exists(public_path() . $row->thumb)) {
                    unlink(public_path() . $row->thumb);
                }
            }
            Propic::where('proid', $id)->delete();
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }

    public function updateStatus()
    {
        $id   = input('id', 0, 'intval');
        $tui  = input('tui', 0, 'intval');
        $row  = Product::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $row->tui = $tui;
        $row->save();
        return jsonSuccess(null, '状态更新成功');
    }

    public function picindexlist()
    {
        $page  = input('page', 1);
        $rows  = input('rows', 20);
        $proid = input('proid', 0, 'intval');
        $query = Propic::alias('p');
        if ($proid > 0) {
            $query = $query->where('p.proid', $proid);
        }
        $list = $query->order('p.ord', 'asc')
            ->order('p.id', 'desc')
            ->paginate(['page' => $page, 'list_rows' => $rows]);

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '/' : '/public/';
        foreach ($list->items() as $item) {
            $item->pic = $item->pic ? $pubpath . $item->pic : '';
        }
        return jsonSuccess([
            'total' => $list->total(),
            'rows'  => $list->items(),
        ]);
    }

    public function picgetinfo()
    {
        $id  = input('id', 0, 'intval');
        $row = Propic::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }

        $ispublic = config('app.ispublic');
        $pubpath = $ispublic ? '' : '/public';
        $row->pic = $row->pic ? $pubpath . '/' . $row->pic : '';
        return jsonSuccess($row);
    }

    public function picinsert()
    {
        $proid = input('proid', 0, 'intval');
        $ord   = input('ord', 0, 'intval');
        $data  = [
            'proid' => $proid,
            'ord'   => $ord,
        ];
        $file = null;
        if (!empty($_FILES['pic']['tmp_name']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('pic');
        }
        if ($file) {
            $result = uploadImage($file, 'uploads/product');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            $data['pic'] = $result['data'];
        }
        Propic::create($data);
        return jsonSuccess(null, '添加成功');
    }

    public function picupdate()
    {
        $id  = input('id', 0, 'intval');
        $row = Propic::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        $data = [
            'ord' => input('ord', 0, 'intval'),
        ];
        $file = null;
        if (!empty($_FILES['pic']['tmp_name']) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('pic');
        }
        if ($file && $file->isValid()) {
            $result = uploadImage($file, 'uploads/product');
            if ($result['code'] !== 200) {
                return jsonError($result['msg']);
            }
            if ($row->pic) {
                $oldFile = public_path() . $row->pic;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            $data['pic'] = $result['data'];
        }
        $row->save($data);
        return jsonSuccess(null, '修改成功');
    }

    public function picdel()
    {
        $id  = input('id', 0, 'intval');
        $row = Propic::find($id);
        if (!$row) {
            return jsonError('记录不存在');
        }
        if ($row->pic) {
            if (file_exists(public_path() . $row->pic)) {
                unlink(public_path() . $row->pic);
            }
        }
        $row->delete();
        return jsonSuccess(null, '删除成功');
    }
}