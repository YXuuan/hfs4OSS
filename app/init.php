<?php
require_once __DIR__ . '/../config/oss.config.php';
require_once __DIR__ . '/function/app.function.php';
$APPConfig = include(__DIR__ . '/../config/app.config.php');

date_default_timezone_set("UTC");		//OSS返回的XML Schema时间是UTC时区的

$hfs4OSS_cookies = @check_var($_COOKIE['hfs4OSS_cookies']) ? json_decode($_COOKIE['hfs4OSS_cookies'], true) : null;
$resultToSendBack = array();

if($APPConfig['ROOT_AUTH']['ENABLED'] == true){
	if(!@check_var($hfs4OSS_cookies['passwords']['index'])){
		$resultToSendBack['stat'] = 201;
		$resultToSendBack['msg'] = $APPConfig['ROOT_AUTH']['FIRSTMET'];
		die(json_encode($resultToSendBack));
	}else{
		if($hfs4OSS_cookies['passwords']['index'] !== $APPConfig['ROOT_AUTH']['PASSWORD']){
			$resultToSendBack['stat'] = 202;
			$resultToSendBack['msg'] = $APPConfig['ROOT_AUTH']['IFWRONG'];
			die(json_encode($resultToSendBack));
		}
	}
}