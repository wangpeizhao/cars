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
            <div class="ads ">
                <a href="#" style="background-image: url('<?=img_url('un68ifk27tary2fy.jpeg')?>')"></a>
                <span class="mark">广告</span>
            </div>

            <div class="real_time">
                <h3><span>7×24h 快讯</span></h3>
                <ul>
                    <?php for($i=0;$i<10;$i++){?>                            
                    <li class="real_time_wrapper">
                        <span class="triangle"></span>
                        <div class="con">
                            <h4>
                                <span class="title" data-stat-click="kuaixunmokuai.kuaixunbiaoti.1.99171">麦当劳中国联手融创 缔结策略伙伴关系加快业务发展</span>
                            </h4>
                            <div class="item0 hide show-content" style="display: none;">
                                据美通社报道，麦当劳中国与融创房地产集团有限公司签订长期战略合作协议。麦当劳与融创在后者布局的全国八大区域的综合体、社区商业、写字楼、文旅等板块进行全面合作。融创将在其全国社区、文旅项目中为麦当劳餐厅提供优先选址权利；与此同时，麦当劳也将发挥其全球品牌效应，支持融创的社区配套与餐饮配套发展。
                            </div>
                            <div>
                                <span class="time" title="2018-01-19 18:32"><?=$i+3?>分钟前</span>
                                <span class="share">
                                    <div class="fast-section-share-box hide-phone">
                                        <span class="share-title">分享至&nbsp;&nbsp; </span>
                                        <a class="item-weixin hide-phone">
                                            <span class="icon-weixin"></span>
                                            <div class="panel-weixin">
                                                <section class="weixin-section">
                                                    <p><img src="http://s.jiathis.com/qrcode.php?url=http://36kr.com/newsflashes/99171" alt=""></p>
                                                </section>
                                                <h3>打开微信“扫一扫”，打开网页后点击屏幕右上角分享按钮</h3>
                                            </div>
                                        </a>
                                        <a href="http://share.baidu.com/s?type=text&amp;searchPic=1&amp;sign=on&amp;to=tsina&amp;key=595885820&amp;url=http://36kr.com/newsflashes/99171&amp;title=%E3%80%9036%E6%B0%AA%E5%BF%AB%E8%AE%AF%E3%80%91%E9%BA%A6%E5%BD%93%E5%8A%B3%E4%B8%AD%E5%9B%BD%E8%81%94%E6%89%8B%E8%9E%8D%E5%88%9B%20%E7%BC%94%E7%BB%93%E7%AD%96%E7%95%A5%E4%BC%99%E4%BC%B4%E5%85%B3%E7%B3%BB%E5%8A%A0%E5%BF%AB%E4%B8%9A%E5%8A%A1%E5%8F%91%E5%B1%95" target="_blank">
                                            <span class="icon-sina"></span>
                                        </a>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </li>
                    <?php }?>
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
                <a class="more-fastsection" href="/newsflash" target="_blank">浏览更多<span class="icon-arrow-right"></span></a>
            </div>


            <div class="hot_article hot_posts pad_inner">
                <h3><span>热门文章</span></h3>
                <ul class="am-list">
                    <li class="top" data-index="1">
                        <div>
                            <div class="img-cover">
                                <a href="/p/5114448.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.1.5114448">
                                    <span class=""><b><!-- react-text: 950 -->Top<!-- /react-text --><!-- react-text: 951 -->1<!-- /react-text --></b></span>
                                    <div class="img-cell" style="background-image: url('https://pic.36krcnd.com/201801/17080416/8bbji6pk757xxcq3!heading');"></div>
                                    <div class="article-title"><div class="article-wrapper">36氪首发 | 「阿丘科技」完成800万美元A轮融资，DCM和百度风投领投</div></div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="top" data-index="2">
                        <div>
                            <div class="img-cover">
                                <a href="/p/5114307.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.2.5114307">
                                    <span class="top2"><b><!-- react-text: 961 -->Top<!-- /react-text --><!-- react-text: 962 -->2<!-- /react-text --></b></span>
                                    <div class="img-cell" style="background-image: url('https://pic.36krcnd.com/201801/18105631/y80ez69x99o4r1p6!heading');"></div>
                                    <div class="article-title"><div class="article-wrapper">深度 | 暴跌不止的币圈：史上最疯狂与脆弱的一幕</div></div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <?php for($i=2;$i<5;$i++){?>
                    <li class="top" data-index="<?=$i+1?>">
                        <div>
                            <div>
                                <div class="img-left-cover">
                                    <a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1"><span><?=$i?></span>
                                        <div class="img-cell" style="background-image: url('https://pic.36krcnd.com/201801/18095434/bs9t7wnpbvb0db5f!heading');"></div>
                                    </a>
                                </div>
                                <div class="right-article">
                                    <h4><a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1">36氪首发 | 获华润数千万人民币 A+ 轮融资,「极视角」上线计算机视觉 PasS 云平台</a></h4>
                                    <div class="time_about" title="2018-01-20 09:34">刚刚</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="top" data-index="<?=$i+1?>">
                        <div>
                            <div>
                                <div class="img-left-cover">
                                    <a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1"><span><?=$i?></span>
                                        <div class="img-cell" style="background-image: url('https://pic.36krcnd.com/201801/18033513/9j4drjyecbcs4n4e!heading');"></div>
                                    </a>
                                </div>
                                <div class="right-article">
                                    <h4><a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1">刚刚，任天堂用一套纸盒子，吊打索尼微软的高科技</a></h4>
                                    <div class="time_about" title="2018-01-20 09:34">刚刚</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="top" data-index="<?=$i+1?>">
                        <div>
                            <div>
                                <div class="img-left-cover">
                                    <a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1"><span><?=$i?></span>
                                        <div class="img-cell" style="background-image: url('https://pic.36krcnd.com/201801/18120042/8nq8y8j5fqmcx7tw!heading');"></div>
                                    </a>
                                </div>
                                <div class="right-article">
                                    <h4><a href="/p/1.html" target="_blank" data-stat-click="remenwenzhang.wenzhangbiaoti.3.1">血战2018：优酷、爱奇艺、腾讯共预算亏损190亿</a></h4>
                                    <div class="time_about" title="2018-01-20 09:34">刚刚</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php }?>
                </ul>
            </div>


            <div class="hot_tag_right pad_inner">
                <h3><span>热点标签</span></h3>
                <div class="inner_con am-cf">
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/taodangpu">淘当铺</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/bitebi">比特币</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/p/5055657.html">Uber新APP</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/p/5055625.html">网约车新政第一天</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/feifanwang">飞凡网</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/qufenqi">趣分期</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/licai">理财相关</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/tags/wangyinqianbao">网银钱包</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/p/5055707.html">苹果iPhone8</a>
                    <a class="kr-tag-blue" data-stat-click="rementag.biaoqian" target="_blank" href="http://36kr.com/p/5055572.html">网约车新政</a>
                </div>
            </div>

            <div class="hot_focus_right pad_inner">
                <h3><span>热点聚焦</span></h3>
                <div class="inner_con am-cf">
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="https://www.36jr.com/projects">股权投资项目</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="https://www.36jr.com/project/203">普安药房股权投资</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/p/533801.html">机器翻译</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/tags/yijiaminsu">一家民宿</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/p/5061409.html">王思聪香蕉体育</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/p/532777.html">三好网社区</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/p/5035627.html">更美医疗</a>
                    <a data-stat-click="redianjujiao.biaoqian" target="_blank" href="http://36kr.com/tags/feifanwang">非凡网相关报道</a>
                </div>
            </div>

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
                                <li><a href="/news/info/<?=$item['id']?>.html"><?=$item['title']?></a></li>
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