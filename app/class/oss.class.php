<?php
require_once __DIR__ . '/../sdk/aliyun-oss-php-sdk-2.3.0/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class OSS{
	public static function getOssClient(){
		try {
			$ossClient = new OssClient(OSSConfig::ACCESS_ID, OSSConfig::ACCESS_KEY, OSSConfig::ENDPOINT, OSSConfig::ENDPOINT_IS_CNAME);
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