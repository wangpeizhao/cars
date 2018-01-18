<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_premier_list.js"></script>
<script type="text/javascript">
	var _TYPE_ = 'premier';
	$(function(){
		
	});
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
						<option value="username"><?=$_title_?>登录名</option>
						<option value="nickname"><?=$_title_?>真实姓名</option>
					</select> 
					<input class="small" name="keywords" type="text" value="" />
					<button class="btn" type="button" onclick="doSearch();">
						<span class="sch">搜 索</span>
					</button>
				</div>
				<div class="operating">
					<a href="javascript:;" id="add">
						<button class="operating_btn" type="button">
							<span class="addition">添加<?=$_title_?></span>
						</button>
					</a> 
					<a href="javascript:;" id="selectAll" status="uncheck">
						<button class="operating_btn" type="button">
							<span class="sel_all">全选</span>
						</button>
					</a> 
					<a href="javascript:;" id="deleteAll">
						<button class="operating_btn" type="button">
							<span class="delete">批量删除</span>
						</button>
					</a> 
					<a href="javascript:;" id="recycle">
						<button class="operating_btn" type="button">
							<span class="recycle">回收站</span>
						</button>
					</a>
				</div>
			</div>
			<div class="searchbar">
				<select class="auto" name="role_id">
					<option value="">- 选择角色 -</option>
					<?php if($roles){
						foreach($roles as $item){?>
						<option value="<?=$item['id']?>"><?=$item['role_name']?></option>
					<?php }
					}?>
				</select>
				<input class="small" name="startTime" id="startTime" style="width:150px;" type="text" value=""/>
				<input class="small" name="endTime" id="endTime" style="width:150px;" type="text" value="<?=_DATETIME_?>"/>
				<button class="btn" type="button" onclick="doSearch();">
					<span class="sel">筛 选</span>
				</button>
				<input type="hidden" name="currentPage" value="1">
				<input type="hidden" name="rows" value="10">
			</div>
		</form>
		<iframe name="_MrParker_" style="display:none;"></iframe>
	</div>
	<div class="content">
		<table id="list_table" class="list_table" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
			<tr class="field">
				<th width="5%">选择</th>
				<th width="5%">ID</th>
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
	</div>
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->
