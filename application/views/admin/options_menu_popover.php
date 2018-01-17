<script type="text/javascript">
	$(function(){
		$(".close").live('click',function() {
			isIE()==6?$(".bg").hide():$(".bg").fadeOut("slow");
			if($(".lay").is(':visible')){$(".lay").fadeOut("slow");}
		});
	});
</script>
<div class="menu_lay lay hide" align="center" style="height:350px;margin-top:-175px !important;border-radius: 10px;">
	<table style="margin-top:5px;">
		<tr>
			<td class="layTop">
				<span class="layTitle l">&nbsp;</span>
				<span class="r cursor" id=""><a href="javascript:;" class="closeBtn close"></a></span>
			</td>
		</tr>
		<tr>
			<td align="left">
				<div style="padding:0px;margin:0px;">
					<div style="padding:0px;margin:5px;" class="Comment">
						<!-- 用户评论-->
						<div class="Comment_item menu" style="padding:0px;margin-top:10px;width:500px;">
							<table id="user_info" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 30px;">
								<tr bgcolor="#ddd">
									<td width="20%" bgcolor="#ffffff" align="right" style="padding-right:10px;">父级菜单</td>
									<td width="80%" bgcolor="#ffffff" align="left" style="padding-left:10px;">
										<select name="pid">
											<option value="">- 请选择 -</option>
											<option value="0">顶级菜单</option>
										</select>
									</td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>菜单名称</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input placeholder="菜单名称" type="text" name="title" class="txt" style="width: 365px;"></td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>菜单链接</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input placeholder="菜单链接" type="text" name="link" class="txt" style="width: 365px;"></td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>附加参数</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input placeholder="附加参数" type="text" name="parameter" class="txt" style="width: 365px;"></td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>菜单排序</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input placeholder="菜单排序" type="number" name="sort" class="txt" style="width: 365px;"></td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>打开方式</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
										<select name="link_target">
											<option value="">- 请选择 -</option>
											<option value="_self" selected>当前窗口中打开</option>
											<option value="_blank">新的窗口中打开</option>
										</select>
									</td>
								</tr>
								<tr bgcolor="#ddd">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>是否显示</b></td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
										<label style="width:50px;display:inline;"><input style="width:20px;" type="radio" checked name="show" value="1">是</label> 
			        					<label style="width:50px;display:inline;"><input style="width:20px;" type="radio" name="show" value="0">否</label>
									</td>
								</tr>
								<tr bgcolor="#ddd" id="add_user">
									<td bgcolor="#ffffff" align="right" style="padding-right:10px;">&nbsp;</td>
									<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
								</tr>
							</table>
							<table width="100%" border="0" align="left" id="Btn">
								<tr>
									<td align="center">
									<input type="hidden" name="act" value="add">
									<input type="hidden" name="menu_id" value="">
									<input class="submit" type="button" id="submit_menu" value="保存" onfocus="this.blur();"/>
									<input class="submit close" type="button" id="" value="关闭" onfocus="this.blur();"/>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>