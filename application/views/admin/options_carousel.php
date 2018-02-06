<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_add_edit.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_lists.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_blogroll_list.js"></script>
<style type="text/css">
	.seeimg{max-height:50px;overflow: hidden;}
	.seeimg img{width:72px;cursor: pointer;}
</style>
<script type="text/javascript">
<!--
	var _TYPE_ = 'blogroll';
	$(function(){
		try{

			$(".addLinkBtn").click(function(){
				// document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
				$('select[name="link_term"]').val('');
				$('input[name="link_name"]').val('');
				$('input[name="link_url"]').val('');
				$('input[name="link_sort"]').val('99');
				$('textarea[name="link_description"]').val('');
				$("input:radio[name='link_target']").each(function(){
					if($(this).val()=='_blank'){
						$(this).attr("checked",true);
					}
				});
				$("input:radio[name='link_visible']").each(function(){
					if($(this).val()==1){
						$(this).attr("checked",true);
					}
				});
				$(".seeimg img").html('src','');
				$(".addLinkBtn span").html('添加');
				$(".popup_content form").attr('action',baseUrl + lang +'/admin/blogroll/add');
				$(".thumbImage img").attr('src','');
				$(".thumbImage img").attr('_src','');
				$('.thumbImage a.del').hide();
				$('.popup_bg').fadeIn();
			});

			$(".submitBtn").click(function(){
				var link_name = $('input[name="link_name"]'),
					link_url = $('input[name="link_url"]'),
					link_sort = $('input[name="link_sort"]');
				if(!$.trim(link_name.val())){
					link_name.css('border','1px #ff0000 solid;');
					alert('名称不能为空!');
					link_name.focus();
					return false;
				}
				// if(!validate(link_url.val(),'url')){
				// 	link_url.css('border','1px #ff0000 solid;');
				// 	alert('URL地址格式不正确!');
				// 	link_url.focus();
				// 	return false;
				// }
				// if(link_sort.val() && isNaN(link_sort.val())){
				// 	alert('排序等级只能为两位整数!');
				// 	link_sort.focus();
				// 	return false;
				// }
			});
		}catch(e){
			alert(e.message);
		}
	});

	function click_list_table_a_edit(id){
		try{
			$.post(baseUrl + lang + "/admin/"+_TYPE_+"/edit",{id:id,act:'get'}, function(data){
				if(data.code=='1'){
					var _data = data.result;
					$('input[name="link_name"]').val(_data.link_name);
					$('input[name="link_url"]').val(_data.link_url);
					$('input[name="link_sort"]').val(_data.link_sort);
					$('textarea[name="link_description"]').val(_data.link_description);
					$('select[name="link_term"]').val(_data.link_term);
					$("input:radio[name='link_target']").each(function(){
						if($(this).val()==_data.link_target){
							$(this).attr("checked",true);
						}
					});
					$("input:radio[name='isHidden']").each(function(){
						if($(this).val()==_data.link_visible){
							$(this).attr("checked",true);
						}
					});
					// $(".addLinks").slideDown("slow",function(){
					// 	$(".content").scrollTop($("#list_table").height());
					// });
					$(".popup_content .title h1").html('编辑');
					
					$(".popup_content form").attr('action',baseUrl + lang +"/admin/"+_TYPE_+"/edit/"+_data.id);
					if(_data.link_image){
						$(".thumbImage img").attr('src',site_url+_data.link_image_thumb);
						$(".thumbImage img").attr('_src',site_url+_data.link_image);
						$('input[name="thumb"]').val(_data.link_image);
						$('.thumbImage a.del').fadeIn();
						// $(".seeimg").html('<br><a href="'+(site_url+_data.link_image)+'" target="_blank" title="点击浏览原图"><img src="'+(site_url+_data.link_image)+'" width="100"/></a>&nbsp;<a href="javascript:;" link_id="'+_data.id+'" src="'+_data.link_image+'" style="color:#339900;" title="点击【删除】可直接删除该图片" id="delImg" class="dn">删除</a>');
					}else{
						$(".thumbImage img").attr('src','');
						$(".thumbImage img").attr('_src','');
					}
					// $('input[name="link_image"]').val('');
					$('.popup_bg').fadeIn();
				}else if(data.msg){
					alert(data.msg);
					return false;
				}
			},"json");
		}catch(e){
			alert(e.message);
		}
	}

	function click_list_table_a_del(id){
		try{
			if(!confirm('确定要彻底删除'+(_TITLE_?_TITLE_:'信息')+'吗？该操作不能回撤！')){
				return false;
			}
			$.post(baseUrl + lang + "/admin/"+_TYPE_+"/dump",{id:id}, function(data){
				if (data.done === true) {
					alert('删除成功');
					setData($('input[name="currentPage"]').val());
					$(".addLinks").slideUp("slow");
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('提删除失败，请重试');
					return false;
				}
			},"json");
		}catch(e){
			alert(e.message);
		}
	}

	function fileResult(str){
		if(str==1 || str==2){
			// document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
			$('input[name="link_name"]').val('');
			$('input[name="link_url"]').val('');
			$('input[name="link_sort"]').val('99');
			$('textarea[name="link_description"]').val('');
			$('input[name="link_target"]').attr("checked",'_blank');
			$('input[name="link_visible"]').attr("checked",'1');
			if(str==1){
				alert('添加成功');
			}else{
				alert('修改成功');
			}
			$(".addLinks").slideUp("slow",function(){
				$(".popup_content .title h1").html('[+]添加');
				setData($('input[name="currentPage"]').val());
			});
		}else{
			alert(str);
			return false;
		}
	}

	function _check(){
		// var link_url = $('input[name="link_url"]');
		// if(!IsURL($.trim(link_url.val()))){
		// 	alert('跳转URL地址格式不正确!');
		// 	link_url.focus();
		// 	return false;
		// }
	}

	function setDataAsync(){
		// $('select[name="link_term"]').val('');
		setData($('input[name="currentPage"]').val());
		// $(".content").scrollTop(0);
		// $(".addLinks").slideUp("slow");
		// $(".addLinkBtn span").html('添加');
		$('.popup_bg').fadeOut();
	}
//-->
</script>
<div class="headbar">
	<form action="" method="POST" name="_Form_" target="ajaxifr">
		<div class="searchbar" style="margin-top:5px;padding-left: 0px;">
			<a href="javascript:;" style="color:#0099ff;float:left;margin: 3px 10px 0 10px;" class="addLinkBtn"><span>添加</span></a>
			<select class="auto" name="link_term" style="margin-left:0;">
				<option value="">- 选择分类 -</option>
				<?php include('terms.php');?>
			</select>
			<select class="auto" name="link_target">
				<option value="">选择链接打开方式</option>
				<option value="_blank" >新的窗口(_blank)</option>
				<option value="_self" >当前窗口(_self)</option>
			</select>
			<input class="small" name="startTime" id="startTime" style="width:150px;" type="text" value="" placeholder="更新时间(开始)"/>
			<input class="small" name="endTime" id="endTime" style="width:150px;" type="text" value="" placeholder="更新时间(结束)"/>
			<button class="btn" type="button" onclick="doSearch();">
				<span class="sel">筛 选</span>
			</button>
			<input type="reset" vaule="重置" class="reset btn">
			<input type="hidden" name="currentPage" value="1">
			<input type="hidden" name="rows" value="10">
		</div>
	</form>
</div>
<div class="content link_target" align="left">
	<table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="3%">选择</th>
			<th width="3%">ID</th>
			<th width="10%">分类</th>
			<th width="10%">名称</th>
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
	<div style="width:100%;" >
		<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
			<tr class="field">
				<td colspan="3" align="left"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" class="addLinkBtn"><span>添加</span></a></td>
			</tr>
		</table>
	</div>
</div>
<div class="clear"></div>
<div class="popup_bg">
	<div class="popup_content">
		<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/blogroll/add" enctype="multipart/form-data" target="ajaxifr" onSubmit="return _check();">
			<span class="js-none"><i>×</i></span>
			<div class="title">
				<h1>添加</h1>
			</div>		
			<div class="form">
				<table class="list_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="">
					<tr>
						<td style="text-align:right;" width="10%"><span>*</span>分类：</td>
						<td align="left" width="35%">
							<select name="link_term" class="normal" style="width:264px;">
								<option value="">-选择分类-</option>
								<?php include('terms.php');?>
							</select>
						</td>
						<td align="left" width="45%">
							<span style="color:#999;">若需要添加、编辑分类，可请到 <a href="<?=WEB_DOMAIN?>/admin/classify/index" target="_blank">分类管理</a></span>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;"><span>*</span>名称：</td>
						<td align="left"><input type="text" class="normal" name="link_name" placeholder="名称"></td>
						<td align="left" style="text-align:left;"><span style="color:#999;">例如：四叶草工作室。鼠标滑过时显示</span></td>
					</tr>
					<tr>
						<td style="text-align:right;"><span>*</span>URL：</td>
						<td align="left"><input type="text" class="normal" name="link_url" value="" placeholder="完整 OR 相对URL"></td>
						<td align="left" ><span style="color:#999;">例如：<a href="<?=site_url('')?>"><?=site_url('')?></a></span></td>
					</tr>
					<tr style="height:80px;">
						<td style="text-align:right;">选择图片：</td>
						<td class="f chooseImage">
	                      <div class="thumbImage">
	                        <a style="margin:0 0 3px 1px;" target="_blank" class="thumb" href="javascript:;">
	                          <img class="popover" 
	                          _src="/themes/admin/images/tv-expandable.gif" 
	                          src="/themes/admin/images/tv-expandable.gif">
	                        </a>
	                        <a href="javascript:;" class="del" title="删除"></a>
	                      </div>
	                      <div class="clear"></div>
	                      <input type="hidden" name="thumb" value="">
	                      <a href="javascript:;" class="choose">选择缩略图</a> <span>仅支持格式：jpg、jpeg、gif和png！</span>
	                      <!-- 引入图片弹出层-->
	                      <?php include('popover.php');?>
	                      <!-- /引入图片弹出层-->
	                    </td>
						<td align="left">
							<span style="color:#999;">上传图片</span>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">打开方式：</td>
						<td align="left" class="link_target">
							<label><input type="radio" name="link_target" value="_blank" checked> _blank(新窗口)</label>
							<label><input type="radio" name="link_target" value="_self"> _self(本窗口)</label>
						</td>
						<td align="left"><span style="color:#999;">打开方式</span></td>
					</tr>
					<tr class="dn">
						<td style="text-align:right;">是否激活：</td>
						<td align="left"><label><input type="radio" name="isHidden" value="0" checked>是</label>&nbsp;&nbsp;<label><input type="radio" name="isHidden" value="1">否</label></td>
						<td align="left"><span style="color:#999;">选择“是”才会显示在页面</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">排序等级：</td>
						<td align="left"><input type="number" class="normal" name="link_sort" value="99" pattern='^\d{1,2}$' placeholder="设置排序等级，越大越靠前"></td>
						<td align="left" ><span style="color:#999;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font></span></td>
					</tr>
					<tr>
						<td style="text-align:right;">更多描述：</td>
						<td align="left"><textarea name="link_description" style="width:256px;height:50px;" class="normal" placeholder="更多描述"></textarea></td>
						<td align="left"><span style="color:#999;">更多描述。</span></td>
					</tr>
					<!-- <tr>
						<td>&nbsp;</td>
						<td align="left" colspan="2">
							<input type="submit" class="submitBtn cursor submit" value="提交" onfocus="this.blur();"/>
							<input class="submit close" type="button" value="关闭" onfocus="this.blur();"/>
						</td>
					</tr> -->
				</table>
			</div>
			<div class="btns">
				<table width="100%" border="0" align="center" id="Btn" style="text-align:center;margin-top:5px;">
					<tr>
						<td valign="center">
						<input type="hidden" name="id" value="0">
						<input type="submit" class="submitBtn cursor submit" value="提交" onfocus="this.blur();"/>
						<input class="submit close" type="button" value="关闭" onfocus="this.blur();"/>
						</td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</div>
<style type="text/css">
	.popup_content{
		width: 800px!important;
		height:450px!important;
	}
</style>
<script type='text/javascript'>
	$(function(){

        var width = $(window).width();
		var height = $(window).height();
		var _width = 800+40;
		var _height = 500+40;
		var left = _width>width?0:((width-_width)/2);
		var top = _height>height?0:((height-_height)/2);
		$('.popup_content').css({'left':left,'top':top});

		$('.popup_content .js-none,input:button.close').click(function(){
			$('.popup_bg').fadeOut();
		});
	});

	function setRadio(obj,val){
		obj.each(function(k,v){
			$(v).attr('checked',$(v).attr('value') == val?'checked':false);
		});
	}
</script>
<?php include('popover.php');?>
<input type="hidden" name="currentPage" value="1">
