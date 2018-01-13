<!-- 引入头部-->
<?php include('header.php');?>
<!-- /引入头部-->
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_newsInfo_list.js"></script>
<script type="text/javascript">
	var _TYPE_ = 'news';
</script>
<div id="admin_right">
	<div class="headbar">
		<div class="position">
			<span>系统</span><span>></span><span>新闻资讯</span><span>
		</div>
		<div class="operating">
			<div class="search f_r">
				<select class="auto" name="search">
					<option value="id">新闻资讯ID</option>
					<option value="title">新闻资讯名称</option>
					<option value="summary">新闻资讯摘要</option>
					<option value="content">新闻资讯内容</option>
				</select> 
				<input class="small" name="keywords" type="text" value="" />
				<button class="btn" type="submit" onclick="doSearch();">
					<span class="sch">搜 索</span>
				</button>
			</div>
			<div class="operating">
				<a href="javascript:;" id="add">
					<button class="operating_btn" type="button">
						<span class="addition">添加新闻资讯</span>
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
			<select class="auto" name="term_id">
				<option value="">选择分类</option>
				<?php if(!empty($terms)){
					foreach($terms as $item){?>
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
			<input class="small" name="startTime" id="startTime" style="width:150px;" type="text" value="" onfocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2011-11-11 11:11:11',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
			<input class="small" name="endTime" id="endTime" style="width:150px;" type="text" value="<?=date("Y-m-d H:i:s")?>" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'startTime\')}',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
			<button class="btn" type="submit" onclick="doSelect();">
				<span class="sel">筛 选</span>
			</button>
		</div>
	</div>
	<div class="content">
		<table id="list_table" class="list_table settingList" border="0"
			align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee"
			style="line-height: 25px;">
			<tr class="field">
				<th width="3%">选择</th>
				<th width="5%">编号</th>
				<th width="8%">分组名称</th>
				<th width="20%">新闻资讯标题</th>
				<th width="15%">新闻资讯摘要</th>
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
