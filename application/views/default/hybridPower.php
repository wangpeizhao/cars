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
        </div><div class="banner-right iblock">
            <ul>
                <?php if(!empty($rands)){
                    foreach($rands as $item){?>
                <li>
                    <a href="<?=$item['link_url']?>"<?=$item['target']?>>
                        <img src="<?=site_url($item['link_image'])?>" alt="<?=$item['link_name']?>">
                    </a>
                    <a href="<?=$item['link_url']?>"<?=$item['target']?> title="<?=$item['link_name']?>">
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
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
                        <li><span><a href="javascript:;">What Is?</a></span></li>
                    </ul>
                </div>
                <div class="car_tab" id="subNavHide">
                    <ul>
                        <li class="active"><span><a href="javascript:;" _act="all">最新文章</a></span></li>
                        <?php if(!empty($terms['childs'])){
                            foreach($terms['childs'] as $item){?>
                        <li><span><a href="javascript:;"><?=$item['name']?></a></span></li>
                        <?php }}?>
                        <li><span><a href="javascript:;">What Is?</a></span></li>
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