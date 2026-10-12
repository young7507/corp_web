<?php
namespace app\validate;

use think\Validate;

class MessageValidate extends Validate
{
    protected $rule = [
    	'username'  => 'require|max:10',
    	'phone'  => 'require|number|max:11',
    	'email'  => 'email',
    	'msgcon'  => 'require',
    	'code'  => 'require',
        'reply' => 'require|length:1,200',
	];
	protected $message = [
    	'username.require'     => '您的姓名不能為空',
    	'username.max'     => '您的姓名不能超过10个字符',
    	'phone.require'     => '您的手机不能為空',
    	'phone.number'     => '您的手机只能输入数字',
    	'phone.max'     => '您的手机不能超过11个字符',
    	'email.email'     => '您的Email不正确',
    	'msgcon.require'     => '内容不能為空',
        'reply.require' => '回复内容不能为空',
        'reply.length'  => '回复内容长度为1-200个字符',
    	'code.require'     => '验证码不能为空'
	];
    
    protected $scene = [
        'add'  =>  ['usrname','phone','msgcon','code'],
        'reply' => ['reply']
    ];
}