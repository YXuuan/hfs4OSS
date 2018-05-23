<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();
$bucket = OSSConfig::BUCKET;
$options = array(
	'prefix' => @check_var($_POST['prefix']) ? $APPConfig['ROOT_DIR'] . $_POST['prefix'] : $APPConfig['ROOT_DIR'],
	'delimiter' => @check_var($_POST['delimiter']) ? $_POST['delimiter'] : '/',
	//delimiter为“/”时仅获取当前目录下内容
	'max-keys' => @check_var($_POST['maxkeys']) ? $_POST['maxkeys'] : 1000,
	'marker' => @check_var($_POST['marker']) ? $_POST['marker'] : '',
);
$sortBy = @check_var($_POST['sortBy'], null);
$descending = @check_var($_POST['descending'], null);

$t1 = microtime(true);
$listObjectResult = OSS::listObjects($ossClient, $bucket, $options);
$resultToReturn = array();        //存放action结果的二维数组
$objectList = $listObjectResult->getObjectList();
$prefixList = $listObjectResult->getPrefixList();
if (!empty($objectList)) {
	if($APPConfig['SHOW_FILEDATE'] == true){
		foreach ($objectList as $objectInfo) {
			$resultToReturn["fileList"][] = array(
				'name' => substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
				'time' => strtotime($objectInfo->getLastModified()),
				'size' =>$objectInfo->getSize(),
			);
		}
	}else{
		foreach ($objectList as $objectInfo) {
			$resultToReturn["fileList"][] = array(
				'name' => substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
				'size' =>$objectInfo->getSize(),
			);
		}
	}
	if($options['prefix'] !== ''){
		@array_shift($resultToReturn['fileList']);       //$fileList第一个object为当前目录，忽略
	}
}
if (!empty($prefixList)) {
	foreach ($prefixList as $prefixInfo) {
		$resultToReturn["folderList"][] = array(
			'name' => substr($prefixInfo->getPrefix(), strlen($options['prefix'])),		//去掉父级路径放入
		);
	}
}
//排序相关
$descending = ($descending == "true") ? SORT_ASC : SORT_DESC ;
$sortBy = ($sortBy) ? $sortBy : "label";
switch($sortBy){
	case "time":
		@array_multisort(array_column($resultToReturn['fileList'], 'time'), $descending, $resultToReturn['fileList']);
		break;
	case "size":
		@array_multisort(array_column($resultToReturn['fileList'], 'size'), $descending, $resultToReturn['fileList']);
		break;
	case "name":
		@array_multisort(array_column($resultToReturn['fileList'], 'name'), $descending, $resultToReturn['fileList']);
		@array_multisort(array_column($resultToReturn['folderList'], 'name'), $descending, $resultToReturn['folderList']);
		//@array_multisort($resultToReturn['folderList'], $descending, $resultToReturn['folderList']);
		break;
}
$t2 = microtime(true);
@$resultToReturn['fileCount'] = count($resultToReturn["fileList"]);
@$resultToReturn['folderCount'] = count($resultToReturn["folderList"]);
$resultToReturn['takes'] = floor(($t2-$t1)*1000);
$resultToReturn['memUsed'] = memory_get_usage();

$resultToReturn['stat'] = $APPConfig['SHOW_FILEDATE'] == true ? "100" : "111";

print(json_encode($resultToReturn));