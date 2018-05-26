var hfs4OSS_cookies = $.cookie('hfs4OSS_cookies') ? JSON.parse($.cookie('hfs4OSS_cookies')) : {};
var appConfig = {};
$.ajax({
	type: 'GET',
	url: 'config/static.config.json?t=' + new Date().getTime(),
	dataType: 'text',
	success: function(resultRow){
		try{
			appConfig = JSON.parse(resultRow);
		}catch(e){       //异常捕获： 捕获请求成功但无法解析JSON的异常，意思就是有个傻逼把app.config.json改死了
			alert('ERROR!\najaxget app.config succeed but JSON.parse() failed\nGo FLUCKING to check for the console');
			console.error('JSON.parse() error:\n' + e);
		}
		$(document).attr("title", appConfig.SITE_NAME);
		if(appConfig.FOOTER !== ""){
			$("#stats").after(
				'<div style="margin-top:15px">' +
					'<span id="footer">' + appConfig.FOOTER + '</span>' +
				'</div>');
		}
		$("#crumbbar").html(        //我也不知道为什么非要在这里加一个才能在会话创建后就显示出站点名字
			'<a href="#" class="crumb">' +
				'<span class="name">' + appConfig.SITE_NAME + '</span>' +
				'<img class="hint" src="static/h5ai/public/images/themes/h5ai-0.27/folder-page.svg" alt="#">' +
			'</a>'
		);
	},
	error: function(textStatus, errorThrown){
		alert('ERROR!\najaxget app.config failed:\nGo FLUCKING to check for the console');
		console.error(XMLHttpRequest.status);
		console.error(XMLHttpRequest.readyState);
		console.error(textStatus);
	}
});
listObjects(window.location.hash.substring(1));
$(document).ready(function(){
	//Event Loop
	$(".header").on("click", "a", function(event){     //定义的#back是为了每次覆盖
		sortSwitch($(this));
	});
	//对Ajax返回数据后新生成的元素进行绑定
	$("#back").on("click", "li.item.folder.folder-parent", function(event){     //定义的#back是为了每次覆盖
		listObjects($(this).attr("data"));    //listObjects(当前元素的data值)
	});
	$("#list").on("click", "li.item.folder", function(event){       //定义的#list是为了不清空所有
		listObjects($(this).attr("data"));
	});
	$("#crumbbar").on("click", 'a.crumb', function(event){
		listObjects($(this).attr("data"));
	});
	$("#list").on("click", "li.item.file", function(event){
		//如果已经请求过一次则直接下载
		if($(this).find("a").attr("href")){
			this.click();
		}else{
			downloadObject($(this).attr("data"), this);
			this.click();
		}
	});
});
function listObjects(prefix = ""){
	//有参数传入时优先使用参数，无参数传入时判断当前页面已有的状态，两者都无时走默认
	//哪段程序体现了暴力美学?
	/*var prefix = arguments[0] !== undefined ? decodeURI(arguments[0]) : window.location.hash.substring(1) ? decodeURI(window.location.hash.substring(1)) : "";
	*/
	prefix = decodeURI(prefix);
	var sortBy = arguments[1] !== undefined ? arguments[1] : $(".name").hasClass("descending") || $(".name").hasClass("ascending") ? "name" : $(".time").hasClass("descending") || $(".time").hasClass("ascending") ? "time" : $(".size").hasClass("descending") || $(".size").hasClass("ascending") ? "size" : "name";
	var descending = arguments[2] !== undefined ? arguments[2] : $(".name, .size, .time").hasClass("descending") ? "true" : $(".name, .size, .time").hasClass("ascending") ? "false" : "true";
	$("#items").attr("style", "opacity: 0.5;-moz-opacit: 0.5;");
	console.log('listObjects(' + decodeURI(prefix) + ', ' + sortBy + ', ' + descending + '): ');
	$.ajax({
		type: 'POST',
		url: 'app/action/listObjects.action.php',
		data: {
				prefix: prefix,
				sortBy: sortBy,
				descending: descending
		},
		dataType: 'text',
		success: function(resultRow){
			console.log(resultRow);
			$("#list").html('');        //清空原有内容
			//密码相关处理
			try{
				result = JSON.parse(resultRow);
			}catch(e){           //异常捕获： 捕获请求成功但无法解析JSON的异常，多为listObjects.action抛出的异常
				alert('ERROR!\najax listObjects.action succeed but JSON.parse() failed:\nGo FLUCKING to check the console');
				console.error('JSON.parse() error:\n' + e);
				return false;
			}
			if(!(result.stat >= 100 && result.stat <= 199)){
				if(exceptionHandler(result.stat, result, prefix) === true){
					listObjects(prefix);
				}
				return;
			}
			//列表动作
			$.each(result.folderList, function(i, folderInfo){
				$("#list").append(
					'<li class="item folder" data="' + prefix + encodeURI(folderInfo.name) + '">' +
						'<a href="#' + prefix + encodeURI(folderInfo.name) + '">' +
							'<span class="icon square">' +
								'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder.svg" alt="folder" />' +
							'</span>' +
							'<span class="icon landscape">' +
								'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder.svg" alt="folder" />' +
							'</span>' +
							'<span class="name" title="'+ decodeURI(prefix) + folderInfo.name.replace("/", "") + '">' +
								folderInfo.name.replace("/", "") +
							'</span>' +
							'<span style="display: none;" class="time" title="-">-</span>' +
							'<span class="size" title="-">-</span>' +
						'</a>' +
					'</li>'
				);
			});
			var fileSuffix = "";
			var fileIcon = "";
			$.each(result.fileList, function(i, fileInfo){
				fileSuffix =  fileInfo.name.substring(fileInfo.name.lastIndexOf('.') + 1);
				switch(fileSuffix){
					case 'avi': case 'wmv': case 'mpeg': case 'mp4': case 'mov': case 'mkv': case 'flv': case 'f4v': case 'm4v': case 'rmvb': case 'rm': case '3gp': case 'dat': case 'ts': case 'mts':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/vid.svg';
						break;
					case 'bmp': case 'jpg': case 'png': case 'tiff': case 'gif': case 'pcx': case 'tga': case 'exif': case 'fpx': case 'svg': case 'psd': case 'cdr': case 'pcd': case 'dxf': case 'ufo': case 'eps': case 'ai': case 'raw': case 'wmf': case 'webp':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/img.svg';
						break;
					case 'mp3': case 'wma': case 'ape': case 'flac': case 'aac': case 'ac3': case 'mmf': case 'amr': case 'm4a': case 'm4r': case 'ogg': case 'wav': case 'mp2':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/aud.svg';
						break;
					case 'zip': case 'rar': case '7z': case 'tar': case 'gz':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/ar.svg';
						break;
					case 'exe': case 'dll': case 'com': case 'bat': case 'vbs': case 'sh':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/bin.svg';
						break;
					case 'txt': case 'html': case 'htm': case 'md':
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/txt.svg';
						break;
					default:
						fileIcon = 'static/h5ai/public/images/themes/h5ai-0.27/file.svg';
				}
				$("#list").append(
					'<li class="item file" data="' + prefix + encodeURI(fileInfo.name) + '">' +
						'<a>' +
							'<span class="icon square">' +
								'<img src="' + fileIcon + '" alt="file" />' +
							'</span>' +
							'<span class="icon landscape">' +
								'<img src="' + fileIcon + '" alt="file" />' +
							'</span>' +
							'<span class="name" title="' + decodeURI(prefix) + fileInfo.name +'">' +
								fileInfo.name +
							'</span>' +
							'<span class="time" style="display: none;" title="' + fileInfo.time +'">' +
								getTime(fileInfo.time) +
							'</span>' +
							'<span class="size" title="' + fileInfo.size +'">' +
								bytesToSize(fileInfo.size) +
							'</span>' +
						'</a>' +
					'</li>'
				);
			});
			if(result.stat != 111){
				$(".time").attr("style", "display: inline;");
				//$(".name").attr("style", "margin-right: 100px;")
			}
			if(result.fileCount === 0 && result.folderCount === 0){
				$("#list").html('<div id="view-hint" class="l10n-empty">Nothing\'s here, pal</div>');
			}
			$("#stats").html(result.fileCount + ' file(s), ' + result.folderCount + ' folder(s)');
			if(appConfig.SHOW_STATS){
				$("#stats").append('<br>listObjects takes ' + result.takes + ' ms, memory used ' + bytesToSize(result.memUsed));
			}
		},
		error: function(textStatus, errorThrown){
			$("#list").html('');
			alert('ERROR!\najax listObjects.action failed:\nGo FLUCKING to check for the console');
			console.error(XMLHttpRequest.status);
			console.error(XMLHttpRequest.readyState);
			console.error(textStatus);
		},
		complete: function(){
			//渲染顶部crumbbar和back按钮
			if(prefix !== ''){      //通过有prefix参数传入判断当前不为根文件夹
				var prefixSplited = decodeURI(prefix).split("/"); //分割文件夹路径字符串为数组，此处解码是为了防止由于js标准不同导致的对"/"的处理标准不同
				var nowFolderName = prefixSplited[prefixSplited.length - 2];
				var parentFolderName = prefixSplited[prefixSplited.length - 3] ? prefixSplited[prefixSplited.length - 3] : '/';    //上一层文件夹的名字，其中一个-1是数组下标，另一个是由于split(prefix)的结果最后一个元素总为空，再一个是当前文件夹名
				var parentFolder = "";
				$.each(prefixSplited, function(i){		//重新编码
					prefixSplited[i] = encodeURI(prefixSplited[i]);
				});
				$("#crumbbar").html(
					'<a href="#" class="crumb" data="">' +
						'<span class="name">' + appConfig.SITE_NAME + '</span>' +
						'<img class="hint" src="static/h5ai/public/images/themes/h5ai-0.27/folder-page.svg" alt=">">' +
					'</a>' +
					'<a href="#' + prefix + '" class="crumb" data="' + prefixSplited[0] + '/">' +       //手动定义crumbbar的第一层data
					//'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt=">">' +
					'<span class="name">' + decodeURI(prefixSplited[0]) + '</span>' +
					'</a>'
				);
				//合成上一层文件夹路径用作“返回上一层”按钮使用，我他妈就是不写函数
				if(prefixSplited.length == 2){      //进入一级子目录时，prefixSplited[0]为目录名，prefixSplited[1]为空
					parentFolder = '' ;
				}else if(prefixSplited[0] !== ''){      //存在多级子目录，别问我我也不知道怎么来的
					parentFolder = prefixSplited[0];
					$("#crumbbar").append(
						'<a href="#' + parentFolder + '/' + prefixSplited[1] + '/" class="crumb" data="' + parentFolder + '/' + prefixSplited[1] + '/">' +     //手动定义crumbbar的第一层data后添加每层数据，+1是因为要取得比父级目录多一层，并在结尾添加“/”
							'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt="/">' +
							'<span class="name">' + decodeURI(prefixSplited[1]) + '</span>' +
						'</a>'
						);
					for(i = 1; i < prefixSplited.length - 2; i++){      //-2是因为只要取到父级目录即可
						if(prefixSplited[i] !== ''){
							parentFolder = parentFolder + '/' + prefixSplited[i];
							$("#crumbbar").append(
							'<a href="#' + parentFolder + '/' + prefixSplited[i+1] + '" class="crumb" data="' + parentFolder + '/' + prefixSplited[i+1] + '/">' +     //手动定义crumbbar的第一层data后添加每层数据，+1是因为要取得比父级目录多一层，并在结尾添加“/”
								'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt="/">' +
								'<span class="name">' + decodeURI(prefixSplited[i+1]) + '</span>' +
							'</a>'
							);
						}
					}
					parentFolder = parentFolder + '/';      //在路径结尾添加“/”才能正确请求
				}
				$("#crumbbar a.crumb:last").attr("class", "crumb active");           //设置crumbbar的最后一层
				$(document).attr("title", decodeURI(nowFolderName) + " - " + appConfig.SITE_NAME);
				$("#back").html(
					'<li class="item folder folder-parent" data="' + parentFolder + '">' +
						'<a href="#' + parentFolder + '">' +
							'<span class="icon square">' +
							'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder-parent.svg" alt="folder">' +
							'</span>' +
							'<span class="icon landscape">' +
							'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder-parent.svg" alt="folder">' +
							'</span>' +
							'<span class="name">' +
							'<b>' + decodeURI(parentFolderName) + '</b>' +
							'</span>' +
							'<span class="size" data-bytes="null">' +
							'</span>' +
						'</a>' +
					'</li>'
				);
			}else{
				$("#crumbbar").html(
				'<a href="#" class="crumb site active">' +
					'<span class="name">' + appConfig.SITE_NAME + '</span>' +
					'<img class="hint" src="static/h5ai/public/images/themes/h5ai-0.27/folder-page.svg" alt="#">' +
				'</a>'
			);
				$("#back").html('');
				$(document).attr("title", appConfig.SITE_NAME);
			}
			$("#items").attr("style", "opacity: 1.0;-moz-opacit: 1.0;");
		}
	});
}
function downloadObject(target, who){
	console.log('getObject("' + decodeURI(target) + '"): ');
	$(who).attr('style', 'opacity: 0.5;-moz-opacit: 0.5;');
	$.ajax({
		type: 'POST',
		url: 'app/action/getSignedUrlForGettingObject.action.php',
		data: {
			target: decodeURI(target),
		},
		async: false,		//就相当于执行了两次~
		dataType: 'text',
		success: function(resultRow){
			console.log(resultRow);
			try{
				result = JSON.parse(resultRow);
			}catch(e){           //异常捕获： 捕获请求成功但无法解析JSON的异常，多为listObjects.action抛出的异常
				alert('ERROR!\najax getSignedUrlForGettingObject.action succeed but JSON.parse() failed:\nGo FLUCKING to check for the console');
				console.error('JSON.parse() error:\n' + e);
				return;
			}
			if(!(result.stat >= 100 && result.stat <= 199)){
				if(exceptionHandler(result.stat, result, target) === true){
					downloadObject(target, who);
				}
				return;
			}
			$(who).find("a").attr("href", result.url).attr("target", "_blank");
			//a.appendTo('body');
		},
		error: function(textStatus, errorThrown){
			alert('ERROR!\najax getSignedUrlForGettingObject.action failed:\nGo FLUCKING to check for the console');
			console.error(XMLHttpRequest.status);
			console.error(XMLHttpRequest.readyState);
			console.error(textStatus);
		},
		complete: function(){
			$(who).attr("style", "opacity: 1.0;-moz-opacit: 1.0;");
		}
	});
	$(who).attr("style", "opacity: 1.0;-moz-opacit: 1.0;");
	return;
}
function exceptionHandler(stat, data = ""){
	var retry = false;
	if(stat > 200 && stat < 300){
		retry = authHandler(stat, data, arguments[2]);
	}else{
		alert('Unexpected Error, \nGo FLUCKING to check for the console');
		retry = false;
	}
	return retry;
}
function authHandler(stat, data){
	var retry = false;
	var inputed = "";
	hfs4OSS_cookies.passwords = hfs4OSS_cookies.passwords ? hfs4OSS_cookies.passwords : {};
	switch(stat){
		case 201:
		case 202:
			inputed = prompt(data.msg);
			if(inputed !== null){
				hfs4OSS_cookies.passwords.index = inputed;
				$.cookie('hfs4OSS_cookies', JSON.stringify(hfs4OSS_cookies));
				retry = true;
			}else{
				retry = false;
			}
			break;
		case 211:
		case 212:
			inputed = prompt(data.msg);
			if(inputed !== null){
				hfs4OSS_cookies.passwords[arguments[2]] = inputed;
				$.cookie('hfs4OSS_cookies', JSON.stringify(hfs4OSS_cookies));
			retry = true;
			}else{
				retry = false;
			}
			break;
		case 221:
		case 222:
			inputed = prompt(data.msg);
			if(inputed !== null){
				hfs4OSS_cookies.passwords[arguments[2]] = inputed;
				$.cookie('hfs4OSS_cookies', JSON.stringify(hfs4OSS_cookies));
			retry = true;
			}else{
				retry = false;
			}
			break;
	}
	return retry;
}
function sortSwitch(who){
	if($(who).hasClass("ascending")){
		sortClear();
		listObjects(window.location.hash.substring(1), $(who).attr("class"), "true");
		$(who).addClass("descending");
	}else if($(who).hasClass("descending")){
		sortClear();
		listObjects(window.location.hash.substring(1), $(who).attr("class"), "false");
		$(who).addClass("ascending");
	}else{
		sortClear();
		listObjects(window.location.hash.substring(1), $(who).attr("class"), "true");		//默认新一次排序为倒序
		$(who).addClass("descending");
	}
	return;
}
function sortClear(){
	$(".header a").removeClass("ascending").removeClass("descending");
	return;
}
function getTime() {
	var ts = arguments[0] || 0;
	var t,y,m,d,h,i,s;
	t = ts ? new Date(ts*1000) : new Date();
	y = t.getFullYear();
	m = t.getMonth()+1;
	d = t.getDate();
	h = t.getHours();
	i = t.getMinutes();
	s = t.getSeconds();
	return y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d)+' '+(h<10?'0'+h:h)+':'+(i<10?'0'+i:i)+':'+(s<10?'0'+s:s);
}
function bytesToSize(bytes) {
	if (bytes === 0) return '0 B';
	var k = 1024;
	sizes = ['B','KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
	i = Math.floor(Math.log(bytes) / Math.log(k))
	return (bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i];
}