<?php
include_once 'error.php';
$http=new \swoole\http\server('0.0.0.0',9502);
$http->set([
    'package_max_length'=>1024*1024*10,
    'upload_tmp_dir'=>__DIR__.'/upload'
]);


//监听http协议
$http->on('request',function ($request,$response){
    $response->header('Content-Type','text/html');
    $response->header('Charset','utf-8');
    $server=$request->server;
    $path_info=$server['path_info'];
    if($path_info=='/'){
        $path_info='/';
    }else{
        $path_info=explode('/',$path_info);
    }

    if(!is_array($path_info)) {
        $response->status(404);
        $response->end('<meta charset="UTF-8">请求路径无效');
    }
    //模块
    $model=(isset($path_info[1]) && !empty($path_info[1]))?$path_info[1]:'Home';
    //控制器
    $controller=(isset($path_info[2]) && !empty($path_info[1]))?$path_info[2]:'Index';
    //方法
    $method=(isset($path_info[3]) && !empty($path_info[1]))?$path_info[3]:'index';

    //结合错误处理
   try{
        $class_name="\\{$model}\\$controller";

       //$obj= new $class_name;
       throw  new Exception('某个方法不存在');
   }catch(\Exception $e){
       $response->status(200);
       $response->end('<meta charset="UTF-8">'.$e->getMessage());
   }

});

function autoLoad($class){

     $path=\str_replace('\\',DIRECTORY_SEPARATOR,$class).'.php';

     $classPath=__DIR__.'/'.$path;

     if(is_file($classPath)){
            include_once $classPath;
            return;
     }
}

spl_autoload_register('autoLoad');

$http->start();




