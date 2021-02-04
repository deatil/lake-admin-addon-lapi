<?php

namespace app\lapi;

use think\Service as BaseService;

/**
 * 服务
 *
 * @create 2020-8-18
 * @author deatil
 */
class Service extends BaseService
{
    public function boot()
    {
        $this->app->event->listen('lake_admin_module', function () {
            $infoFile = __DIR__ . DIRECTORY_SEPARATOR . 'info.php';
            if (file_exists($infoFile)) {
                $info = include $infoFile;
            } else {
                $info = [];
            }
            
            return $info;
        });
    }
    
}
