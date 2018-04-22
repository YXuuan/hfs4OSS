# hfs4OSS
在具备PHP代码执行环境和OSS对象存储服务的条件下，
作为云HTTP文件服务器，提供简单的文件列表、上传下载、管理等功能。  
相较于将文件直接存放在本地，无存储总量限制、传输速率限制、低可靠数据安全等问题。
  
SDK：aliyun-oss-php-[sdk](https://promotion.aliyun.com/ntms/act/ossdoclist.html)-2.3.0  
样式：[h5ai](https://larsjung.de/h5ai/)

## 预览/Demo
* oovoo.site：[http://file.oovoo.site/](http://file.oovoo.site/)
* ![image](https://yxuuan.github.io/hfs4oss-demo/demo.png)

## 部署/Build
* 环境：  
PHP 5 >= 5.3，cURL()支持
* 下载：  
Releases：[https://github.com/YXuuan/hfs4OSS/releases/](https://github.com/YXuuan/hfs4OSS/releases/)，  
或使用git：
~~~
git clone https://github.com/YXuuan/hfs4OSS.git
~~~
* 更新：
Releases：[https://github.com/YXuuan/hfs4OSS/releases/](https://github.com/YXuuan/hfs4OSS/releases/)，  
或使用git
* 配置OSS服务：
1. 开通OSS服务、新建存储空间、上传文件：[OSS新手入门](https://promotion.aliyun.com/ntms/ossedu2.html)
2. 了解基本的OSS属性信息，得到Endpoint
3. 申请具有对应访问权限的AccessKey
* 配置：
填写oss.config.php和static.config.json：
~~~
/config/oss.config.php：	--APP配置文件
	OSS_ACCESS_ID		：OSS的AccessKey ID值
	OSS_ACCESS_KEY		：OSS的AcOSS的cessKey Key值
	OSS_ENDPOINT		：OSS的Endpoint值
		注意：必须带前缀http://或https://
	OSS_ENDPOINT_IS_CNAME	：(true/false)如果OSS的Endpoint为自定义域名，此项为true
	OSS_BUCKET		：OSS的存储空间(Bucket)名
	ROOT_DIR		：根目录路径，类似于FTP服务器的虚拟目录显示（例如此项为"photo/"则会将photo文件夹下的内容当作根目录显示）。缺省值为空
		注意：必须以"/"结尾且开头无需用"/"表示根目录
	SIGNEDURL_TIMEOUT   ：(int)每次下载文件时请求的签名URL有效期时长（秒）。缺省值：3600


/config/static.config.json：	--前端配置文件
	SITE_NAME		：站点名称，请遵从json语法规范
	SHOW_STATS		：(true/false)底部是否显示状态信息，缺省值为true
	FOOTER			：底部Footer的HTML代码，请遵从json语法规范。缺省值为空
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
  ├──app.config.php	--后端配置文件
  └──static.config.json	--前端配置文件
├──static/	--前端目录
  ├──_h5ai/		--h5ai目录
  └──script/		--前端脚本
└──index.html
```
## 更新日志/ChangeLog
```
version 2.1.0 2018-04-22
	[新增] 根据文件后缀名显示不同的图标
	[修复] 前端ajax异步请求导致的错误
```
更多：[CHANGELOG.md](https://github.com/YXuuan/hfs4OSS/CHANGELOG.md)

## 已知的问题/Problems
```
顶部crumbbar过长时无法展示也无法收缩
```

## 后续可能的改动/Preview
```
(划掉)给不同类型的文件不一样的图标
(划掉)输出item的大小
批量下载（非压缩闭包）
简单的object管理功能（上传，重命名等）
列表排序
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