<?php
namespace app\middleware;

use app\common\Sign;
use think\Request;

class ApiSign
{
    public function handle($request, \Closure $next)
    {
        $uri = strtolower($request->url());

        if (strpos($uri, 'captcha') !== false || strpos($uri, 'wangeditor') !== false || strpos($uri, 'signtest') !== false) {
            return $next($request);
        }

        $params = $request->param();
        unset($params['content'], $params['con']);

        if (!Sign::verify($params)) {
            return $this->errorResponse($params, $request);
        }

        return $next($request);
    }

    private function errorResponse(array $params, Request $request)
    {
        $secret = env('API_SECRET', 'change-me-2026');
        $now = time();

        $debugParams = $params;
        unset($debugParams['sign']);
        ksort($debugParams);
        $signStr = http_build_query($debugParams) . $secret;
        $serverSign = strtolower(md5($signStr));

        $clientSign = $params['sign'] ?? '';
        $clientTimestamp = $params['timestamp'] ?? 0;
        $clientNonce = $params['nonce'] ?? '';

        $reason = '未知';
        if (!isset($params['timestamp'], $params['nonce'], $params['sign'])) {
            $reason = '缺少必要参数(timestamp/nonce/sign)';
        } elseif (abs($clientTimestamp - $now) > 60) {
            $reason = '时间戳过期';
        } elseif ($clientSign !== $serverSign) {
            $reason = '签名不匹配';
        } else {
            $cacheKey = 'nonce:' . $clientNonce;
            if (cache($cacheKey)) {
                $reason = 'nonce重复使用';
            }
        }

        return json([
            'code' => 400,
            'msg'  => '签名错误或请求已过期',
            'data' => [
                'reason'            => $reason,
                'server_time'       => $now,
                'client_timestamp'  => $clientTimestamp,
                'timestamp_diff'    => $now - $clientTimestamp,
                'client_nonce'      => $clientNonce,
                'client_sign'       => $clientSign,
                'server_sign'       => $serverSign,
                'sign_string'       => $signStr,
                'secret'            => $secret,
                'param_keys'        => array_keys($params),
                'param_count'       => count($params),
                'request_url'       => $request->url(true),
                'request_method'    => $request->method(),
            ],
        ]);
    }
}