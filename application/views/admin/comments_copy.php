<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-留言管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/form.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_comments_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10,
		condition = [],
		click = false;
	$(function(){
		try{
			setData(1);

			$("a").live('click',function(){
				 this.blur();
			});

			$("#close").live('click',function() {
				click = false;
				isIE()==6?$(".bg").hide():$(".bg").fadeOut("slow");
				if($(".layComment").is(':visible')){$(".layComment").fadeOut("slow");}
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'edit':
						showComment(id);
						break;
					case 'del':
						if(confirm('是否把信息放到回收站内？')){
							delComment(id);
						}
						break;
				}
			});

			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			//批量删除
			$("#deleteAll").click(function(){
				if(deleteAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var ids = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							ids.push(idDOM.eq(i).val());
						}
					}
					delComment(ids);
				}
			});

			//回收站
			$("#TfileRecycle").click(function(){
				event_link(baseUrl + lang +'/admin/system/commentRecycleList');
			});

			$("#submit_commentInfo").click(function(){
				if(click){
					alert('正在提交，请稍等...');
					return false;
				}
				click = true;
				var cid = $('input[name="cid"]').val();
				var is_shield = $('input[name="is_shield"]:checked').val();
				var replyContent = $('textarea[name="replyContent"]').val();
				$.post(baseUrl + lang + "/admin/system/replyComment",{id:cid,act:'reply',is_shield:is_shield,replyContent:replyContent}, function(data){
					if(data.done===true){
						click = false;
						// alert('提交成功,系统已向用户发送邮件');
						alert('提交成功');
						setData($('input[name="currentPage"]').val());
						isIE()==6?$(".bg,.layComment").hide():$(".bg,.layComment").fadeOut("slow");
					}else if(data.msg){
						alert(data.msg);
						return false;
					}else{
						alert('提交失败,请重试');
						return false;
					}
				},"json");
			});

		}catch(e){
			alert(e.message);
		}
	});

	function delComment(id){
		try{
			$.post(baseUrl + lang + "/admin/system/delComment",{id:id}, function(data){
				if (data.done === true) {
					alert('已放入回收站');
					setData($('input[name="currentPage"]').val());
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('提交失败，请重试');
					return false;
				}
			},"json");
		}catch(e){
			alert(e.message);
		}
	}

	function showComment(id){
		try{
			$.post(baseUrl + lang + "/admin/system/replyComment",{id:id,act:'get'}, function(data){
				$('input[name="id"]').val(data.data.id);
				$('input[name="username"]').val(data.data.username);
				$('input[name="phone"]').val(data.data.phone);
				$('input[name="email"]').val(data.data.email);
				$('input[name="user_ip"]').val(data.data.user_ip);
				$('input[name="create_time"]').val(data.data.create_time);
				$('input[name="is_public"]').eq(data.data.is_public).attr("checked",true);
				$('input[name="is_shield"]').eq(data.data.is_shield).attr("checked",true);
				$('textarea[name="replyContent"]').val(data.data.replyContent);
				$('textarea[name="declare"]').val(data.data.declare);
				$('input[name="cid"]').val(data.data.id);

				isIE()==6?$(".bg,.layComment").show():$(".bg,.layComment").fadeIn("slow");
			},"json");
		}catch(e){
			alert(e.message);
		}
	}
	
	//搜索
	function doSearch(){
		try{
			var type = $('select[name="search"]').val(),
				keywords = $('input[name="keywords"]'),
				isError = false;
			if(type=='phone'){
				keywordsVal = keywords.val();
				if(!validate(keywordsVal,'phone')){
					keywords.val('');
					keywords.focus();
					keywords.val(keywordsVal);
					keywords.css('border','1px #FFABAB solid');
					alert('电话号码格式不正确');
					return isError;
				}
			}
			if(type=='email'){
				keywordsVal = keywords.val();
				if(!validate(keywordsVal,'email')){
					keywords.val('');
					keywords.focus();
					keywords.val(keywordsVal);
					keywords.css('border','1px #FFABAB solid');
					alert('Emial格式不正确');
					return isError;
				}
			}
			if(type=='user_ip'){
				keywordsVal = keywords.val();
				if(!validate(keywordsVal,'ip')){
					keywords.val('');
					keywords.focus();
					keywords.val(keywordsVal);
					keywords.css('border','1px #FFABAB solid');
					alert('Ip地址格式不正确');
					return isError;
				}
			}
			keywords.css('border','1px #d7d7d7 solid');
			condition = [];
			condition.push({"type":type,'keywords':keywords.val()});
			setData(1);
		}catch(e){
			alert(e.message);
		}
	}

	//筛选
	function doSelect(){
		condition = [];
		condition.push({
			"type":$('select[name="type"]').val(),
			//"is_public":$('select[name="is_public"]').val(),
			"is_shield":$('select[name="is_shield"]').val(),
			"replyContent":$('select[name="replyContent"]').val(),
			"startTime":$('input[name="startTime"]').val(),
			"endTime":$('input[name="endTime"]').val()
		});
		setData(1);
	}
//-->
</script>
</head>
<body>
<div class="bg hide"></div>
<div class="container">
  <!-- 引入头部-->
	<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
	<?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <div id="admin_right">
    <div class="headbar">
		<div class="position">
			<span>系统</span><span>></span><span>留言管理</span><span>></span><span>留言</span>
		</div>
		<div class="operating">
			<div class="search f_r">
				<select class="auto" name="search">
					<option value="username">用户名</option>
					<option value="phone">用户电话</option>
					<option value="email">用户Email</option>
					<option value="user_ip">用户IP</option>
					<option value="declare">留言内容</option>
				</select> 
				<input class="small" name="keywords" type="text" value="" />
				<button class="btn" type="submit" onclick="doSearch();">
					<span class="sch">搜 索</span>
				</button>
			</div>
			<div class="operating">
				<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
				<a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
				<a href="javascript:;" id="TfileRecycle"><button class="operating_btn" type="button"><span class="recycle">回收站</span></button></a> 
			</div>
		</div>
		<div class="searchbar">
			<select class="auto" name="type">
				<option value="">选择类型</option>
				<option value="message" >留言管理</option>
				<option value="comment" >评论管理</option>
			</select>
			<select class="auto" name="is_public">
				<option value="">选择公开</option>
				<option value="0" >未公开</option>
				<option value="1" >已公开</option>
			</select>
			<select class="auto" name="is_shield">
				<option value="">选择屏蔽</option>
				<option value="0" >未屏蔽</option>
				<option value="1" >已屏蔽</option>
			</select>
			<select class="auto" name="replyContent">
				<option value="">选择回复</option>
				<option value="0" >未回复</option>
				<option value="1" >已回复</option>
			</select>
			<input class="small" name="startTime" id="startTime" style="width:120px;" type="text" value="" onfocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2011-11-11 11:11:11',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
			<input class="small" name="endTime" id="endTime" style="width:120px;" type="text" value="" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'startTime\')}',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:'<?=date("Y-m-d H:i:s")?>'})"/>
			<button class="btn" type="submit" onclick="doSelect();">
				<span class="sel">筛 选</span>
			</button>
		</div>
	</div>
    <div class="content">
		<table id="list_table" class="list_table" width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="3%">选择</th>
			<th width="3%">编号</th>
			<th width="10%">用户名</th>
			<th width="10%">用户电话</th>
			<th width="12%">用户Email</th>
			<th width="8%">用户IP</th>
			<th width="20%">留言内容</th>
			<th width="3%">公开</th>
			<th width="3%">屏蔽</th>
			<th width="13%">管理员回复</th>
			<th width="10%">留言日期时间</th>
			<th width="5%">操作</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
		</table>
		<div id="pageLists" class="pageLists clearfix hide"></div>
		<input type="hidden" name="currentPage" value="1">
		<div class="layComment hide" align="center">
			<table style="margin-top:5px;">
				<tr>
					<td class="layTop">
						<span class="layTitle l">查看/回复留言</span>
						<span class="r cursor" id="close"><a href="javascript:;" class="closeBtn"></a></span>
					</td>
				</tr>
				<tr>
					<td align="left">
						<div style="padding:0px;margin:0px;">
							<div style="padding:0px;margin:5px;" class="Comment">
								<!-- 用户评论-->
								<div class="Comment_item" style="padding:0px;margin-top:10px;width:500px;">
									<table id="user_info" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 30px;">
										<tr bgcolor="#ddd" id="uid">
											<th width="25%" bgcolor="#ffffff" align="right" style="padding-right:10px;">编号(ID)</th>
											<th width="60%" bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="id" class="txt" disabled></th>
											<th width="15%" bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</th>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>用户名</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="username" class="txt" disabled></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>用户电话</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="phone" class="txt" disabled></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										
										<tr bgcolor="#ddd" id="repassword">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>用户Email</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="email" class="txt" disabled></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>用户IP</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="user_ip" class="txt" disabled></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>公开</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<label><input type="radio" name="is_public" value="0" class="default" disabled>否</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="is_public" value="1" class="default" disabled>是</label>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>留言时间日期</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="create_time" class="txt" disabled></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>留言内容</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<textarea name="declare" style="width:300px; height:70px;font-size:12px;margin:5px 0;" disabled></textarea>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>屏蔽</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<label><input type="radio" name="is_shield" value="0" class="cursor">否</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="is_shield" value="1" class="cursor">是</label>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>回复内容</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<textarea name="replyContent" style="width:300px; height:60px;font-size:12px;margin:5px 0;"></textarea><br>
												<font color="#999999">请直接回复内容，不需要填写尊称，已默认有</font>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
									</table>
									<table width="100%" border="0" align="left" id="Btn">
										<tr>
											<td align="center">
											<input class="submit" type="button" id="submit_commentInfo" value="保存" onfocus="this.blur();"/>
											<input class="submit" type="button" id="close" value="关闭" onfocus="this.blur();"/>
											<input type="hidden" name="cid">
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
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->
