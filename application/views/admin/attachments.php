<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_attachments_list.js"></script>
<style type="text/css">
	
</style>
<script type="text/javascript">
<!--
	var _TYPE_ = 'attachments';
	$(function(){
		try{
			$('.b_g2 .n_center .js-none,.b_g,.close').click(function(){
				$('.b_g2').fadeOut();
			});
			$('a.addBtn').click(function(){
				addAttachments();
			});
		}catch(e){
			alert(e.message);
		}
	});

	function click_list_table_a_edit(id){
		try{
			var width = $(window).width();
    		var height = $(window).height();
    		var _width = 600+20;
    		var _height = 200+20;
    		var left = _width>width?0:((width-_width)/2);
    		var top = _height>height?0:((height-_height)/2);
    		$('.b_g2 .n_center').css({'left':left,'top':200});
    		$('.b_g2 .n_center h1').html('编辑附件(重新选择附件将自动覆盖)');
    		var tid = $('._'+id).attr('tid');
    		$('select[name="tid"]').val(tid?tid:'');
    		$('input[name="id"]').val(id);
    		$('input[name="attachments[]"]').removeAttr('multiple').val('');
    		$('form[name="attachmentsForm"]').attr('action','/admin/attachments/edit');
			$('.b_g2').fadeIn();
		}catch(e){
			alert(e.message);
		}
	}

	function addAttachments(){
		try{
			var width = $(window).width();
    		var height = $(window).height();
    		var _width = 600+20;
    		var _height = 200+20;
    		var left = _width>width?0:((width-_width)/2);
    		var top = _height>height?0:((height-_height)/2);
    		$('.b_g2 .n_center').css({'left':left,'top':200});
    		$('.b_g2 .n_center h1').html('添加附件(可选多个)');
    		$('select[name="tid"]').val('');
    		$('input[name="id"]').val(0);
    		$('input[name="attachments[]"]').attr('multiple',true).val('');
    		$('form[name="attachmentsForm"]').attr('action','/admin/attachments/add');
			$('.b_g2').fadeIn();
		}catch(e){
			alert(e.message);
		}
	}

	function _check(){
		if(!$('input[name="attachments[]"]').val()){
			alert('请先选择附件');
			return false;
		}
	}
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
						<option value="file_name"><?=$_title_?>附件现名称</option>
						<option value="file_orig"><?=$_title_?>附件原名称</option>
						<option value="file_ext"><?=$_title_?>附件扩展名</option>
						<option value="file_type"><?=$_title_?>附件类型mime</option>
						<option value="file_size"><?=$_title_?>附件大小</option>
					</select> 
					<input class="small" name="keywords" type="text" value="" />
					<button class="btn" type="button" onclick="doSearch();">
						<span class="sch">搜 索</span>
					</button>
				</div>
				<div class="operating">
					<a href="javascript:;" class="addBtn">
						<button class="operating_btn" type="button">
							<span class="addition">[+]添加<?=$_title_?></span>
						</button>
					</a> 
					<a href="/admin/upload/index" class="addBtn">
						<button class="operating_btn" type="button">
							<span class="addition">[+]添加网络图片</span>
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
				<select class="auto" name="tid">
					<option value="">- 选择分类 -</option>
					<?php include('terms.php');?>
				</select>
				<select class="auto" name="is_image">
					<option value="">- 选择是否为图片 -</option>
					<option value="1" >是</option>
					<option value="0" >否</option>
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
				<th width="5%">分类名称</th>
				<th width="15%">原名称</th>
				<th width="15%">现名称</th>
				<th width="5%">扩展名</th>
				<th width="5%">类型mime</th>
				<th width="8%">附件大小</th>
				<th width="8%">附件</th>
				<th width="10%">是否图片</th>
				<th width="9%">管理员</th>
				<th width="10%">创建时间</th>
				<th width="10%">更新时间</th>
				<th width="5%">操作</th>
			</tr>
			<tr><td colspan="14" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
		</table>
		<div id="pageLists" class="pageLists clearfix hide"></div>
		<div class="clear"></div>

		<div class="b_g2">
			<div class="n_center">
				<span class="js-none"><i>×</i></span>
				<h1>添加附件</h1>
				<form method="post" name="attachmentsForm" action="/admin/attachments/add" enctype="multipart/form-data" novalidate target="ajaxifr" onSubmit="return _check();">
					<table class="list_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="line-height: 35px;">
						<tr>
							<th width="20%">附件分类</th>
							<td width="80%">
								<select name="tid" class="normal" required>
									<option value="">- 请选择 -</option>
									<?php include('terms.php');?>
								</select>
							</td>
						</tr>
						<tr>
							<th>附件</th>
							<td><input type="file" name="attachments[]" class="normal" style="width: 365px;" required><span>*</span></td>
						</tr>
					</table>
					<table width="100%" border="0" align="center" id="Btn" style="text-align:center;margin-top:5px;">
						<tr>
							<td valign="center">
							<input type="hidden" name="id" value="0">
							<input class="submit" type="submit" id="submit_menu" value="提交" onfocus="this.blur();"/>
							<input class="submit close" type="button" value="关闭" onfocus="this.blur();"/>
							</td>
						</tr>
					</table>
				</form>
          		<iframe name="ajaxifr" style="display:none;"></iframe>
			</div>
		</div>
		<style type="text/css">
			
			.b_g2{position: fixed;top: 0;left: 0;bottom: 0;right: 0;z-index: 9999;background: rgba(255,255,255,0.6);display: none;}
			.b_g2 .n_center{
				border-radius: 6px;padding: 20px;margin: auto;position: absolute;background: #fff;text-align: center;    
				-webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
			    box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
			    width: 600px; height: 160px; /*left: 399.5px; top: 136.5px;*/
			}
			.b_g2 .n_center .js-none{position: absolute;right: -5px;;top: -4px;;background: #000;color: #fff;width: 25px;border-radius: 12.5px;margin-top: -10px;margin-right: -7px;cursor: pointer;height:25px;line-height: 25px;}
			.b_g2 .n_center .js-none i{font-size: 15px}

			.b_g2 .n_center table.list_table th{text-align: right;padding-right: 10px;}
			.b_g2 .n_center table.list_table td{text-align: left;padding-left: 10px;}

			.b_g2 .n_center h1{text-align: left;font-size: 16px;margin-bottom: 10px;}

		</style>

		<input type="hidden" name="currentPage" value="1">
	<?php include('popover.php');?>
	<!--/container-->
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->
