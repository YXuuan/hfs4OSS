<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';

array_multisort(array_column($APPConfig['AUTH'], 'PATH'), SORT_STRING, SORT_DESC, $APPConfig['AUTH']);		//这么做是为了使AUTH中的路径按长到短排序，即每次如果有父目录和子目录同时加密的情况，就可以让子目录的验证覆盖父目录的
foreach($APPConfig['AUTH'] as $eachAuth){
	//foreach的作用只是用来找到经过上面排序后的AUTH中符合当前请求路径的第一个，每个分支验证密码成功后都break，也就是说通过上面的排序后，只验证AUTH中距离最接近本次请求路径最接近的那个，即为验证成功
	$prefixWithoutRootDir = str_replace_limit($APPConfig['ROOT_DIR'], "", $eachAuth['PATH'], 1);		//ROOT_DIR对前端不可视，传入的cookies中不包含ROOT_DIR前缀，故与AppConfig中的路径比较时要截掉每一个key的ROOT_DIR前缀部分
	substr($_POST['prefix'], 0, strlen($prefixWithoutRootDir)) == $prefixWithoutRootDir;
	if(
		($eachAuth['ENABLED'] == true) &&
		(
			($_POST['prefix'] == $prefixWithoutRootDir) ||
			(substr($_POST['prefix'], 0, strlen($prefixWithoutRootDir)) == $prefixWithoutRootDir)
		)
	){
		$autoPassword = @check_var($hfs4OSS_cookies['passwords'][$prefixWithoutRootDir]) ? $hfs4OSS_cookies['passwords'][$prefixWithoutRootDir] : (@check_var($hfs4OSS_cookies['passwords'][$_POST['prefix']]) ? $hfs4OSS_cookies['passwords'][$_POST['prefix']] : null);		//任选其一做为验证的密码
		$resultToSendBack['authedPrefix'] = $prefixWithoutRootDir;		//用于前端接管：密码赋值到Cookies中和AUTH对应的正确路径
		if(!$autoPassword){
			//211：空密码，返回FIRSTMET。前端接管：要求输入密码
			$resultToSendBack['stat'] = 211;
			$resultToSendBack['msg'] = $eachAuth['FIRSTMET'];
		}elseif($autoPassword != $eachAuth['PASSWORD']){
			//212：密码错误，返回IFWRONG。前端接管：重新要求输入密码
			$resultToSendBack['stat'] = 212;
			$resultToSendBack['msg'] = $eachAuth['IFWRONG'];
		}elseif($autoPassword == $eachAuth['PASSWORD']){
			//验证成功出口。
			break;
		}else{
			//210：未知的错误。前端接管：暂无
			$resultToSendBack['stat'] = 210;
		}
		die(json_encode($resultToSendBack));
	}else{
		continue;
	}
}

$bucket = OSSConfig::BUCKET;
$options = array(
	'prefix' => @check_var($_POST['prefix']) ? $APPConfig['ROOT_DIR'] . $_POST['prefix'] : $APPConfig['ROOT_DIR'],
	'delimiter' => @check_var($_POST['delimiter']) ? $_POST['delimiter'] : '/',
	//delimiter为“/”时仅获取当前目录下内容
	'max-keys' => @check_var($_POST['maxkeys']) ? $_POST['maxkeys'] : 1000,
	'marker' => @check_var($_POST['marker']) ? $_POST['marker'] : '',
);
$sortBy = @check_var($_POST['sortBy']) ? $_POST['sortBy'] : "name";
$descending = @check_var($_POST['descending']) ? ($_POST['descending'] == "true" ? SORT_ASC : SORT_DESC) : SORT_ASC;

$ossClient = OSS::getOssClient();
$listObjectResult = OSS::listObjects($ossClient, $bucket, $options);
$objectList = $listObjectResult->getObjectList();
$prefixList = $listObjectResult->getPrefixList();
if (!empty($objectList)) {
	if($APPConfig['SHOW_FILEDATE'] == true){
		foreach($objectList as $objectInfo){
			$resultToSendBack["fileList"][] = array(
				'name' => substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
				'time' => strtotime($objectInfo->getLastModified()),
				'size' =>$objectInfo->getSize(),
			);
		}
	}else{
		foreach($objectList as $objectInfo){
			$resultToSendBack["fileList"][] = array(
				'name' => substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
				'time' => "",
				'size' =>$objectInfo->getSize(),
			);
		}
	}
	if($options['prefix'] !== ''){
		@array_shift($resultToSendBack['fileList']);       //$fileList第一个object为当前目录，忽略
	}
}
if (!empty($prefixList)) {
	foreach ($prefixList as $prefixInfo) {
		$resultToSendBack["folderList"][] = array(
			'name' => substr($prefixInfo->getPrefix(), strlen($options['prefix'])),		//去掉父级路径放入
		);
	}
}
//排序相关
switch($sortBy){
	case "time":
		@array_multisort(@array_column($resultToSendBack['fileList'], 'time'), SORT_NUMERIC, $descending, $resultToSendBack['fileList']);
		break;
	case "size":
		@array_multisort(@array_column($resultToSendBack['fileList'], 'size'), SORT_NUMERIC, $descending, $resultToSendBack['fileList']);
		break;
	case "name":
		@array_multisort(@array_column($resultToSendBack['fileList'], 'name'), SORT_STRING, $descending, $resultToSendBack['fileList']);
		@array_multisort(@array_column($resultToSendBack['folderList'], 'name'), SORT_STRING, $descending, $resultToSendBack['folderList']);
		//@array_multisort($resultToSendBack['folderList'], $descending, $resultToSendBack['folderList']);
		break;
}
$resultToSendBack['fileCount'] = @count($resultToSendBack["fileList"]);
$resultToSendBack['folderCount'] = @count($resultToSendBack["folderList"]);
$resultToSendBack['takes'] = floor((microtime(true) - $_SERVER['REQUEST_TIME']) * 1000);
$resultToSendBack['memUsed'] = memory_get_usage();

$resultToSendBack['stat'] = $APPConfig['SHOW_FILEDATE'] == true ? 100 : 111;

print(json_encode($resultToSendBack));