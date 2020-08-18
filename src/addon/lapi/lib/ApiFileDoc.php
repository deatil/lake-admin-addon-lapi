<?php

namespace app\lapi\lib;

use app\lapi\lib\FileDoc;

/**
 * API文件注释解析
 * 
 * @create 2020-8-18
 * @author deatil
 */
class ApiFileDoc
{
    /**
     * 解析注释
     * 
     * @create 2020-8-18
     * @author deatil
     */
    public function parsePath($path = [])
    {
        if (empty($path)) {
            return [];
        }
        
        $appNamespace = app()->config->get('app.module_namespace');
        $apiControllerNamespace = $appNamespace . '\\api\\controller';
        
        $FileDoc = new FileDoc();
        $apiFiles = $FileDoc->scanFile($path);
        
        $classList = [];
        if (!empty($apiFiles)) {
            foreach ($apiFiles as $apiFile) {
                $fileName = substr($apiFile['basename'], 0 , -(strlen($apiFile['extension']) + 1));
                
                $fileDir = ltrim($apiFile['dirname'], '\\');
                if (!empty($fileDir)) {
                    $fileDir = '\\' . $fileDir;
                } else {
                    $fileDir = '';
                }
                
                $classList[] = $apiControllerNamespace . $fileDir . '\\' . $fileName;
            }
        }
        
        $classFileDocList = $FileDoc->getClassFileDoc($classList);
        
        $classInfoList = [];
        if (!empty($classFileDocList)) {
            foreach ($classFileDocList as $classFileDoc) {
                $classShortname = $classFileDoc['class']['shortname'];
                $classNamespacename = $classFileDoc['class']['namespacename'];
                
                if (!empty($classFileDoc['method'])) {
                    foreach ($classFileDoc['method'] as $classFileDocMethod) {
                        $methodName = $classFileDocMethod['name'];
                        
                        $layeClassName = substr($classNamespacename, strlen($apiControllerNamespace . '\\'));
                        $className = (!empty($layeClassName) ? $layeClassName . '/' : '') . $classShortname;
                        $className = str_replace('\\', '/', $className);
                        
                        $url = 'api/'.$className.'/'.$methodName;
                        
                        if (isset($classFileDocMethod['comment']['title']) 
                            && !empty($classFileDocMethod['comment']['title'])
                        ) {
                            $classInfoList[] = [
                                'title' => $classFileDocMethod['comment']['title'],
                                'url' => $url,
                                'method' => isset($classFileDocMethod['comment']['method']) ? $classFileDocMethod['comment']['method'] : 'GET',
                                'request' => isset($classFileDocMethod['comment']['request']) ? $classFileDocMethod['comment']['request'] : '',
                                'response' => isset($classFileDocMethod['comment']['response']) ? $classFileDocMethod['comment']['response'] : '',
                                'description' => isset($classFileDocMethod['comment']['description']) ? $classFileDocMethod['comment']['description'] : '',
                                'listorder' => isset($classFileDocMethod['comment']['listorder']) ? $classFileDocMethod['comment']['listorder'] : 100,
                                'status' => isset($classFileDocMethod['comment']['status']) ? $classFileDocMethod['comment']['status'] : 1,
                            ];
                        }
                    }
                }
            }
        }
        
        return $classInfoList;
    }

}
