<!-- 引入头部-->
<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_comments_recycle_list.js"></script>
<script type="text/javascript">
	var _TYPE_ = 'comments';
	$(function(){
		$('select[name="batch"]').change(function(){
			var val = $(this).val();
			var txt = $(this).find('option:selected').text();
			if(!val){
				return false;
			}
			if(!confirm('确定要'+txt+'吗？')){
				$(this).val('');
				return false;
			}
			var idDOM = $("#list_table"+" :checkbox");
			var ids = [];
			for(var i=0;i<idDOM.length;i++){
				if(idDOM[i].checked){
					ids.push(idDOM.eq(i).val());
				}
			}
			$.post(baseUrl + lang + '/admin/'+_TYPE_+'/batch',{val:val,ids:ids}, function(data){
				$('select[name="batch"]').blur();
				if (data.done === true) {
					alert('操作成功');
					setData($('input[name="currentPage"]').val());
					$('.batch :checkbox').attr('checked',false);
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('提交失败，请重试');
					return false;
				}
			},"json");
		});
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
						<option value="username">用户名</option>
						<option value="phone">用户电话</option>
						<option value="email">用户Email</option>
						<option value="user_ip">用户IP</option>
						<option value="declare">留言内容</option>
					</select> 
					<input class="small" name="keywords" type="text" value="" />
					<button class="btn" type="button" onclick="doSearch();">
						<span class="sch">搜 索</span>
					</button>
				</div>
				<div class="operating"> 
					<a href="/admin/comments/index" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a> 
					<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
					<a href="javascript:;" id="dumpAll"><button class="operating_btn" type="button"><span class="delete">彻底删除</span></button></a> 
					<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a>  
				</div>
			</div>
			<div class="searchbar">
				<select class="auto" name="type">
					<option value="">选择类型</option>
					<option value="message" >留言管理</option>
					<option value="comment" >评论管理</option>
				</select>
				<select class="auto" name="is_public">
					<option value="">选择公开</option>
					<option value="0" >未公开</option>
					<option value="1" >已公开</option>
				</select>
				<select class="auto" name="is_shield">
					<option value="">选择屏蔽</option>
					<option value="0" >未屏蔽</option>
					<option value="1" >已屏蔽</option>
				</select>
				<select class="auto" name="replyContent">
					<option value="">选择回复</option>
					<option value="0" >未回复</option>
					<option value="1" >已回复</option>
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
		<table id="list_table" class="list_table settingList" border="0"
			align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee"
			style="line-height: 25px;">
			<tr class="field">
				<th width="3%">选择</th>
				<th width="3%">编号</th>
				<th width="10%">用户名</th>
				<th width="10%">用户电话</th>
				<th width="12%">用户Email</th>
				<th width="8%">用户IP</th>
				<th width="20%">留言内容</th>
				<th width="3%">公开</th>
				<th width="3%">屏蔽</th>
				<th width="13%">管理员回复</th>
				<th width="10%">留言日期时间</th>
				<th width="5%">操作</th>
			</tr>
			<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
		</table>
		<div id="pageLists" class="pageLists clearfix hide"></div>
		<div class="batch" style="text-align:left;margin-top:20px;">
			<label><input type="checkbox" onclick="selectAll('list_table');">全选</label>
			<select class="auto" name="batch" style="margin-left:10px;">
				<option value="">- 批量操作 -</option>
				<option value="1">批量标记推荐</option>
				<option value="2">批量取消推荐</option>
				<option value="3">批量标记发布</option>
				<option value="4">批量取消发布</option>
			</select>
		</div>
	</div>
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->
