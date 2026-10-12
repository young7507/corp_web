<?php
return [
    'default_timezone' => 'Asia/Shanghai',
    'app_debug'        => true,
    'app_trace'        => true,
    'default_module'   => 'index',
    'default_controller' => 'Index',
    'default_action'   => 'index',
    'url_route_on'     => true,
    'url_route_must'   => false,
    'url_convert'      => true,
    'url_domain_deploy'=> false,
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',
    'error_tmpl'       => app()->getThinkPath() . 'tpl/dispatch_jump.tpl',
    'show_error_msg'   => true,
    // 开启多应用（多模块）
    'auto_multi_app' => true,
    // 应用映射（自动多应用模式有效）
    'app_map' => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind' => [],
    // 禁止访问的应用列表
    'deny_app_list' => [],

    // 默认应用
    'default_app' => 'index',
    
    'ispublic'         => true,
    'sys' => [
        'sitename' => '麻城市康馨之家养老院',
		'sitetitle' => '麻城市康馨之家养老院',
		'key' => '康馨,康馨之家,养老,养老院,康馨之家养老院,麻城,麻城市,麻城市康馨,麻城市康馨之家,麻城市养老,麻城市养老院,麻城市康馨之家养老院',
		'description' => '麻城市康馨之家养老院',
        'mail' => 'yangzhaohui@kangxinhome.com',
        'tel' => '0713-2628421',
        'addr' => '麻城市白果镇景家山路特1号',
        'website' => 'http://education.eyesyang.com/',
        'icp' => '鄂ICP备2021013641号-1 ',
		'mnav' => ['home' => '首页','about' => '关于我们', 'contact' => '联系我们1', 'news' => '新闻中心', 'product' => '产品中心1', 'case' => '案例展示1', 'join' => '加入我们1', 'message' => '留言板']
    ],
];