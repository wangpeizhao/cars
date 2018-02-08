<?php include('header.php');?>
<?=css_url('home.css')?>
<?=js_url('unslider/unslider.min.js')?>
<!-- ^contenter -->
<div id="app">
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">
            <div class="carousels">
                <ul>
                    <?php if(!empty($carousels)){
                        foreach($carousels as $k=>$item){?>
                        <li><a href="<?=$item['link_url']?>"<?=$item['target']?> title="<?=$item['link_name']?>" style="background-image: url('<?=site_url($item['link_image'])?>')"></a></li>
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
            <div class="hotFocus">
                <ul>
                    <?php if(!empty($rands)){
                        foreach($rands as $item){?><li>
                        <a href="<?=$item['link_url']?>"<?=$item['target']?>>
                            <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                        </a>
                        <a href="<?=$item['link_url']?>"<?=$item['target']?> title="<?=$item['link_name']?>">
                            <span><?=$item['link_name']?></span>
                        </a>
                    </li><?php }}?>
                </ul>
            </div>

            <div class="list_con">
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
                        <?php if(!empty($mainLists)){
                            foreach($mainLists as $_k=>$item){?>
                        <li>
                            <div class="am-cf inner_li inner_li_abtest">
                                <a href="/p/<?=$item['id']?>.html" target="_blank">
                                    <div class="img_box">
                                        <div href="/p/<?=$item['id']?>.html" target="_blank">
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
                                            <span class="time" title="<?=$item['create_time']?>"><?=$item['create_time']?></span>
                                            <span class="time h5_time" title="<?=$item['timeLine']?>"><?=$item['timeLine']?></span>
                                        </div>
                                    </div>
                                    <div class="tags-list">
                                        <?php if($item['tags']){?>
                                            <?php foreach($item['tags'] as $k=>$_item){?>
                                                <span><a href="/tag/<?=$k?>.html" target="_blank"><?=$_item?></a></span><span class="separate">，</span>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                    <div class="comments-list">
                                        <span class="comments"><?=$item['praises']>$item['views']?$item['praises'].'赞':$item['views'].'浏览'?></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php if($_k && ($_k+1)%5 == 0){?>
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
                        <?php }?>
                        <?php }}?>
                    </ul>
                </div>

                <div class="loading_more" data-stat-click="zhufeed.wenzhanggengduo"><!-- react-text: 715 -->浏览更多<!-- /react-text --><span class="icon-arrow-right"></span></div>
            </div>
        </div><!-- $main --><!-- ^right side --><div class="rightlib">
            <div class="ads ">
                <a href="#" style="background-image: url('<?=img_url('un68ifk27tary2fy.jpeg')?>')"></a>
                <span class="mark">广告</span>
            </div>

            <div class="real_time">
                <h3><span>7×24h 快讯</span></h3>
                <ul>
                    <?php if($newsflash){
                        foreach($newsflash as $item){?>                            
                    <li class="real_time_wrapper">
                        <span class="triangle"></span>
                        <div class="con">
                            <h4>
                                <span class="title"><?=$item['title']?></span>
                            </h4>
                            <div class="item0 hide show-content" style="display: none;">
                                <?=$item['summary']?>
                            </div>
                            <div>
                                <span class="time" title="<?=$item['create_time']?>"><?=$item['timeLine']?></span>
                                <span class="share">
                                    <div class="fast-section-share-box hide-phone">
                                        <span class="share-title">分享至&nbsp;&nbsp; </span>
                                        <a class="item-weixin hide-phone">
                                            <span class="icon-weixin"></span>
                                            <div class="panel-weixin">
                                                <section class="weixin-section">
                                                    <!-- <p><img src="http://s.jiathis.com/qrcode.php?url=<?=site_url('/p/'.$item['id'].'.html')?>" alt=""></p> -->
                                                </section>
                                                <h3>打开微信“扫一扫”，打开网页后点击屏幕右上角分享按钮</h3>
                                            </div>
                                        </a>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </li>
                    <?php }}?>
                    <script type="text/javascript">
                        $('.real_time_wrapper h4').click(function(){
                            var _S = $(this).parent().parent();
                            if(_S.hasClass('show')){
                                _S.removeClass('show');
                                _S.find('.show-content').slideUp();
                            }else{
                                _S.addClass('show');
                                _S.find('.show-content').slideDown();
                            }
                        });
                    </script>
                </ul>
                <a class="more-fastsection" href="/nf" target="_blank">浏览更多<span class="icon-arrow-right"></span></a>
            </div>


            <?php include('sidebar.php');?>

        </div>
        <!-- $right side -->

    </div>

    <!-- ^aboutUs -->
    <div class="h-aboutUs">
        <div class="hd">
            <strong>关于我们</strong>
            <div class="bor-bton"></div>
        </div>
        <div class="container">
            <div class="mod-l"></div>
            <div class="mod-r">
                <!-- 首页关于我们碎片start -->
                <div class="h-about">
                    <div class="hdd"><strong>关于我们</strong></div>
                    <div class="bd">
                        <p><?=$about?></p>
                        <a class="link-more" href="/company/info/about">了解详情 &gt;&gt;</a>
                    </div>
                </div>                <!-- 首页关于我们碎片end -->
                <div class="h-news">
                    <div class="hdd">
                        <strong>新闻分享</strong>
                    </div>
                    <div class="bd">
                        <ul class="h-news-list">
                            <!-- 首页资讯推荐start -->
                            <?php if($news){
                                foreach($news as $item){?>
                                <li><a href="/p/<?=$item['id']?>.html"><?=$item['title']?></a></li>
                            <?php    }
                            }?>
                        </ul>
                       <a href="/news" class="link-more">更多 &gt;&gt;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- $aboutUs -->

</div>
<!-- $contenter -->

<!-- ^footer -->
<?php include('footer.php');?>