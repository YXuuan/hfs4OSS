<?php
require_once __DIR__ . '/../config/app.config.php';
require_once __DIR__ . '/function/app.function.php';

date_default_timezone_set("PRC");

if(APPConfig::INDEX_PASSWORD !== ""){
	if(!isset($_COOKIE['hfs4OSS_indexPassword']) || (empty($_COOKIE['hfs4OSS_indexPassword']))){
		die('Exception201');
	}else{
		if($_COOKIE['hfs4OSS_indexPassword'] !== APPConfig::INDEX_PASSWORD){
			die('Exception202');
		}
	}
}
