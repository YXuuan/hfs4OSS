<?php
require_once 'aliyun-oss-php-sdk-2.2.4/common.php' ;
use OSS \OssClient  ;
use OSS \Core \OssException  ;
$ossClient = Common::getOssClient() ;
if (is_null($ossClient))exit(1) ;
function  listObjects ($ossClient,$dir){
    $bucket = $bucket = Config::OSS_BUCKET;
	$prefix = $dir ;
	$delimiter = '/' ;
	$nextMarker = '' ;
	$maxkeys = 1000 ;
	$options = array ('delimiter' => $delimiter,'prefix' => $prefix,'max-keys' => $maxkeys,'marker' => $nextMarker,) ;
	try {
		$listObjectInfo = $ossClient->listObjects ($bucket,$options) ;
	}
	catch (OssException $e){
		printf(__FUNCTION__.": FAILED\n") ;
		printf($e->getMessage()."\n") ;
		return  ;
	}
	$objectList = $listObjectInfo->getObjectList() ;
	// 文件列表
	$prefixList = $listObjectInfo->getPrefixList() ;
	// 目录列表
	if (!empty($objectList)){
		$i = 0 ;
		global $object ;
		$object = array() ;
		foreach ($objectList as $objectInfo){
			$n = $objectInfo->getKey() ;
			$object[] = $n ;
			$i++ ;
		}
		
	}
	if (!empty($prefixList)){
		$i = 0 ;
		global $prefix ;
		$prefix = array() ;
		foreach ($prefixList as $prefixInfo){
			$n = $prefixInfo->getPrefix() ;
			$prefix[] = $n ;
			$i++ ;
		}
		
	}
}