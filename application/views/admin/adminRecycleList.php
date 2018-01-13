<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-管理员管理-回收站</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/form.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_recycle_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10;
	$(function(){
		try{
			setData(1);

			$("a").click(function(){
				 this.blur();
			});

			$("#close").live('click',function() {
				$(".bg").fadeOut("slow");
				if($(".lay").is(':visible')){$(".lay").fadeOut("slow");}
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var uid = $(this).parent().attr('uid');
				switch(act){
					case 'recover':
						if(confirm('确定要还原管理员信息吗？')){
							recoverUserInfo(uid);
						}
						break;
					case 'del':
						if(confirm('确定要彻底删除？不再回收的哦！')){
							dumpUserInfo(uid);
						}
						break;
				}
			});

			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			//批量删除
			$("#deleteAll").click(function(){
				if(deleteAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					dumpUserInfo(ids);
				}
			});

			//批量还原
			$("#recoverAll").click(function(){
				if(deleteAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					recoverUserInfo(ids);
				}
			});

			//返回列表
			$("#import").click(function(){
				event_link(baseUrl + lang +'/admin/system/adminList')
			});

		}catch(e){
			alert(e.message);
		}
	});

	function dumpUserInfo(id){
		try{
			$.ajax({
				type: 'post',
				url: baseUrl + lang + "/admin/system/dumpUserInfo",
				data: {id:id},
				dataType: 'json',
				timeout: 30000,
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("status：" + XMLHttpRequest.status + ";\nreadyState："+ XMLHttpRequest.readyState + ";\ntextStatus："+ textStatus);
				},
				success: function(data) {
					if (data.done === true) {
						alert('提交成功');
						setData($('input[name="currentPage"]').val());
					} else if(data.msg){
						alert(data.msg);
						return false;
					}else {
						alert('提交失败，请重试');
						return false;
					}
				}
			});
		}catch(e){
			alert(e.message);
		}
	}

	function recoverUserInfo(id){
		try{
			$.ajax({
				type: 'post',
				url: baseUrl + lang + "/admin/system/recoverUserInfo",
				data: {id:id},
				dataType: 'json',
				timeout: 30000,
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("status：" + XMLHttpRequest.status + ";\nreadyState："+ XMLHttpRequest.readyState + ";\ntextStatus："+ textStatus);
				},
				success: function(data) {
					if (data.done === true) {
						alert('提交成功');
						setData($('input[name="currentPage"]').val());
					}else if(data.msg){
						alert(data.msg);
						return false;
					} else {
						alert('提交失败，请重试');
						return false;
					}
				}
			});
		}catch(e){
			alert(e.message);
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
      <div class="position"><span>系统</span><span>></span><span>管理员管理</span><span>></span><span>回收站</span></div>
      <div class="operating"> 
	    <a href="javascript:;" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a> 
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
		<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a> 
	  </div>
    </div>
    <div class="content">
	  <table id="list_table" class="list_table" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="5%">选择</th>
			<th width="5%">编号</th>
			<th width="10%">登录名</th>
			<th width="10%">真实姓名</th>
			<th width="10%">所属角色</th>
			<th width="10%">所属部门</th>
			<th width="10%">工作邮箱</th>
			<th width="10%">工作手机</th>
			<th width="7%">上次登录IP</th>
			<th width="5%">激活</th>
			<th width="3%">排序</th>
			<th width="10%">上次登录时间</th>
			<th width="5%">操作</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
      </table>
	  <div id="pageLists" class="pageLists clearfix hide"></div>
	<input type="hidden" name="currentPage" value="1">
	<!--/container-->
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->