<!-- 引入头部-->
<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_news_recycle_list.js"></script>
<script type="text/javascript">
	var _TYPE_ = 'news';
</script>
<div id="admin_right">
<div class="headbar">
	<form action="" method="POST" name="_Form_" target="_MrParker_">
	  <div class="position"><span>系统</span><span>></span><span><?=$_title_?></span><span>></span><span>回收站</span></div>
	  <div class="operating">
	    <div class="search f_r">
			<select class="auto" name="search">
				<option value="id"><?=$_title_?>ID</option>
				<option value="title"><?=$_title_?>名称</option>
				<option value="summary"><?=$_title_?>摘要</option>
				<option value="content"><?=$_title_?>内容</option>
			</select> 
			<input class="small" name="keywords" type="text" value="" />
			<button class="btn" type="submit" onclick="doSearch();">
				<span class="sch">搜 索</span>
			</button>
		</div>
		<div class="operating"> 
			<a href="/admin/news/index" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a> 
			<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
			<a href="javascript:;" id="dumpAll"><button class="operating_btn" type="button"><span class="delete">彻底删除</span></button></a> 
			<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a> 
		</div>
	  </div>
	  <div class="searchbar">
	      <input type='hidden' name='controller' value='goods' />
	      <input type='hidden' name='action' value='goods_list' />
	      <select class="auto" name="term_id">
	        <option value="">- 选择分类 -</option>
	        <?php include('terms.php');?>
	      </select>
	      <select class="auto" name="is_commend">
	        <option value="">- 选择推荐 -</option>
	        <option value="0" >未推荐</option>
	        <option value="1" >已推荐</option>
	      </select>
	      <select class="auto" name="is_issue">
	        <option value="">- 选择发布 -</option>
	        <option value="0" >未发布</option>
	        <option value="1" >已发布</option>
	      </select>
			<input class="small" name="startTime" id="startTime" style="width:150px;" type="text" value=""/>
			<input class="small" name="endTime" id="endTime" style="width:150px;" type="text" value="<?=_DATETIME_?>"/>
			<button class="btn" type="button" onclick="doSearch();">
				<span class="sel">筛 选</span>
			</button>
	  </div>
	</form>
	<iframe name="_MrParker_" style="display:none;"></iframe>
</div>
<div class="content">
  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
	<tr class="field">
		<th width="3%">选择</th>
		<th width="5%">ID</th>
		<th width="8%">分类</th>
		<th width="20%">标题</th>
		<th width="15%">摘要</th>
		<th width="12%">管理员</th>
		<th width="5%">来源</th>
		<th width="5%">作者</th>
		<th width="5%">推荐</th>
		<th width="5%">发布</th>
		<th width="12%">创建时间</th>
		<th width="5%">操作</th>
	</tr>
	<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
  </table>
  <div id="pageLists" class="pageLists clearfix hide"></div>
  <input type="hidden" name="currentPage" value="1">
</div>
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->
