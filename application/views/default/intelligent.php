<?php include('header.php');?>
<?=css_url('home.css')?>
<style type="text/css"> 


</style>
<!-- ^contenter -->
<div id="app" style="margin-top: 0px;">
    <div class="focus-banner">
        <div class="fb-left iblock">
            <?php if(!empty($carousels_left)){
                foreach($carousels_left as $item){?>
                <a href="<?=$item['link_url']?>">
                    <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                </a>
                <a class="con" href="<?=$item['link_url']?>" title="<?=$item['link_name']?>">
                    <span><?=$item['link_name']?></span>
                    <p><?=$item['link_description']?></p>
                </a>
            <?php }
            }?>
        </div><div class="fb-right iblock">
            <ul>
                <li>
                    <?php if(!empty($carousels_top)){
                        foreach($carousels_top as $k=>$item){?><div class="<?=$k==0?'fb-first-left':'fb-first-right'?> iblock">
                        <a href="<?=$item['link_url']?>">
                            <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                        </a>
                        <a class="con" href="<?=$item['link_url']?>" title="<?=$item['link_name']?>">
                            <span><?=$item['link_name']?></span>
                            <p><?=$item['link_description']?></p>
                        </a>
                    </div><?php }}?>
                </li>
                <li>
                    <?php if(!empty($rands)){
                        foreach($rands as $item){?><div class="fb-second iblock">
                        <a href="<?=$item['link_url']?>">
                            <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                        </a>
                        <a class="con" href="<?=$item['link_url']?>" title="<?=$item['link_name']?>">
                            <span><?=$item['link_name']?></span>
                            <p><?=$item['link_description']?></p>
                        </a>
                    </div><?php }}?>
                </li>
            </ul>
        </div>
    </div>
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">

            <div class="list_con" style="margin-top:0;">

                <div class="car_tab" id="subNavShow">
                    <ul>
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
                        <li><span><a href="javascript:;">VR Is?</a></span></li>
                        <li><span><a href="javascript:;">AR Is?</a></span></li>
                        <li><span><a href="javascript:;">AI Is?</a></span></li>
                        <li><span><a href="javascript:;">UAV Is?</a></span></li>
                    </ul>
                </div>
                <div class="car_tab" id="subNavHide">
                    <ul>
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
                        <li><span><a href="javascript:;">VR Is?</a></span></li>
                        <li><span><a href="javascript:;">AR Is?</a></span></li>
                        <li><span><a href="javascript:;">AI Is?</a></span></li>
                        <li><span><a href="javascript:;">UAV Is?</a></span></li>
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