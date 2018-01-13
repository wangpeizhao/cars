<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-管理员列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script src="<?=site_url('')?>/themes/common/js/form.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10;
		var uid = <?=isset($uid) && $uid?$uid:0?>;
	$(function(){
		try{
			setData(1);

			$("a").click(function(){
				 this.blur();
			});

			$("#close").live('click',function() {
				isIE()==6?$(".bg").hide():$(".bg").fadeOut("slow");
				if($(".lay").is(':visible')){$(".lay").fadeOut("slow");}
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var uid = $(this).parent().attr('uid');
				switch(act){
					case 'edit':
						getUserInfo(uid);
						break;
					case 'del':
						if(confirm('确定要删除,放入回收站？')){
							delUserInfo(uid);
						}
						break;
				}
			});

			$("#submit_userInfo").click(function(){
				var reg = /^((1[3458])\d{9})$/; 
				var reg2 = /^(\d{7,8})$/; 
				var sort = /^(\d{1,3})$/; 
				var email = /^([a-z0-9+_]|\-|\.)+@(([a-z0-9_]|\-)+\.)+[a-z]{2,5}$/i;
				var emailDOM = $('input[name="email"]');
				var phoneDOM = $('input[name="phone"]');
				var sortDOM = $('input[name="sort"]');
				var url = baseUrl + lang + "/admin/system/editUserInfo";
				if($('input[name="act"]').val()=='add'){//添加管理员
					var usernameDOM = $('input[name="username"]');
					var passwordDOM = $('input[name="password"]');
					var repasswordDOM = $('input[name="repassword"]');
					url = baseUrl + lang + "/admin/system/addUserInfo";
					if(!$.trim(usernameDOM.val())){
						usernameDOM.focus();
						$('#tips').html('<font color="#ff0000">登录名不能为空!</font>');
						return false;
					}else{
						if(parseInt($('input[name="checkUsername"]').val())==1){
							$('#tips').html('<font color="#ff0000">登录名已存在!</font>');
							return false;
						}
					}
					if(!$.trim(passwordDOM.val())){
						passwordDOM.focus();
						$('#tips').html('<font color="#ff0000">登录密码不能为空!</font>');
						return false;
					}
					if($.trim(passwordDOM.val())!=$.trim(repasswordDOM.val())){
						repasswordDOM.focus();
						$('#tips').html('<font color="#ff0000">两次登录密码不相同!</font>');
						return false;
					}
				}
				if($.trim(emailDOM.val()) && !email.test(emailDOM.val())){
					$('#tips').html('<font color="#ff0000">邮箱格式不正确</font>');
					var emailVal=emailDOM.val();
					emailDOM.val('');
					emailDOM.focus();
					emailDOM.val(emailVal);
					return false;
				}
				if($.trim(phoneDOM.val()) && !reg.test(phoneDOM.val()) && !reg2.test(phoneDOM.val())){
					$('#tips').html('<font color="#ff0000">电话号码格式不正确</font>');
					var phoneVal=phoneDOM.val();
					phoneDOM.val('');
					phoneDOM.focus();
					phoneDOM.val(phoneVal);
					return false;
				}

				if($.trim(sortDOM.val()) && !sort.test(sortDOM.val())){
					$('#tips').html('<font color="#ff0000">排序只能为0~~999整数</font>');
					var sortVal=sortDOM.val();
					sortDOM.val('');
					sortDOM.focus();
					sortDOM.val(sortVal);
					return false;
				}

				var data = [];
				var submitData = [];
				if($('input[name="act"]').val()=='edit'){//修改管理员
					data.push({
						'email':emailDOM.val(),
						'phone':phoneDOM.val(),
						'username':$('input[name="username"]').val(),
						'is_active':$('input[name="is_active"]:checked').val(),
						'grade':$('select[name="grade"]').val(),
						'nickname':$('input[name="nickname"]').val(),
						'branch':$('input[name="branch"]').val(),
						'mobile':$('input[name="mobile"]').val(),
						'describe': $('input[name="describe"]').val(),
						'sort': $('input[name="sort"]').val(),
						'qq': $('input[name="qq"]').val()
					});
					submitData = {data:data[0],uid:$('input[name="uid"]').val(),password:$('input[name="password"]').val()};
				}else{
					data.push({
						'email':emailDOM.val(),
						'phone':phoneDOM.val(),
						'username':$('input[name="username"]').val(),
						'password':$('input[name="password"]').val(),
						'is_active':$('input[name="is_active"]:checked').val(),
						'grade':$('select[name="grade"]').val(),
						'create_time':$('select[name="create_time"]').val(),
						'nickname':$('input[name="nickname"]').val(),
						'branch':$('input[name="branch"]').val(),
						'mobile':$('input[name="mobile"]').val(),
						'describe': $('input[name="describe"]').val(),
						'sort': $('input[name="sort"]').val(),
						'qq': $('input[name="qq"]').val()
					});
					submitData = {data:data[0]};
				}
				$.ajax({
					type: 'post',
					url: url,
					data: submitData,
					dataType: 'json',
					timeout: 30000,
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("status：" + XMLHttpRequest.status + ";\nreadyState："+ XMLHttpRequest.readyState + ";\ntextStatus："+ textStatus);
					},
					success: function(data) {
						if (data.done === true) {
							setData($('input[name="currentPage"]').val());
							$('#tips').html('<font color="#339900">保存成功！</font>');
							$(".bg").fadeOut("2000");
							$(".lay").fadeOut("2000");
						} else {
							$('#tips').html('<font color="#ff0000">提交失败，请重试!</font>');
							alert('提交失败，请重试');
						}
					}
				});
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
					delUserInfo(ids);
				}
			});

			//管理员回收站
			$("#adminRecycle").click(function(){
				event_link(baseUrl + lang +'/admin/system/adminRecycleList')
			});

			//添加管理员
			$("#addUser").click(function(){
				$('#tips').html('');
				$('.layTitle').html('添加管理员');
				$('.passwordTip').html('');
				$("#repassword,#add_user").show();
				$("#last_login_time,#uid").hide();
				$('input[name="email"]').val('');
				$('input[name="phone"]').val('');
				$('input[name="password"]').val('');
				$('input[name="username"]').val('');
				$('input[name="nickname"]').val('');
				$('input[name="branch"]').val('');
				$('input[name="mobile"]').val('');
				$('input[name="describe"]').val('');
				$('input[name="qq"]').val('');
				$('input[name="sort"]').val(''),
				$('input[name="create_time"]').val('<?=date("Y-m-d H:i:s")?>');
				$('input[name="is_active"]').eq(1).attr("checked",true);
				$('input[name="act"]').val('add');
				isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
				$(".lay").fadeIn("slow");
			});

			//检测登录名是否存在
			$('input[name="username"]').blur(function(){
				if($('input[name="act"]').val()=='add' && $.trim($(this).val())){
					try{
						$.post(baseUrl + lang + "/admin/system/addUserInfo",{username:$(this).val(),act:'check'}, function(data){
							$('input[name="checkUsername"]').val('0');
							$('#tips').html('');
							if(typeof(data.data.id)!="undifined" && parseInt(data.data.id)>0){
								$('#tips').html('<font color="#ff0000">登录名已存在!</font>');
								$('input[name="checkUsername"]').val('1');
							}
						},"json");
					}catch(e){
						alert(e.message);
					}
				}
			});
		}catch(e){
			alert(e.message);
		}
	});

	function getUserInfo(){
		try{
			$('#tips').html('&nbsp;');
			$.ajax({
				type: 'post',
				url: baseUrl + lang + "/admin/system/editUserInfo",
				data: {
					uid:arguments[0],
					act:'getUserInfo'
				},
				dataType: 'json',
				timeout: 30000,
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("status：" + XMLHttpRequest.status + ";\nreadyState："+ XMLHttpRequest.readyState + ";\ntextStatus："+ textStatus);
				},
				success: function(data) {
					if (data.done === true) {
						$("#repassword,#add_user").hide();
						$("#last_login_time,#uid").show();
						$('input[name="uid"]').val(data.data.userInfo.id);
						$('input[name="email"]').val(data.data.userInfo.email);
						$('input[name="phone"]').val(data.data.userInfo.phone);
						$('input[name="password"]').val('');
						$('input[name="username"]').val(data.data.userInfo.username);
						$('input[name="nickname"]').val(data.data.userInfo.nickname);
						$('input[name="branch"]').val(data.data.userInfo.branch);
						$('input[name="mobile"]').val(data.data.userInfo.mobile);
						$('input[name="describe"]').val(data.data.userInfo.describe);
						$('input[name="qq"]').val(data.data.userInfo.qq);
						$('input[name="sort"]').val(data.data.userInfo.sort);
						$('select[name="grade"] option').each(function(){
							if($(this).val()==data.data.userInfo.grade){
								$(this).attr("selected",true);
							}
						});
						$('input[name="create_time"]').val(data.data.userInfo.create_time);
						$('input[name="last_login_time"]').val(data.data.userInfo.last_login_time);
						$('input[name="is_active"]').eq(data.data.userInfo.is_active).attr("checked",true);
						$('.layTitle').html('管理员用户信息');
						$('.passwordTip').html('留空不修改');
						isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
						isIE()==6?$(".lay").show():$(".lay").fadeIn("slow");
						$('input[name="act"]').val('edit');
					}else if(data.msg){
						alert(data.msg);
						return false;
					}else{
						alert('读取错误！');
					}
				}
			});
		}catch(e){
			alert(e.message);
		}
	}

	function delUserInfo(id){
		try{
			$.ajax({
				type: 'post',
				url: baseUrl + lang + "/admin/system/delUserInfo",
				data: {id:id},
				dataType: 'json',
				timeout: 30000,
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("status：" + XMLHttpRequest.status + ";\nreadyState："+ XMLHttpRequest.readyState + ";\ntextStatus："+ textStatus);
				},
				success: function(data) {
					if (data.done === true) {
						alert('提交成功');
						setData($('input[name="currentPage"]').val());
					}else if(data.msg){
						alert(data.msg);
						return false;
					} else {
						alert('提交失败，请重试');
						return false;
					}
				}
			});
		}catch(e){
			alert(e.message);
		}
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
      <div class="position"><span>系统</span><span>></span><span>权限管理</span><span>></span><span>管理员列表</span></div>
      <div class="operating"> 
	    <a href="javascript:;" id="addUser"><button class="operating_btn" type="button"><span class="addition">添加管理员</span></button></a> 
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
		<a href="javascript:;" id="adminRecycle"><button class="operating_btn" type="button"><span class="recycle">回收站</span></button></a> 
	  </div>
    </div>
    <div class="content">
	  <table id="list_table" class="list_table" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="5%">选择</th>
			<th width="5%">编号</th>
			<th width="10%">登录名</th>
			<th width="10%">真实姓名</th>
			<th width="10%">所属角色</th>
			<th width="10%">所属部门</th>
			<th width="10%">工作邮箱</th>
			<th width="10%">工作手机</th>
			<th width="7%">上次登录IP</th>
			<th width="5%">激活</th>
			<th width="3%">排序</th>
			<th width="10%">上次登录时间</th>
			<th width="5%">操作</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
      </table>
	  <input type="hidden" name="currentPage" value="1">
	  <div id="pageLists" class="pageLists clearfix hide"></div>
		<div class="lay hide" align="center" style="height: 580px;margin-top:-290px !important;border-radius: 10px;">
			<table style="margin-top:5px;">
				<tr>
					<td class="layTop">
						<span class="layTitle l">&nbsp;</span>
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
											<th width="20%" bgcolor="#ffffff" align="right" style="padding-right:10px;">编号(UID)</th>
											<th width="42%" bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="uid" class="txt" disabled></th>
											<th width="38%" bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</th>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>登录账号</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="username" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;<input type="hidden" name="checkUsername"></td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>登录密码</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="password" name="password" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;<font color="#999" class="passwordTip"></font></td>
										</tr>
										<tr bgcolor="#ddd" id="repassword">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>确认密码</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="password" name="repassword" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>真实姓名</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="nickname" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"></td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>所属部门</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="branch" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"></td>
										</tr>
										
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>管理角色</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<select name="grade" class="select cursor">
												<?php if(!empty($role)){
													foreach($role as $key=>$item){?>
													<option value="<?=$item['groupid']?>"><?=$item['grouptitle']?></option>
												<?php }}?>
												</select>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>是否激活</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">
												<label><input type="radio" name="is_active" value="0" class="cursor">否</label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="is_active" value="1" class="cursor">是</label>
											</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">“是”才能显示前台</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>显示排序</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="sort" class="txt" maxlength="3"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">显示顺序,越大越靠前</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>工作邮箱</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="email" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>电话号码</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="phone" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>手机号码</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="mobile" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>工作QQ</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="qq" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>个人说明</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="describe" class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>注册时间</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="create_time" disabled class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd" id="last_login_time">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;"><b>上次登录时间</b></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;"><input type="text" name="last_login_time" disabled class="txt"></td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
										<tr bgcolor="#ddd" id="add_user">
											<td bgcolor="#ffffff" align="right" style="padding-right:10px;">&nbsp;</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
											<td bgcolor="#ffffff" align="left" style="padding-left:10px;">&nbsp;</td>
										</tr>
									</table>
									<table width="100%" border="0" align="left" id="Btn">
										<tr>
											<td align="center">
											<input class="submit" type="button" id="submit_userInfo" value="保存" onfocus="this.blur();"/>
											<input class="submit" type="button" id="close" value="关闭" onfocus="this.blur();"/>
											<input type="hidden" name="act">
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