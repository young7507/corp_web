<?php
namespace app\kcadmin\middleware;

use think\facade\Cache;
use think\facade\Session;

class AdminAuth
{
    public function handle($request, \Closure $next)
    {
        if (request()->isAjax()) {
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
        }else{
            $token = $request->param('token', '');
            if (empty($token)) {
                $token = Session::get('token');
            }
            $username = Session::get('username');
            if (empty($token)) {
                return redirect('/kcadmin/login/index');
            }
            $admin = \app\model\Admin::where('token', $token)->find();
            if (!$admin) {
                return redirect('/kcadmin/login/index');
            }
            $request->admin = $admin;
        }
        return $next($request);
    }
}