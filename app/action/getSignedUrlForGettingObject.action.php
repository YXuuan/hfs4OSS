<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';

$target = @check_var($_POST['target']) ? $_POST['target'] : null;

if($target){
	array_multisort(array_column($APPConfig['AUTH'], 'PATH'), SORT_STRING, SORT_DESC, $APPConfig['AUTH']);		//这么做是为了使AUTH中的路径按长到短排序，即每次如果有父目录和子目录同时加密的情况，就可以让子目录的验证覆盖父目录的
	foreach($APPConfig['AUTH'] as $eachAuth){
		//foreach的作用只是用来找到经过上面排序后的AUTH中符合当前请求路径的第一个，每个分支验证密码成功后都break，也就是说通过上面的排序后，只验证AUTH中距离最接近本次请求路径最接近的那个，即为验证成功
		$prefixWithoutRootDir = str_replace_limit($APPConfig['ROOT_DIR'], "", $eachAuth['PATH'], 1);		//ROOT_DIR对前端不可视，传入的cookies中不包含ROOT_DIR前缀，故与AppConfig中的路径比较时要截掉每一个key的ROOT_DIR前缀部分
		substr($_POST['target'], 0, strlen($prefixWithoutRootDir)) == $prefixWithoutRootDir;
		if(($_POST['target'] == $prefixWithoutRootDir) || (substr($_POST['target'], 0, strlen($prefixWithoutRootDir)) == $prefixWithoutRootDir)){
			$autoPassword = @check_var($hfs4OSS_cookies['passwords'][$prefixWithoutRootDir]) ? $hfs4OSS_cookies['passwords'][$prefixWithoutRootDir] : (@check_var($hfs4OSS_cookies['passwords'][$_POST['target']]) ? $hfs4OSS_cookies['passwords'][$_POST['target']] : null);		//任选其一做为验证的密码
			$resultToSendBack['authedPrefix'] = $prefixWithoutRootDir;		//用于前端接管：密码赋值到Cookies中和AUTH对应的正确路径
			if(!$autoPassword){
				//211：空密码，返回FIRSTMET。前端接管：要求输入密码
				$resultToSendBack['stat'] = 221;
				$resultToSendBack['msg'] = $eachAuth['FIRSTMET'];
			}
			elseif($autoPassword != $eachAuth['PASSWORD']){
				//212：密码错误，返回IFWRONG。前端接管：重新要求输入密码
				$resultToSendBack['stat'] = 222;
				$resultToSendBack['msg'] = $eachAuth['IFWRONG'];
			}
			elseif($autoPassword == $eachAuth['PASSWORD']){
				//验证成功出口。
				break;
			}
			else{
				//210：未知的错误。前端接管：暂无
				$resultToSendBack['stat'] = 220;
			}
			die(json_encode($resultToSendBack));
		}
		else{
			continue;
		}
	}
	$ossClient = OSS::getOssClient();
	
	$getSignedUrlForGettingObjectResult =
		OSS::getSignedUrlForGettingObject(
			$ossClient,
			OSSConfig::BUCKET,
			$APPConfig['ROOT_DIR'] . $target,
			$APPConfig['SIGNEDURL_TIMEOUT']
		);

		//OSSConfig::CDN_ENABLE == true?$getSignedUrlForGettingObjectResult = str_replace(OSSConfig::BUCKET_URL,OSSConfig::CDN,$getSignedUrlForGettingObjectResult):;
		if(OSSConfig::CDN_ENABLE == true){
			$getSignedUrlForGettingObjectResult = str_replace(OSSConfig::BUCKET_URL,OSSConfig::CDN,$getSignedUrlForGettingObjectResult);
		}

	$resultToSendBack['stat'] = "100";
	$resultToSendBack['url'] = $getSignedUrlForGettingObjectResult;
}
else{
	$resultToSendBack['stat'] = "301";
	$resultToSendBack['msg'] = "Invalid Target";
}

print(json_encode($resultToSendBack));