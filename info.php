<?php

return [
    'module' => 'lapi',
    'name' => 'lapi接口管理',
    'introduce' => 'lapi接口管理，基于lake-admin开发',
    'author' => 'deatil',
    'authorsite' => 'http://github.com/deatil',
    'authoremail' => 'deatil@github.com',
    'version' => '2.0.3',
    'adaptation' => '2.3.27',
    
    'path' => __DIR__,
    'need_module' => [],
    'setting' => [],
    
    // 事件
    'event' => [],
    
    // 菜单
    'menus' => include __DIR__ . '/menu.php',
    
    // 安装演示数据
    'demo' => 0,
];
