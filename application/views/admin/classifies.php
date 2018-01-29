<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
  <?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <script type="text/javascript" src="<?=site_url('')?>/themes/common/js/parsley.js"></script>
  <link rel="stylesheet" href="<?=site_url('')?>/themes/common/css/parsley.css">
  <script type="text/javascript">
  	$(function(){
  // 		$.post(baseUrl+lang+ "/admin/classify/index",{act:'get'}, function(data){
		// 	if (data.code == 1) {
		// 		menu_options = data.result.select_menus;
		// 		$('.menu select[name="pid"] option:gt(1)').remove();
		// 		$('.menu select[name="pid"]').append(menu_options);
		// 		var menus_lists = data.result.menus_lists;
		// 		$('#classifies tbody tr').remove();
		// 		$('#classifies tbody').append(menus_lists);
		// 	}
		// },"json");
  	});

  	function checkForm(){

  	}

	function iResultAlter(str,status){
	    if(status==0){
	        alert(str);
	        return false;
	    }
	    alert('操作成功!');
	    window.location.reload();
	}
  </script>
  <style type="text/css">
	._action a{
		margin-right: 10px!important;
		display: inline-block;
		width:12px;
		height:12px;
	}
	._action a._add{
		background: url('/themes/admin/images/icon_add.gif') no-repeat scroll center center;
	}
	._action a._edit{
		background: url('/themes/admin/images/icon_edit.gif') no-repeat scroll center center;
	}
	._action a._del{
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
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span>分类管理</span><span>></span><span>分类管理</span></div>
      <div class="operating">
		<a href="javascript:;" id="_add" _id="0"><button class="operating_btn" type="button"><span class="sel_all">添加分类</span></button></a>
	  </div>
    </div>
    <div class="content setting_form">
		<table id="classifies" class="list_table" border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;width:100%;">
			<thead>
				<tr class="field">
					<th width="20%" style="text-align: left;padding-left:10px">分类名称</th>
					<th width="10%">URL别名</th>
					<th width="7%">分类排序</th>
					<th width="20%">分类描述</th>
					<th width="10%">文章数量</th>
					<th width="10%">是否激活</th>
					<th width="13%">创建时间</th>
					<th width="10%">操作</th>
				</tr>
			</thead>
			<tbody><?=$menus_lists?></tbody>
		</table>
		<div class="clear"></div>
		<div class="popup_bg">
			<div class="popup_content">
				<form method="post" name="ModelForm" action="" data-parsley-validate="" novalidate target="ajaxifr" onSubmit="return checkForm();">
					<span class="js-none"><i>×</i></span>
					<div class="title">
						<h1>添加分类</h1>
					</div>
				
				
					<div class="form">
						<table class="list_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="line-height: 35px;">
							<tr>
								<th width="20%">父级分类</th>
								<td width="80%">
									<select name="pid" class="normal" required>
										<option value="">- 请选择 -</option>
										<option value="0">顶级菜单</option>
										<?=$select_menus?>
									</select>
								</td>
							</tr>
							<tr>
								<th>分类名称</th>
								<td><input placeholder="分类名称" type="text" name="name" class="normal" style="width: 365px;" required></td>
							</tr>
							<tr>
								<th>URL别名</th>
								<td><input placeholder="URL别名" type="text" name="slug" class="normal" style="width: 365px;" required></td>
							</tr>
							<tr>
								<th>分类描述</th>
								<td><input placeholder="分类描述" type="text" name="description" class="normal" style="width: 365px;"></td>
							</tr>
							<tr>
								<th>分类排序</th>
								<td><input placeholder="分类排序" type="number" name="sort" class="normal" style="width: 365px;"></td>
							</tr>
							<tr>
								<th>是否激活</th>
								<td class="radioIsHidden">
									<label style="width:50px;display:inline;"><input style="width:20px;" type="radio" checked name="isHidden" value="0">是</label> 
		        					<label style="width:50px;display:inline;"><input style="width:20px;" type="radio" name="isHidden" value="1">否</label>
								</td>
							</tr>
						</table>
					</div>

					<div class="btns">
						<table width="100%" border="0" align="center" id="Btn" style="text-align:center;margin-top:5px;">
							<tr>
								<td valign="center">
								<input type="hidden" name="id" value="0">
								<input class="submit" type="submit" id="submit_menu" value="保存" onfocus="this.blur();"/>
								<input class="submit close" type="button" value="关闭" onfocus="this.blur();"/>
								</td>
							</tr>
						</table>
					</div>
				</form>
          		<iframe name="ajaxifr" style="display:none;"></iframe>
			</div>
		</div>
		<script type='text/javascript'>
			$(function(){

		        var width = $(window).width();
        		var height = $(window).height();
        		var _width = 600+40;
        		var _height = 400+40;
        		var left = _width>width?0:((width-_width)/2);
        		var top = _height>height?0:((height-_height)/2);
        		$('.popup_content').css({'left':left,'top':top});

				$('.popup_content .js-none,input:button.close').click(function(){
					$('.popup_bg').fadeOut();
				});

		        $('._add,#_add').live('click',function(){
		        	$('.popup_content h1').text('添加分类');
		        	$('.popup_content form[name="ModelForm"]').attr('action','<?=WEB_DOMAIN?>/admin/classify/add');
		        	var pid = $(this).attr('_id');
		        	$('.popup_content select[name="pid"]').val(pid);
		        	$('.popup_content input[name="name"]').val('');
		        	$('.popup_content input[name="description"]').val('');
		        	$('.popup_content input[name="slug"]').val('');
		        	$('.popup_content input[name="sort"]').val('');
		        	$('.popup_content input[name="id"]').val('0');
		        	setRadio($('.radioIsHidden input:radio'),'0');
		        	$('.popup_bg').fadeIn();
		        });
		        $('._edit').live('click',function(){
		        	$('.popup_content h1').text('编辑分类');
		        	$('.popup_content form[name="ModelForm"]').attr('action','<?=WEB_DOMAIN?>/admin/classify/edit');
		        	var tr = $(this).parent().parent();
		        	var pid = tr.attr('_pid');
		        	var name = tr.attr('_name');
		        	var desc = tr.attr('_desc');
		        	var slug = tr.attr('_slug');
		        	var sort = tr.attr('_sort');
		        	var isHidden = tr.attr('_isHidden');
		        	var parent = tr.attr('_parent');
		        	var id = $(this).attr('_id');
		        	$('.popup_content select[name="pid"]').val(pid);
		        	$('.popup_content input[name="name"]').val(name);
		        	$('.popup_content input[name="description"]').val(desc);

		        	$('.popup_content input[name="slug"]').val(slug);
		        	if(!parseInt(parent)){
		        		$('.popup_content input[name="slug"]').attr('readonly',true);
		        	}else{
		        		$('.popup_content input[name="slug"]').removeAttr('readonly',false);
		        	}

		        	$('.popup_content input[name="sort"]').val(sort);
		        	$('.popup_content input[name="id"]').val(id);
		        	setRadio($('.radioIsHidden input:radio'),isHidden);
		        	$('.popup_bg').fadeIn();
		        });

		        $('._del').live('click',function(){
		        	if(!confirm('确定要删除吗？')){
		        		return false;
		        	}
		        	var id = $(this).attr('_id');
		        	$.post(baseUrl+lang+ "/admin/classify/del",{id:id}, function(data){
		        		if(data.code == '1'){
		        			$('tr#_'+id).remove();
		        			alert('删除成功');
		        			return true;
		        		}
		        		alert(data.msg);
		        		return false;
		        	},'json');
		        });
			});

			function setRadio(obj,val){
				obj.each(function(k,v){
					$(v).attr('checked',$(v).attr('value') == val?'checked':false);
				});
			}
		</script>
    </div>
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->