<?php

namespace app\lapi\middleware;

use Closure;
use think\App;

use app\lapi\traits\Lapi as LapiTrait;

/*
 * API检测中间件
 *
 * @create 2020-8-15
 * @author deatil
 */
class Lapi
{
    use LapiTrait;

    /** @var App */
    protected $app;

    public function __construct(App $app)
    {
        $this->app  = $app;
    }
    
    /**
     * 入口
     *
     * @create 2020-8-15
     * @author deatil
     */
    public function handle($request, Closure $next)
    {
        $this->checkApi();
        
        return $this->app->middleware->pipeline('app')
            ->send($request)
            ->then(function ($request) use ($next) {
                return $next($request);
            });
    }

}
