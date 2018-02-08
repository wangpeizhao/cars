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
                    <?php include('articleList.php');?>
                </div>

                <div class="loading_more">浏览更多<span class="icon-arrow-right"></span></div>
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