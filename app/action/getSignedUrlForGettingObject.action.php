<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();

$target = @check_var($_POST['target'], null);

foreach($APPConfig['AUTH'] as $key => $value){
	if($target == $key){
		if(!@check_var($hfs4OSS_cookies['passwords'][$key])){
			$resultToSendBack['stat'] = 221;
			$resultToSendBack['msg'] = $APPConfig['AUTH'][$key]['FIRSTMET'];
			die(json_encode($resultToSendBack));
		}else{
			if($hfs4OSS_cookies['passwords'][$key] !== $APPConfig['AUTH'][$key]['PASSWORD']){
				$resultToSendBack['stat'] = 222;
				$resultToSendBack['msg'] = $APPConfig['AUTH'][$key]['IFWRONG'];
				die(json_encode($resultToSendBack));
			}
		}
	}
}

if($target){
	$getSignedUrlForGettingObjectResult =
		OSS::getSignedUrlForGettingObject(
			$ossClient,
			OSSConfig::BUCKET,
			$APPConfig['ROOT_DIR'] . $target,
			$APPConfig['SIGNEDURL_TIMEOUT']
		);
	$resultToSendBack['stat'] = "100";
	$resultToSendBack['url'] = $getSignedUrlForGettingObjectResult;
}else{
	$resultToSendBack['stat'] = "301";
	$resultToSendBack['msg'] = "Invalid Target";
}

print(json_encode($resultToSendBack));