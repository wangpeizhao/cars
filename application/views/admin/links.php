<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-友情链接</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/form.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_partners_link_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<style type="text/css">
	
</style>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		link_type = 'link',
		rows = 10;
	$(function(){
		try{
			setData(1);

			$("a").click(function(){
				 this.blur();
			});

			$("#addLink").toggle(
				function () {
					$(".addLinks").slideDown("slow",function(){
						$(".content").scrollTop($("#list_table").height());
						document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
						$('input[name="link_name"]').val('');
						$('input[name="link_url"]').val('http://www.');
						$('input[name="link_rating"]').val('99');
						$('input[name="link_description"]').val('');
						$("input:radio[name='link_target']").each(function(){
							if($(this).val()=='_blank'){
								$(this).attr("checked",true);
							}
						});
						$("input:radio[name='link_visible']").each(function(){
							if($(this).val()==1){
								$(this).attr("checked",true);
							}
						});
						$(".seeimg").html('');
					});
					$(this).html('[-]添加友情链接');
					$(".addLinks form").attr('action',baseUrl + lang +'/admin/system/addLink');
				},
				function () {
					$(".addLinks").slideUp("slow");
					$(this).html('[+]添加友情链接');
				}
			);

			$(".submit").click(function(){
				var link_name = $('input[name="link_name"]'),
					link_url = $('input[name="link_url"]'),
					link_rating = $('input[name="link_rating"]');
				if(!$.trim(link_name.val())){
					//link_url.css('border','1px #ff0000 solid;');
					alert('客户名称不能为空!');
					link_name.focus();
					return false;
				}
				if(!validate(link_url.val(),'url')){
					//link_url.css('border','1px #ff0000 solid;');
					alert('Web 地址格式不正确!');
					link_url.focus();
					return false;
				}
				if(link_rating.val() && isNaN(link_rating.val())){
					alert('排序等级只能为两位整数!');
					link_rating.focus();
					return false;
				}
                /*
				$.post(baseUrl + lang + "/admin/system/addLink",{act:'checkLP'}, function(data){
					if (data.done === true) {
						result=true;
					}else if(data.msg){
						alert(data.msg);
						result=false;
					}else{
						result=false;
					}
					return result;
				},"json");
                */
			});

			$(".seeimg a#delImg").live('click',function(){
				if(confirm('确定要删除该友情链接图片！')){
					var imgPath = $(this).attr('src');
					var link_id = $(this).attr('link_id');
					$.post(baseUrl + lang + "/admin/system/editLink",{imgPath:imgPath,link_id:link_id,act:'delLinkImage'}, function(data){
						if (data.done === true) {
							alert('删除成功');
							$(".seeimg").html('');
							$("#"+link_id).find('td').eq(4).html('<font color="#0099ff">无</font>');
						}else if(data.msg){
							alert(data.msg);
							return false;
						}else{
							alert('提交失败，请重试');
							return false;
						}
					},"json");
				}
			});

			$("#list_table a").live('click',function(){
				try{
					var act = $(this).attr('act');
					var id = $(this).parent().attr('id');
					switch(act){
						case 'edit':
							$.post(baseUrl + lang + "/admin/system/editLink",{id:id,act:'get'}, function(data){
								if(data.done===true){
									$('input[name="link_name"]').val(data.data.link_name);
									$('input[name="link_url"]').val(data.data.link_url);
									$('input[name="link_rating"]').val(data.data.link_rating);
									$('input[name="link_description"]').val(data.data.link_description);
									$('select[name="link_term"]').val(data.data.link_term);
									$("input:radio[name='link_target']").each(function(){
										if($(this).val()==data.data.link_target){
											$(this).attr("checked",true);
										}
									});
									$("input:radio[name='link_visible']").each(function(){
										if($(this).val()==data.data.link_visible){
											$(this).attr("checked",true);
										}
									});
									$(".addLinks").slideDown("slow",function(){
										$(".content").scrollTop($("#list_table").height());
									});
									$("#addLink").html('[-]添加友情链接');
									//if(!$(".addLinks").is(":visible")){
									//	$("#addLink").click();
									//}
									$(".addLinks form").attr('action',baseUrl + lang +'/admin/system/editLink/'+data.data.link_id);
									if(data.data.link_image){
										$(".seeimg").html('<br><a href="'+(site_url+data.data.link_image)+'" target="_blank" title="点击浏览原图"><img src="'+(site_url+data.data.link_image)+'" width="100"/></a>&nbsp;<a href="javascript:;" link_id="'+data.data.link_id+'" src="'+data.data.link_image+'" style="color:#339900;" title="点击【删除】可直接删除该友情链接图片" id="delImg">删除</a>');
									}else{
										$(".seeimg").html('');
									}
								}else if(data.msg){
									alert(data.msg);
									return false;
								}
							},"json");
							break;
						case 'del':
							if(confirm('确定要删除？本操作将直接删除，无法回撤！')){
								$.post(baseUrl + lang + "/admin/system/delLink",{id:id}, function(data){
									if (data.done === true) {
										alert('提交成功');
										setData($('input[name="currentPage"]').val());
										$(".addLinks").slideUp("slow");
									}else if(data.msg){
										alert(data.msg);
										return false;
									}else{
										alert('提交失败，请重试');
										return false;
									}
								},"json");
							}
							break;
					}
				}catch(e){
					alert(e.message);
				}
			});
		}catch(e){
			alert(e.message);
		}
	});

	function fileResult(str){
		if(str==1 || str==2){
			document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
			$('input[name="link_name"]').val('');
			$('input[name="link_url"]').val('http://www.');
			$('input[name="link_rating"]').val('99');
			$('input[name="link_description"]').val('');
			$('input[name="link_target"]').attr("checked",'_blank');
			$('input[name="link_visible"]').attr("checked",'1');
			if(str==1){
				alert('添加成功');
			}else{
				alert('修改成功');
			}
			$(".addLinks").slideUp("slow",function(){
				$("#addLink").html('[+]添加友情链接');
				setData($('input[name="currentPage"]').val());
			});
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
</head>
<body>
<div class="container">
  <!-- 引入头部-->
	<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
	<?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span>友情链接</span><span>></span><span>友情链接</span></div>
    </div>
    <div class="content link_target" align="left">
		<table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
			<tr class="field">
				<th width="3%">选择</th>
				<th width="3%">编号</th>
				<th width="10%">友情链接分类</th>
				<th width="10%">友情链接名称</th>
				<th width="15%">Web地址</th>
				<th width="5%">图片？</th>
				<th width="5%">打开方式</th>
				<th width="5%">是否激活</th>
				<th width="5%">排序等级</th>
				<th width="15%">更多描述</th>
				<th width="9%">管理员</th>
				<th width="10%">日期时间</th>
				<th width="5%">操作</th>
			</tr>
			<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
		</table>
		<div id="pageLists" class="pageLists clearfix hide"></div>
		<div style="width:100%;" >
			<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
				<tr class="field">
					<td colspan="3" align="left"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" id="addLink">[+]添加友情链接</a></td>
				</tr>
			</table>
			<div class="addLinks hide">
				<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addLink" enctype="multipart/form-data" target="ajaxifr">
					<table class="list_table link_list_table field" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height:30px;margin-bottom:50px;">
						<tr>
							<td style="text-align:right;" width="10%"><span>*</span>请选择分类：</td>
							<td align="left" width="25%">
								<select name="link_term">
									<option value="">-选择分类-</option>
									<?php if(isset($link_term) && $link_terms=$link_term[0]){
										foreach($link_terms['sunTerm'] as $item){?>
									<option value="<?=$item['id']?>"><?=$item['name']?></option>
									<?php }}?>
								</select>
							</td>
							<td align="left" width="65%">
								<span style="color:#999;">请选择分类，若需要添加、编辑分类，可请到 <a href="<?=WEB_DOMAIN?>/admin/system/classify">分类管理</a></span>
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"><span>*</span>客户名称：</td>
							<td align="left"><input type="text" class="txt" name="link_name"></td>
							<td align="left" style="text-align:left;"><span style="color:#999;">例如：四叶草工作室。鼠标滑过时显示</span></td>
						</tr>
						<tr>
							<td style="text-align:right;"><span>*</span>Web地址：</td><td align="left"><input type="text" class="txt" name="link_url" value="http://www." ></td><td align="left" ><span style="color:#999;">例如：<a href="http://www.baibu.com">http://www.baibu.com</a> —— 不要忘了 http://</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">选择图片：</td><td align="left"><input type="file" name="link_image" id="link_image"><span class="seeimg"></span></td><td align="left"><span style="color:#999;">上传友情链接图片.建议图片为客户网站Logo;</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">打开方式：</td>
							<td align="left" class="link_target">
								<ul>
									<li><label><input type="radio" name="link_target" value="_blank" checked> _blank — 新窗口或新标签。</label></li>
									<li><label><input type="radio" name="link_target" value="_top"> _top — 不包含框架的当前窗口或标签。</label></li>
									<li><label><input type="radio" name="link_target" value="_parent"> _parent — 同一窗口或标签。</label></li>
								</ul>
							</td>
							<td align="left"><span style="color:#999;">为您的链接选择目标框架(打开方式)。</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">是否激活：</td><td align="left"><label><input type="radio" name="link_visible" value="1" checked>是</label>&nbsp;&nbsp;<label><input type="radio" name="link_visible" value="0">否</label></td><td align="left"><span style="color:#999;">选择“是”才会显示在页面</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">排序等级：</td><td align="left"><input type="text" class="txt" name="link_rating" maxlength="2" value="99" pattern='^\d{1,2}$'></td><td align="left" ><span style="color:#999;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font></span></td>
						</tr>
						<tr>
							<td style="text-align:right;">更多描述：</td><td align="left"><textarea name="link_description" style="width:250px;height:50px;padding:2px;"></textarea></td><td align="left"><span style="color:#999;">对客户的更多描述。</span></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="left" colspan="2"><input type="submit" class="submit cursor" value="提交" onfocus="this.blur();"/></td>
						</tr>
					</table>
				</form>
				<iframe name="ajaxifr" style="display:none;"></iframe>
			</div>
		</div>
	<input type="hidden" name="currentPage" value="1">
	<!--/container-->
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->
