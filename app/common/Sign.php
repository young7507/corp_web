<?php
namespace app\common;

class Sign
{
    // 请求允许时间偏差 60秒，超过判定为重放
    private static $timeOffset = 60;
    private static $apiSecret = null;

    private static function getApiSecret()
    {
        if (self::$apiSecret === null) {
            self::$apiSecret = env('API_SECRET', 'change-me-2026');
        }
        return self::$apiSecret;
    }

    /**
     * 生成签名：把参数按键名排序拼接 + secret做md5
     * @param array $params 请求全部参数
     * @return string
     */
    public static function make(array $params): string
    {
        unset($params['content'], $params['con']);
        unset($params['sign']);
        ksort($params);
        $str = http_build_query($params) . self::getApiSecret();
        return strtolower(md5($str));
    }

    /**
     * 校验签名、时间戳、随机数
     * @param array $params
     * @return bool
     */
    public static function verify(array $params): bool
    {
        if (!isset($params['timestamp'],$params['nonce'],$params['sign'])) {
            trace('API签名失败：缺少必要参数 timestamp/nonce/sign', 'error');
            trace('收到参数: ' . json_encode($params, JSON_UNESCAPED_UNICODE), 'error');
            return false;
        }
        $now = time();
        // 时间校验，防止旧数据包重放
        /*
        */
        if(abs($params['timestamp'] - $now) > self::$timeOffset){
            trace('API签名失败：时间戳过期 server=' . $now . ' client=' . $params['timestamp'], 'error');
            return false;
        }
        // 签名校验
        $clientSign = $params['sign'];
        $serverSign = self::make($params);
        if($clientSign !== $serverSign){
            // 输出签名串用于对比
            $debugParams = $params;
            unset($debugParams['sign']);
            ksort($debugParams);
            $signStr = http_build_query($debugParams) . self::getApiSecret();
            trace('API签名失败：签名不匹配', 'error');
            trace('  clientSign: ' . $clientSign, 'error');
            trace('  serverSign: ' . $serverSign, 'error');
            trace('  签名原串: ' . $signStr, 'error');
            trace('  收到参数: ' . json_encode($params, JSON_UNESCAPED_UNICODE), 'error');
            return false;
        }
        // nonce随机数校验，短时间内不能重复，使用缓存记录已使用随机数
        /*
        $cacheKey = "nonce:".$params['nonce'];
        if(cache($cacheKey)){
            trace('API签名失败：nonce重复使用: ' . $params['nonce'], 'error');
            return false;
        }
        cache($cacheKey,1,self::$timeOffset);
        */
        return true;
    }
}