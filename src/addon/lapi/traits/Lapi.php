<?php

namespace app\lapi\traits;

use app\lapi\facade\Lapi as LapiFacade;

/*
 * API检测
 *
 * @create 2020-8-22
 * @author deatil
 */
trait Lapi
{
    /*
     * 检测签名
     *
     * @create 2020-8-12
     * @author deatil
     */
    protected function checkApiSign()
    {
        LapiFacade::checkApiSign();
    }
    
    /*
     * 返回成功json
     *
     * @create 2020-8-12
     * @author deatil
     */
    protected function successJson($msg = '获取成功', $data = null, $code = 0) 
    {
        return LapiFacade::successJson($msg, $data, $code);
    }
    
    /*
     * 返回错误json
     *
     * @create 2020-8-12
     * @author deatil
     */
    protected function errorJson($msg = null, $code = 1, $data = []) 
    {
        return LapiFacade::errorJson($msg, $code, $data);
    }
    
}
