<?php
$t1 = microtime(true);  
include_once 'get_files.php' ;
listObjects($ossClient,$_GET['path']) ;
$t2 = microtime(true);  
?>

<html class="hb-loaded" lang="en">

    <head>
        <style>
        .ll-nam {
            color:#2196f3
        }
        .ll-num {
            color:#ec407a
        }
        .ll-str {
            color:#43a047
        }
        .ll-rex {
            color:#ef6c00
        }
        .ll-pct {
            color:#666
        }
        .ll-key {
            color:#555;
            font-weight:bold
        }
        .ll-com {
            color:#aaa;
            font-style:italic
        }
        </style>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php 
        if(!$_GET['path'] == ""){
            echo substr($_GET['path'], 0, strlen($_GET['path'])-1)." - " ;
            }
        echo $site ;
        ?></title>
        <meta name="description" content="index - powered by h5ai v0.29.0 (https://larsjung.de/h5ai/)">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="h5ai/images/favicon/favicon-16-32.ico">
        <link rel="apple-touch-icon-precomposed" type="image/png" href="h5ai/images/favicon/favicon-152.png">
        <link rel="stylesheet" href="h5ai/css/styles.css">
        <script src="h5ai/js/scripts.js" data-module="index"></script>
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:300,400,700%7CUbuntu+Mono:400,700" class="x-head">
        <!--<style class="x-head">
        #root, input, select {
            font-family:"Ubuntu", "Roboto", "Helvetica", "Arial", "sans-serif"!important
        }
        pre, code {
            font-family:"Ubuntu Mono", "Monaco", "Lucida Sans Typewriter", "monospace"!important
        }
        </style>-->
        <!--<link type="text/css" rel="stylesheet" href="chrome-extension://ckphchjljlekndhjifdfpmmnlaijimcd/style.css">-->
        <style>
            #view.view-details.view-size-20 .item .label{line-height:34px !important;}#view.view-details.view-size-20 .item .date{line-height:34px !important;}#view.view-details.view-size-20 .item .size{line-height:34px !important;}#view.view-details.view-size-20 .square{width:20px !important;height:20px !important;}#view.view-details.view-size-20 .square img{width:20px !important;height:20px !important;}#view.view-details.view-size-20 .label{margin-left:52px !important;}#view.view-grid.view-size-20 .item .label{line-height:40px !important;}#view.view-grid.view-size-20 .square{width:40px !important;height:40px !important;}#view.view-grid.view-size-20 .square img{width:40px !important;height:40px !important;}#view.view-icons.view-size-20 .item{width:107px !important;}#view.view-icons.view-size-20 .landscape{width:107px !important;height:80px !important;}#view.view-icons.view-size-20 .landscape img{width:80px !important;height:80px !important;}#view.view-icons.view-size-20 .landscape .thumb{width:107px !important;}#view.view-details.view-size-40 .item .label{line-height:54px !important;}#view.view-details.view-size-40 .item .date{line-height:54px !important;}#view.view-details.view-size-40 .item .size{line-height:54px !important;}#view.view-details.view-size-40 .square{width:40px !important;height:40px !important;}#view.view-details.view-size-40 .square img{width:40px !important;height:40px !important;}#view.view-details.view-size-40 .label{margin-left:72px !important;}#view.view-grid.view-size-40 .item .label{line-height:40px !important;}#view.view-grid.view-size-40 .square{width:40px !important;height:40px !important;}#view.view-grid.view-size-40 .square img{width:40px !important;height:40px !important;}#view.view-icons.view-size-40 .item{width:107px !important;}#view.view-icons.view-size-40 .landscape{width:107px !important;height:80px !important;}#view.view-icons.view-size-40 .landscape img{width:80px !important;height:80px !important;}#view.view-icons.view-size-40 .landscape .thumb{width:107px !important;}#view.view-details.view-size-60 .item .label{line-height:74px !important;}#view.view-details.view-size-60 .item .date{line-height:74px !important;}#view.view-details.view-size-60 .item .size{line-height:74px !important;}#view.view-details.view-size-60 .square{width:60px !important;height:60px !important;}#view.view-details.view-size-60 .square img{width:60px !important;height:60px !important;}#view.view-details.view-size-60 .label{margin-left:92px !important;}#view.view-grid.view-size-60 .item .label{line-height:60px !important;}#view.view-grid.view-size-60 .square{width:60px !important;height:60px !important;}#view.view-grid.view-size-60 .square img{width:60px !important;height:60px !important;}#view.view-icons.view-size-60 .item{width:107px !important;}#view.view-icons.view-size-60 .landscape{width:107px !important;height:80px !important;}#view.view-icons.view-size-60 .landscape img{width:80px !important;height:80px !important;}#view.view-icons.view-size-60 .landscape .thumb{width:107px !important;}#view.view-details.view-size-80 .item .label{line-height:94px !important;}#view.view-details.view-size-80 .item .date{line-height:94px !important;}#view.view-details.view-size-80 .item .size{line-height:94px !important;}#view.view-details.view-size-80 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-80 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-80 .label{margin-left:112px !important;}#view.view-grid.view-size-80 .item .label{line-height:80px !important;}#view.view-grid.view-size-80 .square{width:80px !important;height:80px !important;}#view.view-grid.view-size-80 .square img{width:80px !important;height:80px !important;}#view.view-icons.view-size-80 .item{width:107px !important;}#view.view-icons.view-size-80 .landscape{width:107px !important;height:80px !important;}#view.view-icons.view-size-80 .landscape img{width:80px !important;height:80px !important;}#view.view-icons.view-size-80 .landscape .thumb{width:107px !important;}#view.view-details.view-size-100 .item .label{line-height:94px !important;}#view.view-details.view-size-100 .item .date{line-height:94px !important;}#view.view-details.view-size-100 .item .size{line-height:94px !important;}#view.view-details.view-size-100 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-100 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-100 .label{margin-left:112px !important;}#view.view-grid.view-size-100 .item .label{line-height:100px !important;}#view.view-grid.view-size-100 .square{width:100px !important;height:100px !important;}#view.view-grid.view-size-100 .square img{width:100px !important;height:100px !important;}#view.view-icons.view-size-100 .item{width:133px !important;}#view.view-icons.view-size-100 .landscape{width:133px !important;height:100px !important;}#view.view-icons.view-size-100 .landscape img{width:100px !important;height:100px !important;}#view.view-icons.view-size-100 .landscape .thumb{width:133px !important;}#view.view-details.view-size-140 .item .label{line-height:94px !important;}#view.view-details.view-size-140 .item .date{line-height:94px !important;}#view.view-details.view-size-140 .item .size{line-height:94px !important;}#view.view-details.view-size-140 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-140 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-140 .label{margin-left:112px !important;}#view.view-grid.view-size-140 .item .label{line-height:140px !important;}#view.view-grid.view-size-140 .square{width:140px !important;height:140px !important;}#view.view-grid.view-size-140 .square img{width:140px !important;height:140px !important;}#view.view-icons.view-size-140 .item{width:187px !important;}#view.view-icons.view-size-140 .landscape{width:187px !important;height:140px !important;}#view.view-icons.view-size-140 .landscape img{width:140px !important;height:140px !important;}#view.view-icons.view-size-140 .landscape .thumb{width:187px !important;}#view.view-details.view-size-180 .item .label{line-height:94px !important;}#view.view-details.view-size-180 .item .date{line-height:94px !important;}#view.view-details.view-size-180 .item .size{line-height:94px !important;}#view.view-details.view-size-180 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-180 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-180 .label{margin-left:112px !important;}#view.view-grid.view-size-180 .item .label{line-height:160px !important;}#view.view-grid.view-size-180 .square{width:160px !important;height:160px !important;}#view.view-grid.view-size-180 .square img{width:160px !important;height:160px !important;}#view.view-icons.view-size-180 .item{width:240px !important;}#view.view-icons.view-size-180 .landscape{width:240px !important;height:180px !important;}#view.view-icons.view-size-180 .landscape img{width:180px !important;height:180px !important;}#view.view-icons.view-size-180 .landscape .thumb{width:240px !important;}#view.view-details.view-size-220 .item .label{line-height:94px !important;}#view.view-details.view-size-220 .item .date{line-height:94px !important;}#view.view-details.view-size-220 .item .size{line-height:94px !important;}#view.view-details.view-size-220 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-220 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-220 .label{margin-left:112px !important;}#view.view-grid.view-size-220 .item .label{line-height:160px !important;}#view.view-grid.view-size-220 .square{width:160px !important;height:160px !important;}#view.view-grid.view-size-220 .square img{width:160px !important;height:160px !important;}#view.view-icons.view-size-220 .item{width:293px !important;}#view.view-icons.view-size-220 .landscape{width:293px !important;height:220px !important;}#view.view-icons.view-size-220 .landscape img{width:220px !important;height:220px !important;}#view.view-icons.view-size-220 .landscape .thumb{width:293px !important;}#view.view-details.view-size-260 .item .label{line-height:94px !important;}#view.view-details.view-size-260 .item .date{line-height:94px !important;}#view.view-details.view-size-260 .item .size{line-height:94px !important;}#view.view-details.view-size-260 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-260 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-260 .label{margin-left:112px !important;}#view.view-grid.view-size-260 .item .label{line-height:160px !important;}#view.view-grid.view-size-260 .square{width:160px !important;height:160px !important;}#view.view-grid.view-size-260 .square img{width:160px !important;height:160px !important;}#view.view-icons.view-size-260 .item{width:347px !important;}#view.view-icons.view-size-260 .landscape{width:347px !important;height:260px !important;}#view.view-icons.view-size-260 .landscape img{width:260px !important;height:260px !important;}#view.view-icons.view-size-260 .landscape .thumb{width:347px !important;}#view.view-details.view-size-300 .item .label{line-height:94px !important;}#view.view-details.view-size-300 .item .date{line-height:94px !important;}#view.view-details.view-size-300 .item .size{line-height:94px !important;}#view.view-details.view-size-300 .square{width:80px !important;height:80px !important;}#view.view-details.view-size-300 .square img{width:80px !important;height:80px !important;}#view.view-details.view-size-300 .label{margin-left:112px !important;}#view.view-grid.view-size-300 .item .label{line-height:160px !important;}#view.view-grid.view-size-300 .square{width:160px !important;height:160px !important;}#view.view-grid.view-size-300 .square img{width:160px !important;height:160px !important;}#view.view-icons.view-size-300 .item{width:400px !important;}#view.view-icons.view-size-300 .landscape{width:400px !important;height:300px !important;}#view.view-icons.view-size-300 .landscape img{width:300px !important;height:300px !important;}#view.view-icons.view-size-300 .landscape .thumb{width:400px !important;}#view .icon img{max-width:40px;max-height:40px;}
        </style>
    </head>
    <body class="index" id="root" screen_capture_injected="true" huaban_collector_injected="true">
        <div id="topbar">
            <div id="flowbar">
                <div id="crumbbar">
                    <a class="crumb" href="?path=">
                        <img class="sep" src="h5ai/images/ui/crumb.svg">
                        <span class="label"><b><?php echo $site ; ?></b></span>
                    </a>
                    <a class="crumb active">
                        <img class="sep" src="h5ai/images/ui/crumb.svg">
                        <span class="label"><?php echo $_GET['path'] ;?></span>
                    </a>
                </div>
            </div>
            <a id="backlink" href="https://github.com/YuXuan220/" target="_blank" title="Powered by YuXuan_0012 - https://github.com/YuXuan220/">
                <div>Powered by</div>
                <div>YuXuan_0012</div>
            </a>
            <a id="backlink" href="https://larsjung.de/h5ai/" target="_blank" title="Themed by h5ai - https://larsjung.de/h5ai/">
                <div>Themed</div>
                <div>by h5ai</div>
            </a>
        </div>
        <div id="mainrow">
            <div id="content">
                <div id="view" class="view-details view-size-20">
                    <ul id="items" class="clearfix">
                        <li class="header">
                            <a class="label ascending" href="#"><span class="l10n-name">Name</span></a>
                            <a class="size">
                                <img src="h5ai/images/ui/sort.svg" class="sort" alt="sort order"><span class="l10n-size">Size</span>
                            </a>
                        </li><?php 
                        $last_floder_path = substr(substr($_GET['path'],0,strlen($_GET['path'])-1), 0, strripos(substr($_GET['path'],0,strlen($_GET['path'])-1), "/")) ;
                        $last_floder_name = substr(substr(substr("/".$_GET['path'],0,strlen("/".$_GET['path'])-1), 0, strripos(substr("/".$_GET['path'],0,strlen("/".$_GET['path'])-1), "/")), strripos(substr(substr($_GET['path'],0,strlen($_GET['path'])-1), 0, strripos(substr($_GET['path'],0,strlen($_GET['path'])-1), "/")), "/")+1);
                        if(isset($_GET['path']) && $_GET['path'] !== ""){
                            unset($object[0]) ;    //如当前目录不为“/”，则删除$object[]的第一个元素
                            echo '<li class="item folder folder-parent"><a href="?path=' ;
                            if($last_floder_name == ""){$last_floder_name = "/" ;}
                            if($last_floder_path == ""){echo $last_floder_path ;}else{echo $last_floder_path."/" ;}
                            echo '"> <span class="icon square"><img src="h5ai/images/themes/default/folder-parent.svg" alt="folder"></span>
 <span class="icon landscape"><img src="h5ai/images/themes/default/folder-parent.svg" alt="folder"></span>
 <span class="label"><b>'.$last_floder_name.'</b></span><span class="size" data-bytes="null"></span></a></li>' ;
                            }
                        foreach ($prefix as $floders){
                            echo '<li class="item folder"><a href="?path='.$floders.'"><span class="icon square"><img src="h5ai/images/themes/default/folder.svg" alt="folder"></span><span class="icon landscape"><img src="h5ai/images/themes/default/folder.svg" alt="folder"></span><span class="label">'.substr(substr($floders, 0, strlen($floders)-1), strlen($_GET['path'])).'</span><span class="size" data-bytes="null"></span></a></li>' ;
                        }
                        foreach ($object as $files){
                            echo '<li class="item file"><a href="http://file.oovoo.site/'.$files.'" target="_blank"><span class="icon square"><img src="h5ai/images/themes/default/file.svg" alt="file"></span><span class="icon landscape"><img src="h5ai/images/themes/default/file.svg" alt="file"></span><span class="label">'.substr($files, strlen($_GET['path'])).'<span class="size" data-bytes="0">0 B</span></a></li>';
                        }
                        ?>
                    </ul>
                    <div style="text-align:center; margin-top:25px">
                        <span style="color:#8E8E8E"><?php
                        echo count($prefix).' floders, ' ;
                        echo count($object).' items<br />' ;
                        echo date('Y-m-d H:s:i') ;
                        if($stats == true){echo '<br />listObjects() takes '.floor(($t2-$t1)*1000).'ms';}
                        echo '</span><br />' ;
                        echo $footer ;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="notification" class="hidden">loading...</div>
        <div id="pv-overlay" class="hidden">
            <div id="pv-container" style="width: 955px; height: 875px; left: 20px; top: 20px;"></div>
            <div id="pv-spinner" class="hidden" style="left: 497.5px; top: 481.5px;">
                <img class="back">
                <img class="spinner" src="h5ai/images/ui/spinner.svg">
            </div>
            <div id="pv-prev-area" class="hof">
                <img src="h5ai/images/ui/preview-prev.svg">
            </div>
            <div id="pv-next-area" class="hof">
                <img src="h5ai/images/ui/preview-next.svg">
            </div>
            <div id="pv-bottombar" class="clearfix hof">
                <ul id="pv-buttons">
                    <li id="pv-bar-close" class="bar-right bar-button">
                        <img src="h5ai/images/ui/preview-close.svg">
                    </li>
                    <li id="pv-bar-raw" class="bar-right">
                        <a class="bar-button" target="_blank">
                            <img src="h5ai/images/ui/preview-raw.svg">
                        </a>
                    </li>
                    <li id="pv-bar-fullscreen" class="bar-right bar-button">
                        <img src="h5ai/images/ui/preview-fullscreen.svg">
                    </li>
                    <li id="pv-bar-next" class="bar-right bar-button">
                        <img src="h5ai/images/ui/preview-next.svg">
                    </li>
                    <li id="pv-bar-idx" class="bar-right bar-label"></li>
                    <li id="pv-bar-prev" class="bar-right bar-button">
                        <img src="h5ai/images/ui/preview-prev.svg">
                    </li>
                </ul>
            </div>
        </div>
    </body>

</html>