<?php
return [
    'default_lang'    => 'zh-cn',
    'allow_lang_list' => ['zh-cn', 'en-us'],
    'use_cookie'      => true,
    'extend_list'     => [],
    'cookie_var'      => 'think_lang',
    'header_var'      => 'think-lang',
    'detect_var'      => 'lang',
    'accept_language' => [
        'zh-hans-cn' => 'zh-cn',
        'en-us'      => 'en-us',
        'en'         => 'en-us',
    ],
    'allow_group'     => false,
];