<?php
namespace app\validate;

use think\Validate;

class AdminValidate extends Validate
{
    protected $rule = [
        'username' => 'require|length:6,30',
        'userpwd'  => 'require|length:6,30',
        'psdpwd'   => 'require|length:6,20',
        'newpwd'   => 'require|length:6,20',
        'cfmpwd'   => 'require|length:6,20',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.length'  => '用户名长度为6-30个字符',
        'userpwd.require'  => '密码不能为空',
        'userpwd.length'   => '密码长度为6-30个字符',
        'psdpwd.require'   => '原密码不能为空',
        'psdpwd.length'    => '原密码长度为6-20个字符',
        'newpwd.require'   => '新密码不能为空',
        'newpwd.length'    => '新密码长度为6-20个字符',
        'cfmpwd.require'   => '确认密码不能为空',
        'cfmpwd.length'    => '确认密码长度为6-20个字符',
    ];

    public function sceneLogin()
    {
        return $this->only(['username', 'userpwd'])
            ->remove('username', 'length')
            ->append('username', 'require|length:3,30')
            ->remove('userpwd', 'length')
            ->append('userpwd', 'require|length:3,30');
    }

    public function scenePassword()
    {
        return $this->only(['psdpwd', 'newpwd', 'cfmpwd']);
    }
}