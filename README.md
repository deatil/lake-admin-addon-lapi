## api管理系统


### 项目介绍

*  基于 `lake-admin` 后台管理框架的api管理系统模块插件
*  `签名算法` 借鉴于微信支付，具体算法可以查看微信支付文档


### 使用方法 

*  正确安装了 `lake-admin` 后台
*  `composer require lake/lake-admin-addon-lapi`，然后选择最新版本
*  进入后台安装该模块
*  请求示例：http://yourdomain.com/api/Index/index?name=name2&app_id=API2020090322513090789&nonce_str=6V0RVgWV9uCJXncv&timestamp=1599145016&sign=E3728BEEE2A5753CAD2556EA00C92A86


### 模块内 `api` 文件方法设置

*  方法设置
~~~
<?php

namespace app\api\controller;

/**
 * @title 接口标题[必需]
 * @description 接口描述
 */
class Index
{
    /**
     * 接口方法
     *
     * @title 接口方法标题[必需]
     * @method GET[必需]
     * @request {"a":"c"}
     * @response {"d":"e"}
     * @description 接口方法描述
     * @listorder 100
     * @status 1
     */
    public function index()
    {
        return json([
            'code' => 0,
            'msg' => 'hello world!',
            'data' => 'api data',
        ]);
    }
}

~~~


### 模块内使用 

*  `trait` 引用
~~~
use app\BaseController;
use app\lapi\traits\Lapi as LapiTrait;

class Index extends BaseController
{
    use LapiTrait;

    // 初始化
    protected function initialize()
    {
        parent::initialize();
        $this->checkApiSign();
    }
}
~~~

*  `继承` 使用
~~~
use app\lapi\boot\Lapi as LapiBase;

class Index extends LapiBase
{
    // 初始化
    protected function initialize()
    {
        parent::initialize();
        
        $this->checkApiSign();
    }
}
~~~

*  `控制器中间件` 使用
~~~
use app\BaseController;
use app\lapi\middleware\Lapi as LapiMiddleware;

class Index extends BaseController
{
    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [
        LapiMiddleware::class,
    ];
    
    // 初始化
    protected function initialize()
    {
        parent::initialize();
    }
}
~~~


### 开源协议

*  `lake-admin` 遵循 `Apache2` 开源协议发布，在保留本系统版权（包括版权文件及系统相关标识，相关的标识需在明显位置标示出来）的情况下提供个人及商业免费使用。  
*  使用该项目时，请在明显的位置保留该系统的版权标识（标识包括：lake，lake-admin及该系统所属logo），并不得修改后台版权信息。


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
