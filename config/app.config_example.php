<?php

return array(
	'ROOT_DIR' => "mydir1/",
	'SIGNEDURL_TIMEOUT' => 3600,
	'SHOW_FILEDATE' => true,
	'AUTH' => array(
		'INDEX' => array(
			'FIRSTMET' => '你好呀~\n请输入密码：',
			'PASSWORD' => '2333',
			'IFWRONG' => '输错了>_<\n再试一次：'
		),
		'My Favourite avi/' => array(
			'FIRSTMET' => '看啥？',
			'PASSWORD' => '12345678',
			'IFWRONG' => '不给你看！'
		),
		'Private Files/' => array(
			'FIRSTMET' => '谁他妈需要你的爱\n老子爱你就够了（恶龙咆哮）',
			'PASSWORD' => '那我不爱了',
			'IFWRONG' => '好 你竟然敢不爱我（恶龙咆哮）',
		),
		'index.html' => array(
			'FIRSTMET' => '<初次见面显示的文本>',
			'PASSWORD' => '<密码>',
			'IFWRONG' => '<验证失败时显示的文本>'
		),
	),
);