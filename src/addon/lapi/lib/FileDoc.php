<?php

namespace app\lapi\lib;

use ReflectionClass;
use ReflectionMethod;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

use lake\Doc;

/**
 * 文件注释解析
 * 
 * @create 2020-8-17
 * @author deatil
 */
class FileDoc
{

    /**
     * 扫描文件
     * 
     * @create 2020-8-17
     * @author deatil
     */
    public function scanFile($path)
    {
        // 匹配出所有的文件
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $fileinfo) {
            if ($fileinfo->isFile()) {
                $filePath = $fileinfo->getPathName();
                $path2 = str_replace($path, '', $filePath);
                
                $filePathinfo = pathinfo($path2);
                
                if ($filePathinfo['dirname'] == '.') {
                    $filePathinfo['dirname'] = '';
                } else {
                    $filePathinfo['dirname'] = str_replace(DIRECTORY_SEPARATOR, '\\', $filePathinfo['dirname']);
                }
                
                $list[] = [
                    'dirname' => $filePathinfo['dirname'],
                    'basename' => $filePathinfo['basename'],
                    'extension' => isset($filePathinfo['extension']) ? $filePathinfo['extension'] : '',
                ];
            }
        }

        return $list;
    }

    /**
     * 获取链接注释信息
     * 
     * @create 2020-8-17
     * @author deatil
     */
    public function getClassFileDoc($classList = [])
    {
        if (empty($classList)) {
            return [];
        }
        
        $list = [];
        foreach ($classList as $class) {
            if (class_exists($class)) {
                $reflection = new ReflectionClass($class);
                $classDocComment = $this->parser($reflection->getDocComment());
                
                $class = [
                    'name' => $reflection->getName(),
                    'hasNamespace' => $reflection->inNamespace(),
                    'shortname' => $reflection->getShortName(),
                    'namespacename' => $reflection->getNamespaceName(),
                    'comment' => $classDocComment,
                ];
                
                $method = [];
                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $key => $v) {
                    $methodDocComment = $this->parser($v->getDocComment());
                    $method[] = [
                        'name' => $v->getName(),
                        'comment' => $methodDocComment,
                    ];
                }
                
                $list[] = [
                    'class' => $class,
                    'method' => $method,
                ];
            }
        }

        return $list;
    }
    
    /**
     * 解析注释
     * 
     * @create 2020-8-17
     * @author deatil
     */
    public function parser($text)
    {
        $doc = new Doc();
        return $doc->parse($text);
    }

}
