<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

$target = @check_var($_POST['target'], null);
$resultToReturn = array();        //存放action结果的二维数组

if($target){
	$getSignedUrlForGettingObjectResult =
		OSS::getSignedUrlForGettingObject(
			$ossClient,
			OSSConfig::BUCKET,
			$APPConfig['ROOT_DIR'] . $target,
			$APPConfig['SIGNEDURL_TIMEOUT']
		);
	$resultToReturn['stat'] = "100";
	$resultToReturn['SignedUrlForGettingObject'] = $getSignedUrlForGettingObjectResult;
}else{
	$resultToReturn['stat'] = "301";
	$resultToReturn['msg'] = "Invalid Target";
}

print(json_encode($resultToReturn));