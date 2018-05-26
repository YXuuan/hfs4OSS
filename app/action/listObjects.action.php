<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';

$APPConfig['AUTH']['bucket'] = OSSConfig::BUCKET;
$options = array(
	'prefix' => @check_var($_POST['prefix']) ? $APPConfig['ROOT_DIR'] . $_POST['prefix'] : $APPConfig['ROOT_DIR'],
	'delimiter' => @check_var($_POST['delimiter']) ? $_POST['delimiter'] : '/',
	//delimiter为“/”时仅获取当前目录下内容
	'max-keys' => @check_var($_POST['maxkeys']) ? $_POST['maxkeys'] : 1000,
	'marker' => @check_var($_POST['marker']) ? $_POST['marker'] : '',
);
$sortBy = @check_var($_POST['sortBy']) ? $_POST['sortBy'] : "name";
$descending = @check_var($_POST['descending']) ? ($_POST['descending'] == "true" ? SORT_ASC : SORT_DESC) : SORT_ASC;
foreach($APPConfig['AUTH'] as $key => $value){
	if(substr($options['prefix'], 0, strlen($key)) == $key){
		if(!@check_var($hfs4OSS_cookies['passwords'][$key])){
			$resultToSendBack['stat'] = 211;
			$resultToSendBack['msg'] = $APPConfig['AUTH'][$key]['FIRSTMET'];
			die(json_encode($resultToSendBack));
		}else{
			if($hfs4OSS_cookies['passwords'][$key] !== $APPConfig['AUTH'][$key]['PASSWORD']){
				$resultToSendBack['stat'] = 212;
				$resultToSendBack['msg'] = $APPConfig['AUTH'][$key]['IFWRONG'];
				die(json_encode($resultToSendBack));
			}
		}
	}
}
$t1 = microtime(true);
$ossClient = OSS::getOssClient();
$listObjectResult = OSS::listObjects($ossClient, $APPConfig['AUTH']['bucket'], $options);
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
		@array_multisort(@array_column($resultToSendBack['fileList'], 'time'), $descending, $resultToSendBack['fileList']);
		break;
	case "size":
		@array_multisort(@array_column($resultToSendBack['fileList'], 'size'), $descending, $resultToSendBack['fileList']);
		break;
	case "name":
		@array_multisort(@array_column($resultToSendBack['fileList'], 'name'), $descending, $resultToSendBack['fileList']);
		@array_multisort(@array_column($resultToSendBack['folderList'], 'name'), $descending, $resultToSendBack['folderList']);
		//@array_multisort($resultToSendBack['folderList'], $descending, $resultToSendBack['folderList']);
		break;
}
$t2 = microtime(true);
$resultToSendBack['fileCount'] = @count($resultToSendBack["fileList"]);
$resultToSendBack['folderCount'] = @count($resultToSendBack["folderList"]);
$resultToSendBack['takes'] = floor(($t2-$t1)*1000);
$resultToSendBack['memUsed'] = memory_get_usage();

$resultToSendBack['stat'] = $APPConfig['SHOW_FILEDATE'] == true ? "100" : "111";

print(json_encode($resultToSendBack));