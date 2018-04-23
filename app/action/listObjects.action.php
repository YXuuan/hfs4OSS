<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();
		
$listObjectsOptions = array();
$listObjectsOptions['prefix'] = (isset($_POST['prefix']) && !empty($_POST['prefix']))
    ? APPConfig::ROOT_DIR.$_POST['prefix']
    : APPConfig::ROOT_DIR;
$listObjectsOptions['delimiter'] = (isset($_POST['delimiter']) && !empty($_POST['delimiter']))
    ? $_POST['delimiter'] 
    : '/';        //delimiter为“/”时仅获取当前目录下内容
$listObjectsOptions['max-keys'] = (isset($_POST['maxkeys']) && !empty($_POST['maxkeys']))
    ? $_POST['maxkeys'] 
    : 1000;
$listObjectsOptions['marker'] = (isset($_POST['marker']) && !empty($_POST['marker']))
    ? $_POST['marker'] 
    : '';
    
$t1 = microtime(true);
$listObjectInfo = OSS::listObjects($ossClient, APPConfig::OSS_BUCKET, $listObjectsOptions);
$t2 = microtime(true);

$listObjectsResults = array();        //存放action结果的二维数组

$objectList = $listObjectInfo->getObjectList();
$prefixList = $listObjectInfo->getPrefixList();
if (!empty($objectList)) {
	foreach ($objectList as $objectInfo) {
		$listObjectsResults["fileList"][] = array(		//取出每一个
			substr($objectInfo->getKey(), strlen($listObjectsOptions['prefix'])),		//并去掉父级路径
			date("Y-m-d H:i", strtotime($objectInfo->getLastModified())),
			format_bytes($objectInfo->getSize()),
		);
	}
}
if (!empty($prefixList)) {
	foreach ($prefixList as $prefixInfo) {
		$listObjectsResults["folderList"][] = substr($prefixInfo->getPrefix(), strlen($listObjectsOptions['prefix']));        //取出每一个并去掉父级路径放入数组
	}
}
		
if($listObjectsOptions['prefix'] !== ''){
	@array_shift($listObjectsResults['fileList']);       //$fileList第一个object为当前目录，忽略
}
@$listObjectsResults['fileCount'] = count($listObjectsResults["fileList"]);
@$listObjectsResults['folderCount'] = count($listObjectsResults["folderList"]);
$listObjectsResults['takes'] = floor(($t2-$t1)*1000) . 'ms';
$listObjectsResults['memUsed'] = format_bytes(memory_get_usage());

echo json_encode($listObjectsResults);