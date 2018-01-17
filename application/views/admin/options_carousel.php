<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_link_list.js"></script>
<script type="text/javascript">
	$(function(){
		$("#addLink").toggle(
			function () {
				$(".addLinks").slideDown("slow",function(){
					$(".content").scrollTop($("#list_table").height());
					document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
					$('.addLinks input[name="link_name"]').val('');
					$('.addLinks input[name="link_parent"]').val('');
					$('.addLinks input[name="link_url"]').val('http://www.');
					$('.addLinks input[name="link_rating"]').val('99');
					$('.addLinks textarea[name="link_description"]').val('');
					$(".addLinks input:radio[name='link_target']").each(function(){
						if($(this).val()=='_blank'){
							$(this).attr("checked",true);
						}
					});
					$(".addLinks input:radio[name='link_visible']").each(function(){
						if($(this).val()==1){
							$(this).attr("checked",true);
						}
					});
					$(".seeimg").html('');
				});
				$(this).html('[-]添加该类切图');
				$(".addLinks form").attr('action',baseUrl+lang+'/admin/system/addLink');
			},
			function () {
				$(".addLinks").slideUp("slow");
				$(this).html('[+]添加首页切图');
			}
		);

		$(".addLinkBtn").click(function(){
			var link_name = $('.addLinks input[name="link_name"]'),
				link_url = $('.addLinks input[name="link_url"]'),
				link_rating = $('.addLinks input[name="link_rating"]');
			if(!$.trim(link_name.val())){
				//link_url.css('border','1px #ff0000 solid;');
				alert('切图标题不能为空!');
				link_name.focus();
				return false;
			}
			//if(!validate(link_url.val(),'url')){
			if(!IsURL($.trim(link_url.val()))){
				//link_url.css('border','1px #ff0000 solid;');
				alert('跳转URL地址格式不正确!');
				link_url.focus();
				return false;
			}
			if(link_rating.val()<10 || link_rating.val() && isNaN(link_rating.val())){
				alert('排序等级只能为两位整数!');
				link_rating.focus();
				return false;
			}
		});

		$(".seeimg a#delImg").live('click',function(){
			if(confirm('确定要删除该切图图片！')){
				var imgPath = $(this).attr('src');
				var link_id = $(this).attr('link_id');
				$.post(baseUrl+lang+ "/admin/system/editAdFlexSlider",{act:'delImage',imgPath:imgPath,link_id:link_id}, function(data){
					if (data.done === true) {
						alert('删除成功');
						$(".seeimg").html('');
						$("#"+link_id).find('td').eq(4).html('<font color="#0099ff">无</font>');
					}else if(data.msg){
						alert(data.msg);
						return false;
					}else{
						alert('提交失败，请重试');
						return false;
					}
				},"json");
			}
		});

		$("#list_table a").live('click',function(){
			try{
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'edit':
						$.post(baseUrl+lang+ "/admin/system/editAdFlexSlider",{id:id,act:'get'}, function(data){
							if(data.done===true){
								$('.addLinks input[name="link_name"]').val(data.data.link_name);
								$('.addLinks input[name="link_url"]').val(data.data.link_url);
								$('.addLinks input[name="link_rating"]').val(data.data.link_rating);
								$('.addLinks textarea[name="link_description"]').val(data.data.link_description);
								$(".addLinks input:radio[name='link_target']").each(function(){
									if($(this).val()==data.data.link_target){
										$(this).attr("checked",true);
									}
								});
								$("input:radio[name='link_visible']").each(function(){
									if($(this).val()==data.data.link_visible){
										$(this).attr("checked",true);
									}
								});
								$(".addLinks").slideDown("slow",function(){
									$(".content").scrollTop($("#list_table").height());
								});
								$("#addLink").html('[-]添加首页切图');
								$(".addLinks form").attr('action',baseUrl+lang+'/admin/system/editAdFlexSlider/'+data.data.link_id);
								if(data.data.link_image){
									$(".seeimg").html('<br><a href="'+(site_url+data.data.link_image)+'" target="_blank" title="点击浏览原图"><img src="'+(site_url+data.data.link_image)+'" width="257"/></a>&nbsp;<a href="javascript:;" link_id="'+data.data.link_id+'" src="'+data.data.link_image+'" style="color:#339900;" title="点击【删除】可直接删除该首页切图图片" id="delImg">删除</a>');
								}else{
									$(".seeimg").html('');
								}
							}else if(data.msg){
								alert(data.msg);
								return false;
							}
						},"json");
						break;
					case 'del':
						if(confirm('确定要删除？本操作将直接删除，无法回撤！')){
							$.post(baseUrl+lang+ "/admin/system/delAdFlexSlider",{id:id}, function(data){
								if (data.done === true) {
									alert('提交成功');
									setData($('input[name="currentPage"]').val());
									$(".addLinks").slideUp("slow");
								}else if(data.msg){
									alert(data.msg);
									return false;
								}else{
									alert('提交失败，请重试');
									return false;
								}
							},"json");
						}
						break;
				}
			}catch(e){
				alert(e.message);
			}
		});
		$('select[name="adsPic"]').live('change',function(){
			link_type = $(this).val();
			$('input[name="link_type"]').val(link_type);
			setData(1);
		});
	});
</script>
<div id="" class="index_pic" style="margin-top:10px;width:100%;">
	<div style="margin:10px 0;">
		<font color="#0066ff"><b>请选择切图类型：</b></font>
		<select class="auto" name="adsPic">
			<option value="indexPic" selected>站点首页广告</option>
			<option value="productPic">产品中心广告</option>
			<option value="marketPic">营销服务广告</option>
			<option value="newsPic">新闻资讯广告</option>
			<option value="companyPic">关于公司广告</option>
			<option value="contactPic">联系我们广告</option>
			<option value="jobPic">招贤纳士广告</option>
		</select>
	</div>
	<table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="3%">选择</th>
			<th width="3%">编号</th>
			<th width="10%">切图标题</th>
			<th width="15%">跳转URL地址</th>
			<th width="5%">图片</th>
			<th width="5%">打开方式</th>
			<th width="5%">激活</th>
			<th width="5%">排序</th>
			<th width="20%">更多描述</th>
			<th width="11%">管理员</th>
			<th width="12%">日期时间</th>
			<th width="6%">操作</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
	</table>
	<div id="pageLists" class="pageLists clearfix hide"></div>
	<div style="width:100%;" >
		<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
			<tr class="field">
				<td colspan="3" align="left"><a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" id="addLink">[+]添加该类切图</a><!-- <font color="#ff0000" style="float:left;"> &nbsp; &nbsp;请注意类型的选择</font><font color="#0066ff" style="float:left;"> &nbsp; &nbsp;若该类没图片，前台将直接继承站点首页广告</font> --> &nbsp; &nbsp;最多显示5张</td>
			</tr>
		</table>
		<div class="addLinks hide">
			<form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/addLink" enctype="multipart/form-data" target="ajaxifr">
				<table class="list_table link_list_table field" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height:30px;margin-bottom:50px;">
					<tr>
						<td style="text-align:right;" width="10%"><span>*</span>切图标题：</td><td align="left" width="25%"><input type="text" class="txt" name="link_name"></td><td width="65%" align="left" style="text-align:left;"><span style="color:#999;">例如：四叶草工作室。</span></td>
					</tr>
					<tr>
						<td style="text-align:right;"><span>*</span>跳转URL地址：</td><td align="left"><input type="text" class="txt" name="link_url" value="http://www." ></td><td align="left" ><span style="color:#999;">例如：<a href="http://www.shoppinghao.com">http://www.shoppinghao.com</a> —— 不要忘了 http://</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">选择图片：</td><td align="left"><input type="file" name="link_image" id="link_image"><span class="seeimg"></span></td><td align="left"><span style="color:#999;">上传图片规格：<font color="#339900">width:780px;height:250px;</font></span></td>
					</tr>
					<tr>
						<td style="text-align:right;">打开方式：</td>
						<td align="left" class="link_target">
							<ul>
								<li><label><input type="radio" name="link_target" value="_blank"> _blank — 新窗口或新标签。</label></li>
								<li><label><input type="radio" name="link_target" value="_top"> _top — 不包含框架的当前窗口或标签。</label></li>
								<li><label><input type="radio" name="link_target" value="_parent" checked> _parent — 同一窗口或标签。</label></li>
							</ul>
						</td>
						<td align="left"><span style="color:#999;">为您的链接选择目标框架(打开方式)。</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">是否激活：</td><td align="left"><label><input type="radio" name="link_visible" value="1" checked>是</label>&nbsp;&nbsp;<label><input type="radio" name="link_visible" value="0">否</label></td><td align="left"><span style="color:#999;">选择“是”才会显示在页面</span></td>
					</tr>
					<tr>
						<td style="text-align:right;">排序等级：</td><td align="left"><input type="text" class="txt" name="link_rating" maxlength="2" value="99" pattern='^\d{1,2}$'></td><td align="left" ><span style="color:#999;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font></span></td>
					</tr>
					<tr>
						<td style="text-align:right;">更多描述：</td><td align="left"><textarea name="link_description" style="width:250px;height:50px;padding:2px;"></textarea></td><td align="left"><span style="color:#999;">切图的更多描述。</span></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left" colspan="2"><input type="hidden" name="link_type" value="indexPic"><input type="submit" class="addLinkBtn submit cursor" value="提交" onfocus="this.blur();"/></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>