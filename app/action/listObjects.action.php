<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();
		
$options = array();
$options['prefix'] = @check_var($_POST['prefix']) ? APPConfig::ROOT_DIR.$_POST['prefix'] : APPConfig::ROOT_DIR;
$options['delimiter'] = @check_var($_POST['delimiter']) ? $_POST['delimiter'] : '/';        //delimiter为“/”时仅获取当前目录下内容
$options['max-keys'] = @check_var($_POST['maxkeys']) ? $_POST['maxkeys'] : 1000;
$options['marker'] = @check_var($_POST['marker']) ? $_POST['marker'] : '';
$sortBy = @check_var($sortBy) ? $sortBy : false;
$descending = @check_var($_POST['descending']) ? $_POST['descending'] : false;

$t1 = microtime(true);
$listObjectResult = OSS::listObjects($ossClient, APPConfig::OSS_BUCKET, $options);
$resultToReturn = array();        //存放action结果的二维数组
$objectList = $listObjectResult->getObjectList();
$prefixList = $listObjectResult->getPrefixList();
if (!empty($objectList)) {
	foreach ($objectList as $objectInfo) {
		$resultToReturn["fileList"][] = array(
			//取出每一个
			substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
			//$objectInfo->getLastModified(),
			strtotime($objectInfo->getLastModified()),
			$objectInfo->getSize()
			//format_bytes($objectInfo->getSize()),
		);
	}
	if($options['prefix'] !== ''){
		@array_shift($resultToReturn['fileList']);       //$fileList第一个object为当前目录，忽略
	}
}
//取出每一个并去掉父级路径放入数组
if (!empty($prefixList)) {
	foreach ($prefixList as $prefixInfo) {
		$resultToReturn["folderList"][] = substr($prefixInfo->getPrefix(), strlen($options['prefix']));
	}
}
//排序相关
if($sortBy && $descending){
	$descending = $descending == "true" ? SORT_ASC : SORT_DESC ;
	if($sortBy == "date"){
		@array_multisort(array_column($resultToReturn['fileList'], 1), $descending, $resultToReturn['fileList']);
	}elseif($sortBy == "size"){
		@array_multisort(array_column($resultToReturn['fileList'], 2), $descending, $resultToReturn['fileList']);
	}else{
		@array_multisort(array_column($resultToReturn['fileList'], 0), $descending, $resultToReturn['fileList']);
		@array_multisort($resultToReturn['folderList'], $descending, $resultToReturn['folderList']);
	}
}
$t2 = microtime(true);
@$resultToReturn['fileCount'] = count($resultToReturn["fileList"]);
@$resultToReturn['folderCount'] = count($resultToReturn["folderList"]);
$resultToReturn['takes'] = floor(($t2-$t1)*1000);
$resultToReturn['memUsed'] = memory_get_usage();

print(json_encode($resultToReturn));