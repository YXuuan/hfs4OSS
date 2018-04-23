<?php
require_once __DIR__ . '/../sdk/aliyun-oss-php-sdk-2.3.0/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class OSS{
	public static function getOssClient(){
		try {
			$ossClient = new OssClient(APPConfig::OSS_ACCESS_ID, APPConfig::OSS_ACCESS_KEY, APPConfig::OSS_ENDPOINT, APPConfig::OSS_ENDPOINT_IS_CNAME);
		} catch (OssException $e) {
			printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
			printf($e->getMessage() . "\n");
			return null;
		}
		return $ossClient;
	}
	public static function listObjects($ossClient, $bucket, $options){
		try {
			$listObjectInfo = $ossClient->listObjects($bucket, $options);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $listObjectInfo;
		/*
		$objectList = $listObjectInfo->getObjectList();
		$prefixList = $listObjectInfo->getPrefixList();
		$result = array();        //函数返回的二维数组
		if (!empty($objectList)) {
			foreach ($objectList as $objectInfo) {
				$result["fileList"][] = array(
					str_replace($options["prefix"], "", $objectInfo->getKey()),
					format_bytes($objectInfo->getSize()),
				);		//存放文件列表
			}
		}
		if (!empty($prefixList)) {
			foreach ($prefixList as $prefixInfo) {
				 $result["folderList"][] = str_replace($options['prefix'], "", $prefixInfo->getPrefix());        //存放目录列表
			}
		}
		return $result;
		*/
	}
	public static function getSignedUrlForGettingObject($ossClient, $bucket, $object, $timeout){
		try{
			$signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
		} catch(OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $signedUrl;
	}
}