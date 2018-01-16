<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理 - <?=$title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.7.2.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		_DATETIME_ = '<?=_DATETIME_?>',
		_TITLE_ = '<?=!empty($_title_)?$_title_:''?>';
		id = '<?=!empty($data['id'])?$data['id']:0?>';
//-->
</script>
</head>
<body>
<div class="container">
  <div id="header">
    <div class="logo"> 
		<a href=""><img src="<?=LOGO?>" height="56"/></a> 
	</div>
	<!-- 引入菜单-->
    <div id="menu">
		<?php include('menu.php');?>
    </div>
	<!-- /引入菜单-->

	<script type="text/javascript">
	<!--
		var lang = '';//'/<?=_LANGUAGE_?>';
		$(function(){
			try{
				$("#loginout").click(function(){
					if(confirm('确定退出后台管理？')){
						window.location.href="<?=WEB_DOMAIN?>/admin/login/logout";
						if(isIE()==6 || isIE()==7 || isIE()==8){
							window.location.reload();
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
	  <a>您好:<?php $userInfo=$this->session->userdata('adminLoginInfo');?>
			<label class='bold'><?=isset($userInfo['username'])?$userInfo['username']:''?></label>
			 ，当前身份
			<label class='bold'><?=isset($userInfo['role_name'])?$userInfo['role_name']:''?></label>
    </a>
	</p>
  </div>
  <div id="info_bar">
    <!-- <label class="navindex"><a href="#">快速导航管理</a></label> -->
    <span class="nav_sec"> </span>
  </div>