<?php

return array(
	'ROOT_DIR' => "mydir/",
	'SIGNEDURL_TIMEOUT' => 3600,
	'INDEX_AUTH' => array(
		'FIRSTMET' => '请输入密码：',
		'PASSWORD' => '2333',
		'IFWRONG' => '我不会告诉你密码是2333的，请重新输入：'
	),
	'PREFIX_AUTH' => array(
		'mydir/myphotos/' => array(
			'FIRSTMET' => '请输入相册密码：',
			'PASSWORD' => '2334',
			'IFWRONG' => '密码错误，重新输入：'
		),
		'mydir/myvideos/' => array(
			'FIRSTMET' => '请输入相册密码：',
			'PASSWORD' => '2335',
			'IFWRONG' => '你以为我会给你看吗？'
		)
	),
	'FILE_AUTH' => array(
		'mydir/something/SNIS-048.avi' => array(
			'FIRSTMET' => '你真的想看，输密码吧？',
			'PASSWORD' => '啧',
			'IFWRONG' => '密码错误，重新输入：'
		),
	),
);