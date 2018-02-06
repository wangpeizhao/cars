<?php include('header.php');?>
<?=css_url('home.css')?>
<?=js_url('unslider/unslider.min.js')?>
<!-- ^contenter -->
<style type="text/css">
    #app .carousels{
        height: 351px!important;
    }
    #app .carousels ul li{
        height: 351px!important;
    }
    .focus-banner{
        height: 351px!important;
    }
    .focus-banner .banner-left{
        width: 819px;
        height: 100%;
    }
    .focus-banner .banner-left li{
        overflow: hidden;
    }
    .focus-banner .banner-right{
        width: 300px;
        height: 351px;
        margin-left: 1px;
    }
    .focus-banner .banner-right img{
        width: 300px;
        min-height: 175px;
    }
    .focus-banner .banner-right li{
        margin-bottom: 1px;
        overflow: hidden;
        width: 300px;
        height: 175px;
    }
    .focus-banner .banner-right li:last-child{
        margin-bottom: 0px;
    }
</style>
<div id="app">
    <div class="focus-banner">
        <div class="banner-left iblock">
            <div class="carousels">
                <ul>
                    <?php if(!empty($carousels)){
                        foreach($carousels as $k=>$item){?>
                        <li><a href="<?=$item['link_url']?>" title="<?=$item['link_name']?>" style="background-image: url('<?=site_url($item['link_image'])?>')"></a></li>
                    <?php }
                    }?>
                </ul>
                <script type="text/javascript">
                    $(function(){
                        $('.carousels').unslider({
                            // animation: 'fade',
                            speed: 500,               //  滚动速度
                            delay: 3000,              //  动画延迟
                            complete: function() {},  //  动画完成的回调函数
                            keys: true,               //  启动键盘导航
                            dots: true,               //  显示点导航
                            fluid: false,              //  支持响应式设计
                            autoplay: true,   //自动滚动
                            infinite: true,   //无限循环
                            arrows: false,    //next|prve 箭头，默认：true
                        });
                    });
                </script>
            </div>
        </div><div class="banner-right iblock">
            <ul>
                <?php if(!empty($rands)){
                    foreach($rands as $item){?>
                <li>
                    <a href="<?=$item['link_url']?>">
                        <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                    </a>
                    <a href="<?=$item['link_url']?>" title="<?=$item['link_name']?>">
                        <span><?=$item['link_name']?></span>
                    </a>
                </li>
                <?php }
                }?>
            </ul>
        </div>
    </div>
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">
            <div class="list_con" style="margin-top:0;">
                <div class="car_tab" id="subNavShow">
                    <ul>
                        <li class="active"><span><a href="">最新文章</a></span></li>
                        <li><span><a href="">早期项目</a></span></li>
                        <li><span><a href="">大公司</a></span></li>
                        <li><span><a href="">创投新闻</a></span></li>
                        <li><span><a href="">AI is</a></span></li>
                        <li><span><a href="">消费升级</a></span></li>
                        <li><span><a href="">深氪</a></span></li>
                        <li><span><a href="">技能Get</a></span></li>
                        <li><span><a href="">新能源</a></span></li>
                    </ul>
                </div>
                <div class="car_tab" id="subNavHide">
                    <ul>
                        <li class="active"><span><a href="">最新文章</a></span></li>
                        <li><span><a href="">早期项目</a></span></li>
                        <li><span><a href="">大公司</a></span></li>
                        <li><span><a href="">创投新闻</a></span></li>
                        <li><span><a href="">AI is</a></span></li>
                        <li><span><a href="">消费升级</a></span></li>
                        <li><span><a href="">深氪</a></span></li>
                        <li><span><a href="">技能Get</a></span></li>
                        <li><span><a href="">新能源</a></span></li>
                    </ul>
                </div>
                <script type="text/javascript">
                    $(function(){
                        var top = $('#subNavShow').offset().top;
                        window.onscroll = function(){
                            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                            if(scrollTop>=top+39){
                                $('#subNavHide').slideDown();
                            }else{
                                $('#subNavHide').hide();
                            }
                        }
                    });
                </script>
                
                <div class="car_article_list">
                    <ul>
                        <?php if(!empty($news)){?>
                            <?php foreach($news as $item){?>
                                <li>
                                    <div class="am-cf inner_li inner_li_abtest">
                                        <a href="/p/<?=$item['id']?>.html" target="_blank">
                                            <div class="img_box">
                                                <div target="_blank">
                                                    <img src="<?=WEB_DOMAIN.'/'.$item['thumb']?>" alt="<?=$item['title']?>" class="load-img fade">
                                                </div>
                                            </div>
                                            <div class="intro">
                                                <h3><?=$item['title']?></h3>
                                                <div class="abstract"><?=$item['summary']?></div>
                                            </div>
                                        </a>
                                        <div class="info">
                                            <div class="info-list info-list-abtest">
                                                <div class="user-info">
                                                    <a href="javascript:;" target="_blank" class="name"><?=$item['author']?></a>
                                                    <span class="oblique_line">·</span>
                                                </div>
                                                <div class="time-div">
                                                    <span class="time" title="<?=$item['create_time']?>"><?=$item['timeLine']?></span>
                                                </div>
                                            </div>
                                            <div class="tags-list">
                                                <!-- <i class="icon-tag"></i> -->
                                                <?php if($item['tags']){?>
                                                    <?php foreach($item['tags'] as $k=>$item){?>
                                                        <span><a href="/tag/<?=$k?>.html" target="_blank"><?=$item?></a></span><span class="separate">，</span>
                                                    <?php }?>
                                                <?php }?>
                                            </div>
                                            <div class="comments-list"><span class="comments">10收藏</span></div>
                                        </div>
                                    </div>
                                </li>
                            <?php }?>
                        <?php }?>

                        <?php for($i=0;$i<3;$i++){?>
                        <li>
                            <div class="am-cf inner_li inner_li_abtest">
                                <a data-stat-click="103.zhufeed.wenzhangbeijing.1" href="/p/5114673.html" target="_blank">
                                    <div class="img_box">
                                        <div data-stat-click="103.zhufeed.wenzhangtupian.1" href="/p/5114673.html" target="_blank">
                                            <img src="https://pic.36krcnd.com/201801/18090922/yh6d36x9nxl3ulxb!heading" alt="微信是怎么做到亿级用户的异常检测的？" class="load-img fade">
                                        </div>
                                    </div>
                                    <div class="intro">
                                        <h3 data-stat-click="103.zhufeed.wenzhangbiaoti.1">微信是怎么做到亿级用户的异常检测的？</h3>
                                        <div class="abstract">本文将带你一窥究竟，微信是怎么做异常检测框架的？</div>
                                    </div>
                                </a>
                                <div class="info">
                                    <div class="info-list info-list-abtest">
                                        <div class="user-info">
                                            <a href="/user/323686438" data-stat-click="zhufeed.wenzhangzuozhe.1.5114673" target="_blank" class="name">InfoQ技术媒体</a>
                                            <span class="oblique_line">·</span>
                                        </div>
                                        <div class="time-div">
                                            <span class="time" title="2018-01-18 21:07">3小时前</span>
                                            <span class="time h5_time">3小时前</span>
                                        </div>
                                    </div>
                                    <div class="tags-list">
                                        <i class="icon-tag"></i>
                                        <span><a href="/tag/%E5%A4%A7%E5%85%AC%E5%8F%B8" target="_blank">大公司</a><span>，
                                        </span></span><span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a></span>
                                    </div>
                                    <div class="comments-list"><span class="comments">10收藏</span></div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="am-cf inner_li inner_li_abtest">
                                <a data-stat-click="103.zhufeed.wenzhangbeijing.1" href="/p/5114673.html" target="_blank">
                                    <div class="img_box">
                                        <div data-stat-click="103.zhufeed.wenzhangtupian.1" href="/p/5114673.html" target="_blank">
                                            <img src="https://pic.36krcnd.com/201801/19103046/4frfoke8by1d0dh4!heading" alt="微信是怎么做到亿级用户的异常检测的？" class="load-img fade">
                                        </div>
                                    </div>
                                    <div class="intro">
                                        <h3 data-stat-click="103.zhufeed.wenzhangbiaoti.1">「易企秀」获6400万元B+轮融资，H5第一梯队已稳定</h3>
                                        <div class="abstract">「易企秀」获6400万元B+轮融资，H5第一梯队已稳定</div>
                                    </div>
                                </a>
                                <div class="info">
                                    <div class="info-list info-list-abtest">
                                        <div class="user-info">
                                            <a href="/user/323686438" data-stat-click="zhufeed.wenzhangzuozhe.1.5114673" target="_blank" class="name">InfoQ技术媒体</a>
                                            <span class="oblique_line">·</span>
                                        </div>
                                        <div class="time-div">
                                            <span class="time" title="2018-01-18 21:07">3小时前</span>
                                            <span class="time h5_time">3小时前</span>
                                        </div>
                                    </div>
                                    <div class="tags-list">
                                        <i class="icon-tag"></i>
                                        <span><a href="/tag/%E5%A4%A7%E5%85%AC%E5%8F%B8" target="_blank">大公司</a><span>，
                                        </span></span><span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a></span>
                                    </div>
                                    <div class="comments-list"><span class="comments">10收藏</span></div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="am-cf inner_li inner_li_abtest">
                                <a data-stat-click="103.zhufeed.wenzhangbeijing.1" href="/p/5114673.html" target="_blank">
                                    <div class="img_box">
                                        <div data-stat-click="103.zhufeed.wenzhangtupian.1" href="/p/5114673.html" target="_blank">
                                            <img src="https://pic.36krcnd.com/201801/18110620/4ovp0gvgy8f16f0o!heading" alt="微信是怎么做到亿级用户的异常检测的？" class="load-img fade">
                                        </div>
                                    </div>
                                    <div class="intro">
                                        <h3 data-stat-click="103.zhufeed.wenzhangbiaoti.1">瞄准全国晋商，「山西老乡严选」用小程序电商销售情怀和特产</h3>
                                        <div class="abstract">瞄准全国晋商，「山西老乡严选」用小程序电商销售情怀和特产</div>
                                    </div>
                                </a>
                                <div class="info">
                                    <div class="info-list info-list-abtest">
                                        <div class="user-info">
                                            <a href="/user/323686438" data-stat-click="zhufeed.wenzhangzuozhe.1.5114673" target="_blank" class="name">InfoQ技术媒体</a>
                                            <span class="oblique_line">·</span>
                                        </div>
                                        <div class="time-div">
                                            <span class="time" title="2018-01-18 21:07">3小时前</span>
                                            <span class="time h5_time">3小时前</span>
                                        </div>
                                    </div>
                                    <div class="tags-list">
                                        <i class="icon-tag"></i>
                                        <span><a href="/tag/%E5%A4%A7%E5%85%AC%E5%8F%B8" target="_blank">大公司</a><span>，
                                        </span></span><span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a></span>
                                    </div>
                                    <div class="comments-list"><span class="comments">10收藏</span></div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="am-cf inner_li info-flow-monographic-wrapper">
                                <div class="mark">专题</div>
                                <div class="info-flow-monographic-inner">
                                    <a href="/topics/1696?version=new" target="_blank">
                                        <div class="img-pad" style="background-image: url('https://pic.36krcnd.com/201801/18035501/iqzl0lqi8scmzigx!heading');"></div>
                                        <div class="background-cover"></div>
                                        <div class="info">
                                            <div class="title">飞机上终于可以玩手机了，空中Wi-Fi市场潜力究竟有多大？</div>
                                            <div class="desc">1月15日，中国民用航空局网站发布了《机上便携式电子设备(PED)使用评估指南》，开放机上便携式电子设备的使用禁令，随后东航、海南航空等纷纷响应，其中东航能够提供空中WiFi服务的空中互联机队规模已达到74架，未来“空中Wi-Fi”这块蛋糕会有多大？将催生多少价值的新市场？</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="am-cf inner_li inner_li_abtest">
                                <a data-stat-click="103.zhufeed.wenzhangbeijing.1" href="/p/5114673.html" target="_blank">
                                    <div class="img_box">
                                        <div data-stat-click="103.zhufeed.wenzhangtupian.1" href="/p/5114673.html" target="_blank">
                                            <img src="https://pic.36krcnd.com/201801/15104447/clza2vb6hsa2755v!heading" alt="微信是怎么做到亿级用户的异常检测的？" class="load-img fade">
                                        </div>
                                    </div>
                                    <div class="intro">
                                        <h3 data-stat-click="103.zhufeed.wenzhangbiaoti.1">用供应链服务置换餐厅消费额度，「黄牛派」想同时连接供货商、餐厅和消费者</h3>
                                        <div class="abstract">用供应链服务置换餐厅消费额度，「黄牛派」想同时连接供货商、餐厅和消费者</div>
                                    </div>
                                </a>
                                <div class="info">
                                    <div class="info-list info-list-abtest">
                                        <div class="user-info">
                                            <a href="/user/323686438" data-stat-click="zhufeed.wenzhangzuozhe.1.5114673" target="_blank" class="name">InfoQ技术媒体</a>
                                            <span class="oblique_line">·</span>
                                        </div>
                                        <div class="time-div">
                                            <span class="time" title="2018-01-18 21:07">3小时前</span>
                                            <span class="time h5_time">3小时前</span>
                                        </div>
                                    </div>
                                    <div class="tags-list">
                                        <i class="icon-tag"></i>
                                        <span><a href="/tag/%E5%A4%A7%E5%85%AC%E5%8F%B8" target="_blank">大公司</a><span>，
                                        </span></span><span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a></span>
                                    </div>
                                    <div class="comments-list"><span class="comments">10收藏</span></div>
                                </div>
                            </div>
                        </li>
                        <?php }?>
                    </ul>
                </div>

                <div class="loading_more" data-stat-click="zhufeed.wenzhanggengduo"><!-- react-text: 715 -->浏览更多<!-- /react-text --><span class="icon-arrow-right"></span></div>
            </div>
        </div><!-- $main --><!-- ^right side --><div class="rightlib">
            <?php include('sidebar.php');?>
        </div>
        <!-- $right side -->

    </div>

</div>
<!-- $contenter -->

<!-- ^footer -->
<?php include('footer.php');?>