<?php
namespace app\kcadminapi\middleware;

use think\facade\Lang;

class ForceZhCn
{
    public function handle($request, \Closure $next)
    {
        Lang::setLangSet('zh-cn');
        Lang::switchLangSet('zh-cn');
        return $next($request);
    }
}