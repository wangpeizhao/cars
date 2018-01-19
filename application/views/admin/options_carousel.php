<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
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

			$(".addLinkBtn").toggle(
				function () {
					$(".addLinks").slideDown("slow",function(){
						$(".content").scrollTop($("#list_table").height());
						document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
						$('input[name="link_name"]').val('');
						$('input[name="link_url"]').val('');
						$('input[name="link_rating"]').val('99');
						$('input[name="link_description"]').val('');
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
					});
					$(".addLinkBtn span").html('[-]添加');
					$(".addLinks form").attr('action',baseUrl + lang +'/admin/blogroll/add');
				},
				function () {
					$(".addLinks").slideUp("slow");
					$(".addLinkBtn span").html('[+]添加');
				}
			);

			$(".submitBtn").click(function(){
				var link_name = $('input[name="link_name"]'),
					link_url = $('input[name="link_url"]'),
					link_rating = $('input[name="link_rating"]');
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
				if(link_rating.val() && isNaN(link_rating.val())){
					alert('排序等级只能为两位整数!');
					link_rating.focus();
					return false;
				}
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
					$('input[name="link_rating"]').val(_data.link_sort);
					$('input[name="link_description"]').val(_data.link_description);
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
					$(".addLinks").slideDown("slow",function(){
						$(".content").scrollTop($("#list_table").height());
					});
					$(".addLinkBtn span").html('[-]添加');
					
					$(".addLinks form").attr('action',baseUrl + lang +"/admin/"+_TYPE_+"/edit/"+_data.id);
					if(_data.link_image){
						$("._link_image .seeimg img").attr('src',site_url+_data.link_image);
						$("._link_image .seeimg img").attr('_src',site_url+_data.link_image);
						// $(".seeimg").html('<br><a href="'+(site_url+_data.link_image)+'" target="_blank" title="点击浏览原图"><img src="'+(site_url+_data.link_image)+'" width="100"/></a>&nbsp;<a href="javascript:;" link_id="'+_data.id+'" src="'+_data.link_image+'" style="color:#339900;" title="点击【删除】可直接删除该图片" id="delImg" class="dn">删除</a>');
					}else{
						$(".seeimg img").attr('src','');
					}
					$('input[name="link_image"]').val('');
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
			document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
			$('input[name="link_name"]').val('');
			$('input[name="link_url"]').val('');
			$('input[name="link_rating"]').val('99');
			$('input[name="link_description"]').val('');
			$('input[name="link_target"]').attr("checked",'_blank');
			$('input[name="link_visible"]').attr("checked",'1');
			if(str==1){
				alert('添加成功');
			}else{
				alert('修改成功');
			}
			$(".addLinks").slideUp("slow",function(){
				$(".addLinkBtn span").html('[+]添加');
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
		$('select[name="link_term"]').val('');
		setData($('input[name="currentPage"]').val());
		$(".content").scrollTop(0);
		$(".addLinks").slideUp("slow");
		$(".addLinkBtn span").html('[+]添加');
	}
//-->
</script>
<div class="headbar">
	<form action="" method="POST" name="_Form_" target="ajaxifr">
		<div class="searchbar" style="margin-top:5px;padding-left: 0px;">
			<select class="auto" name="link_type" style="margin-left:0;">
				<option value="">- 选择分类 -</option>
				<option value="indexPic">站点首页广告</option>
				<option value="productPic">产品中心广告</option>
				<option value="marketPic">营销服务广告</option>
				<option value="newsPic">新闻资讯广告</option>
				<option value="companyPic">关于公司广告</option>
				<option value="contactPic">联系我们广告</option>
				<option value="jobPic">招贤纳士广告</option>
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
			<!-- <th width="10%">分类</th> -->
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
				<td colspan="3" align="left"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" class="addLinkBtn"><span>[+]添加</span></a></td>
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
								<option value="indexPic">站点首页广告</option>
								<option value="productPic">产品中心广告</option>
								<option value="marketPic">营销服务广告</option>
								<option value="newsPic">新闻资讯广告</option>
								<option value="companyPic">关于公司广告</option>
								<option value="contactPic">联系我们广告</option>
								<option value="jobPic">招贤纳士广告</option>
							</select>
						</td>
						<td align="left" width="65%">
							<span style="color:#999;">请选择分类，若需要添加、编辑分类，可请到 <a href="<?=WEB_DOMAIN?>/admin/classify/index">分类管理</a></span>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;"><span>*</span>链接名称：</td>
						<td align="left"><input type="text" class="normal" name="link_name" placeholder="链接名称"></td>
						<td align="left" style="text-align:left;"><span style="color:#999;">例如：四叶草工作室。鼠标滑过时显示</span></td>
					</tr>
					<tr>
						<td style="text-align:right;"><span>*</span>URL地址：</td>
						<td align="left"><input type="text" class="normal" name="link_url" value="" placeholder="http://www.***.com"></td>
						<td align="left" ><span style="color:#999;">例如：<a href="<?=site_url('')?>"><?=site_url('')?></a> —— 不要忘了 http://</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">选择图片：</td>
						<td align="left _link_image">
							<div class="seeimg"><img src="" class="popover"></div>
							<input type="file" name="link_image" id="link_image" class="normal">
						</td>
						<td align="left">
							<span style="color:#999;">上传图片.建议图片为客户网站Logo;</span>
						</td>
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
						<td style="text-align:right;">是否激活：</td>
						<td align="left"><label><input type="radio" name="isHidden" value="0" checked>是</label>&nbsp;&nbsp;<label><input type="radio" name="isHidden" value="1">否</label></td>
						<td align="left"><span style="color:#999;">选择“是”才会显示在页面</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">排序等级：</td>
						<td align="left"><input type="text" class="normal" name="link_sort" maxlength="2" value="99" pattern='^\d{1,2}$' placeholder="设置排序等级，越大越靠前"></td>
						<td align="left" ><span style="color:#999;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font></span></td>
					</tr>
					<tr>
						<td style="text-align:right;">更多描述：</td>
						<td align="left"><textarea name="link_description" style="width:256px;height:50px;" class="normal" placeholder="更多描述"></textarea></td>
						<td align="left"><span style="color:#999;">对客户的更多描述。</span></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left" colspan="2"><input type="submit" class="submitBtn cursor submit" value="提交" onfocus="this.blur();"/></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<?php include('popover.php');?>
<input type="hidden" name="currentPage" value="1">
