  <div id="header">
    <div class="logo"> 
		<a href=""><img src="<?=LOGO?>" height="56"/></a> 
	</div>
	<!-- 引入菜单-->
    <div id="menu">
		<?php include('menu.php');?>
    </div>
	<!-- /引入菜单-->
	<div style="float:left;width:100%;">
		<?php function _path_info_($language='cn'){
			$uri = $_SERVER['PATH_INFO'];
            if(trim($uri)>3){
              if(in_array(substr($uri,0,4),array('/en/','/cn/'))){
                  $uri = substr($uri,3);
              }
            }else{
              if(in_array(substr($uri,0,3),array('/en','/cn'))){
                  $uri = substr($uri,3);
              }
            }
			if($language=='cn'){
				$uri = '/en'.$uri;
			}else 
			if($language=='en'){
				$uri = '/cn'.$uri;
			}
			return site_url($uri);
		}?>
		<!-- <a href="<?=_path_info_('en')?>" style="color:<?=_LANGUAGE_!='en'?'blue':''?>;margin-right:10px;"><?=_LANGUAGE_!='en'?'<strong>中文</strong>':'中文'?></a>
		<a href="<?=_path_info_('cn')?>" style="color:<?=_LANGUAGE_=='en'?'blue':''?>;"><?=_LANGUAGE_=='en'?'<strong>English</strong>':'English'?></a> -->
	</div>
	<script type="text/javascript">
	<!--
		var lang = '';//'/<?=_LANGUAGE_?>';
		$(function(){
			try{
				$("#loginout").click(function(){
					if(confirm('确定退出后台管理？')){
						//window.navigate("<?=WEB_DOMAIN?>/admin/system/loginout");
						//self.location='<?=WEB_DOMAIN?>/admin/system/loginout';
						window.location.href="<?=WEB_DOMAIN?>/admin/login/logout";
						if(isIE()==6 || isIE()==7 || isIE()==8){
							location.reload();
						}
					}
				});
			}catch(e){
				alert(e.message);
			}
		});
	//-->
	</script>
    <p>
	  <a href="javascript:;" id="loginout">退出管理</a> 
	  <a href="<?=WEB_DOMAIN?>/admin">后台首页</a> 
	  <a href="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>" target='_blank'>网站首页</a> 
	  <a>您好:<?php $userInfo=$this->session->userdata('userInfo');?>
			<label class='bold'><?=isset($userInfo['username'])?$userInfo['username']:''?></label>
			 ，当前身份
			<label class='bold'><?=isset($userInfo['grouptitle'])?$userInfo['grouptitle']:''?></label>
    </a>
	</p>
  </div>
  <div id="info_bar">
    <!-- <label class="navindex"><a href="#">快速导航管理</a></label> -->
    <span class="nav_sec"> </span>
  </div>