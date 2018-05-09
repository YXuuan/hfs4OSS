var appConfig;		//全局变量
$.ajax({
	type: 'GET',
	url: 'config/static.config.json?t=' + new Date().getTime(),
	dataType: 'text',
	success: function(data){
		console.log('ajaxget app.config.json succeed:\n' + data);
		try{
			appConfig = JSON.parse(data);
		}catch(e){       //异常捕获： 捕获请求成功但无法解析JSON的异常，意思就是有个傻逼把app.config.json改死了
			alert('ERROR!\najaxget app.config succeed but JSON.parse() failed\nGo FLUCKING to check for console.log');
			console.error('JSON.parse() error:\n' + e);
			return;
		}
		console.log('JSON.parse() succeed\n');
		$(document).attr("title", appConfig.SITE_NAME);
		if(appConfig.FOOTER !== ""){
			$("#stats").after(
				'<div style="margin-top:15px">' +
					'<span id="footer">' + appConfig.FOOTER + '</span>' +
				'</div>');
		}
		$("#crumbbar").html(        //我也不知道为什么非要在这里加一个才能在会话创建后就显示出站点名字
			'<a href="#" class="crumb">' +
				'<span class="label">' + appConfig.SITE_NAME + '</span>' +
				'<img class="hint" src="static/h5ai/public/images/themes/h5ai-0.27/folder-page.svg" alt="#">' +
			'</a>'
		);
	},
	error: function(textStatus, errorThrown){
		alert('ERROR!\najaxget app.config failed:\nGo FLUCKING to check for console.log');
		console.log(XMLHttpRequest.status);
		console.log(XMLHttpRequest.readyState);
		console.log(textStatus);
	}
});
if(window.location.hash){
	listObjects(window.location.hash.substring(1));		//截掉“#”
}else{
	listObjects();
}
$(document).ready(function(){
	//Event Loop
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

function listObjects(path = ''){
	$("#items").attr("style", "opacity: 0.5;-moz-opacit: 0.5;");
	console.log('-----listObjects("' + decodeURI(path) + path + ' = ' + '")-----');
	$.ajax({
		type: 'POST',
		url: 'app/action/listObjects.action.php',
		data: {
				prefix: decodeURI(path),
		},
		dataType: 'text',
		success: function(data){
			console.log('ajaxpost listObjects() succeed:' + decodeURI(data));
			//密码相关处理
			try{
				result_listObjects = JSON.parse(data);
			}catch(e){           //异常捕获： 捕获请求成功但无法解析JSON的异常，多为listObjects.action抛出的异常
				$("#list").html('');
				if(exceptionHandler(data) === false){
					alert('ERROR!\najaxpostgetJSON() succeed but JSON.parse() failed:\nGo FLUCKING to check the console.log');
					console.error('JSON.parse() error:\n' + e);
				}
				return;
			}
			console.log('JSON.parse() succeed');
			//列表动作
			$("#list").html('');        //清空原有内容
			$.each(result_listObjects.folderList, function(i, folderInfo){
				$("#list").append(
					'<li class="item folder" data="' + path + encodeURI(folderInfo) + '">' +
						'<a href="#' + path + encodeURI(folderInfo) + '">' +
							'<span class="icon square">' +
								'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder.svg" alt="folder" />' +
							'</span>' +
							'<span class="icon landscape">' +
								'<img src="static/h5ai/public/images/themes/h5ai-0.27/folder.svg" alt="folder" />' +
							'</span>' +
							'<span class="label" title="'+ decodeURI(path) + folderInfo.replace("/", "") + '">' +
								folderInfo.replace("/", "") +
							'</span>' +
							'<span class="date" title="-">-</span>' +
							'<span class="size" title="-">-</span>' +
						'</a>' +
					'</li>'
				);
			});
			var fileSuffix, fileIcon;
			$.each(result_listObjects.fileList, function(i, fileInfo){
				fileSuffix =  fileInfo[0].substring(fileInfo[0].lastIndexOf('.') + 1);
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
					'<li class="item file" data="' + path + encodeURI(fileInfo[0]) + '">' +
						'<a>' +
							'<span class="icon square">' +
								'<img src="' + fileIcon + '" alt="file" />' +
							'</span>' +
							'<span class="icon landscape">' +
								'<img src="' + fileIcon + '" alt="file" />' +
							'</span>' +
							'<span class="label" title="' + decodeURI(path) + fileInfo[0] +'">' +
								fileInfo[0] +
							'</span>' +
							'<span class="date" title="' + fileInfo[1] +'">' +
								fileInfo[1] +
							'</span>' +
							'<span class="size" title="' + fileInfo[2] +'">' +
								fileInfo[2] +
							'</span>' +
						'</a>' +
					'</li>'
				);
			});
			if(result_listObjects.fileCount === 0 && result_listObjects.folderCount === 0){
				$("#list").html('<div id="view-hint" class="l10n-empty">Nothing\'s here, pal</div>');
			}
			$("#stats").html(result_listObjects.fileCount + ' file(s), ' + result_listObjects.folderCount + ' folder(s)');
			if(appConfig.SHOW_STATS){
				$("#stats").append('<br />listObjects takes ' + result_listObjects.takes + ', Memory used ' + result_listObjects.memUsed);
			}
		},
		error: function(textStatus, errorThrown){
			$("#list").html('');
			alert('ERROR!\najaxpost listObjects.action failed:\nGo FLUCKING to check for console');
			console.log(XMLHttpRequest.status);
			console.log(XMLHttpRequest.readyState);
			console.log(textStatus);
		},
		complete: function(){
			//渲染顶部crumbbar和back按钮
			if(path !== ''){      //通过有path参数传入判断当前不为根文件夹
				var pathSplited = decodeURI(path).split("/"); //分割文件夹路径字符串为数组，此处解码是为了防止由于js标准不同导致的对"/"的处理标准不同
				var nowFolderName = pathSplited[pathSplited.length - 2];
				var parentFolderName = pathSplited[pathSplited.length - 3] ? pathSplited[pathSplited.length - 3] : '/';    //上一层文件夹的名字，其中一个-1是数组下标，另一个是由于split(path)的结果最后一个元素总为空，再一个是当前文件夹名
				var parentFolder;
				$.each(pathSplited, function(i){		//重新编码
					pathSplited[i] = encodeURI(pathSplited[i]);
				});
				$("#crumbbar").html(
					'<a href="#" class="crumb">' +
						'<span class="label">' + appConfig.SITE_NAME + '</span>' +
						'<img class="hint" src="static/h5ai/public/images/themes/h5ai-0.27/folder-page.svg" alt="#">' +
					'</a>' +
					'<a href="#' + path + '" class="crumb" data="' + pathSplited[0] + '/">' +       //手动定义crumbbar的第一层data
					//'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt=">">' +
					'<span class="label">' + decodeURI(pathSplited[0]) + '</span>' +
					'</a>'
				);
				//合成上一层文件夹路径用作“返回上一层”按钮使用，我他妈就是不写函数
				if(pathSplited.length == 2){      //进入一级子目录时，pathSplited[0]为目录名，pathSplited[1]为空
					parentFolder = '' ;
				}else if(pathSplited[0] !== ''){      //存在多级子目录，别问我我也不知道怎么来的
					parentFolder = pathSplited[0];
					$("#crumbbar").append(
						'<a href="#' + path + '" class="crumb" data="' + parentFolder + '/' + pathSplited[1] + '/">' +     //手动定义crumbbar的第一层data后添加每层数据，+1是因为要取得比父级目录多一层，并在结尾添加“/”
							'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt=">">' +
							'<span class="label">' + decodeURI(pathSplited[1]) + '</span>' +
						'</a>'
						);
					for(i = 1; i < pathSplited.length - 2; i++){      //-2是因为只要取到父级目录即可
						if(pathSplited[i] !== ''){
							parentFolder = parentFolder + '/' + pathSplited[i];
							$("#crumbbar").append(
							'<a href="#' + path + '" class="crumb" data="' + parentFolder + '/' + pathSplited[i+1] + '/">' +     //手动定义crumbbar的第一层data后添加每层数据，+1是因为要取得比父级目录多一层，并在结尾添加“/”
								'<img class="sep" src="static/h5ai/public/images/ui/crumb.svg" alt=">">' +
								'<span class="label">' + decodeURI(pathSplited[i+1]) + '</span>' +
							'</a>'
							);
						}
					}
					parentFolder = parentFolder + '/';      //在路径结尾添加“/”才能正确请求
				}
				console.log('parentFolder: ' + decodeURI(parentFolder));
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
							'<span class="label">' +
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
					'<span class="label">' + appConfig.SITE_NAME + '</span>' +
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
	console.log('-----getObject("' + decodeURI(target) + '")-----');
	$(who).attr('style', 'opacity: 0.5;-moz-opacit: 0.5;');
	$.ajax({
		type: 'POST',
		url: 'app/action/getSignedUrlForGettingObject.action.php',
		data: {
			target: decodeURI(target),
		},
		async: false,
		dataType: 'text',
		success: function(data){
			try{
				result_getSignedUrlForGettingObject = JSON.parse(data);
			}catch(e){
				if(exceptionHandler(data) === false){
					alert('ERROR!\najaxget app.config succeed but JSON.parse() failed\nGo FLUCKING to check for console.log');
					console.error('JSON.parse() error:\n' + e);
				}
				return;
			}
			console.log('JSON.parse() succeed\n');
			console.log('ajaxpost getSignedUrlForGettingObject.action.php:\n' + decodeURI(result_getSignedUrlForGettingObject));
			$(who).find("a").attr("href", result_getSignedUrlForGettingObject).attr("target", "_blank");
			//a.appendTo('body');
		},
		error: function(textStatus, errorThrown){
			alert('ERROR!\najaxget app.config failed:\nGo FLUCKING to check for console.log');
			console.log(XMLHttpRequest.status);
			console.log(XMLHttpRequest.readyState);
			console.log(textStatus);
		}
	});
	$(who).attr("style", "opacity: 1.0;-moz-opacit: 1.0;");
}
function exceptionHandler(msg){
	var handlerFlag = null;
	switch(msg){
		case 'Exception201':
			var inputedPassword = prompt(appConfig.INDEX_PASSWORD_MESSAGE + "\nPassword needed: ");
			if(inputedPassword !== null){
				$.cookie('hfs4OSS_indexPassword', inputedPassword);
				listObjects();
			}
			handlerFlag = true;
			break;
		case 'Exception202':
			var inputedPassword = prompt("Invalid password, try again: ");
			if(inputedPassword !== null){
				$.cookie('hfs4OSS_indexPassword', inputedPassword)
				listObjects();
			}
			handlerFlag = true;
			break;
	}
	return handlerFlag;
}