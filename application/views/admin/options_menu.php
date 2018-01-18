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
</table>
<div class="clear"></div>
<!-- </form> -->
<?php include('options_menu_popover.php');?>