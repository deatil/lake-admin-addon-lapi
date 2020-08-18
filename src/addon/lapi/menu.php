<?php

/*
 * 菜单
 *
 * @create 2020-8-13
 * @author deatil
 */
return [
    [
        "route" => "admin/LapiApp/index",
        "method" => "GET",
        "type" => 1,
        "is_menu" => 1,
        "title" => "APP授权",
        "icon" => "icon-apartment",
        "tip" => "",
        "listorder" => 115,
        "child" => [
            [
                "route" => "admin/LapiSetting/index",
                "method" => "GET",
                "type" => 1,
                "is_menu" => 1,
                "title" => "授权设置",
                "icon" => "icon-setup",
                "listorder" => 5,
                "child" => [
                    [
                        "route" => "admin/LapiSetting/index",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "授权设置",
                    ],
                ],
            ],
            [
                "route" => "admin/LapiApp/index",
                "method" => "GET",
                "type" => 1,
                "is_menu" => 1,
                "title" => "授权列表",
                "icon" => "icon-apartment",
                "listorder" => 15,
                "child" => [
                    [
                        "route" => "admin/LapiApp/index",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "授权列表",
                    ],
                    [
                        "route" => "admin/LapiApp/add",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "添加授权",
                        "child" => [
                            [
                                "route" => "admin/LapiApp/add",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "添加授权",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiApp/edit",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "编辑授权",
                        "child" => [
                            [
                                "route" => "admin/LapiApp/edit",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "编辑授权",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiApp/delete",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "删除授权",
                    ],
                    [
                        "route" => "admin/LapiApp/state",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "授权状态",
                    ],
                    [
                        "route" => "admin/LapiApp/access",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "授权接口",
                        "child" => [
                            [
                                "route" => "admin/LapiApp/access",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "授权接口",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiApp/accessUrl",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "访问授权列表",
                        "child" => [
                            [
                                "route" => "admin/LapiApp/accessUrl",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "访问授权列表",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiApp/accessUrlSet",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "访问授权链接设置",
                    ],
                ],
            ],
            [
                "route" => "admin/LapiUrl/index",
                "method" => "GET",
                "type" => 1,
                "is_menu" => 1,
                "title" => "接口列表",
                "icon" => "icon-lianjie",
                "listorder" => 25,
                "child" => [
                    [
                        "route" => "admin/LapiUrl/index",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "接口列表",
                    ],
                    [
                        "route" => "admin/LapiUrl/all",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "全部接口",
                        "child" => [
                            [
                                "route" => "admin/LapiUrl/all",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "全部接口",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiUrl/add",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "添加接口",
                        "child" => [
                            [
                                "route" => "admin/LapiUrl/add",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "添加接口",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiUrl/edit",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "编辑接口",
                        "child" => [
                            [
                                "route" => "admin/LapiUrl/edit",
                                "method" => "POST",
                                "type" => 1,
                                "is_menu" => 0,
                                "title" => "编辑接口",
                            ],
                        ],
                    ],
                    [
                        "route" => "admin/LapiUrl/detail",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "接口详情",
                    ],
                    [
                        "route" => "admin/LapiUrl/delete",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "删除接口",
                    ],
                    [
                        "route" => "admin/LapiUrl/state",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "接口状态",
                    ],
                    [
                        "route" => "admin/LapiUrl/listorder",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "接口排序",
                    ],
                ],
            ],
            [
                "route" => "admin/LapiAppLog/index",
                "method" => "GET",
                "type" => 1,
                "is_menu" => 1,
                "title" => "接口日志",
                "icon" => "icon-rank",
                "listorder" => 35,
                "child" => [
                    [
                        "route" => "admin/LapiAppLog/view",
                        "method" => "GET",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "日志详情",
                    ],
                    [
                        "route" => "admin/LapiAppLog/clear",
                        "method" => "POST",
                        "type" => 1,
                        "is_menu" => 0,
                        "title" => "清除日志",
                    ],

                ],
            ],
        ],
    ],
];
