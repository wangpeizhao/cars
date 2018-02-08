<?php include('header.php');?>
<?=css_url('home.css')?>
<!-- ^contenter -->
<div id="app">
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">
            <div class="focus">
                <div class="focus-left">
                    <?php if(!empty($carousels)){
                        foreach($carousels as $item){?>
                        <a href="<?=$item['link_url']?>">
                            <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                        </a>
                        <a href="<?=$item['link_url']?>" title="<?=$item['link_name']?>">
                            <span><?=$item['link_name']?></span>
                        </a>
                    <?php }
                    }?>
                </div><div class="focus-right">
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

            <div class="list_con" >
                <div class="car_tab" id="subNavShow">
                    <ul>
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
                    </ul>
                </div>
                <div class="car_tab" id="subNavHide">
                    <ul>
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
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