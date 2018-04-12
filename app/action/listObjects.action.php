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
$result_listObjects['takes'] = floor(($t2-$t1)*1000);
$result_listObjects['memUsed'] = round((memory_get_usage()/1024), 2);

echo json_encode($result_listObjects);


/*
//测试用本地缓存数据
if($listObjectsOptions['prefix'] == ''){
    echo '
    {"folderList":["dir\/","hfs_for_oss-demo\/","htdocs-backup\/","oss-accesslog\/"],"takes":"1909","memUsed":"749.11"}
    ';
}elseif($listObjectsOptions['prefix'] == 'hfs_for_oss-demo/'){
    echo '
    {"fileList":["hfs_for_oss-demo\/a.file","hfs_for_oss-demo\/file.php"],"folderList":["hfs_for_oss-demo\/dir\/","hfs_for_oss-demo\/samples\/","hfs_for_oss-demo\/targetdir\/","hfs_for_oss-demo\/test\/"],"takes":"2192","memUsed":"756.38"}
    ';
}elseif($listObjectsOptions['prefix'] == 'hfs_for_oss-demo/samples/'){
    echo '
    {"fileList":[],"folderList":["hfs_for_oss-demo\/samples\/codes\/"],"takes":"297","memUsed":"755.79"}
    ';
}elseif($listObjectsOptions['prefix'] == 'hfs_for_oss-demo/samples/codes/'){
    echo '
    {"fileList":["hfs_for_oss-demo\/samples\/codes\/Bucket.php","hfs_for_oss-demo\/samples\/codes\/BucketCors.php","hfs_for_oss-demo\/samples\/codes\/BucketLifecycle.php","hfs_for_oss-demo\/samples\/codes\/BucketLogging.php","hfs_for_oss-demo\/samples\/codes\/BucketReferer.php","hfs_for_oss-demo\/samples\/codes\/BucketWebsite.php","hfs_for_oss-demo\/samples\/codes\/Callback.php","hfs_for_oss-demo\/samples\/codes\/Common.php","hfs_for_oss-demo\/samples\/codes\/Config.php","hfs_for_oss-demo\/samples\/codes\/Image.php","hfs_for_oss-demo\/samples\/codes\/LiveChannel.php","hfs_for_oss-demo\/samples\/codes\/MultipartUpload.php","hfs_for_oss-demo\/samples\/codes\/Object.php","hfs_for_oss-demo\/samples\/codes\/Signature.php"],"folderList":["hfs_for_oss-demo\/samples\/codes\/master\/"],"takes":"346","memUsed":"757.8"}
    ';
}elseif($listObjectsOptions['prefix'] == 'hfs_for_oss-demo/samples/codes/master/'){
    echo '
    {"fileList":[],"takes":"3864","memUsed":"753.03"}
    ';
}
*/