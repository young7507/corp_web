<?php
namespace app\kcadmin\controller;

use think\facade\View;

class Index
{
    protected $middleware = [\app\kcadmin\middleware\AdminAuth::class];
    /**
    //public function _init()
    //{
        //parent::_init();
    //}
    **/
    
    public function main()
    {
        return View::fetch('index/main');
    }
}