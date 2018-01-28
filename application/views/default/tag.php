<?php include('header.php');?>
<?=css_url('home.css')?>
<style type="text/css">  
.tagsbanner{
    background-color: #fafafa;
    width: 100%;
    height: 9rem;
    position: relative;
    padding: 0 1rem;
    margin: 0 auto;
    color: #68727d;
}
.tags_wrapper {
    height: 9rem;
    max-width: 1120px;
    position: relative;
    margin: 0 auto;
}
.tagsbanner .tag_tip {
    padding: 1.6rem 0 .5rem;
    font-size: .8rem;
}
.tags_wrapper .tag_read {
    vertical-align: top;
}
.tagsbanner .content {
    width: 100%;
}
.tagsbanner .content .tags_keywords {
    color: #3d464d;
}
.tagsbanner .content .tags_keywords h1 {
    margin: 0;
    padding: 0;
    color: #4285f4;
    padding-right: .25rem;
    font-weight: 400;
    display: inline-block;
    font-size: 1.2rem;
}
.tagsbanner .content .tags_keywords span {
    font-size: 1.2rem;
    font-weight: 400;
}
.tagsbanner .content .note {
    font-size: .7rem;
    letter-spacing: 1px;
    color: #aaa;
}
.tagsbanner .content .note .select_word {
    color: #68727d;
    font-weight: 500;
}
.tagsbanner .content .note {
    font-size: .7rem;
    letter-spacing: 1px;
    color: #aaa;
}
.tagsbanner .content .note a {
    color: #4285f4!important;
    margin-right: 6px;
}
</style>
<!-- ^contenter -->
<div id="app" style="margin-top: 0px;">
    <div class="tagsbanner">
        <div class="tags_wrapper">
            <div class="tag_tip">
                <span class="icon-tag"></span>
                <span class="tag_read">聚合阅读</span>
            </div>
            <div class="content">
                <div>
                    <div class="tags_keywords">
                        <h1><?=$name?></h1>
                        <span>相关的文章</span>
                    </div>
                    <p class="note"><?=SITESNAMESHORT?>聚合所有<span class="select_word"><?=$name?></span>相关的文章报道，并为你提供最新的相关资讯.</p>
                    <p class="note" style="margin-top: 2px;">
                        热门标签推荐：
                        <?php if($hotTagsRSS){?>
                            <?php foreach($hotTagsRSS as $k=>$item){?>
                            &nbsp;&nbsp;&nbsp;<a href="/tag/<?=$item['slug']?>.html" style="color:#FFF"><?=$item['name']?></a>
                            <?php }?>
                        <?php }?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">

            <div class="list_con" style="margin-top: 0;">
                <div class="car_article_list">
                    <ul>
                        <?php if($news){?>
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