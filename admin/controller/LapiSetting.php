<?php

namespace app\admin\controller;

use think\facade\Cache;

use app\lapi\model\LapiConfig as LapiConfigModel;

/**
 * 设置
 *
 * @create 2020-8-14
 * @author deatil
 */
class LapiSetting extends LapiBase
{
    /**
     * 设置
     *
     * @create 2020-8-14
     * @author deatil
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            if (!empty($data)) {
                foreach ($data as $key => $item) {
                    LapiConfigModel::where([
                        'name' => $key,
                    ])->update([
                        'value' => $item,
                    ]);
                }
            }
            
            Cache::delete("lapi_config");
            
            return $this->success('设置更新成功');
        } else {
            $config = LapiConfigModel::column('name,value');
            
            $setting = [];
            if (!empty($config)) {
                foreach ($config as $val) {
                    $setting[$val['name']] = $val['value'];
                }
            }
                
            $this->assign('setting', json_encode($setting, true));

            return $this->fetch();
        }

    }
    
}
