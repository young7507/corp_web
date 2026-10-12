<?php
namespace app\validate;

use think\Validate;

class NewsValidate extends Validate
{
    protected $rule = [
        'title'    => 'require|length:1,80',
        'subtitle' => 'require|length:1,50',
        'cate'     => 'require|number',
        'thumb'   => 'require|file|fileExt:jpg,png,jpeg|fileMime:image/jpeg,image/png,image/jpg|fileSize:10240000',
        'content'   => 'require',
        'ord'      => 'require|number',
    ];

    protected $message = [
        'title.require'    => '标题不能为空',
        'title.length'     => '标题长度为1-80个字符',
        'subtitle.require' => '副标题不能为空',
        'subtitle.length'  => '副标题长度为1-50个字符',
        'cate.require'     => '分类不能为空',
        'cate.number'      => '分类必须为数字',
        'thumb.require'   => '缩略图不能为空',
        'thumb.fileExt'   => '缩略图必须为jpg、png、jpeg格式',
        'thumb.fileMime'   => '缩略图必须为jpg、png、jpeg格式',
        'thumb.fileSize'   => '缩略图大小不能超过1MB',
        'content.require'   => '内容不能为空',
        'ord.require'      => '排序不能为空',
        'ord.number'       => '排序必须为数字',
    ];

    protected $scene = [
        'add'  =>  ['title', 'subtitle', 'cate', 'thumb', 'content', 'ord'],
        'edit' =>  ['title', 'subtitle', 'cate',  'content', 'ord'],
    ];
}