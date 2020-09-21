<?php

namespace app\lapi\service;

use think\exception\HttpResponseException;
use think\Response;
use think\facade\Env;

use app\lapi\lib\Sign;
use app\lapi\lib\Sha256Sign;

use app\lapi\model\LapiApp as LapiAppModel;
use app\lapi\model\LapiAppLog as LapiAppLogModel;
use app\lapi\model\LapiConfig as LapiConfigModel;
use app\lapi\model\LapiUrl as LapiUrlModel;
use app\lapi\model\LapiUrlAccess as LapiUrlAccessModel;

/*
 * API检测
 *
 * @create 2020-8-12
 * @author deatil
 */
class Lapi
{
    /*
     * 检测签名
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function checkApi()
    {
        // api设置
        $appConfig = LapiConfigModel::getList();
        if (isset($appConfig['api_close']) 
            && $appConfig['api_close'] == 1
        ) {
            return $this->errorJson($appConfig['api_close_tip'], 99999);
        }
        Env::set([
            'app_config' => $appConfig,
        ]);
        
        $data = request()->param();
        if (empty($data)) {
            return $this->errorJson("数据错误", 99);
        }
        
        if (isset($appConfig['open_putlog']) 
            && $appConfig['open_putlog'] == 1
        ) {
            // 记录日志
            $this->createApiLog([
                'app_id' => isset($data['app_id']) ? $data['app_id'] : 'error',
            ]);
        }
        
        if (!isset($data['app_id']) || empty($data['app_id'])) {
            return $this->errorJson("app_id错误", 99);
        }
        $appId = $data['app_id'];

        $app = LapiAppModel::where([
            'app_id' => $appId,
        ])->find();
        if (empty($app) || !$app['status']) {
            return $this->errorJson("授权错误", 97);
        }

        // 签名检测
        if ($app['is_check'] == 1) {
            $userAgent = request()->server('HTTP_USER_AGENT');
            if (empty($userAgent)) {
                return $this->errorJson("客户端错误", 99);
            }
        
            $nonceStr = $data['nonce_str'];
            if (empty($nonceStr)) {
                return $this->errorJson("nonce_str错误", 99);
            }
            if (strlen($nonceStr) != 16) {
                return $this->errorJson("nonce_str格式错误", 99);
            }

            $timestamp = $data['timestamp'];
            if (empty($timestamp)) {
                return $this->errorJson("时间戳错误", 99);
            }
            if (strlen($timestamp) != 10) {
                return $this->errorJson("时间戳格式错误", 99);
            }
            if (time() - $timestamp > (60 * 30)) {
                return $this->errorJson("时间错误，请确认你的时间为正确的北京时间", 99);
            }

            // 验证签名
            if ($app['check_type'] == 'SHA256') {
                $checkSign = Sha256Sign::getInstance();
            } else {
                $checkSign = Sign::getInstance();
            }

            if ($app['sign_postion'] == 'header') {
                $header = request()->header();
                if (!isset($header['sign'])) {
                    return $this->errorJson("签名错误", 99);
                }
                
                $sign = $header['sign'];
            } else {
                if (!isset($data['sign'])) {
                    return $this->errorJson("签名错误", 99);
                }
                
                $sign = $data['sign'];
            }

            if (empty($sign)) {
                return $this->errorJson("签名错误", 99);
            }
            
            $checkSignData = $data;
            $checkSignKey = $app['app_secret'];
            $checkSignString = $checkSign->makeSign($checkSignData, $checkSignKey);

            if ($checkSignString != $sign) {
                return $this->errorJson("授权验证失败", 99);
            }
        }
        
        Env::set([
            'app' => $app,
        ]);
        
        $this->checkUrlApiAuth();
    }
    
    /*
     * 检测接口链接权限
     *
     * @create 2020-8-17
     * @author deatil
     */
    public function checkUrlApiAuth()
    {
        $requestMethod = app()->request->method();
        $requestUrl = str_replace('.', '/', 
            app()->http->getName() . 
            '/' . app()->request->controller() . 
            '/' . app()->request->action()
        );
        
        $requestUrlInfo = LapiUrlModel::where([
                'url' => $requestUrl,
            ])
            ->field("*")
            ->find();
        if (empty($requestUrlInfo) 
            || $requestUrlInfo['status'] != 1
        ) {
            return $this->errorJson("该链接拒绝访问", 99);
        }
        
        if ($requestUrlInfo['method'] != strtoupper($requestMethod)) {
            return $this->errorJson("该链接拒绝访问", 99);
        }
        
        $app = Env::get('app');
        $requestUrlAccessInfo = LapiUrlAccessModel::where([
                'app_id' => $app['id'],
                'url_id' => $requestUrlInfo['id'],
            ])
            ->field("max_request")
            ->find();
        if (empty($requestUrlAccessInfo)) {
            return $this->errorJson("该链接拒绝访问", 99);
        }
        
        $appConfig = Env::get('app_config');
        if (isset($appConfig['open_putlog']) 
            && $appConfig['open_putlog'] == 1
        ) {
            $LapiAppLogCount = LapiAppLogModel::where([
                    ['app_id', '=', $app['app_id']],
                    ['api', '=', $requestUrl],
                    ['add_time', '>=', (time() - 1)],
                ])
                ->count();
            if ($LapiAppLogCount > intval($requestUrlAccessInfo['max_request'])) {
                return $this->errorJson("请求访问太快了", 99);
            }
        }
    }

    /*
     * 添加日志
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function createApiLog($data = []) 
    {
        if (empty($data)) {
            return false;
        }
        
        $requestUrl = str_replace('.', '/', 
            app()->http->getName() . 
            '/' . app()->request->controller() . 
            '/' . app()->request->action()
        );
        
        $data = array_merge([
            'id' => md5(time().mt_rand(10000, 99999).mt_rand(100, 999)),
            'api' => $requestUrl,
            'url' => urldecode(request()->url(true)),
            'method' => app()->request->method(),
            'useragent' => request()->server('HTTP_USER_AGENT'),
            'header' => json_encode(request()->header(), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
            'payload' => json_encode(request()->param(), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
            'content' => request()->getContent(),
            'cookie' => json_encode($_COOKIE, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
            'add_time' => time(),
            'add_ip' => request()->ip(),
        ], $data);
        
        $status = LapiAppLogModel::insert($data);
        if ($status === false) {
            return false;
        }
        
        return true;
    }
    
    /*
     * 返回错误json
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function errorJson($msg = null, $code = 1, $data = []) 
    {
        return $this->httpResponse(false, $code, $msg, $data);
    }
    
    /*
     * 返回成功json
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function successJson($msg = '获取成功', $data = null, $code = 0) 
    {
        return $this->httpResponse(true, $code, $msg, $data);
    }
    
    /*
     * 公用
     *
     * @create 2020-8-12
     * @author deatil
     */
    public function httpResponse($success = true, $code, $msg = "", $data = [])
    {
        $result['success'] = $success;
        $result['code'] = $code;
        $msg ? $result['msg'] = $msg : null;
        $data ? $result['data'] = $data : null;

        $type = 'json';
        
        $app = Env::get('app');

        $header = [];
        if ($app['allow_origin'] == 1) {
            $header['Access-Control-Allow-Origin']  = '*';
            $header['Access-Control-Allow-Headers'] = 'X-Requested-With,X_Requested_With,Content-Type';
            $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        }
        
        $response = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

}
