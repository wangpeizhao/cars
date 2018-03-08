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
                            <span class="time am-fl"><span class="dot">&nbsp;•&nbsp;</span><abbr class="time"><?=TimeLine(strtotime($create_time))?></abbr></span>
                            <span class="time am-fl"><span class="dot">&nbsp;•&nbsp;</span><abbr class="time"><?=$term_name?></abbr></span>
                        </div>
                    </div>
                    <section class="summary"><?=$summary?></section>
                    <div class="content-wrapper">
                        <div><?=html_entity_decode($content)?></div>
                    </div>

                    <div class="article-oper">
                        <div class="article-oper-1 left">
                            <span class="read-num">阅读 (<em><?=round($views/10000,2)?>万</em>)</span>
                        </div>
                        <div class="article-oper-r right">
                            <span class="uninterested" id="uninterested"><a href="javascript:;" target="_blank" class="uninterested-link">不感兴趣</a>
                                <div class="uninterested-box" style="display: none;">
                                    <div class="cort"></div>
                                    <h4>不感兴趣</h4>
                                    <ul>
                                        <?php if(!empty($uninterested)){
                                            foreach($uninterested as $key=>$item){?>
                                            <li data-val="<?=$key?>" class="<?=$key==10?'otherquestion':''?>"><span class="checkbox-icon"><input type="checkbox"></span><?=$item?></li>
                                        <?php   }
                                        }?>
                                    </ul>
                                    <div class="unia" style="display: none;"><textarea maxlength="500" name="reason"></textarea></div>
                                    <div class="vCode">
                                        <input type="text" name="vCode" class="input-mode" placeholder="请输入验证码" maxlength="4">
                                        <img src="<?=site_url('')?>themes/common/images/loadding.gif" id="loaddingNote">
                                        <img src="" onclick="this.src='/home/vCodeFB?'+Math.round(Math.random()*1000000)" id="vCodeImg">
                                    </div>
                                    <div class="btn"><a href="javascript:;" class="uninterested-ok">确定</a><span style="display: none;">*请填写原因</span></div>
                                </div>
                                <div class="uninterested-no"><div class="cort"></div>请勿重复提交</div>
                                <div class="unfeedback" style="display: none;"><i class="feedback-icon"></i><p>感谢您的反馈，我们将会减少此类文章的推荐</p></div>
                            </span>
                            <a class="complain-link contact-fun"  href="javascript:;"><em class="complain-icon icon"></em>投诉</a>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                var domain = '<?=site_url()?>';
                                var feedbacked = false;
                                $('a.uninterested-link').click(function(e){
                                    if(feedbacked){
                                        return true;
                                    }
                                    $('.uninterested-box').fadeIn(function(){
                                        $('#vCodeImg').attr('src','/home/vCodeFB');
                                        $('#loaddingNote').fadeOut(function(){
                                            $('#vCodeImg').fadeIn();
                                        });
                                    });
                                    stopPropagation(e);
                                });
                                document.onclick = function(){
                                    $(".uninterested-box").fadeOut();
                                }
                                $(".uninterested-box").click(function(e){
                                    stopPropagation(e);
                                });
                                $('.article-detail .uninterested-box ul li').click(function(){
                                    if($(this).hasClass('clk')){
                                        $(this).removeClass('clk');
                                        if($(this).hasClass('otherquestion')){
                                            $('.unia').slideUp();
                                        }
                                    }else{
                                        $(this).addClass('clk');
                                        if($(this).hasClass('otherquestion')){
                                            $('.unia').slideDown();
                                        }
                                    }

                                });
                                $('.uninterested-ok').click(function(){
                                    var clks = $('.article-detail .uninterested-box ul li.clk');
                                    if(clks.length==0){
                                        alert('请至少选择一项不感兴趣的原因');
                                        return false;
                                    }
                                    var other = false;
                                    var tids = [];
                                    clks.each(function(k,v){
                                        var val = $(v).attr('data-val');
                                        tids.push(val);
                                        if(val == '10'){
                                            other = true;
                                        }
                                    });
                                    var reason = $.trim($('textarea[name="reason"]').val());
                                    if(other && !reason){
                                        $('textarea[name="reason"]').focus();
                                        alert('请填写“其他问题，我要吐槽”的原因');
                                        return false;
                                    }
                                    if(other && reason.length<10){
                                        alert('“其他问题，我要吐槽”的原因描述不能低于10个字.');
                                        return false;
                                    }
                                    if(!other){
                                        reason = '';
                                    }
                                    var vCode = $.trim($('input[name="vCode"]').val());
                                    if(!vCode){
                                        $('input[name="vCode"]').focus();
                                        alert('请填写验证码');
                                        return false;
                                    }
                                    var oid = '<?=$id?>';
                                    $.post(domain+ "/home/feedback",{oid:oid,tids:tids,reason:reason,vCode:vCode}, function(data){
                                        if(data.code == 1){
                                            feedbacked = true;
                                            $('.uninterested-link').text('感谢您的反馈，我们将会减少此类文章的推荐。');
                                            alert('感谢您的反馈，我们将会减少此类文章的推荐。');
                                            $(".uninterested-box").fadeOut();
                                            return true;
                                        }else if(data.msg){
                                            alert(data.msg);
                                            return false;
                                        }else{
                                            alert('提交失败,请重试');
                                            return false;
                                        }
                                    },"json");
                                });
                                

                                function stopPropagation(e){
                                    var ev = e || window.event;
                                    if(ev.stopPropagation){
                                        ev.stopPropagation();
                                    }
                                    else if(window.event){
                                        window.event.cancelBubble = true;//兼容IE
                                    }
                                }
                            });

                            
                        </script>
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
                            <span class="total-count-box">(<b><?=$praises?$praises:0?></b>)</span>
                        </a>
                        <script type="text/javascript">
                            $(function(){
                                $('a.post-pc-like').click(function(){
                                    var id = '<?=$id?>';
                                    $.ajax({
                                        type: "POST",
                                        url: '/news/doPraises',
                                        data: {id:id},
                                        dataType: "json",
                                        timeout: 30000,
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            alert('赞不成功')
                                        },
                                        success: function(data) {
                                            if(data.code=='1'){
                                                alert('success');
                                                var num = $('a.post-pc-like span b').text();
                                                $('a.post-pc-like span b').text(parseInt(num) + 1);
                                                return true;
                                            }else{
                                                alert(data.msg);
                                                return false;
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>

                </div>
            </div>
            <?php if($interested){?>
            <div class="related-articles">
                <h4>您可能感兴趣的文章</h4>
                <div class="layout">
                    <ul>
                        <?php foreach($interested as $k=>$item){?><li>
                            <a href="/p/<?=$item['id']?>.html" class="img-box">
                                <img src="/<?=$item['thumb']?>"/>
                            </a>
                            <a href="" class="dec"><span><?=$item['title']?></span></a>
                        </li><?php echo $k>0 && $k%2==0?'</ul><ul>':'';} ?>
                    </ul>
                </div>
            </div>
            <?php }?>

            <div class="ads_box dn">
                <a href="" style="background-image:url(https://pic.36krcnd.com//avatar/201712/18032420/p37snbq34xqdjtl7.jpg)" target="_blank" rel="nofollow"></a>
            </div>
            
        </div><!-- $main --><!-- ^right side --><div class="rightlib">
            <div class="pad_inner">
                <div class="pin-wrapper" id="pin-wrapper-fixed">
                    <div class="custom-pin-wrapper" style="margin-bottom:20px;">
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
                            <div class="item">
                                <?php if($next){?>
                                <a href="/p/<?=$next['id']?>.html" class="title" target="_blank"><?=$next['title']?></a>
                                <div class="tags-list">
                                    <i class="icon-tag"></i>
                                    <?php if($next['tags']){?>
                                        <?php foreach($next['tags'] as $k=>$item){?>
                                    <span><a href="/tag/<?=$k?>.html" target="_blank"><?=$item?></a><span>，</span></span>
                                    <?php }}?>
                                </div>
                                <?php }else{?>
                                <a href="/tag/<?=$other?$other['slug']:'javascript:;'?>.html" class="title" target="_blank">没有喽，试下其他？</a>
                                <?php }?>
                            </div>
                        </div>


                        <div class="next-post-wrapper show">
                            <h4>上一篇</h4>
                            <div class="item">
                                <?php if($prev){?>
                                <a href="/p/<?=$prev['id']?>.html" class="title" target="_blank"><?=$prev['title']?></a>
                                <div class="tags-list">
                                    <i class="icon-tag"></i>
                                    <?php if($prev['tags']){?>
                                        <?php foreach($prev['tags'] as $k=>$item){?>
                                    <span><a href="/tag/<?=$k?>.html" target="_blank"><?=$item?></a><span>，</span></span>
                                    <?php }}?>
                                </div>
                                <?php }else{?>
                                <a href="/tag/<?=$other?$other['slug']:'javascript:;'?>.html" class="title" target="_blank">没有喽，试下其他？</a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function(){
                            var height = document.getElementById('pin-wrapper-fixed').offsetTop;
                            var top = parseInt($('#pin-wrapper-fixed').css('top'));
                            var fixed = $('#pin-wrapper-fixed').height(); 
                            var footer = $('footer').height();
                            var _top = 0;
                            window.onscroll = function(){
                                var maxHeight = $(document).height();
                                var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                                // console.log('maxHeight:'+maxHeight+';fixed:'+fixed+';footer:'+footer+';top:'+top+';scrollTop:'+scrollTop+';height:'+height);
                                
                                if(maxHeight-scrollTop<fixed+footer){
                                    _top = (maxHeight-scrollTop) - (fixed+footer);
                                }else if(scrollTop<=height){
                                    _top = top-scrollTop;
                                }
                                $('#pin-wrapper-fixed').css('top',_top+'px');
                            }
                        });
                    </script>
                    <?php include('feedback.php');?>
                </div>
            </div>
        </div>
        <!-- $right side -->

    </div>
</div>
<!-- $contenter -->

<!-- ^footer -->
<?php include('footer.php');?>