<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-产品中心-产品回收站</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_products_recycle_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10,
		condition = [];
	$(function(){
		try{
			setData(1);

			$("a").live('click',function(){
				 this.blur();
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'recover':
						if(confirm('确定要还原该产品吗？')){
							recoverProducts(id);
						}
						break;
					case 'del':
						if(confirm('确定要彻底删除该产品？不再回收的哦！')){
							dumpProducts(id);
						}
						break;
				}
			});

			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			//批量删除
			$("#dumpAll").click(function(){
				if(dumpAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					dumpProducts(ids);
				}
			});

			//批量还原
			$("#recoverAll").click(function(){
				if(recoverAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					recoverProducts(ids);
				}
			});

			//返回列表
			$("#import").click(function(){
				event_link(baseUrl + lang +'/admin/system/products')
			});

		}catch(e){
			alert(e.message);
		}
	});

	function dumpProducts(id){
		try{
			$.post(baseUrl + lang + "/admin/system/dumpProducts",{id:id}, function(data){
				if (data.done === true) {
					alert('已彻底粉碎该产品');
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

	function recoverProducts(id){
		try{
			$.post(baseUrl + lang + "/admin/system/recoverProducts",{id:id}, function(data){
				if (data.done === true) {
					alert('已还原该产品');
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

	//搜索
	function doSearch(){
		//$('select[name="term_id"]').val('');
		//$('select[name="is_commend"]').val('');
		//$('select[name="is_issue"]').val('');
		//$('input[name="startTime"]').val('');
		//$('input[name="endTime"]').val('');
		condition = [];
		condition.push({"type":$('select[name="search"]').val(),'keywords':$('input[name="keywords"]').val()});
		setData(1);
	}

	//筛选
	function doSelect(){
		//$('select[name="search"]').val('');
		//$('input[name="keywords"]').val('');
		condition = [];
		condition.push({
			"term_id":$('select[name="term_id"]').val(),
			"is_commend":$('select[name="is_commend"]').val(),
			"is_issue":$('select[name="is_issue"]').val(),
			"startTime":$('input[name="startTime"]').val(),
			"endTime":$('input[name="endTime"]').val()
		});
		setData(1);
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
  <div class="position"><span>系统</span><span>></span><span>产品管理</span><span>></span><span>产品回收站</span></div>
  <div class="operating">
    <div class="search f_r">
		<select class="auto" name="search">
			<option value="id">产品ID</option>
			<option value="title">产品名称</option>
			<option value="summary">产品摘要</option>
			<option value="content">产品内容</option>
		</select> 
		<input class="small" name="keywords" type="text" value="" />
		<button class="btn" type="submit" onclick="doSearch();">
			<span class="sch">搜 索</span>
		</button>
	</div>
	<div class="operating"> 
		<a href="javascript:;" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a> 
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="dumpAll"><button class="operating_btn" type="button"><span class="delete">彻底删除</span></button></a> 
		<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a> 
	</div>
  </div>
  <div class="searchbar">
      <input type='hidden' name='controller' value='goods' />
      <input type='hidden' name='action' value='goods_list' />
      <select class="auto" name="term_id">
        <option value="">选择分类</option>
        <?php if(!empty($product_term)){
					foreach($product_term as $item){?>
					<?php if(!empty($item['sunTerm'])){
						foreach($item['sunTerm'] as $sunItem){?>
							<option value="<?=$sunItem['id']?>" style="color:#ff6600;"><?=$sunItem['name']?></option>
							<?php if(!empty($sunItem['grandson'])){?>
								<?php foreach($sunItem['grandson'] as $son_key=>$son){?>
							<option value="<?=$son['id']?>"> &nbsp;&nbsp;&nbsp;&nbsp;<?=$son['name']?></option>
								<?php }?>
							<?php }?>
				<?php }}}}?>
      </select>
      <select class="auto" name="is_commend">
        <option value="">选择推荐</option>
        <option value="0" >未推荐</option>
        <option value="1" >已推荐</option>
      </select>
      <select class="auto" name="is_issue">
        <option value="">选择发布</option>
        <option value="0" >未发布</option>
        <option value="1" >已发布</option>
      </select>
      <input class="small" name="startTime" id="startTime" style="width:120px;" type="text" value="" onfocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2011-11-11 11:11:11',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
	  <input class="small" name="endTime" id="endTime" style="width:120px;" type="text" value="" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'startTime\')}',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
	  <button class="btn" type="submit" onclick="doSelect();">
		<span class="sel">筛 选</span>
	  </button>
  </div>
</div>
<div class="content">
  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
	<tr class="field">
		<th width="3%">选择</th>
		<th width="5%">编号</th>
		<th width="8%">分组名称</th>
		<th width="20%">产品标题</th>
		<th width="20%">产品摘要</th>
		<th width="12%">管理员</th>
		<th width="5%">推荐</th>
		<th width="5%">发布</th>
		<th width="12%">创建时间</th>
		<th width="5%">操作</th>
	</tr>
	<tr><td colspan="10" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
  </table>
  <div id="pageLists" class="pageLists clearfix hide"></div>
  <input type="hidden" name="currentPage" value="1">
</div>
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->
