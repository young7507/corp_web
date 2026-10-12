<?php
namespace app\validate;

use think\Validate;

class AltplayValidate extends Validate
{
    protected $rule = [
        'thumb' => 'require',
        'ord' => 'require|number',
    ];

    protected $message = [
        'thumb.require'  => '请上传轮播图片',
        'ord.require'  => '排序不能为空',
        'ord.number'   => '排序必须为数字',
    ];
}