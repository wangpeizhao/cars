<div class="ads" style="margin-bottom: 8px;">
    <a href="#" style="background-image: url('<?=img_url('un68ifk27tary2fy.jpeg')?>')"></a>
    <span class="mark">广告</span>
</div>

<div class="hot_article hot_posts pad_inner">
    <h3><span>热门文章</span></h3>
    <ul class="am-list">
        <?php if($hotNews){?>
            <?php foreach($hotNews as $k=>$item){?>
                <?php if($k<2){?>
                <li class="top<?=$k+1?>" data-index="<?=$k+1?>">
                    <div>
                        <div class="img-cover">
                            <a href="/p/<?=$item['id']?>.html" target="_blank">
                                <span class="top<?=$k+1?>"><b>Top<?=$k+1?></b></span>
                                <div class="img-cell" style="background-image: url('/<?=$item['thumb']?>');"></div>
                                <div class="article-title"><div class="article-wrapper"><?=$item['term_name'].$item['title']?></div></div>
                            </a>
                        </div>
                    </div>
                </li>
                <?php }else{?>
                    <li class="top" data-index="<?=$k+1?>">
                        <div>
                            <div>
                                <div class="img-left-cover">
                                    <a href="/p/<?=$item['id']?>.html" target="_blank"><span><?=$k+1?></span>
                                        <div class="img-cell" style="background-image: url('/<?=$item['thumb']?>');"></div>
                                    </a>
                                </div>
                                <div class="right-article">
                                    <h4><a href="/p/<?=$item['id']?>.html" target="_blank"><?=$item['term_name'].$item['title']?></a></h4>
                                    <div class="time_about" title="2018-01-20 09:34"><?=$item['timeLine']?></div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php }?>
            <?php }?>
        <?php }?>
    </ul>
</div>


<div class="hot_tag_right pad_inner">
    <h3><span>热点标签</span></h3>
    <div class="inner_con am-cf">
        <?php if($hotTags){
            foreach($hotTags as $item){?>
            <a class="kr-tag-blue" target="_blank" href="<?=site_url('/tag/'.$item['slug'].'.html')?>"><?=$item['name']?></a>
        <?php }
        }?>
    </div>
</div>

<div class="hot_focus_right pad_inner" style="margin-bottom: 1.5rem;">
    <h3><span>热点聚焦</span></h3>
    <div class="inner_con am-cf">
        <a target="_blank" href="https://www.36jr.com/projects">股权投资项目</a>
        <a target="_blank" href="https://www.36jr.com/project/203">普安药房股权投资</a>
        <a target="_blank" href="http://36kr.com/p/533801.html">机器翻译</a>
        <a target="_blank" href="http://36kr.com/tags/yijiaminsu">一家民宿</a>
        <a target="_blank" href="http://36kr.com/p/5061409.html">王思聪香蕉体育</a>
        <a target="_blank" href="http://36kr.com/p/532777.html">三好网社区</a>
        <a target="_blank" href="http://36kr.com/p/5035627.html">更美医疗</a>
        <a target="_blank" href="http://36kr.com/tags/feifanwang">非凡网相关报道</a>
    </div>
</div>

<?php include('feedback.php');?>