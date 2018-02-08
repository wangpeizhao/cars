<?php include('header.php');?>
<?=css_url('newsflash.css')?>
<!-- ^contenter -->
<div id="app">
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">

            <div class="list_con">
                <div class="fast_section_filter">
                    <dl class="am-fl hide-phone">
                        <dt>筛选情报:</dt>
                        <dd class="selected"><div data-stat-click="kuaixun.company"><span>大公司</span><i>×</i></div></dd>
                        <dd class="selected"><div data-stat-click="kuaixun.product"><span>产品</span><i>×</i></div></dd>
                        <dd class="selected"><div data-stat-click="kuaixun.capital"><span>资本</span><i>×</i></div></dd>
                        <dd class=""><div data-stat-click="kuaixun.policy"><span>政策</span><i>×</i></div></dd>
                        <dd class=""><div data-stat-click="kuaixun.industry"><span>产业</span><i>×</i></div></dd>
                        <dd class=""><div data-stat-click="kuaixun.starding"><span>早期项目</span><i>×</i></div></dd>
                        <dd class=""><div data-stat-click="kuaixun.bplus"><span>B轮后</span><i>×</i></div></dd>
                        <dd class=""><div data-stat-click="kuaixun.other"><span>其他</span><i>×</i></div></dd>
                    </dl>
                </div>
                <div class="clear"></div>
                <div class="fast_section_list">
                    <div class="date">
                        <span><?=intval(date('m'))?>月</span>
                        <b><?=date('d')?></b>
                    </div><div class="newsflashs">
                        <ul>
                            <?php if(!empty($newsflash)){?>
                            <?php foreach($newsflash as $item){?>
                            <li>
                                <div>
                                    <h2><?=$item['title']?></h2>
                                    <div class="fast_news_content">
                                        <?=$item['summary']?$item['summary']:$item['title']?>
                                    </div>
                                    <div class="share_groups"><span title="<?=$item['create_time']?>"><?=$item['timeLine']?></span></div>
                                </div>      
                            </li>
                            <?php }}?>
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
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