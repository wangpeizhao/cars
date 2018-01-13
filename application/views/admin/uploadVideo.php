<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-视频管理-浏览/上传</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.9.1.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/jquery.json.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_video_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type="text/javascript">
<!--
	var lang = '/<?=_LANGUAGE_?>';
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 8,
		pathVal = '';
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
				pathVal = $(this).find('embed').attr('srcval');
			});
			
			$(document).on('dblclick','.div_box ul li',function(){
				$(this).addClass('selected').siblings().removeClass('selected');
				//获取值
				pathVal = $(this).find('embed').attr('srcval');
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
					alert('请先选择视频');
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

			$(document).on('click','div.cover',function(){
				if(confirm("确定要删除")){
					var path = $(this).parent().find("embed").attr('srcval');
					var id = $(this).attr('id');
					$.post(baseUrl + lang + "/admin/upload/dumpUploadVideo",{path:path}, function(data){
						if(data.done===true){
							$("#i_"+id+" div.cover_bg").slideDown();
							$("#i_"+id+" div.cover").fadeIn('slow');
							$("#i_"+id+" div.cover").addClass('success');
							setTimeout(function(){
								$("#i_"+id).fadeOut();
							},'2000');
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
		var isChrome = window.navigator.userAgent.indexOf("Chrome") !== -1;
		if(isChrome){
			window.opener.window._getDialogFlash(pathVal);
		}else{
			window.returnValue = pathVal;
		}
	}

	function fileResult(status,path){
		try{
			if(status==1){
				if(confirm('上传成功,是否继续上传')){
					$("#progress_bar").fadeOut();
					document.getElementById("file").outerHTML = document.getElementById("file").outerHTML;
					$('input[name="file"]').click();
				}else{
					$("#progress_bar").fadeOut();
					window.returnValue = path;
					window.close();
				}
			}else{
				alert(status);
				return false;
			}
		}catch(e){
			alert(e.message);
		}
	}

	function checkForm(){
		if(!$.trim($('input[name="file"]').val())){
			alert('请先选择图像文件');
			return false;
		}
		$("#progress_bar").fadeIn();
	}
//-->
</script>
<style type="text/css">
	.div_box ul li div.cover_bg2 {
    filter: alpha(opacity=0);
    opacity: 0;
    z-index: 100;
    position: absolute;
    width: 100%;
    height: 147px;
    bottom: 0px;
    left: 0px;
    background: #000;
	}
	.div_box ul li embed{
		cursor:pointer;
	}
</style>
</head>
<body>
<div class="container">
	<div class="headbar">
		<div class="position"><h1>后台管理 - 视频管理 - 浏览/上传</h1></div>
		<ul class="tab" style="margin-top:0px;">
			<li class="selected" id="li_1"><a href="javascript:;">浏览视频</a></li>
			<li id="li_2" class=""><a href="javascript:;">上传视频</a></li>
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
					<input id="add" class="button" type="button" onfocus="this.blur();" value="插入视频">
					<input id="close" class="button" type="button" onfocus="this.blur();" value="关闭">
				</div>
			</div>
			<div class="div" style="display: none;margin:20px;" align="left">
				<form method="post" enctype="multipart/form-data" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/uploadVideo" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
					<input type="file" id="file" size="30" name="file">
					<input type="submit" class="button" style="margin-left:10px;" value="上传">
					<img src="<?=site_url()?>/themes/common/images/progress_bar.gif" style="margin-left:3px;display:none;position:relative;top:3px;" id="progress_bar" alt="正在上传...">
					<input type="hidden" value="file" name="inputDOM">
					<p style="padding-top:5px;" class="uploadTips">最大可上传文件<?=ini_get("upload_max_filesize");?>，仅支持<font color="#0099ff">'flv','mp4','swf','f4v'</font>格式视频文件。</p>
				</form>
				<iframe name="ajaxifr" style="display:none;"></iframe>
			</div>
		</div>
	</div>
</div>
</body>
</html>