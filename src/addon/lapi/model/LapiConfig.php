<?php

namespace app\lapi\model;

use think\Model;
use think\facade\Cache;

/*
 * LapiConfig 模型
 *
 * @create 2020-8-14
 * @author deatil
 */
class LapiConfig extends Model
{
    protected $name = 'lapi_config';
    
    /*
     * 获取列表
     *
     * @create 2020-8-15
     * @author deatil
     */
    public static function getList()
    {
        $setting = Cache::get("lapi_config");
        if (!$setting) {
            $config = self::column('name,value');
            
            $setting = [];
            if (!empty($config)) {
                foreach ($config as $val) {
                    $setting[$val['name']] = $val['value'];
                }
            }
            
            Cache::set("lapi_config", $setting);
        }
        
        return $setting;
    }
    
    /*
     * 获取数据
     *
     * @create 2020-8-15
     * @author deatil
     */
    public static function getNameValue($name)
    {
        $value = self::where([
            'name' => $name,
        ])->value('value');
        
        return $value;
    }
    
}
