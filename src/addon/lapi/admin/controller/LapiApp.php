<?php

namespace app\admin\controller;

use Lake\TTree;

use app\lapi\model\LapiApp as LapiAppModel;
use app\lapi\model\LapiConfig as LapiConfigModel;
use app\lapi\model\LapiUrl as LapiUrlModel;
use app\lapi\model\LapiUrlAccess as LapiUrlAccessModel;

/*
 * 授权列表
 *
 * @create 2020-8-12
 * @author deatil
 */
class LapiApp extends LapiBase
{
    
    /*
     * 首页
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function index()
    {
        if (request()->isPost()) {
            $key = input('post.key');
            
            $where = [];
            $where[] = ['name|app_id', 'like', "%".$key."%"];
            
            $list = LapiAppModel::field('*')
                ->where($where)
                ->order('id ASC')
                ->select()
                ->toArray();
                
            $count = LapiAppModel::field('id')
                ->where($where)
                ->count();
            
            $data = [
                'code' => 0,
                'msg' => '获取成功!',
                'data' => $list,
                'count' => $count,
                'rel' => 1
            ];
            
            return json($data);
        }
        
        return $this->fetch();
    }
    
    /**
     * 添加
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function add()
    {
        if (request()->isPost()) {
            $post = input('post.');
            
            $appidPre = LapiConfigModel::getNameValue('api_app_pre');
            if (empty($appidPre)) {
                $appidPre = 'API';
            }
            
            $data = [
                'id' => md5(mt_rand(10000, 99999).time().mt_rand(10000, 99999)),
                'name' => trim($post['name']),
                'app_id' => $appidPre.date('YmdHis').mt_rand(10000, 99999),
                'app_secret' => md5(mt_rand(10000, 99999).time().mt_rand(10000, 99999)),
                'description' => trim($post['description']),
                'allow_origin' => 0,
                'is_check' => 1,
                'status' => (isset($post['status']) && $post['status'] == 1) ? 1 : 0,
                'last_active' => time(),
                'last_ip' => request()->ip(),
                'add_time' => time(),
                'add_ip' => request()->ip(),
            ];

            $ststus = LapiAppModel::insert($data);
            if ($ststus === false) {
                return $this->error('添加失败!');
            }

            return $this->success('添加成功!');
        } else {
            return $this->fetch();
        }
    }
    
    /**
     * 编辑
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function edit($id = '')
    {
        if (request()->isPost()) {
            $post = input('post.');
            
            $id = $post['id'];
            
            $data = [
                'name' => trim($post['name']),
                'description' => trim($post['description']),
                'allow_origin' => (isset($post['allow_origin']) && $post['allow_origin'] == 1) ? 1 : 0,
                'is_check' => (isset($post['is_check']) && $post['is_check'] == 1) ? 1 : 0,
                'check_type' => trim($post['check_type']),
                'sign_postion' => trim($post['sign_postion']),
                'status' => (isset($post['status']) && $post['status'] == 1) ? 1 : 0,
                'last_active' => time(),
                'last_ip' => request()->ip(),
            ];
            
            if (isset($post['update_secret']) && $post['update_secret'] == 1) {
                $data['app_secret'] = md5(mt_rand(10000, 99999).time().mt_rand(10000, 99999));
            }

            $status = LapiAppModel::where([
                'id' => $id,
            ])->update($data);
            if ($status === false) {
                return $this->error('修改失败!');
            }
            
            return $this->success('修改成功!');
        } else {
            $info = LapiAppModel::where([
                'id' => $id,
            ])->field("
                id,
                name,
                app_id,
                app_secret,
                description,
                allow_origin,
                is_check,
                check_type,
                sign_postion,
                status
            ")
            ->find();
            $this->assign('info', json_encode($info, true));
            
            return $this->fetch();
        }
    }
    
    /*
     * 更改状态
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function state()
    {
        $id = input('post.id');
        $status = input('post.status');
        
        $status = LapiAppModel::where([
            'id' => $id,
        ])->update([
            'status' => $status,
        ]);
        if ($status === false) {
            return $this->error('设置失败!');
        }
        
        return $this->success('设置成功!');
    }

    /**
     * 删除
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function delete()
    {
        if (!request()->isPost()) {
            return $this->error('请求错误!');
        }
        
        $id = input('id');
        if (empty($id)) {
            return $this->error('ID错误!');
        }
        
        $status = LapiAppModel::where([
            'id' => $id,
        ])->delete();
        if ($status === false) {
            return $this->error('删除失败!');
        }
        
        return $this->success('删除成功!', (string) url('index'));
    }
    
    /**
     * 访问授权页面
     *
     * @create 2020-8-16
     * @author deatil
     */
    public function access()
    {
        if ($this->request->isPost()) {
            $id = $this->request->post('id');
            if (empty($id)) {
                return $this->error('ID不能为空！');
            }
            
            $newUrlIds = $this->request->post('url_ids');
            
            $urlIds = [];
            if (!empty($newUrlIds)) {
                $urlIds = explode(',', $newUrlIds);
            }
            
            // 删除权限
            LapiUrlAccessModel::where([
                'app_id' => $id,
            ])->delete();
            
            // 有权限就添加
            if (isset($urlIds) && !empty($urlIds)) {
                $urlAccess = [];
                if (!empty($urlIds)) {
                    foreach ($urlIds as $urlId) {
                        $urlAccess[] = [
                            'app_id' => $id,
                            'url_id' => $urlId,
                        ];
                    }
                }
                
                $r = LapiUrlAccessModel::insertAll($urlAccess);
            
                if ($r === false) {
                    return $this->error('授权失败');
                }
            }
            
            return $this->success('授权成功!');
        } else {
            $id = $this->request->param('id');
            if (empty($id)) {
                return $this->error('ID不能为空');
            }
            
            $this->assign('id', $id);
            
            $urlIds = LapiUrlAccessModel::where([
                    'app_id' => $id,
                ])
                ->column('url_id');
        
            $urlList = LapiUrlModel::field('*')
                ->order('id ASC')
                ->select()
                ->toArray();
            
            $json = [];
            if (!empty($urlList)) {
                foreach ($urlList as $rs) {
                    $data = [
                        'nid' => $rs['id'],
                        'parentid' => $rs['parentid'],
                        'name' => (empty($rs['method']) ? $rs['title'] : ($rs['title'] . '[' . strtoupper($rs['method']) . ']')),
                        'id' => $rs['id'],
                        'chkDisabled' => false,
                        'checked' => in_array($rs['id'], $urlIds) ? true : false,
                    ];
                    $json[] = $data;
                }
            }
            
            $this->assign('json', json_encode($json));
            
            return $this->fetch('access');
        }
    }
    
    /**
     * 访问授权列表
     *
     * @create 2020-8-17
     * @author deatil
     */
    public function accessUrl()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->param('id');
            if (empty($id)) {
                return $this->error('ID不能为空');
            }
            
            $this->assign('id', $id);
            
            $auTableName = (new LapiUrlModel())->getName();
            $result = LapiUrlAccessModel::alias('aua')
                ->join($auTableName . ' au', 'au.id = aua.url_id', 'left')
                ->where([
                    ['aua.app_id', '=', $id],
                ])
                ->field('
                    au.*,
                    aua.app_id as app_id,
                    aua.max_request as max_request
                ')
                ->order([
                    'au.listorder' => 'ASC', 
                ])
                ->select()
                ->toArray();
            
            $TTree = new TTree();
            $TTree->withData($result);
            $list = $TTree->buildFormatList($TTree->buildArray(0), 'title');
            $total = count($list);
            
            $result = [
                "code" => 0, 
                "count" => $total, 
                "data" => $list
            ];
            return $this->json($result);
        } else {
            $id = $this->request->param('id');
            if (empty($id)) {
                return $this->error('ID不能为空');
            }
            
            $this->assign('id', $id);
            
            return $this->fetch();
        }
    }
    
    /**
     * 访问授权链接设置
     *
     * @create 2020-8-17
     * @author deatil
     */
    public function accessUrlSet()
    {
        if (!$this->request->isPost()) {
            return $this->error('请求错误！');
        }
        
        $app_id = $this->request->param('app_id/s', 0);
        if (empty($app_id)) {
            return $this->error('参数app_id不能为空！');
        }
        
        $url_id = $this->request->param('url_id/s', 0);
        if (empty($url_id)) {
            return $this->error('参数url_id不能为空！');
        }
        
        $name = $this->request->param('name/s', 0);
        if (empty($name)) {
            return $this->error('参数name不能为空！');
        }
        
        $value = $this->request->param('value', '');
        
        $rs = LapiUrlAccessModel::where([
            'app_id' => $app_id,
            'url_id' => $url_id,
        ])->update([
            $name => $value,
        ]);
        if ($rs === false) {
            return $this->error("设置失败！");
        }
        
        return $this->success("设置成功！");
    }

}
