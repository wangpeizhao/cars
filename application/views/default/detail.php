<?php include('header.php');?>
<?=css_url('detail.css')?>
<!-- ^contenter -->
<div id="app">
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">
            <div class="center_content">
                <h1><?=$title?></h1>
                <div class="content-font article-detail">
                    <div class="am-cf author-panel">
                        <div class="author am-fl">
                            <a href="javascript:;" class="am-fl"><span class="name"><?=$author?></span></a>
                            <span class="time am-fl"><span class="dot">&nbsp;•&nbsp;</span><abbr class="time"><?=$create_time?></abbr></span>
                            <span class="time am-fl"><span class="dot">&nbsp;•&nbsp;</span><abbr class="time"><?=$term_name?></abbr></span>
                        </div>
                    </div>
                    <section class="summary"><?=$summary?></section>
                    <div class="content-wrapper">
                        <div><?=html_entity_decode($content)?></div>
                    </div>
                    <!-- 声明 -->
                    <section class="article-footer-label">
                        <div>
                            <div>
                                <div>
                                    原创文章，作者：<?=$author?> 杜。转载或内容合作请点击<a href="javascript:;" target="_blank">转载说明</a>，违规转载法律必究。
                                </div>
                                <div>寻求报道，<a href="javascript:;" target="_blank">请点击这里</a></div>
                            </div>
                        </div>
                    </section>
                    <div class="ads_box">
                        <a href="" style="background-image:url(https://pic.36krcnd.com//avatar/201712/18032420/p37snbq34xqdjtl7.jpg)" target="_blank" rel="nofollow"></a>
                    </div>
                    <?php if($tags){?>
                    <section class="single-post-tags">
                        <?php foreach($tags as $k=>$item){?>
                        <a class="kr-tag-gray" href="/tag/<?=$k?>.html"><?=$item?></a>
                        <?php }?>
                    </section>
                    <?php }?>
                    <div class="fav-wrapper">
                        <a href="javascript:;" class="post-pc-like">赞
                            <span class="total-count-box"><b>(<?=$praises?>)</b></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="related-articles">
                <h4>您可能感兴趣的文章</h4>
                <div class="layout">
                    <ul>
                        <?php if($interested){?>
                            <?php foreach($interested as $item){?><li>
                            <a href="/p/<?=$item['id']?>.html" class="img-box">
                                <img src="/<?=$item['thumb']?>"/>
                            </a>
                            <a href="" class="dec"><span>36氪首发 | 智能空中机器人研发企业「酷黑科技」完成Pre-A轮融资，启动研发城市空中交通工具</span></a>
                        </li><?php }?>
                        <?php }?>
                        <li>
                            <a href="" class="img-box">
                                <img src="https://pic.36krcnd.com/201801/22024844/9upph0vgeu6c86kr!heading">
                            </a>
                            <a href="" class="dec"><span>深氪｜携程在手，先搞定3万员工的价值观再走</span></a>
                        </li><li>
                            <a href="" class="img-box">
                                <img src="https://pic.36krcnd.com/201801/08104502/lcmyhonqm98y47pm!heading">
                            </a>
                            <a href="" class="dec"><span>消费升级四部曲：同样讲新零售，为什么消费者其实并不care效率问题？</span></a>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <a href="" class="img-box">
                                <img src="https://pic.36krcnd.com/201801/22011657/wmdmt5qgnpxeao5g!heading">
                            </a>
                            <a href="" class="dec"><span>沈南鹏：智能汽车行业比手机大很多，将会是一个几十万亿的市场</span></a>
                        </li><li>
                            <a href="" class="img-box">
                                <img src="https://pic.36krcnd.com/201801/22013827/dr7fz6lubq8kia7c!heading">
                            </a>
                            <a href="" class="dec"><span>央视纪录片全程用AI配音，再现已故播音员李易声音</span></a>
                        </li><li>
                            <a href="" class="img-box">
                                <img src="https://pic.36krcnd.com/201801/22021408/vwla19k7vgswgjxr!heading">
                            </a>
                            <a href="" class="dec"><span>医疗影像运营服务商「同心医联」完成第四轮融资，加速独立医学影像中心发展</span></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="ads_box dn">
                <a href="" style="background-image:url(https://pic.36krcnd.com//avatar/201712/18032420/p37snbq34xqdjtl7.jpg)" target="_blank" rel="nofollow"></a>
            </div>
            
        </div><!-- $main --><!-- ^right side --><div class="rightlib">
            <div class="pad_inner">
                <div class="pin-wrapper" id="pin-wrapper-fixed">
                    <div class="custom-pin-wrapper">
                        <div class="guess-posts-list">
                            <h4>相关文章</h4>
                            <ul>
                                <?php if($related){?>
                                    <?php foreach($related as $item){?>
                                    <li class="top" data-index="1">
                                        <a href="/p/<?=$item['id']?>.html" target="_blank" class="item">
                                            <span class="desc"><?=$item['title']?></span>
                                        </a>
                                    </li>
                                    <?php }?>
                                <?php }?>
                            </ul>
                        </div>
                        <div class="sponsor" style="display: none;">
                            <h5><span>赞助商</span></h5>
                            <ul class="am-list am-list-static">
                                <li> </li><li> </li><li> </li>
                            </ul>
                        </div>
                        <div class="next-post-wrapper show">
                            <h4>下一篇</h4>
                            <div class="item" data-stat-click="articles.next">
                                <a href="/p/5114863.html?from=next" class="title" target="_blank">记录创业者 | C.D.Utility在“慢慢”创业：做阳刚、实用的男装</a>
                                <div class="tags-list">
                                    <i class="icon-tag"></i>
                                    <span><a href="/tag/%E6%B6%88%E8%B4%B9" target="_blank">消费</a><span>，</span></span>
                                    <span><a href="/tag/%E4%BC%81%E4%B8%9A%E6%9C%8D%E5%8A%A1" target="_blank">企业服务</a><span>，</span></span>
                                    <span><a href="/tag/%E6%B0%AA%E7%A9%BA%E9%97%B4" target="_blank">氪空间</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function(){
                            var maxHeight = $(document).height();
                            var height = document.getElementById('pin-wrapper-fixed').offsetTop;
                            var top = parseInt($('#pin-wrapper-fixed').css('top'));
                            var fixed = $('#pin-wrapper-fixed').height(); 
                            var footer = $('footer').height();
                            var _top = 0;
                            window.onscroll = function(){
                                var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                                // console.log('top:'+top+';scrollTop:'+scrollTop+';height:'+height);
                                
                                if(maxHeight-scrollTop<fixed+footer+100){
                                    _top = (maxHeight-scrollTop) - (fixed+footer+100);
                                }else if(scrollTop<=height){
                                    _top = top-scrollTop;
                                }
                                $('#pin-wrapper-fixed').css('top',_top+'px');
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
        <!-- $right side -->

    </div>
</div>
<!-- $contenter -->

<!-- ^footer -->
<?php include('footer.php');?>