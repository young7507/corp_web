<?php
namespace app\validate;

use think\Validate;

class CateValidate extends Validate
{
    protected $rule = [
        'catename' => 'require|length:1,50',
        'ord'  => 'require|number',
    ];

    protected $message = [
        'catename.require' => '分类名称不能为空',
        'catename.length'  => '分类名称长度为1-50个字符',
        'ord.require'  => '排序不能为空',
        'ord.number'   => '排序必须为数字',
    ];
}