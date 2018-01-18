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
				<th width="5%">图片？</th>
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
		<div style="width:100%;" >
			<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
				<tr class="field">
					<td colspan="3" align="left"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" class="addLinkBtn"><span>[+]添加友情链接</span></a></td>
				</tr>
			</table>
			<div class="addLinks hide">
				<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/blogroll/add" enctype="multipart/form-data" target="ajaxifr" onSubmit="return _check();">
					<table class="list_table link_list_table field" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height:30px;margin-bottom:50px;">
						<tr>
							<td style="text-align:right;" width="10%"><span>*</span>请选择分类：</td>
							<td align="left" width="25%">
								<select name="link_term" class="normal" style="width:264px;">
									<option value="">-选择分类-</option>
									<?php include('terms.php');?>
								</select>
							</td>
							<td align="left" width="65%">
								<span style="color:#999;">请选择分类，若需要添加、编辑分类，可请到 <a href="<?=WEB_DOMAIN?>/admin/classify/index">分类管理</a></span>
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"><span>*</span>链接名称：</td>
							<td align="left"><input type="text" class="normal" name="link_name"></td>
							<td align="left" style="text-align:left;"><span style="color:#999;">例如：四叶草工作室。鼠标滑过时显示</span></td>
						</tr>
						<tr>
							<td style="text-align:right;"><span>*</span>Web地址：</td><td align="left"><input type="text" class="normal" name="link_url" value="http://www." ></td><td align="left" ><span style="color:#999;">例如：<a href="<?=site_url('')?>"><?=site_url('')?></a> —— 不要忘了 http://</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">选择图片：</td><td align="left"><input type="file" name="link_image" id="link_image" class="normal"><span class="seeimg"></span></td><td align="left"><span style="color:#999;">上传友情链接图片.建议图片为客户网站Logo;</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">打开方式：</td>
							<td align="left" class="link_target">
								<ul>
									<li><label><input type="radio" name="link_target" value="_blank" checked> _blank — 新窗口或新标签。</label></li>
									<!-- <li><label><input type="radio" name="link_target" value="_top"> _top — 不包含框架的当前窗口或标签。</label></li> -->
									<li><label><input type="radio" name="link_target" value="_self"> _self — 同一窗口或标签。</label></li>
								</ul>
							</td>
							<td align="left"><span style="color:#999;">为您的链接选择目标框架(打开方式)。</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">是否激活：</td><td align="left"><label><input type="radio" name="isHidden" value="0" checked>是</label>&nbsp;&nbsp;<label><input type="radio" name="isHidden" value="1">否</label></td><td align="left"><span style="color:#999;">选择“是”才会显示在页面</span></td>
						</tr>
						<tr>
							<td style="text-align:right;">排序等级：</td><td align="left"><input type="text" class="normal" name="link_sort" maxlength="2" value="99" pattern='^\d{1,2}$'></td><td align="left" ><span style="color:#999;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font></span></td>
						</tr>
						<tr>
							<td style="text-align:right;">更多描述：</td><td align="left"><textarea name="link_description" style="width:256px;height:50px;" class="normal"></textarea></td><td align="left"><span style="color:#999;">对客户的更多描述。</span></td>
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
