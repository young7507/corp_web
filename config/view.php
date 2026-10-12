<?php
return [
    // 模板引擎类型
    'type'         => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转小写 3 保持原始
    'auto_rule'    => 1,
    // 模板目录名（视图文件夹名称）
    'view_dir_name'=> 'view',
    // 模板文件后缀
    'view_suffix'  => 'html',
    // 模板文件分隔符
    'view_depr'    => DIRECTORY_SEPARATOR,

    // 模板缓存配置
    'tpl_cache'          => true,        // 是否开启模板编译缓存，开发环境建议false
    'tpl_begin'          => '{',        // 模板标签左定界符
    'tpl_end'            => '}',         // 模板标签右定界符
    'taglib_begin'       => '{',
    'taglib_end'         => '}',

    // 模板布局（全局布局）
    'layout_on'     => false,    // 开启全局模板布局
    'layout_name'   => 'layout',// 布局模板文件名 view/layout.html
    'layout_item'   => '{__CONTENT__}', // 内容替换标记
];
