<?php

namespace app\lapi\model;

use think\Model;

/*
 * LapiUrl 模型
 *
 * @create 2020-8-15
 * @author deatil
 */
class LapiUrl extends Model
{
    protected $name = 'lapi_url';
    
    // 设置当前模型对应的主键名
    protected $pk = 'id';
    
    /*
     * 获取数据
     *
     * @create 2020-8-18
     * @author deatil
     */
    public static function getDataByUrl($url = '')
    {
        if (empty($url)) {
            return [];
        }
        
        return self::where([
            'url' => $url,
        ])->find();
    }
    
    /*
     * 添加数据
     *
     * @create 2020-8-18
     * @author deatil
     */
    public static function insertUrl($data = [])
    {
        if (empty($data)) {
            return false;
        }
        
        $newData = array_merge([
            'id' => md5(mt_rand(10000, 99999).time().mt_rand(10000, 99999)),
            'edit_time' => time(),
            'edit_ip' => request()->ip(),
            'add_time' => time(),
            'add_ip' => request()->ip(),
        ], $data);
        $newInfo = (new self)->create($newData);
        $newId = $newInfo->id;
        return $newId;
    }
}
