<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_blogroll_recycle_list.js"></script>
<style type="text/css">
	.seeimg{max-height:50px;overflow: hidden;}
	.seeimg img{width:72px;cursor: pointer;}
</style>
<script type="text/javascript">
<!--
	var _TYPE_ = 'blogroll';
//-->
</script>
  <div id="admin_right">
	<div class="headbar">
		<form action="" method="POST" name="_Form_" target="_MrParker_">
			<div class="position">
				<span>系统</span><span>></span><span><?=$_title_?></span><span>
			</div>
			<div class="operating">
				<div class="search f_r">
					<select class="auto" name="search">
						<option value="id"><?=$_title_?>ID</option>
						<option value="link_name"><?=$_title_?>链接名称</option>
						<option value="link_url"><?=$_title_?>链接地址</option>
						<option value="link_description"><?=$_title_?>链接描述</option>
					</select> 
					<input class="small" name="keywords" type="text" value="" />
					<button class="btn" type="button" onclick="doSearch();">
						<span class="sch">搜 索</span>
					</button>
				</div>
				<div class="operating"> 
					<a href="/admin/blogroll/index" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a> 
					<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
					<a href="javascript:;" id="dumpAll"><button class="operating_btn" type="button"><span class="delete">彻底删除</span></button></a> 
					<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a>  
				</div>
			</div>
			<div class="searchbar">
				<select class="auto" name="link_term">
					<option value="">- 选择分类 -</option>
					<?php include('terms.php');?>
				</select>
				<select class="auto" name="link_target">
					<option value="">选择链接打开方式</option>
					<option value="_blank" >新的窗口(_blank)</option>
					<option value="_self" >当前窗口(_self)</option>
				</select>
				<input class="small" name="startTime" id="startTime" style="width:150px;" type="text" value="" placeholder="更新时间(开始)"/>
				<input class="small" name="endTime" id="endTime" style="width:150px;" type="text" value="<?=_DATETIME_?>" placeholder="更新时间(结束)"/>
				<button class="btn" type="button" onclick="doSearch();">
					<span class="sel">筛 选</span>
				</button>
				<input type="reset" vaule="重置" class="reset btn">
				<input type="hidden" name="currentPage" value="1">
				<input type="hidden" name="rows" value="10">
				<input type="hidden" name="link_type" value="link">
			</div>
		</form>
		<iframe name="_MrParker_" style="display:none;"></iframe>
	</div>
    <div class="content link_target" align="left">
		<table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
			<tr class="field">
				<th width="3%">选择</th>
				<th width="3%">ID</th>
				<!-- <th width="10%">友情链接分类</th> -->
				<th width="10%">友情链接名称</th>
				<th width="15%">Web地址</th>
				<th width="5%">图片</th>
				<th width="5%">打开方式</th>
				<!-- <th width="5%">是否激活</th> -->
				<th width="5%">排序等级</th>
				<th width="15%">更多描述</th>
				<th width="9%">管理员</th>
				<th width="10%">更新时间</th>
				<th width="5%">操作</th>
			</tr>
			<tr><td colspan="11" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
		</table>
		<div id="pageLists" class="pageLists clearfix hide"></div>
		<?php include('popover.php');?>
		<input type="hidden" name="currentPage" value="1">
	<!--/container-->
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->
