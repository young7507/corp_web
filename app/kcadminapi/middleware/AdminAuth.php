<?php

namespace app\kcadminapi\middleware;

use think\facade\Cache;

class AdminAuth
{
    public function handle($request, \Closure $next)
    {
        $token = $request->header('token', '');
        if (empty($token)) {
            $token = $request->param('token', '');
        }
        if (empty($token)) {
            return json(['code' => 401, 'data' => null, 'msg' => '未登录，请先登录']);
        }
        $admin = \app\model\Admin::where('token', $token)->find();
        if (!$admin) {
            return json(['code' => 401, 'data' => null, 'msg' => '登录已过期，请重新登录']);
        }
        $request->admin = $admin;
        return $next($request);
    }
}