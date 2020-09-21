## api管理系统


### 项目介绍

*  基于 `lake-admin` 后台管理框架的api管理系统模块插件


### 签名算法

*  第一步，设所有发送或者接收到的数据为集合M，将集合M内非空参数值的参数按照参数名ASCII码从小到大排序（字典序），使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串stringA。 
*  第二步，在stringA最后拼接上key（例如：key=keyValue）得到stringSignTemp字符串，并对stringSignTemp进行MD5运算，再将得到的字符串所有字符转换为大写，得到sign值signValue。
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


### 开源协议

*  `lake-admin` 遵循 `Apache2` 开源协议发布，在保留本系统版权（包括版权文件及系统相关标识，相关的标识需在明显位置标示出来）的情况下提供个人及商业免费使用。  
*  使用该项目时，请在明显的位置保留该系统的版权标识（标识包括：lake，lake-admin及该系统所属logo），并不得修改后台版权信息。


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
