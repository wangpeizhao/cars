<html lang="zh-CN" class="" style="font-size: 20px;">
    <head>
        <title>36氪_让一部分人先看到未来</title>
        <meta name="keywords" content="创业,互联网创业,互联网创业项目">
        <meta name="description" content="36氪为您提供创业资讯、科技新闻、投融资对接、股权投资、极速融资等创业服务，致力成为创业者可以依赖的创业服务平台，为创业者提供最好的产品和服务。">
        <meta property="og:url" content="http://36kr.com/">
        <meta property="og:type" content="article">
        <meta property="og:title" content="36氪 | 让创业更简单">
        <meta property="og:description" content="">
        <meta property="og:image" content="https://krplus-pic.b0.upaiyun.com/201602/24094427/3butngz6peklnpft.jpg"> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="renderer" content="webkit"> 
        <link href="//36kr.com/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"> 
        <meta name="viewport" content="user-scalable=no,width=device-width,initial-scale=1"> 
        <meta name="apple-mobile-web-app-title" content="Title"> 
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
        <meta name="HandheldFriendly" content="True"> 
        <meta name="MobileOptimized" content="320"> 
        <meta name="applicable-device" content="pc,mobile"> 
        <meta name="format-detection" content="telephone=no"> 
        <meta http-equiv="Cache-Control" content="no-transform"> 
        <meta http-equiv="Cache-Control" content="no-siteapp"> 
        <link rel="apple-touch-icon" href="/apple-touch-icon-iphone.png"> 
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-ipad.png"> 
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-iphone4.png">
        <link rel="stylesheet" type="text/css" media="screen and (max-device-width: 400px)" href="<?=_css_url('tinyScreen.css')?>" />
        <link rel="stylesheet" type="text/css" media="screen and (min-width: 400px) and (max-device-width: 600px)" href="<?=_css_url('smallScreen.css')?>" />
        <?=css_url('base.css,common.css')?>
        <?=js_url('jquery-3.2.1.min.js,unslider/unslider.min.js')?>
        <!--[if lt IE 9]><?=js_url('css3-mediaqueries-js/css3-mediaqueries.js')?><![endif]-->
    </head>
    <body>
        <!-- ^header -->
        <header class="common-header">
            <div class="container">
                <div class="pc-nav">
                    <a class="logo" href="<?=_URL_?>"><img src="<?=img_url('logo.png')?>"></a>
                    <nav>
                        <ul class="navList">
                            <li class="active"><a href="#">首页</a></li>
                            <li><a href="">开氪</a></li>
                            <li><a href="">7×24h 快讯</a></li>
                            <li><a href="">近期活动</a></li>
                            <li><a href="">鲸准</a></li>
                            <li><a href="">氪空间</a></li>
                            <li><a href="">找人服务</a></li>
                            <li><a href="">联系我们</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="right-col">
                    <ul class="sub-nav">
                        <li class="search"><a href="javascript:;">搜索</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- $header -->

        <!-- ^contenter -->
        <div id="app">
            <div class="pagewrap">
                <!-- ^main -->
                <div class="mainlib">
                    <div class="carousels">
                        <ul>
                            <?php if(!empty($carousels)){
                                foreach($carousels as $k=>$item){?>
                                <li class="<?=$k==0?'slick-active':''?>"><a href="<?=$item['link_url']?>" title="<?=$item['link_name']?>" style="background-image: url('<?=site_url($item['link_image'])?>')"></a></li>
                            <?php }
                            }?>
                        </ul>
                    </div>
                </div><!-- $main --><!-- ^right side --><div class="rightlib">
                    <div class="ads ">
                        <a href="#" style="background-image: url('<?=img_url('un68ifk27tary2fy.jpeg')?>')"></a>
                    </div>
                </div>
                <!-- $right side -->
            </div>
        </div>
        <!-- $contenter -->
    </body>
</html>