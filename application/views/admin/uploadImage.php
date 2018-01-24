<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-产品图片管理-浏览/上传</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.9.1.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_image_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type="text/javascript">
<!--
	var lang = '';
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 8,
		pathVal = '',
		thumb = '';
	$(function(){
		try{
			setData(1);
			$(document).on('click','a',function(){
				 this.blur();
			});

			$(document).on('click','ul.tab li',function(){
				$(this).addClass('selected').siblings().removeClass('selected');
				var index = $(this).index();
				if(index==0){setData(1);}
				$(".div_box").find("div.div").eq(index).siblings().hide();
				$(".div_box").find("div.div").eq(index).fadeIn();
			});

			$(document).on('click','.div_box ul li',function(){
				$(this).addClass('selected').siblings().removeClass('selected');
				$(this).find("div.cover_bg").slideDown();
				$(this).find("div.cover").fadeIn('slow');
				$(this).find("div.cover").addClass('success');
				$(this).siblings().find("div.cover").removeClass('success');
				$(this).siblings().find("div.cover_bg").slideUp();
				$(this).siblings().find("div.cover").fadeOut('fast');
				//获取值
				pathVal = $(this).find('span img').attr('srcval');
				thumb = $(this).find('span img').attr('src');
			});
			
			$(document).on('dblclick','.div_box ul li',function(){
				$(this).addClass('selected').siblings().removeClass('selected');
				//获取值
				pathVal = $(this).find('span img').attr('srcval');
				thumb = $(this).find('span img').attr('src');
				//传值
				_return_value();
				window.close();
			});

			$("#add").click(function(){
				if(pathVal!=''){
					//传值
					_return_value();
					window.close();
				}else{
					alert('请先选择图片');
					return false;
				}
			});

			$("#close").click(function(){
				window.close();
			});
			$(document).on('mouseenter','.div_box ul li',function(){
				$(this).find("div.cover_bg").slideDown();
				$(this).find("div.cover").fadeIn('slow');
			});

			$(document).on('mouseleave','.div_box ul li',function(){
				if(!$(this).find("div.cover").hasClass('success')){
					$(this).find("div.cover_bg").slideUp();
					$(this).find("div.cover").fadeOut('fast');
				}
			});

			$(document).on('click','a.del',function(){
				if(confirm("确定要删除")){
					var id = $(this).attr('_id');
					$.post(baseUrl + lang + "/admin/upload/del",{id:id}, function(data){
						if(data.done===true){
							$("#i_"+id).fadeOut();
						}else if(data.msg){
							alert(data.msg);
							return false;
						}else{
							alert('提交失败');
							return false;
						}
					},"json");
				}
			});
		}catch(e){
			alert(e.message);
		}
	});

	//return value
	function _return_value(){
		var act = getQueryString('act');
		if(act == 'specify'){
			window.opener.window.chooseImage(pathVal,thumb);
			return true;
		}
		var isChrome = window.navigator.userAgent.indexOf("Chrome") !== -1;
		if(isChrome){
			window.opener.window._getDialogImage(pathVal);
		}else{
			window.returnValue = pathVal;
		}
	}

	function fileResult(str,status){
		try{
			if(status==1){
				if(confirm('上传成功,是否继续上传')){
					$("#progress_bar").fadeOut();
					document.getElementById("file").outerHTML = document.getElementById("file").outerHTML;
					$('input[name="file[]"]').click();
				}else{
					$("#progress_bar").fadeOut();
					$('.tab li').eq(0).click();
					//window.returnValue = path;
					//window.close();
				}
			}else{
				alert(str);
				return false;
			}
		}catch(e){
			alert(e.message);
		}
	}

	function checkForm(){
		if(!$.trim($('input[name="file[]"]').val())){
			alert('请先选择图像文件');
			return false;
		}
		$("#progress_bar").fadeIn();
	}

	function _checkForm(){
		if(!$.trim($('input[name="imageUrl"]').val())){
			alert('请输入网络图片地址');
			return false;
		}
		$("#progress_bar").fadeIn();
	}

	function iResultAlter(str,status){
		if(status ==0){
			alert(str);
			return false;
		}
<<<<<<< HEAD
		alert('提交成功');
=======
		
>>>>>>> 25131d4572247b84e0e2206ea92e53040dfe5aff
	}
//-->
</script>
</head>
<body>
<div class="container">
	<div class="headbar">
		<div class="position"><h1>后台管理 - 图片管理 - 浏览/上传<br><font color="#ff0000">注：一次只能选择插入一张;双击单张可直接插入;可批量上传图片</font></h1></div>
		<ul class="tab" style="margin-top:0px;">
			<li class="selected" id="li_1"><a href="javascript:;">浏览图片</a></li>
			<li id="li_2" class=""><a href="javascript:;">上传本地图片</a></li>
			<li id="li_3" class=""><a href="javascript:;">上传网络图片</a></li>
		</ul>
		<div class="div_box">
			<div class="div" style="display: block;margin:20px;">
				<div class="images">
					<ul id="images">
						<li align="center" style="padding:20px;width:32px;height:32px;border:0px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..." style="width:32px;height:32px;"/></li>
					</ul>
				</div>
				<div class="clear"></div>
				<div id="pageLists" class="pageLists clearfix hide"></div>
				<input type="hidden" name="currentPage" value="1">
				<div class="do_images hide">
					<input id="add" class="button" type="button" onfocus="this.blur();" value="插入图片">
					<input id="close" class="button" type="button" onfocus="this.blur();" value="关闭">
				</div>
			</div>
			<div class="div" style="display: none;margin:20px;" align="left">
				<form method="post" enctype="multipart/form-data" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/uploading" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
					<input type="file" id="file" size="30" name="file[]" multiple="" class="normal">
					<input type="submit" class="button" style="margin-left:10px;" value="上传">
					<img src="<?=site_url()?>/themes/common/images/progress_bar.gif" style="margin-left:3px;display:none;position:relative;top:3px;" id="progress_bar" alt="正在上传...">
					<input type="hidden" value="file" name="inputDOM">
					<input type="hidden" value="location" name="act">
					<p style="padding-top:5px;" class="uploadTips">最大可上传文件1M，仅支持<font color="#0099ff">'jpg','jpeg','gif','png'</font>格式图像文件。一次可上传多张图片。</p>
				</form>
			</div>
			<div class="div" style="display: none;margin:20px;" align="left">
				<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/uploading" novalidate="true" target="ajaxifr" onSubmit="return _checkForm();">
					<input type="text" placeholder="请输入网络图片地址" name="imageUrl" class="normal" style="width:350px;">
					<input type="submit" class="button" style="margin-left:10px;" value="上传">
					<img src="<?=site_url()?>/themes/common/images/progress_bar.gif" style="margin-left:3px;display:none;position:relative;top:3px;" id="progress_bar" alt="正在上传...">
					<input type="hidden" value="file" name="inputDOM">
					<input type="hidden" value="network" name="act">
					<p style="padding-top:5px;" class="uploadTips">最大可上传文件1M，仅支持<font color="#0099ff">'jpg','jpeg','gif','png'</font>格式图像文件。一次可上传多张图片。</p>
				</form>
			</div>
		</div>
		<iframe name="ajaxifr" style="display:none;"></iframe>
	</div>
</div>
</body>
</html>