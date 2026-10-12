<?php
namespace app\validate;

use think\Validate;

class HistoryValidate extends Validate
{
    protected $rule = [
        'hisdate'  => 'require|max:10|min:3',
        'histitle' => 'require|max:10|min:3',
        'hiscon'   => 'require|max:100|min:3',
        'hisord'   => 'require|number',
    ];

    protected $message = [
        'hisdate.require'  => '日期不能为空',
        'hisdate.min'      => '日期长度不能小于3位',
        'hisdate.max'      => '日期长度不能大于10位',
        'histitle.require' => '标题不能为空',
        'histitle.min'     => '标题长度不能小于3位',
        'histitle.max'     => '标题长度不能大于10位',
        'hiscon.require'   => '内容不能为空',
        'hiscon.min'       => '内容长度不能小于3位',
        'hiscon.max'       => '内容长度不能大于100位',
        'hisord.require'   => '排序不能为空',
        'hisord.number'    => '排序必须为数字',
    ];
}