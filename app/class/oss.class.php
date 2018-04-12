<?php
require_once __DIR__ . '/../sdk/aliyun-oss-php-sdk-2.3.0/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class OSS{
    public static function getOssClient(){
        try {
            $ossClient = new OssClient(OSSConfig::OSS_ACCESS_ID, OSSConfig::OSS_ACCESS_KEY, OSSConfig::OSS_ENDPOINT, OSSConfig::OSS_ENDPOINT_IS_CNAME);
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
        //print(__FUNCTION__ . ": OK" . "\n");
        $objectList = $listObjectInfo->getObjectList(); // object list
        $prefixList = $listObjectInfo->getPrefixList(); // directory list
        $result = array();        //函数返回的二维数组
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $result["fileList"][] = str_replace($options['prefix'], "", $objectInfo->getKey());        //存放文件列表
            }
        }
        if (!empty($prefixList)) {
            foreach ($prefixList as $prefixInfo) {
                 $result["folderList"][] = str_replace($options['prefix'], "", $prefixInfo->getPrefix());        //存放目录列表
            }
        }
        return $result;
    }
    public static function getSignedUrlForGettingObject($ossClient, $bucket, $object){
        $timeout = 3600;
        try{
            $signedUrl = $ossClient->signUrl($bucket, $object, $timeout);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        return $signedUrl;
        /**
         * 可以类似的代码来访问签名的URL，也可以输入到浏览器中去访问
         */
        /*
        $request = new RequestCore($signedUrl);
        $request->set_method('GET');
        $request->send_request();
        $res = new ResponseCore($request->get_response_header(), $request->get_response_body(), $request->get_response_code());
        if ($res->isOK()) {
            print(__FUNCTION__ . ": OK" . "\n");
        } else {
            print(__FUNCTION__ . ": FAILED" . "\n");
        };
        */
    }
    /*public static function listObjects($ossClient, $prefix){
        $bucket = Config::OSS_BUCKET;
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 1000;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $listObjectInfo = $ossClient->listObjects($bucket, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
        $objectList = $listObjectInfo->getObjectList(); // object list
        $prefixList = $listObjectInfo->getPrefixList(); // directory list
        $result = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $result["o"][] = $objectInfo->getKey();
            }
        }
        if (!empty($prefixList)) {
            foreach ($prefixList as $prefixInfo) {
                 $result["p"][] = $prefixInfo->getPrefix();
            }
        }
        return $result;
    }*/
}