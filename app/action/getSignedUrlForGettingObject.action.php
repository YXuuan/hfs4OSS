<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

if(isset($_POST['target']) && (!empty($_POST['target']))){
    $result = OSS::getSignedUrlForGettingObject($ossClient, APPConfig::OSS_BUCKET, APPConfig::ROOT_DIR.$_POST['target'], APPConfig::SIGNEDURL_TIMEOUT);
    print(json_encode($result));
}