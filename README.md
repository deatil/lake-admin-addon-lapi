## api管理系统


### 项目介绍

*  基于 `lake-admin` 后台管理框架的api管理系统模块插件


### 签名算法

*  第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串stringA。 
*  第二步，在stringA最后拼接上key（即key=keyValue）得到stringSignTemp字符串，并对stringSignTemp进行MD5运算，再将得到的字符串所有字符转换为大写，得到sign值signValue。
*  特别注意以下重要规则： 
~~~
◆ 参数名ASCII码从小到大排序（字典序）；
◆ 如果参数的值为空不参与签名；
◆ 参数名区分大小写；
◆ 验证调用返回或服务器主动通知签名时，传送的sign参数不参与签名，将生成的签名与该sign值作校验；
◆ 接口可能增加字段，验证签名时必须支持增加的扩展字段 
~~~


### 使用方法 

*  正确安装了 `lake-admin` 后台
*  `composer require lake/lake-admin-addon-lapi`，然后选择最新版本
*  进入后台安装该模块
*  请求示例：/api/Index/index?name=name2&app_id=API2020090322513090789&nonce_str=6V0RVgWV9uCJXncv&timestamp=1599145016&sign=E3728BEEE2A5753CAD2556EA00C92A86


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
        $this->checkApi();
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
        
        $this->checkApi();
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


### 模块推荐

| 名称 | 描述 |
| --- | --- |
| [cms系统](https://github.com/deatil/lake-cms) | 强大的分类管理，完整的模版开发标签系统，配套的友情链接模块和自定义表单模块，让你的cms简单但高效 |
| [用户管理](https://github.com/deatil/lake-admin-addon-luser) | 通用的用户管理模块，实现了用户登陆api的token及jwt双认证 |
| [API接口](https://github.com/deatil/lake-admin-addon-lapi) | 强大的API接口管理系统，支持多种签名算法验证，支持签名字段多个位置存放 |
| [路由美化](https://github.com/deatil/lake-admin-addon-lroute) | 支持thinkphp自带的多种路由美化设置，自定义你的系统url |
| [菜单结构](https://github.com/deatil/lake-admin-addon-lmenu) | 提取后台菜单分级结构格式，为你的模块开发保驾护航 |
| [数据库管理](https://github.com/deatil/lake-admin-addon-database) | 数据库备份、优化、修复及还原，你的系统维护帮手 |


## 版权信息

本模块遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有 Copyright © deatil(https://github.com/deatil)

All rights reserved。
