<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

$listObjectsOptions = array();
$listObjectsOptions['prefix'] = (isset($_POST['prefix']) && !empty($_POST['prefix']))
    ? OSSConfig::OSS_ROOT_DIR.$_POST['prefix']
    : OSSConfig::OSS_ROOT_DIR;
$listObjectsOptions['delimiter'] = (isset($_POST['delimiter']) && !empty($_POST['delimiter']))
    ? $_POST['delimiter'] 
    : '/';        //delimiter为“/”时仅获取当前目录下内容
$listObjectsOptions['max-keys'] = (isset($_POST['maxkeys']) && !empty($_POST['maxkeys']))
    ? $_POST['maxkeys'] 
    : 1000;
$listObjectsOptions['marker'] = (isset($_POST['marker']) && !empty($_POST['marker']))
    ? $_POST['marker'] 
    : '';
    
$t1 = microtime(true);
$result_listObjects = OSS::listObjects($ossClient, OSSConfig::OSS_BUCKET, $listObjectsOptions);
$t2 = microtime(true);

@array_shift($result_listObjects['fileList']);       //$fileList第一个object为当前目录，忽略
@$result_listObjects['fileCount'] = count($result_listObjects["fileList"]);
@$result_listObjects['folderCount'] = count($result_listObjects["folderList"]);
$result_listObjects['takes'] = floor(($t2-$t1)*1000) . 'ms';
$result_listObjects['memUsed'] = format_bytes(memory_get_usage());

echo json_encode($result_listObjects);