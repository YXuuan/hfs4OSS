在具备PHP代码执行环境和OSS对象存储服务的条件下，作为云HTTP文件服务器，提供简单的文件列表、上传下载、管理等功能。
相较于将文件直接存放在本地，无存储总量限制、传输速率限制、低可靠数据安全等问题。
  
SDK：aliyun-oss-php-[sdk](https://promotion.aliyun.com/ntms/act/ossdoclist.html)-2.3.0
样式：[h5ai](https://larsjung.de/h5ai/)

## 预览/Demo
* oovoo.site：http://file.oovoo.site/
* ![image](https://yxuuan.github.io/hfs4oss-demo/demo.png)

## 更新日志/ChangeLog
```
version 2.0.2 2018-04-16
	[增加] 文件大小显示
	[优化] encodeURL()入参的路径，防止出现莫名其妙的Bug
	[优化] 前端点击文件下载体验，简单防止恶意请求
	[优化] 前端部分显示细节
version 2.0.1 2018-04-13
	[优化] JavaScirpt代码运行逻辑
	[优化] 前端部分显示细节
	[修复] 文件URL带特殊符号时下载不正常
version 2.0.0 2018-04-12
	[项目] 代码完全重构

version 1.0.4 2017-09-30
	[优化] 上方crumbbar路径多级显示
	[优化] 页面标题只显示当前目录名称
version 1.0.3 2017-08-12
	[优化] 获得上层目录名称和上层目录路径的算法
	[修复] 路径层级到某一数量后上一级文件夹名称显示不正常
version 1.0.2 2017-06-17
	[修复] folder或item不存在时遍历数组产生报错信息
version 1.0.1 2017-06-17
	[增加] 底部程序版本信息
	[修复] items链接指向错误
	[修复] 底部时间错误，tips：date('Y-m-d H:i:s')
version 1.0.0 2017-06-17
	[项目] 破壳。
```

## 部署/Build
* 环境要求：
PHP 5.5及以上
* 配置：
填写oss.config.php和static.config.json：
~~~
/config/oss.config.php：	--OSS配置文件
	OSS_ACCESS_ID		：AccessKey ID
	OSS_ACCESS_KEY		：AccessKey Key
	OSS_ENDPOINT		：Endpoint
		注意：必须带前缀http://或https://
	OSS_ENDPOINT_IS_CNAME	：(true/false)如果Endpoint为自定义域名，此项为true
	OSS_BUCKET		：Bucket名
	OSS_ROOT_DIR		：根目录，类似于FTP服务器的虚拟目录显示（例如此项为"photo/"则会将photo文件夹下的内容当作根目录显示）
		注意：必须以"/"结尾且开头无需用"/"表示根目录
	OSS_SIGNEDURL_TIMEOUT   ：(int)每次下载文件时请求的签名URL有效期（秒），缺省值：3600


/config/static.config.json：	--前端配置文件
	DIRECTLY_GET_OBJECT	：(true/false)下载文件时直接访问文件URL而不向后端请求SignedURL，Bucket为公共读时可用，否则为false
	SITE_NAME		：站点名称
	SHOW_STATS		：(true/false)底部显示状态信息
	FOOTER			：底部Footer
~~~
 
* 文件结构：
```
/
├──app/		--后端目录
  ├──action/		--后端入口目录
  ├──class/		--类库
  ├──function/		--函数库
  └──sdk/		--SDK目录
├──config/	--配置文件目录
  ├──oss.config.php	--OSS配置文件
  └──static.config.json	--前端配置文件
├──static/	--前端目录
  ├──_h5ai/		--h5ai目录
  └──script/		--前端脚本
└──index.html
```

## 后续可能的改动/Preview
```
给不同类型的文件不一样的图标
输出item的大小
批量下载（非压缩闭包）
简单的object管理功能（上传，重命名等）
文件列表排序
```

## 开源协议/License
```
MIT License

Copyright (c) 2017-2018 YXuuan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```