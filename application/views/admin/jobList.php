<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-招贤纳士列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script src="<?=site_url('')?>/themes/common/js/form.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/job_list.js"></script>
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

			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			//批量删除
			$("#deleteAll").click(function(){
				if(dumpAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					delJob(ids);
				}
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'del':
						if(confirm('确定要删除该信息？')){
							delJob(id);
						}
						break;
				}
			});

			//添加招聘岗位
			$("#addJob").click(function(){
				event_link(baseUrl + lang +'/admin/system/addJob')
			});

		}catch(e){
			alert(e.message);
		}
	});

	function delJob(id){
		try{
			$.ajax({
				type: 'post',
				url: baseUrl + lang + "/admin/system/delJob",
				data: {id:id},
				dataType: 'json',
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
<div class="bg hide"></div>
<div class="container">
  <!-- 引入头部-->
	<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
	<?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span>招贤纳士</span><span>></span><span>诚聘英才</span></div>
      <div class="operating"> 
	    <a href="javascript:;" id="addJob"><button class="operating_btn" type="button"><span class="addition">添加招聘岗位</span></button></a> 
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
	  </div>
    </div>
    <div class="content">
	  <table id="list_table" class="list_table" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="5%">选择</th>
			<th width="5%">编号</th>
			<th width="15%">岗位名称</th>
			<th width="25%">工作职责</th>
			<th width="25%">岗位要求</th>
			<th width="15%">创建时间</th>
			<th width="10%">操作</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
      </table>
	  <input type="hidden" name="currentPage" value="1">
	  <div id="pageLists" class="pageLists clearfix hide"></div>
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->