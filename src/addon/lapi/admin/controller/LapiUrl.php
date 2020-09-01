<?php

namespace app\admin\controller;

use Lake\TTree;

use Lake\Admin\Facade\Module as ModuleFacade;
use Lake\Admin\Model\Module as ModuleModel;

use app\lapi\model\LapiUrl as LapiUrlModel;
use app\lapi\lib\ApiFileDoc;

/*
 * 接口列表
 *
 * @create 2020-8-15
 * @author deatil
 */
class LapiUrl extends LapiBase
{
    
    /**
     * 首页
     *
     * @create 2020-8-16
     * @author deatil
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $result = LapiUrlModel::order([
                    'listorder' => 'ASC', 
                    'id' => 'ASC',
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
        }
        
        return $this->fetch();
    }
    
    /*
     * 全部
     *
     * @create 2020-8-15
     * @author deatil
     */
    public function all()
    {
        if (request()->isPost()) {
            $key = input('post.key');
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : 10;
            
            $list = LapiUrlModel::where('title|url', 'like', "%".$key."%") 
                ->field('*')
                ->order('listorder ASC,id ASC')
                ->paginate([
                    'list_rows' => $pageSize,
                    'page' => $page
                ])
                ->toArray();
            
            $data = [
                'code' => 0,
                'msg' => '获取成功!',
                'data' => $list['data'],
                'count' => $list['total'],
            ];
            
            return json($data);
        } else {
            return $this->fetch();
        }
    }
    
    /**
     * 添加
     *
     * @create 2020-8-15
     * @author deatil
     */
    public function add()
    {
        if (request()->isPost()) {
            $post = input('post.');
            
            $data = [
                'id' => md5(mt_rand(10000, 99999).time().mt_rand(10000, 99999)),
                'parentid' => trim($post['parentid']),
                'title' => trim($post['title']),
                'url' => trim($post['url']),
                'method' => trim($post['method']),
                'status' => (isset($post['status']) && $post['status'] == 1) ? 1 : 0,
                'edit_time' => time(),
                'edit_ip' => request()->ip(),
                'add_time' => time(),
                'add_ip' => request()->ip(),
            ];

            $ststus = LapiUrlModel::insert($data);
            if ($ststus === false) {
                return $this->error('添加失败!');
            }

            return $this->success("添加成功", (string) url('index'));
        } else {
            $parentid = $this->request->param('parentid/s', '');
            $result = LapiUrlModel::order([
                'listorder', 
                'id' => 'DESC',
            ])->select()->toArray();

            $TTree = new TTree();
            $data = $TTree->withData($result)->buildArray(0);
            $parents = $TTree->buildFormatList($data, 'title');
            $this->assign("parents", $parents);
            $this->assign("parentid", $parentid);
            
            return $this->fetch();
        }
    }
    
    /**
     * 编辑
     *
     * @create 2020-8-15
     * @author deatil
     */
    public function edit($id = '')
    {
        if (request()->isPost()) {
            $post = input('post.');
            
            $id = trim($post['id']);
            
            $data = [
                'parentid' => trim($post['parentid']),
                'title' => trim($post['title']),
                'url' => trim($post['url']),
                'method' => trim($post['method']),
                'request' => trim($post['request']),
                'response' => trim($post['response']),
                'description' => trim($post['description']),
                'listorder' => intval($post['listorder']),
                'status' => (isset($post['status']) && $post['status'] == 1) ? 1 : 0,
                'edit_time' => time(),
                'edit_ip' => request()->ip(),
            ];

            $status = LapiUrlModel::where([
                'id' => $id,
            ])->update($data);
            if ($status === false) {
                return $this->error('修改失败!');
            }
            
            return $this->success("修改成功", (string) url('index'));
        } else {
            $info = LapiUrlModel::where([
                'id' => $id,
            ])->field("
                id,
                parentid,
                title,
                url,
                method,
                request,
                response,
                description,
                listorder,
                status
            ")
            ->find();
            $this->assign('info', $info);
            
            $TTree = new TTree();
            $result = LapiUrlModel::order([
                'listorder' => 'ASC', 
                'id' => 'DESC',
            ])->select()->toArray();
            
            $childsId = $TTree->getListChildsId($result, $info['id']);
            $childsId[] = $info['id'];
            
            $array = [];
            foreach ($result as $r) {
                if (in_array($r['id'], $childsId)) {
                    continue;
                }
                
                $array[] = $r;
            }
            
            $data = $TTree->withData($array)->buildArray(0);
            $parents = $TTree->buildFormatList($data, 'title');
            $this->assign("parents", $parents);
            
            return $this->fetch();
        }
    }
    
    /**
     * 详情
     *
     * @create 2020-8-16
     * @author deatil
     */
    public function detail($id = '')
    {
        if (!request()->isGet()) {
            return $this->error('请求错误');
        }
        
        if (empty($id)) {
            return $this->error('ID不能为空');
        }
        
        $data = LapiUrlModel::where([
            'id' => $id,
        ])->field("
            id,
            parentid,
            title,
            url,
            method,
            request,
            response,
            description,
            listorder,
            status,
            edit_time,
            add_time
        ")
        ->find();
        if (empty($data)) {
            return $this->error('数据不存在');
        }
        
        $this->assign('data', $data);
        
        $parent = LapiUrlModel::where([
            'id' => $data['parentid'],
        ])->find();
        $this->assign('parent', $parent);
        
        return $this->fetch();
    }

    /**
     * 排序
     *
     * @create 2020-8-16
     * @author deatil
     */
    public function listorder()
    {
        if (!$this->request->isPost()) {
            return $this->error('请求错误！');
        }
        
        $id = $this->request->param('id/s', 0);
        if (empty($id)) {
            return $this->error('参数不能为空！');
        }
        
        $listorder = $this->request->param('value/d', 100);
        
        $rs = LapiUrlModel::update([
            'listorder' => $listorder,
        ], [
            'id' => $id,
        ]);
        if ($rs === false) {
            return $this->error("菜单排序失败！");
        }
        
        return $this->success("菜单排序成功！");
    }
    
    /*
     * 更改状态
     *
     * @create 2020-8-15
     * @author deatil
     */
    public function state()
    {
        $id = input('post.id');
        $status = input('post.status');
        
        $status = LapiUrlModel::where([
            'id' => $id,
        ])->update([
            'status' => $status,
        ]);
        if ($status === false) {
            return $this->error('设置失败!');
        }
        
        return $this->success("设置成功");
    }

    /**
     * 删除
     *
     * @create 2020-8-15
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
        
        $urlInfo = LapiUrlModel::where([
                'id' => $id,
            ])
            ->find();
        if (empty($urlInfo)) {
            return $this->error('数据不存在!');
        }
        
        $urlChildInfo = LapiUrlModel::where([
                'parentid' => $id,
            ])
            ->find();
        if (!empty($urlChildInfo)) {
            return $this->error('还有子级存在，暂不能被删除!');
        }
        
        $status = LapiUrlModel::where([
            'id' => $id,
        ])->delete();
        if ($status === false) {
            return $this->error('删除失败!');
        }
        
        return $this->success("删除成功", (string) url('index'));
    }
    
    /*
     * 更新接口
     *
     * @create 2020-8-18
     * @author deatil
     */
    public function apis()
    {
        if (request()->isAjax()) {
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : 10;
            
            $list = ModuleModel::field('*')
                ->order('listorder ASC,name ASC')
                ->paginate([
                    'list_rows' => $pageSize,
                    'page' => $page
                ])
                ->toArray();
                
            if (!empty($list['data'])) {
                foreach ($list['data'] as $k => $v) {
                    $list['data'][$k]['icon'] = ModuleFacade::getModuleIconData($v['module']);
                }
            }
            
            $data = [
                'code' => 0,
                'msg' => '获取成功!',
                'data' => $list['data'],
                'count' => $list['total'],
            ];
            
            return json($data);
        } else {
            return $this->fetch();
        }
    }
    
    /*
     * 更新接口
     *
     * @create 2020-8-18
     * @author deatil
     */
    public function apisupdate()
    {
        if (!request()->isPost()) {
            return $this->error('请求错误');
        }
        
        $module = $this->request->post('module/s');
        
        if (empty($module)) {
            return $this->error('module不能为空');
        }
        
        if ($module == 'api') {
            $moduleApiName = '默认API应用';
            $apiPath = root_path() . 'app/api/controller';
        } else {
            $moduleData = ModuleModel::where([
                "module" => $module,
            ])->find();
            if (empty($moduleData)) {
                $this->error('模块信息不存在！');
            }
            
            $modulePath = app()->config->get('app.module_path');
            if (!empty($moduleData['path'])) {
                $modulePath2 = ModuleFacade::getModuleRealPath($moduleData['path']);
            } else {
                $modulePath2 = $modulePath . $moduleData['module'];
            }
            
            $moduleApiName = $moduleData['name'];
            $apiPath = $modulePath2 . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'controller';
        }
        
        if (!is_dir($apiPath)) {
            $this->error('模块无api接口，已退出！');
        }
        
        $ApiFileDoc = new apiFileDoc();
        $apiList = $ApiFileDoc->parsePath($apiPath);
        
        $moduleInfo = LapiUrlModel::getDataByUrl($module);
        if (!empty($moduleInfo)) {
            $parentId = $moduleInfo['id'];
        } else {
            $parentId = LapiUrlModel::insertUrl([
                'title' => $moduleApiName,
                'url' => $module,
                'method' => 'GET',
            ]);
        }
        
        if (!empty($apiList)) {
            foreach ($apiList as $val) {
                $apiUrlInfo = LapiUrlModel::getDataByUrl($val['url']);
                $val['parentid'] = $parentId;
                if (empty($apiUrlInfo)) {
                    LapiUrlModel::insertUrl($val);
                } else {
                    LapiUrlModel::where([
                        'url' => $val['url'],
                    ])->update($val);
                }
            }
        }
        
        return $this->success("更新接口成功", '', $apiList);
    }

}
