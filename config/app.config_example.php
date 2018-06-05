<?php

return array(
	'ROOT_DIR' => "mydir1/",
	'SIGNEDURL_TIMEOUT' => 3600,
	'SHOW_FILEDATE' => true,
	'ROOT_AUTH' => array(
		'ENABLED' => true,
		'PASSWORD' => '爱',
		'FIRSTMET' => '你爱我嘛？~',
		'IFWRONG' => '你再说一遍？？？',
	),
	'AUTH' => array(
		array(
			'PATH' => 'dir1/',
			'ENABLED' => true,
			'PASSWORD' => '谁他妈需要你的爱\n老子爱你就够了（恶龙咆哮）',
			'FIRSTMET' => '那我不爱了',
			'IFWRONG' => '好 你竟然敢不爱我（恶龙咆哮）',
		),
		array(
			'PATH' => 'dir2/1.txt',
			'ENABLED' => false,
			'PASSWORD' => '看啥',
			'FIRSTMET' => '12345678',
			'IFWRONG' => '不给你看！',
		),
		array(
			'PATH' => '<需要验证的绝对路径>',
			'ENABLED' => true,
			'PASSWORD' => '<密码>',
			'FIRSTMET' => '<初次见面显示的文本>',
			'IFWRONG' => '<密码错误时显示的文本>',
		),
	),
);