<?php
namespace app\kcadmin\controller;

use think\facade\View;

class Ueditor
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];

    public function index()
    {
        $action = input('action', '');
        switch ($action) {
            case 'config':
                return $this->config();
            case 'uploadimage':
                return $this->uploadImage();
            case 'uploadfile':
                return $this->uploadFile();
            case 'uploadvideo':
                return $this->uploadVideo();
            case 'uploadscrawl':
                return $this->uploadScrawl();
            case 'listimage':
                return $this->listImage();
            case 'listfile':
                return $this->listFile();
            case 'catchimage':
                return $this->catchImage();
            default:
                return json(['state' => '无效的请求']);
        }
    }

    private function config()
    {
        $config = [
            'imageActionName'       => 'uploadimage',
            'imageFieldName'        => 'upfile',
            'imageMaxSize'          => 2048000,
            'imageAllowFiles'       => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'imageCompressEnable'   => true,
            'imageCompressBorder'   => 1600,
            'imageInsertAlign'      => 'none',
            'imageUrlPrefix'        => '',
            'imagePathFormat'       => '/uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlActionName'      => 'uploadscrawl',
            'scrawlFieldName'       => 'upfile',
            'scrawlPathFormat'      => '/uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlMaxSize'         => 2048000,
            'scrawlUrlPrefix'       => '',
            'scrawlInsertAlign'     => 'none',
            'snapscreenActionName'  => 'uploadimage',
            'snapscreenPathFormat'  => '/uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'snapscreenUrlPrefix'   => '',
            'snapscreenInsertAlign' => 'none',
            'catcherLocalDomain'    => ['127.0.0.1', 'localhost', 'img.baidu.com'],
            'catcherActionName'     => 'catchimage',
            'catcherFieldName'      => 'source',
            'catcherPathFormat'     => '/uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'catcherUrlPrefix'      => '',
            'catcherMaxSize'        => 2048000,
            'catcherAllowFiles'     => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'videoActionName'       => 'uploadvideo',
            'videoFieldName'        => 'upfile',
            'videoPathFormat'       => '/uploads/ueditor/video/{yyyy}{mm}{dd}/{time}{rand:6}',
            'videoUrlPrefix'        => '',
            'videoMaxSize'          => 102400000,
            'videoAllowFiles'       => ['.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'],
            'fileActionName'        => 'uploadfile',
            'fileFieldName'         => 'upfile',
            'filePathFormat'        => '/uploads/ueditor/file/{yyyy}{mm}{dd}/{time}{rand:6}',
            'fileUrlPrefix'         => '',
            'fileMaxSize'           => 51200000,
            'fileAllowFiles'        => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],
            'imageManagerActionName' => 'listimage',
            'imageManagerListPath'  => '/uploads/ueditor/image/',
            'imageManagerListSize'  => 20,
            'imageManagerUrlPrefix' => '',
            'imageManagerInsertAlign'=> 'none',
            'imageManagerAllowFiles'=> ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
            'fileManagerActionName' => 'listfile',
            'fileManagerListPath'   => '/uploads/ueditor/file/',
            'fileManagerUrlPrefix'  => '',
            'fileManagerListSize'   => 20,
            'fileManagerAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg', '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid', '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'],
        ];
        return json($config);
    }

    private function uploadImage()
    {
        $file = null;
        if (!empty($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('upfile');
        }
        if (!$file) {
            return json(['state' => '没有文件被上传']);
        }
        $ext = strtolower($file->getOriginalExtension());
        $allowExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        if (!in_array($ext, $allowExt)) {
            return json(['state' => '不允许的文件类型']);
        }
        $size = $file->getSize();
        if ($size > 2 * 1024 * 1024) {
            return json(['state' => '文件大小不能超过2MB']);
        }
        $savePath = 'uploads/ueditor/image/' . date('Ymd');
        $filename = date('YmdHis') . '_' . rand(100000, 999999) . '.' . $ext;
        $path = public_path() . $savePath;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $filename);
        return json([
            'state'    => 'SUCCESS',
            'url'      => '/' . $savePath . '/' . $filename,
            'title'    => $filename,
            'original' => $file->getOriginalName(),
            'type'     => '.' . $ext,
            'size'     => $size,
        ]);
    }

    private function uploadFile()
    {
        $file = null;
        if (!empty($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('upfile');
        }
        if (!$file) {
            return json(['state' => '没有文件被上传']);
        }
        $ext = strtolower($file->getOriginalExtension());
        $allowExt = ['rar', 'zip', 'tar', 'gz', '7z', 'bz2', 'cab', 'iso', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'md', 'xml'];
        if (!in_array($ext, $allowExt)) {
            return json(['state' => '不允许的文件类型']);
        }
        $size = $file->getSize();
        if ($size > 50 * 1024 * 1024) {
            return json(['state' => '文件大小不能超过50MB']);
        }
        $savePath = 'uploads/ueditor/file/' . date('Ymd');
        $filename = date('YmdHis') . '_' . rand(100000, 999999) . '.' . $ext;
        $path = public_path() . $savePath;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $filename);
        return json([
            'state'    => 'SUCCESS',
            'url'      => '/' . $savePath . '/' . $filename,
            'title'    => $filename,
            'original' => $file->getOriginalName(),
            'type'     => '.' . $ext,
            'size'     => $size,
        ]);
    }

    private function uploadVideo()
    {
        $file = null;
        if (!empty($_FILES['upfile']['tmp_name']) && $_FILES['upfile']['error'] === UPLOAD_ERR_OK) {
            $file = request()->file('upfile');
        }
        if (!$file) {
            return json(['state' => '没有文件被上传']);
        }
        $ext = strtolower($file->getOriginalExtension());
        $allowExt = ['flv', 'swf', 'mkv', 'avi', 'rm', 'rmvb', 'mpeg', 'mpg', 'ogg', 'ogv', 'mov', 'wmv', 'mp4', 'webm', 'mp3', 'wav', 'mid'];
        if (!in_array($ext, $allowExt)) {
            return json(['state' => '不允许的文件类型']);
        }
        $size = $file->getSize();
        if ($size > 100 * 1024 * 1024) {
            return json(['state' => '文件大小不能超过100MB']);
        }
        $savePath = 'uploads/ueditor/video/' . date('Ymd');
        $filename = date('YmdHis') . '_' . rand(100000, 999999) . '.' . $ext;
        $path = public_path() . $savePath;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $filename);
        return json([
            'state'    => 'SUCCESS',
            'url'      => '/' . $savePath . '/' . $filename,
            'title'    => $filename,
            'original' => $file->getOriginalName(),
            'type'     => '.' . $ext,
            'size'     => $size,
        ]);
    }

    private function uploadScrawl()
    {
        $base64 = input('upfile', '');
        if (empty($base64)) {
            return json(['state' => '没有涂鸦数据']);
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
            $ext = $matches[1];
            $base64 = substr($base64, strpos($base64, ',') + 1);
        } else {
            $ext = 'png';
        }
        $img = base64_decode($base64);
        if ($img === false) {
            return json(['state' => '涂鸦数据解码失败']);
        }
        $savePath = 'uploads/ueditor/image/' . date('Ymd');
        $filename = date('YmdHis') . '_' . rand(100000, 999999) . '.' . $ext;
        $path = public_path() . $savePath;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        file_put_contents($path . '/' . $filename, $img);
        return json([
            'state'    => 'SUCCESS',
            'url'      => '/' . $savePath . '/' . $filename,
            'title'    => $filename,
            'original' => 'scrawl.' . $ext,
            'type'     => '.' . $ext,
            'size'     => strlen($img),
        ]);
    }

    private function listImage()
    {
        $start = input('start', 0, 'intval');
        $size  = input('size', 20, 'intval');
        $dir   = public_path() . 'uploads/ueditor/image/';
        $files = $this->scanDir($dir, ['.png', '.jpg', '.jpeg', '.gif', '.bmp']);
        $total = count($files);
        $list  = array_slice($files, $start, $size);
        $result = [];
        foreach ($list as $file) {
            $result[] = ['url' => '/' . str_replace(public_path(), '', $file)];
        }
        return json([
            'state' => 'SUCCESS',
            'list'  => $result,
            'start' => $start,
            'total' => $total,
        ]);
    }

    private function listFile()
    {
        $start = input('start', 0, 'intval');
        $size  = input('size', 20, 'intval');
        $dir   = public_path() . 'uploads/ueditor/file/';
        $files = $this->scanDir($dir, []);
        $total = count($files);
        $list  = array_slice($files, $start, $size);
        $result = [];
        foreach ($list as $file) {
            $result[] = ['url' => '/' . str_replace(public_path(), '', $file)];
        }
        return json([
            'state' => 'SUCCESS',
            'list'  => $result,
            'start' => $start,
            'total' => $total,
        ]);
    }

    private function catchImage()
    {
        $source = input('source', []);
        if (empty($source)) {
            return json(['state' => '没有远程图片地址']);
        }
        $list = [];
        foreach ($source as $imgUrl) {
            $imgContent = $this->fetchRemoteImage($imgUrl);
            if ($imgContent === false) {
                continue;
            }
            $ext = 'jpg';
            if (preg_match('/\.(png|jpg|jpeg|gif|bmp)/i', $imgUrl, $m)) {
                $ext = strtolower($m[1]);
            }
            $savePath = 'uploads/ueditor/image/' . date('Ymd');
            $filename = date('YmdHis') . '_' . rand(100000, 999999) . '.' . $ext;
            $path = public_path() . $savePath;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            file_put_contents($path . '/' . $filename, $imgContent);
            $list[] = [
                'state'    => 'SUCCESS',
                'url'      => '/' . $savePath . '/' . $filename,
                'source'   => $imgUrl,
                'title'    => $filename,
            ];
        }
        return json([
            'state' => 'SUCCESS',
            'list'  => $list,
        ]);
    }

    private function scanDir($dir, $allowExt = [])
    {
        $files = [];
        if (!is_dir($dir)) {
            return $files;
        }
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $ext = '.' . strtolower($item->getExtension());
                if (empty($allowExt) || in_array($ext, $allowExt)) {
                    $files[] = $item->getPathname();
                }
            }
        }
        rsort($files);
        return $files;
    }

    private function fetchRemoteImage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode != 200 || empty($data)) {
            return false;
        }
        return $data;
    }
}