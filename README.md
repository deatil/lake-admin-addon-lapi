### api管理框架


## 项目介绍

*  基于 `lake-admin` 后台管理框架的api管理框架


## 使用方法 

*  正确安装了 `lake-admin` 后台
*  `composer require lake/lake-admin-addon-lapi`，然后选择最新版本
*  进入后台安装该模块


## 模块内使用 

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
