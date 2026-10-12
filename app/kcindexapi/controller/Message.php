<?php

namespace app\kcindexapi\controller;

use app\model\Message as MessageModel;
use app\validate\MessageValidate;
use think\facade\Session;

class Message
{
    protected $middleware = [\app\middleware\ApiSign::class];

    public function add()
    {
        $code = input('code', '');
        if (!captcha_check($code)) {
            return jsonError('验证码错误，请重新输入');
        }

        $data = [
            'username' => input('username', ''),
            'phone'    => input('phone', ''),
            'email'    => input('email', ''),
            'msgcon'   => input('msgcon', ''),
            'code'     => $code,
            'ip'       => $this->getClientIp(),
            'addtime'  => time(),
        ];

        $validate = new MessageValidate();
        if (!$validate->scene('add')->check($data)) {
            return jsonError($validate->getError());
        }

        $messtime = Session::get('messtime');
        if (!empty($messtime)) {
            $min = (time() - $messtime) / 60;
            if ($min < 30) {
                return jsonError('30分钟内不能进行第二次提交，请稍后再试');
            }
        }

        $lastMsg = MessageModel::where('ip', $data['ip'])
            ->order('id', 'desc')
            ->find();
        if (!empty($lastMsg) && !empty($lastMsg['addtime'])) {
            $min = (time() - $lastMsg['addtime']) / 60;
            if ($min < 30) {
                return jsonError('相同IP在30分钟内不能进行第二次提交，请稍后再试');
            }
        }

        MessageModel::create($data);
        Session::set('messtime', time());

        return jsonSuccess(null, '留言成功，感谢您的留言！');
    }

    private function getClientIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) {
                unset($arr[$pos]);
            }
            $ip = trim(current($arr));
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '0.0.0.0';
        }

        $long = ip2long($ip);
        return $long !== false ? $ip : '0.0.0.0';
    }
}