<?php

namespace app\lapi;

use lake\Module;

/**
 * 卸载脚本
 *
 * @create 2020-8-12
 * @author deatil
 */
class Uninstall
{
    public function run()
    {
        $Module = new Module();
        
        // 清除旧数据
        if (request()->param('clear') == 1) {
            // 卸载数据库
            $runSqlStatus = $Module->runSQL(__DIR__ . "/install/uninstall.sql");
            if (!$runSqlStatus) {
                return false;
            }
        }
        
        $Module->uninstallStatic("lapi");
        
        return true;
    }

}
