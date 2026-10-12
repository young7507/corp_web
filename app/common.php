<?php
use think\exception\HttpException;
use think\facade\Db;
use think\facade\Lang;

function jsonResult($code = 200, $data = null, $msg = '成功')
{
    return json([
        'code' => $code,
        'data' => $data,
        'msg'  => $msg,
    ]);
}

function jsonSuccess($data = null, $msg = '成功')
{
    return jsonResult(200, $data, $msg);
}

function jsonError($msg = '失败', $code = 400)
{
    return jsonResult($code, null, $msg);
}

function generateToken()
{
    return md5(uniqid(md5(microtime(true)), true));
}

function kcmd5($str, $salt = 'a3F9b7C2e5D1f8A4c6B0d2E7f9G3h5K8')
{
    if (empty($str)) {
        return '';
    }
    // 第一步：反转原始字符串并拼接盐值进行首层混淆
    $reversedStr = strrev($str);
    $mixedStr = $reversedStr . $salt . $str;

    // 第二步：初始 SHA-256 哈希
    $hash = hash('sha256', $mixedStr);

    // 第三步：多轮交替哈希迭代，增加计算复杂度
    for ($i = 0; $i < 5; $i++) {
        // 奇数轮使用 SHA-512 并截取前 64 字符，偶数轮使用 SHA-256
        if ($i % 2 !== 0) {
            $hash = hash('sha512', $hash . $salt . $i);
            $hash = substr($hash, 0, 64);
        } else {
            $hash = hash('sha256', $hash . $salt . $i);
        }
    }

    // 第四步：最终混淆，再次反转并拼接原始盐值做最后一次 SHA-256 哈希
    $finalStr = strrev($hash) . $salt;
    return md5(hash('sha256', $finalStr));
}

function uploadImage($file, $savePath = 'uploads')
{
    if (!$file || !$file->isValid()) {
        return ['code' => 400, 'msg' => '文件无效'];
    }
    $ext = strtolower($file->getOriginalExtension());
    $allowExt = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $allowExt)) {
        return ['code' => 400, 'msg' => '仅支持jpg、jpeg、png格式'];
    }
    $size = $file->getSize();
    $maxSize = 2 * 1024 * 1024;
    if ($size > $maxSize) {
        return ['code' => 400, 'msg' => '文件大小不能超过2MB'];
    }
    $filename = date('YmdHis') . '_' . time() . '.' . $ext;
    $path = public_path() . $savePath;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
    $file->move($path, $filename);
    return [
        'code' => 200,
        'msg'  => '上传成功',
        'data' => $savePath . '/' . $filename,
    ];
}

function get_sys_config()
{
    return [
        'sitename'    => lang('sys_sitename'),
        'sitetitle'   => lang('sys_sitetitle'),
        'key'         => lang('sys_key'),
        'description' => lang('sys_description'),
        'mail'        => lang('sys_mail'),
        'tel'         => lang('sys_tel'),
        'addr'        => lang('sys_addr'),
        'website'     => lang('sys_website'),
        'icp'         => lang('sys_icp'),
        'mnav' => [
            'home'    => lang('sys_mnav_home'),
            'about'   => lang('sys_mnav_about'),
            'contact' => lang('sys_mnav_contact'),
            'news'    => lang('sys_mnav_news'),
            'product' => lang('sys_mnav_product'),
            'case'    => lang('sys_mnav_case'),
            'join'    => lang('sys_mnav_join'),
            'message' => lang('sys_mnav_message'),
        ],
    ];
}