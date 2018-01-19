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
                    <div class="hotFocus">
                        <ul>
                            <li>
                                <a href="http://36kr.com/p/5114445.html"><img src="<?=img_url('u33ugxahv1vfjegl!heading.jpeg')?>"></a>
                                <a href="http://36kr.com/p/5114445.html"><span>区块链+SaaS系统服务餐饮企业，「亿点点」获A轮融资</span></a>
                            </li><li>
                                <a href="http://36kr.com/p/5114526.html"><img src="<?=img_url('rgpz4dt5crigsxwu!heading.jpeg')?>"></a>
                                <a href="http://36kr.com/p/5114526.html"><span>SpaceCycle完成B轮融资，阿里台湾创业者基金领投</span></a>
                            </li><li>
                                <a href="http://36kr.com/p/5112135.html"><img src="<?=img_url('n1cuq3gm3a0rgkqz!heading.jpeg')?>"></a>
                                <a href="http://36kr.com/p/5112135.html"><span>「分子未来」要做区块链领域的“天天基金网”+“网贷之家”</span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="list_con">
                        <div class="car_tab">
                            <ul>
                                <li class="active"><span><a href="">最新文章</a></span></li>
                                <li><span><a href="">早期项目</a></span></li>
                                <li><span><a href="">大公司</a></span></li>
                                <li><span><a href="">创投新闻</a></span></li>
                                <li><span><a href="">AI is</a></span></li>
                                <li><span><a href="">消费升级</a></span></li>
                                <li><span><a href="">深氪</a></span></li>
                                <li><span><a href="">技能Get</a></span></li>
                            </ul>
                        </div>
                        <div class="car_article_list">
                            <ul>
                                <li><div class="am-cf inner_li inner_li_abtest"><a data-stat-click="103.zhufeed.wenzhangbeijing.1" href="/p/5114673.html" target="_blank"><div class="img_box"><div data-stat-click="103.zhufeed.wenzhangtupian.1" href="/p/5114673.html" target="_blank"><img src="https://pic.36krcnd.com/201801/18090922/yh6d36x9nxl3ulxb!heading" alt="微信是怎么做到亿级用户的异常检测的？" class="load-img fade"></div></div><div class="intro"><h3 data-stat-click="103.zhufeed.wenzhangbiaoti.1">微信是怎么做到亿级用户的异常检测的？</h3><div class="abstract">本文将带你一窥究竟，微信是怎么做异常检测框架的？</div></div></a><div class="info"><div class="info-list info-list-abtest"><div class="user-info"><a href="/user/323686438" data-stat-click="zhufeed.wenzhangzuozhe.1.5114673" target="_blank" class="name">InfoQ技术媒体</a><span class="oblique_line">·</span></div><div class="time-div"><span class="time" title="2018-01-18 21:07">3小时前</span><span class="time h5_time">3小时前</span></div></div><div class="tags-list"><i class="icon-tag"></i><span><a href="/tag/%E5%A4%A7%E5%85%AC%E5%8F%B8" target="_blank">大公司</a><span>，</span></span><span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a></span></div><div class="comments-list"><span class="comments">10收藏</span></div></div></div></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- $main --><!-- ^right side --><div class="rightlib">
                    <div class="ads ">
                        <a href="#" style="background-image: url('<?=img_url('un68ifk27tary2fy.jpeg')?>')"></a>
                        <span class="mark">广告</span>
                    </div>

                    <div class="real_time">
                        <h3><span>7×24h 快讯</span></h3>
                        <ul>
                            <li class="real_time_wrapper">
                                
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- $right side -->
            </div>
        </div>
        <!-- $contenter -->
    </body>
</html>