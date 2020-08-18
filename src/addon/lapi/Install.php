<?php

namespace app\lapi;

use think\facade\Db;

use lake\Module;

/**
 * 安装脚本
 *
 * @create 2020-8-12
 * @author deatil
 */
class Install
{
    /**
     * 安装完回调
     * @return boolean
     */
    public function end()
    {
        // 清除旧数据
        if (request()->param('clear') == 1) {
            // 
        }
        
        // 安装数据库
        $Module = new Module();
        $runSqlStatus = $Module->runSQL(__DIR__ . "/install/install.sql");
        if (!$runSqlStatus) {
            return false;
        }
        
        // 复制静态文件
        $Module->installStatic("lapi");
        
        return true;
    }

}
