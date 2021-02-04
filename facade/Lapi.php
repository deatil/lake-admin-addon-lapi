<?php

namespace app\lapi\facade;

use think\Facade;

use app\lapi\service\Lapi as LapiService;

/**
 * API
 *
 * @create 2020-8-22
 * @author deatil
 */
class Lapi extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return LapiService::class;
    }
}
