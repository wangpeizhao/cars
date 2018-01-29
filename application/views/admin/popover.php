<div class="b_g">
	<div class="n_center">
		<div class="img"> 
			<img src="">
		</div>
		<span class="js-none"><i>×</i></span>
		<div id="leftBtn" class="showImgBtn" style="display: none;"><</div>
		<div id="rightBtn" class="showImgBtn" style="display: none;">></div>
		<div class="prompt" style="display: none;"><span>没有下一张啦！</span></div>
	</div>
</div>
<style type="text/css">
	
	.b_g{position: fixed;top: 0;left: 0;bottom: 0;right: 0;z-index: 9999;background: rgba(255,255,255,0.6);display: none;}
	.n_center{
		border-radius: 6px;
		padding: 10px;
		margin: auto;
		position: absolute;
		text-align: center;    
	    max-width: 900px; 
	    max-height: 1200px; /*left: 399.5px; top: 136.5px;*/


	    /*position: relative;*/
	    background-color: #fff;
	    -webkit-background-clip: padding-box;
	    background-clip: padding-box;
	    border: 1px solid #999;
	    border: 1px solid rgba(0, 0, 0, .2);
	    border-radius: 6px;
	    outline: 0;
	    -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
	    box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
	    
	}
	.n_center .img{overflow: hidden;max-height: 1180px;}
	.n_center .js-none{position: absolute;right: 0;top: 0;background: #ccc;color: #fff;width: 25px;border-radius: 12.5px;margin-top: -10px;margin-right: -7px;cursor: pointer;height:25px;line-height: 25px;}
	.n_center .js-none i{font-size: 20px;margin-left:-1px;}
	.n_center img{overflow: hidden;-webkit-transform-origin: center center;transform-origin: center center;max-width: 880px;}
	.n_center .prompt{left: 250px; top: 200px;   height: 50px;width: 120px;background: rgba(0,0,0,.7);position: absolute;color: #fff;font-size: 14px;text-align: center;line-height: 50px;border-radius: 5px;display: none;}
	.n_center .showImgBtn{top: 40%;height:3em;width: 1.5em;border: 1px solid transparent;-webkit-border-radius: 0.5em;-moz-border-radius: 0.5em;border-radius: 0.5em;background-color: rgba(0,0,0,.5);color: #fff;font-size: 2em;text-align: center;line-height: 3em;position: absolute;}
	.n_center #leftBtn{cursor: pointer;top: 40%;left: 10px;display: block;filter:alpha(opacity=80);-moz-opacity:0.8;-khtml-opacity: 0.8;opacity: 0.8;}
	.n_center #rightBtn{cursor: pointer;top: 40%;right: 10px;filter:alpha(opacity=80);-moz-opacity:0.8;-khtml-opacity: 0.8;opacity: 0.8;}

</style>
<script type='text/javascript'>
	$(function(){
		$('.n_center .js-none,.b_g').click(function(){
			$('.b_g').fadeOut();
		});
        $('img.popover').live('click',function(){
        	var src = $(this).attr('_src');
        	$('.n_center img').attr('src',src).load(function() {
        		var width = $(window).width();
        		var height = $(window).height();
        		var _width = this.width+20;
        		var _height = this.height+20;
        		if(_height>820){
        			// _height = 820;
        		}
        		var left = _width>width?0:((width-_width)/2);
        		var top = _height>height?0:((height-_height)/2);
        		$('.n_center').css({'left':left,'top':top});
        	});
        	$('.b_g').fadeIn();
        });
		<?php if(!empty($data['thumb'])){?>
          $('.chooseImage a.del').fadeIn();
        <?php }?>
	});
	function chooseImage(image,thumb){
        $('.chooseImage a img').attr('_src',image);
        $('.chooseImage a img').attr('src',thumb);
        $('.chooseImage input[name="thumb"]').val(image);
        $('.chooseImage a.del').fadeIn();
    }
</script>