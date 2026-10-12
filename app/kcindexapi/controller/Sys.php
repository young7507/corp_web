<?php

namespace app\kcindexapi\controller;

use app\model\Sys as SysModel;
use app\model\HistoryModel;
use app\common\Sign;

class Sys
{
    protected $middleware = [\app\middleware\ApiSign::class];

    public function signtest()
    {
        $params = request()->param();
        $clientSign = isset($params['sign']) ? $params['sign'] : '';
        $clientTimestamp = isset($params['timestamp']) ? $params['timestamp'] : 0;
        $clientNonce = isset($params['nonce']) ? $params['nonce'] : '';

        $serverSign = Sign::make($params);

        $debugParams = $params;
        unset($debugParams['sign']);
        ksort($debugParams);
        $signStr = http_build_query($debugParams) . env('API_SECRET', 'change-me-2026');

        return json([
            'code' => 200,
            'msg'  => '签名测试',
            'data' => [
                'match'            => $clientSign === $serverSign,
                'server_time'      => time(),
                'client_timestamp' => $clientTimestamp,
                'timestamp_diff'   => time() - $clientTimestamp,
                'client_nonce'     => $clientNonce,
                'client_sign'      => $clientSign,
                'server_sign'      => $serverSign,
                'sign_string'      => $signStr,
                'secret'           => env('API_SECRET', 'change-me-2026'),
                'received_params'  => $params,
            ],
        ]);
    }

    public function getmenu()
    {
        $menu = get_sys_config();
        return jsonSuccess(['sys' => $menu]);
    }

    public function aboutinfo()
    {
        $row = SysModel::where('webkey', 'AboutUs')->find();
        if (!$row) {
            return jsonError('关于我们内容不存在');
        }
        $row1 = SysModel::where('webkey', 'ABExt')->find();
        return jsonSuccess(['content' => $row->webcon, 'subtitle' => $row1 ? $row1->webcon : '']);
    }

    public function contactinfo()
    {
        $row = SysModel::where('webkey', 'ContactUs')->find();
        if (!$row) {
            return jsonError('联络我们内容不存在');
        }
        $row1 = SysModel::where('webkey', 'CTExt')->find();
        return jsonSuccess(['content' => $row->webcon, 'subtitle' => $row1 ? $row1->webcon : '']);
    }

    public function histuilist()
    {
        $list = HistoryModel::where('istui', 1)->order('hisord', 'asc')->order('id', 'asc')->select();

        $row = SysModel::where('webkey', 'DevlpExt')->find();
        if (!$row) {
            return jsonError('发展历程内容不存在');
        }
        if (!$list) {
            return jsonError('发展历程不存在');
        }
        return jsonSuccess([
            'rows'  => $list,
            'subtitle' => $row->webcon,
        ]);
    }

    public function hislist()
    {
        $list = HistoryModel::order('hisord', 'asc')->order('id', 'asc')
            ->select();

        $row = SysModel::where('webkey', 'DevlpExt')->find();
        if (!$row) {
            return jsonError('发展历程内容不存在');
        }
        if (!$list) {
            return jsonError('发展历程不存在');
        }
        return jsonSuccess([
            'rows'  => $list,
            'subtitle' => $row->webcon,
        ]);
    }
}