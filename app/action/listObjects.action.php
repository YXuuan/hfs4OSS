<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../class/oss.class.php';
$ossClient = OSS::getOssClient();
		
$options = array();
$options['prefix'] = (isset($_POST['prefix']) && (!empty($_POST['prefix'])))
    ? APPConfig::ROOT_DIR.$_POST['prefix']
    : APPConfig::ROOT_DIR;
$options['delimiter'] = (isset($_POST['delimiter']) && (!empty($_POST['delimiter'])))
    ? $_POST['delimiter'] 
    : '/';        //delimiter为“/”时仅获取当前目录下内容
$options['max-keys'] = (isset($_POST['maxkeys']) && (!empty($_POST['maxkeys'])))
    ? $_POST['maxkeys'] 
    : 1000;
$options['marker'] = (isset($_POST['marker']) && (!empty($_POST['marker'])))
    ? $_POST['marker'] 
    : '';
    
$t1 = microtime(true);
$listObjectResult = OSS::listObjects($ossClient, APPConfig::OSS_BUCKET, $options);

$resultToReturn = array();        //存放action结果的二维数组
$objectList = $listObjectResult->getObjectList();
$prefixList = $listObjectResult->getPrefixList();
if (!empty($objectList)) {
	foreach ($objectList as $objectInfo) {
		$resultToReturn["fileList"][] = array(		//取出每一个
			substr($objectInfo->getKey(), strlen($options['prefix'])),		//并去掉父级路径
			date("Y-m-d H:i", strtotime($objectInfo->getLastModified())),
			format_bytes($objectInfo->getSize()),
		);
	}
	if($options['prefix'] !== ''){
	@array_shift($resultToReturn['fileList']);       //$fileList第一个object为当前目录，忽略
}
}
if (!empty($prefixList)) {
	foreach ($prefixList as $prefixInfo) {
		$resultToReturn["folderList"][] = substr($prefixInfo->getPrefix(), strlen($options['prefix']));        //取出每一个并去掉父级路径放入数组
	}
}

$t2 = microtime(true);

@$resultToReturn['fileCount'] = count($resultToReturn["fileList"]);
@$resultToReturn['folderCount'] = count($resultToReturn["folderList"]);
$resultToReturn['takes'] = floor(($t2-$t1)*1000) . 'ms';
$resultToReturn['memUsed'] = format_bytes(memory_get_usage());

print(json_encode($resultToReturn));