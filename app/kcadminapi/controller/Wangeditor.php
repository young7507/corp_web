<?php
namespace app\kcadminapi\controller;

class Wangeditor
{
    protected $middleware = [\app\kcadminapi\middleware\AdminAuth::class];

    /**
     * wangeditor图片上传接口
     */
    public function upload()
    {
        $file = request()->file('file');
        if (!$file) {
            return json([
                'errno' => 1,
                'message' => '未获取上传文件'
            ]);
        }
        $allowExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower($file->getOriginalExtension());
        if (!in_array($ext, $allowExt)) {
            return json([
                'errno' => 1,
                'message' => '仅支持 jpg、jpeg、png、gif、webp 格式的图片'
            ]);
        }
        $allowMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime = $file->getOriginalMime();
        if (!in_array($mime, $allowMime)) {
            return json([
                'errno' => 1,
                'message' => '文件不是有效的图片类型'
            ]);
        }
        if ($file->getSize() > 5 * 1024 * 1024) {
            return json([
                'errno' => 1,
                'message' => '图片大小不能超过5MB'
            ]);
        }
        $filename = date('YmdHis') . '_' . time() . '.' . $ext;
        $path = public_path() . 'uploads/wangeditor';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $filename);
        $url = '/uploads/wangeditor/' . $filename;
        return json([
            'errno' => 0,
            'data' => [
                'url' => $url
            ]
        ]);
    }
}