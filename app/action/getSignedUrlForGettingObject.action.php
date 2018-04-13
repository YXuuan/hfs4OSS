<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

if(isset($_POST['target']) && !empty($_POST['target'])){
    $result_getSignedUrlForGettingObject = OSS::getSignedUrlForGettingObject($ossClient, OSSConfig::OSS_BUCKET, OSSConfig::OSS_ROOT_DIR.$_POST['target']);
    echo json_encode($result_getSignedUrlForGettingObject);
}