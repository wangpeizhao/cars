<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_menu.js"></script>
<script type="text/javascript">
	$(function(){
		$('input[name="plat"]').click(function(){
			_refresh_menus();
		});
	});
</script>
<style type="text/css">
	.menu_action a{
		margin-right: 10px!important;
		display: inline-block;
		width:12px;
		height:12px;
	}
	.menu_action a.menu_add{
		background: url('/themes/admin/images/icon_add.gif') no-repeat scroll center center;
	}
	.menu_action a.menu_edit{
		background: url('/themes/admin/images/icon_edit.gif') no-repeat scroll center center;
	}
	.menu_action a.menu_del{
		background: url('/themes/admin/images/icon_del.gif') no-repeat scroll center center;
	}
	.mFirst font{
		display: block;
		padding-left: 0;
		text-align: left;
		margin-left: 20px;
	}
	.mSecond font{
		display: block;
		padding-left: 25px;
		text-align: left;
		margin-left: 20px;
	}
	.mThird font{
		display: block;
		padding-left: 50px;
		text-align: left;
		margin-left: 20px;
	}
	.mFourth font{
		display: block;
		padding-left: 75px;
		text-align: left;
		margin-left: 20px;
	}
	.mFifth font{
		display: block;
		padding-left: 100px;
		text-align: left;
		margin-left: 20px;
	}
	.mSixth font{
		display: block;
		padding-left: 125px;
		text-align: left;
		margin-left: 20px;
	}
	.mSeventh font{
		display: block;
		padding-left: 150px;
		text-align: left;
		margin-left: 20px;
	}
	.mEighth font{
		display: block;
		padding-left: 175px;
		text-align: left;
		margin-left: 20px;
	}
	.mNinth font{
		display: block;
		padding-left: 200px;
		text-align: left;
		margin-left: 20px;
	}
	.mTenth font{
		display: block;
		padding-left: 225px;
		text-align: left;
		margin-left: 20px;
	}
</style>
<!-- <form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/addIndexNav" target="ajaxifr"> -->
<table id="list_table_indexNav" class="list_table" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;width:100%;">
	<thead>
		<tr class="field">
			<th width="25%" style="text-align: left;padding-left:10px">菜单名称</th>
			<th width="10%">排序</th>
			<th width="10%">是否显示</th>
			<th width="10%">打开方式</th>
			<th width="25%">菜单链接</th>
			<th width="10%">附加参数</th>
			<th width="10%">操作</th>
		</tr>
	</thead>
	<tbody>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
	</tbody>
</table>
<div class="clear"></div>
<table class="list_table" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;width:100%;">
	<colgroup>
		<col width="15%"></col>
		<col width="5%"></col>
		<col width="20%"></col>
		<col width="30%"></col>
		<col width="14%"></col>
		<col width="8%"></col>
		<col width="8%"></col>
	</colgroup>
	<tr>
		<td align="left" colspan="7">
			<a class="addMenu" style="color:#0099ff;float:left;padding-left:5px;" href="javascript:;">[+]添加菜单</a>
			<label><input type="checkbox" name="plat" value="admin">后台菜单</label>
		</td>
	</tr>
	<tr class="hide addNavTxt">
		<td colspan="2">
			<select name="link_parent">
				<option value="0" selected>-请先选择菜单-</option>
			</select>
		</td>
		<td><input class="normal" type="text" name="link_name" maxlength="20" title="请填写菜单名称" placeholder="请填写菜单名称" value=""/></td>
		<td><input class="normal" type="text" name="link_url" maxlength="255" title="请填写菜单URL地址" placeholder="请填写菜单URL地址" value="http://www."/></td>
		<td>
			<select name="link_target">
				<option value="" selected>-请选择-</option>
				<option value="_self"> 当前窗口中打开</option>
				<option value="_parent"> 父类窗口集中打开</option>
				<option value="_top"> 整个窗口中打开</option>
				<option value="_blank"> 新的窗口中打开</option>
			</select>
		</td>
		<td><input class="small" type="text" name="link_rating" title="请填写菜单排序" maxlength="2" value="99"/></td>
		<td>-</td>
	</tr>
	<tr class="hide even addNavBtn">	
		<td align="left" class="Btn" colspan="7"><input type="hidden" name="link_id">
			<input type="button" style="padding:0 10px;margin-bottom:10px;border:0px;height: 29px;" onfocus="this.blur();" value=" 添加菜单信息 " class="submit cursor l doAddNav">
			<span style="color:#999;display:block;float:left;text-align:left;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font><br>_blank：新窗口或新标签。_top：不包含框架的当前窗口或标签。_parent：同一窗口或标签。</span>
		</td>
	</tr>
</table>
<div class="clear"></div>
<!-- </form> -->
<?php include('options_menu_popover.php');?>