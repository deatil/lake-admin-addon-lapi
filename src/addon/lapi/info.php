<?php

return [
    'module' => 'lapi',
    'name' => 'lapi接口管理',
    'introduce' => 'lapi接口管理，基于lake-admin开发',
    'author' => 'deatil',
    'authorsite' => 'http://github.com/deatil',
    'authoremail' => 'deatil@github.com',
    'version' => '2.0.2',
    'adaptation' => '2.0.2',
    'sign' => 'b15cc279e3484c13c96c2f7142e2f437',
    
    'path' => '',
    
    'need_module' => [],
    
    'setting' => [],
    
    // 事件
    'event' => [],
    
    // 菜单
    'menus' => include __DIR__ . '/menu.php',
    
    // 数据表，不用加表前缀
    'tables' => [
        'lapi_config',
        'lapi_app',
        'lapi_app_log',
        'lapi_url',
        'lapi_url_access',
    ],
    
    // 安装演示数据
    'demo' => 0,
];
