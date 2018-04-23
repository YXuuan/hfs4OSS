<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

if(isset($_POST['target']) && !empty($_POST['target'])){
    $result_getSignedUrlForGettingObject = OSS::getSignedUrlForGettingObject($ossClient, APPConfig::OSS_BUCKET, APPConfig::ROOT_DIR.$_POST['target'], APPConfig::SIGNEDURL_TIMEOUT);
    echo json_encode($result_getSignedUrlForGettingObject);
}