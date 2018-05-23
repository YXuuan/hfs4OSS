<?php
require_once __DIR__ . '/../config/oss.config.php';
require_once __DIR__ . '/function/app.function.php';

$APPConfig = include(__DIR__ . '/../config/app.config.php');

date_default_timezone_set("UTC");		//OSS返回的XML Schema时间是UTC时区的

if($APPConfig['INDEX_AUTH']['PASSWORD'] !== ""){
	$indexPassword = @check_var($_COOKIE['hfs4OSS_indexPassword'], null);
	$resultToReturn = array();
	if(!$indexPassword){
		$resultToReturn['stat'] = "201";
		$resultToReturn['msg'] = $APPConfig['INDEX_AUTH']['FIRSTMET'];
		die(json_encode($resultToReturn));
	}else{
		if($indexPassword !== $APPConfig['INDEX_AUTH']['PASSWORD']){
			$resultToReturn['stat'] = "202";
			$resultToReturn['msg'] = $APPConfig['INDEX_AUTH']['IFWRONG'];
			die(json_encode($resultToReturn));
		}
	}
}
