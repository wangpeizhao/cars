<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理后台登录</title>
<link rel="stylesheet" href="<?=site_url('')?>themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
<!--
	var tVCode = false;
	$(function(){
		$("#loginBtn").click(function(){
			try{
				var isEmpty = true,
					username = $('input[name="username"]'),
					password = $('input[name="password"]'),
					captcha  = $('input[name="captcha"]'),
					u_name	 = username.val();
				if(!$.trim(username.val())){
					isEmpty=false;
					username.css('border','1px #FFABAB solid');
				}else if($.trim(username.val()).length<2){
					username.val('');
					isEmpty=false;
					username.focus();
					username.val(u_name);
					username.css('border','1px #FFABAB solid');
				}
				if(!$.trim(password.val())){
					isEmpty=false;
					password.css('border','1px #FFABAB solid');
				}
				if(!$.trim(captcha.val())){
					captcha.css('border','1px #FFABAB solid');
				}
				if(isEmpty==false){
					return false;
				}
				if(tVCode==false){
					captcha.css('border','1px #FFABAB solid');
					$(".tipsLogin").html('验证码错误...');
					$(".tipsLogin").css('color','#ff3300');
					return false;
				}
				
				$.ajax({
					type:"POST",
					url: '<?=WEB_DOMAIN?>/admin/login',
					data:{username:$.trim(username.val()),password:$.trim(password.val()),captcha:$.trim(captcha.val())}, //将提交的数据
					dataType:"json",
					timeout:30000,
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert('网络连接超时，请刷新页面');
					},
					success:function(data){
						if(data.code==1){
							$(".tipsLogin").html('登录成功！正在跳转...');
							$(".tipsLogin").css('color','#339900');
							window.location.href = '<?=isset($url) && $url?$url:WEB_DOMAIN."/admin/system/options"?>';
						}else{
							$(".tipsLogin").html(data.msg);
							$(".tipsLogin").css('color','#ff3300');
							return false;
						}
					}
				});
				return false;
			}catch(e){
				alert(e.message);
			}
		});
		$('input[name="username"],input[name="password"],input[name="captcha"]').blur(function(){
			if(!$.trim($(this).val())){
				$(this).css('border','1px #FFABAB solid');
			}else{
				$(this).css('border','1px #ccc solid');
			}
		});
		$('input[name="captcha"]').live('keyup',function(){
			if($(this).val().length==4){
				try{
					$.post("<?=WEB_DOMAIN?>/admin/login/checkVCode",{vCode:$(this).val()}, function(data){
						if(data.done === true){
							$(".login_cont img.status").fadeIn();
							if(data.msg==1){
								tVCode = true;
								$(".login_cont img.status").attr('src','<?=site_url('')?>/themes/common/images/check_right.gif');
								$('input[name="captcha"]').css('border','1px #ccc solid');
								$(".tipsLogin").html('');
							}else{
								$(".login_cont img.status").attr('src','<?=site_url('')?>/themes/common/images/check_error.gif');
								$('input[name="captcha"]').css('border','1px #FFABAB solid');
								$(".tipsLogin").html('<font color="#ff0000">'+data.msg+'</font>');
							}
						}else{
							$(".tipsLogin").html('<font color="#ff0000">'+data.msg+'</font>');
						}
					},"json");
				}catch(e){
					alert(e.message);
				}
			}
		});
	});
//-->
</script>
</head>
<body id="login">
<div class="container">
	<div id="header">
		<div class="logo"> <a href=""><img src="<?=LOGO?>" height="56"/></a>  </div>
		<p><a href="<?=WEB_DOMAIN?>" target='_blank' onclick="this.blur();">网站首页</a> <a>您好，欢迎使用 <strong><?=$sitesName?> </strong>后台管理</a></p>
	</div>
	<div id="wrapper" class="clearfix">
		<div class="login_box">
			<div class="login_title">后台管理登录</div>
			<div class="login_cont">
				<form>
				  <table class="form_table">
					<col width="90px" />
					<col />
					<tr>
					  <th valign="middle">用户名：</th>
					  <td><input class="normal" type="text" name="username" title="请填写用户名" value=""/></td>
					</tr>
					<tr>
					  <th valign="middle">密码：</th>
					  <td><input class="normal" type="password" name="password" pattern='^\w{2,100}$' title="请填写密码" value=""/></td>
					</tr>
					<tr>
					  <th valign="middle">验证码：</th>
					  <td>
						<input style="width:85px" type='text' class='normal' name='captcha' pattern='^\w{4}$' maxlength="4" title='填写左图片所示的字符' value=""/>
						<img src="<?=WEB_DOMAIN?>/admin/login/vCode?<?=time()?>" class="cursor vCode" onclick="this.src='<?=WEB_DOMAIN?>/admin/login/vCode?'+Math.round(Math.random()*1000000)" />&nbsp;
						<img src="<?=site_url('')?>themes/common/images/check_right.gif" class="hide status"></td>
					</tr>
					<tr>
					  <th valign="middle"></th>
					  <td><input class="submit" type="submit" id="loginBtn" value="登录" onfocus="this.blur();"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="submit" type="reset" value="取消" onfocus="this.blur();"/></td>
					</tr>
					<tr>
						<td colspan="2"><div align="center"><font class="tipsLogin">&nbsp;</font></div></td>
					</tr>
				  </table>
				</form>
			</div>
		</div>
	</div>
	<div id="footer">Power by <a href="<?=WEB_DOMAIN?>" style="color:#fff;" target="_blank"><?=$sitesName?></a> Copyright &copy; <?=date('Y')?></div>
</div>
</body>
</html>
