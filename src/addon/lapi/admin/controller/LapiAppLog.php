<?php

namespace app\admin\controller;

use Lake\HttpUserAgent;

use app\lapi\model\LapiApp as LapiAppModel;
use app\lapi\model\LapiAppLog as LapiAppLogModel;

/*
 * app访问记录
 *
 * @create 2020-8-12
 * @author deatil
 */
class LapiAppLog extends LapiBase
{
    
    /**
     * 列表
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function index()
    {
        if (request()->isPost()) {
            $key = input('post.key');
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');
        
            $where = [];
            $where[] = ['aal.url|aal.useragent|aa.app_id|aa.name', 'like', "%".$key."%"];
            
            $start_time = input('param.start_time');
            if (!empty($start_time)) {
                $start_time = strtotime($start_time);
                $where[] = ['aal.add_time', '>=', $start_time];
            }
        
            $end_time = input('param.end_time');
            if (!empty($end_time)) {
                $end_time = strtotime($end_time);
                $where[] = ['aal.add_time', '<=', $end_time];
            }
            
            $appTableName = (new LapiAppModel())->getName();
            $list = LapiAppLogModel::alias('aal')
                ->join($appTableName . ' aa', 'aal.app_id = aa.app_id', 'left')
                ->field('
                    aal.*,
                    aa.name as app_name
                ')
                ->where($where)
                ->order('aal.add_time DESC, aal.id DESC')
                ->paginate([
                    'list_rows' => $pageSize,
                    'page' => $page
                ])
                ->toArray();
            if (!empty($list['data'])) {
                foreach ($list['data'] as $k => $v) {
                    $list['data'][$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
            }
            
            $data = [
                'code' => 0,
                'msg' => '获取成功!',
                'data' => $list['data'],
                'count' => $list['total'],
                'rel' => 1
            ];
            
            return json($data);
        }
        
        return $this->fetch();
    }
    
    /**
     * 详情
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function view($id = '')
    {
        if (request()->isPost()) {
            return $this->error('请求错误');
        }
        
        $api_app_log = LapiAppLogModel::where([
                'id' => $id,
            ])->field("*")
            ->find();
            
        $api_app_log['agent_os'] = HttpUserAgent::getOs($api_app_log['useragent']);
        $api_app_log['agent_browser'] = HttpUserAgent::getBrowser($api_app_log['useragent']);
        
        $this->assign('api_app_log', $api_app_log);
        
        $api_app = LapiAppModel::where([
                'app_id' => $api_app_log['app_id'],
            ])->field("*")
            ->find();
        $this->assign('api_app', $api_app);
        
        return $this->fetch();
    }

    /**
     * 清除记录
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function clear()
    {
        if (!request()->isPost()) {
            return $this->error('请求错误!');
        }
        
        // 删除二十天前数据
        $status = LapiAppLogModel::where([
            ['add_time', '<=', time() - 60 * 60 * 24 * 20],
        ])->delete();
        if ($status === false) {
            return $this->error('清除失败!');
        }
        
        return $this->success('清除成功!', (string) url('index'));
    }
    
}
