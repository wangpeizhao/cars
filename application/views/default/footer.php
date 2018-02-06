	<div class="clear"></div>
	<style type="text/css">
		body { 
			background-attachment:fixed;
		}
		
		.main {
		    width: 1200px;
		    margin: 0 auto;
		}.clear_both {
		    zoom: 1;
		}
		.fl{float:left;}
		.fr{float:right;}
		.clear_both {
		    zoom: 1;
		}
		.clear {
		    margin: 0px auto;
		    clear: both;
		    height: 0px;
		    font-size: 0px;
		    overflow: hidden;
		}
	</style>
	<footer>
		<div class="bg-icon"></div>
	    <div class="f_top">
	        <div class="main">
	            <div class="f_sub clear_both">
	                <div class="f_nav fl">
	                    <ul class="clear_both">

	                    	<?php if($foot_terms){
	                    		foreach($foot_terms as $items){?>
	                        <li class="fl">
	                            <div class="title">
	                                <?=$items['name']?>
	                            </div>
	                            <dl>
	                            	<?php if(!empty($items['childs'])){
	                    				foreach($items['childs'] as $item){?>
	                            	<dd>
	                                    <a href="<?=!empty($foot_mappings[$items['taxonomy']])?'/'.$foot_mappings[$items['taxonomy']].(in_array($foot_mappings[$items['taxonomy']], array('contact','company','sheji'))?'/info/':'/sort/').$item['slug']:'/'?>" title="<?=$item['name']?>"><?=$item['name']?></a>
	                                </dd>
	                                <?php }}?>
	                            </dl>
	                        </li>
	                        <?php }}?>
	                        
	                    </ul>
	                </div>
	                <div class="f_rab fr">
	                    <div class="moge">
	                        <a href="/"><?=$options['companyName']?></a><br>
	                        地址：<?=$options['companyAddress']?><br>
	                        手机：<?=!empty($options['companyMobile'])?$options['companyMobile']:''?><br>
	                        <!-- 电话：<?=$options['companyPhone']?><br> -->
	                        Q Q：<?=!empty($options['companyQQ'])?$options['companyQQ']:''?><br>
	                        邮箱：<a href="mailto:<?=$options['companyEmail']?>"><?=$options['companyEmail']?></a>
	                    </div>
	                    <div class="share clear_both" style="display:none;">
	                        <div class="guan fl">
	                            关注我们 :
	                        </div>
	                        <div class="zhu fl">
	                            <a href=""><img src="<?=img_url('1.png')?>"></a>
	                            <a href=""><img src="<?=img_url('2.png')?>"></a>
	                            <a href=""><img src="<?=img_url('3.png')?>"></a>
	                            <a href=""><img src="<?=img_url('4.png')?>"></a>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="clear"></div>
	            <?php if($links){?>
	            <div class="link clear_both">
	                <div class="l_lab fl">
	                    友情链接 :
	                </div>
	                <div class="l_rab fl">
	                    <ul class="clear_both">
	                    	<?php foreach($links as $item){?>
	                    	<li class="fl">
	                            <a href="<?=$item['link_url']?>" target="<?=$item['link_target']?$item['link_target']:'_blank'?>"><?=$item['link_name']?></a>
	                        </li>
	                        <?php }?>
	                    </ul>
	                </div>
	            </div>
	            <?php }?>
	        </div>
	        <div class="clear"></div>
	    </div>
	    <div class="f_bottom">
	    	<?php include('dynamic/footer_detail.php');?>
	    </div>
	</footer>
	<script type="text/javascript"> 
		// document.body.oncopy = function(){ 
		//   setTimeout( 
		//     function (){ 
		//     var text = window.clipboardData.getData("text"); 
		//     if(text){ 
		//       text = text + "\r\n本文来自: (<?=site_url('/')?>) 详细出处参考："+location.href; 
		//       window.clipboardData.setData("text", text); 
		//     } 
		//   },100) 
		// } 
	</script>
	</body>
</html>