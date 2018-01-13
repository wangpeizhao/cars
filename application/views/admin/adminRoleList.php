<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-管理员角色</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_role_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<style type="text/css">
	.setting_form{padding:0px 0 30px 0px;background:#fff;}
	.setting_form input{border: 1px solid #999;
		display: block;
		font-size: 12px;
		height: 20px;
		line-height: 23px;
		width: 95%;
	}
</style>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10,
		DOMVal = '';
	$(function(){
		try{
			setData(1);
			$("a").click(function(){
				 this.blur();
			});
			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			$("#addSetting").click(function(){
				var date = new Date();
				var time = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
				var html = '';
				html += '<tr class="addSetting">';
				html +=		'<td><input type="checkbox" value="" class="cursor"/></td>';
				html += 	'<td>-</td>';
				html += 	'<td><input type="text" name="" class="p_left5"></td>';
				html += 	'<td>'+time+'<input type="hidden" value="'+time+'"></td>';
				html += 	'<td><a href="javascript:;" class="delete">删除</a></td>';
				html += 	'<td></td>';
				html += '</tr>';
				$(".settingList").append(html);
				 if(!$(".lay").is(':visible')){
					$("#addBtn").fadeIn();
				}
			});

			$(".setting_form .delete").live('click',function(){
				$(this).parent().parent().fadeOut(function(){
					$(this).remove();
				});
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var gid = $(this).parent().attr('gid');
				switch(act){
					case 'edit':
						$(this).parent().parent().find('td').eq(2).html('<input class="p_left5 editGroupTitle small" style="text-align:center;" type="text" value="'+$(this).parent().parent().find('td').eq(2).text()+'">');
						$(".editGroupTitle").select();
						$("#addBtn").fadeOut();
						break;
					case 'del':
						if(confirm('确定要删除？与其相关的管理员将失去相应管理权限！')){
							$.post(baseUrl + lang + "/admin/system/delRole",{gid:gid}, function(data){
								if (data.done === true) {
									alert('删除成功');
									setData($('input[name="currentPage"]').val());
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
			});

			$(".editGroupTitle").live('focus',function(){
				DOMVal = $(this).val();
			});

			$(".editGroupTitle").live('blur',function(){
				if(DOMVal == $(this).val()){
					$(this).parent().text($(this).val());
					return false;
				}
				var gid = $(this).parent().parent().attr('gid');
				$.post(baseUrl + lang + "/admin/system/editRole",{gid:gid,newTitle:$(this).val()}, function(data){
					if (data.done === true) {
						alert('修改成功');
					}else if(data.msg){
						alert(data.msg);
					}else{
						alert('提交失败，请重试');
						return false;
					}
					setData($('input[name="currentPage"]').val());
				},"json");
			});

			$("#addSettingBtn").live('click',function(){
				var data = [],list = [],aid=0;
				list =  $(".setting_form").find('tr.addSetting');
				if(list.length==0){
					alert('您输入 0 行，提交失败！');
					return false;
				}
				$.each(list,function(k,v){
					aid = $(this).find('input');
					if(!$.trim(aid.eq(1).val()) || !$.trim(aid.eq(2).val())){
						alert('第'+(parseInt(k+1))+'行有空值，请先输入');
						data.splice(0);//消除data
						return false;
					}
					data.push({"grouptitle":aid.eq(1).val(),"create_time":aid.eq(2).val()});
				});
				if(data.length>0){
					try{
						$.post(baseUrl + lang + "/admin/system/addRole",{data:data}, function(data){
							if (data.done === true) {
								alert('提交成功');
								setData($('input[name="currentPage"]').val());
							}else if(data.msg){
								alert(data.msg);
								return false;
							}else{
								alert('提交失败，请重试');
								return false;
							}
						},"json");
					}catch(e){
						alert(e.message);
					}
				}
			});
		}catch(e){
			alert(e.message);
		}
	});
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
      <div class="position"><span>系统</span><span>></span><span>权限管理</span><span>></span><span>管理员角色</span></div>
      <div class="operating">
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a>
	  </div>
    </div>
    <div class="content setting_form">
	  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="3%">选择</th>
			<th width="5%">编号</th>
			<th width="20%">管理员角色</th>
			<th width="13%">创建时间</th>
			<th width="10%">操作</th>
			<th width="50%">&nbsp;</th>
		</tr>
		<tr><td colspan="12" align="left" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
	</table>
	<div id="pageLists" class="pageLists clearfix hide"></div>
	<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field" id="settingList">
			<td colspan="7" align="left" width="75%"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" id="addSetting">[+]添加新角色</a><span style="float:left;padding-left:5px;float:left;color:#ff6600;"><b>提示：</b>修改时失去光标即可提交</span></td>
		</tr>
		<tr id="addBtn" class="hide">
			<td colspan="7" align="left" id="Btn">
				<input id="addSettingBtn" type="button" class="submit cursor" value="提交" onfocus="this.blur();" style="margin-bottom:10px;border:0px;margin-left:5px;"/>
			</td>
		</tr>
	</table>
	<!--/container-->
	<input type="hidden" name="currentPage" value="1">
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->