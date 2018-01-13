<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>对不起！您没权限操作该项，请联系管理员</title>
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
<!--
	$(function(){
		$("#loginOutBtn").click(function(){
			if(confirm('确定退出后台管理？')){
				window.location.href = '<?=WEB_DOMAIN?>/admin/login/logout';
			}
		});
	});
//-->
</script>
</head>
<body id="login">
<div class="container">
  <!-- 引入头部-->
  <?php include('header.php');?>
  <!-- /引入头部-->
	<div id="wrapper" class="clearfix">
		<div class="login_box">
			<div class="login_title">权限不足!</div>
			<div class="login_cont">
				<div align="center mar_top_20 rline" style="margin-top:50px;">您<font color="#ff0000"><b>权限不足</b></font>，请联系管理员</div>
				<div align="center mar_top_20 rline">您可以：</div>
				<div align="center mar_top_20 rline">
					<input class="submit" type="button" value="返回" onclick="javascript:history.go(-1);" onfocus="this.blur();"/>
					<input class="submit" type="button" id="loginOutBtn" value="登出" onfocus="this.blur();"/>
				</div>
			</div>
		</div>
	</div>
	<div id="footer">Power by Fzhao 四叶草工作室 Copyright &copy; 2011-2012</div>
</div>
</body>
</html>
