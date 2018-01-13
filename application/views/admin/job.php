<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理 - <?=$act=='add'?'添加招聘岗位':'修改招聘岗位'?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';
	var act = '<?=$act?>';
	function checkForm(){
		var quarters = $('input[name="quarters"]').val(),
			duty = $('textarea[name="duty"]').val(),
			demand = $('textarea[name="demand"]').val();
		if(!$.trim(quarters)){
			alert('岗位名称不能为空');
			return false;
		}
		if(!$.trim(duty)){
			alert('工作职责不能为空');
			return false;
		}
		if(duty.length>1000){
			alert('工作职责描述不能大于1000个字符');
			return false;
		}
		if(!$.trim(demand)){
			alert('岗位要求不能为空');
			return false;
		}
		if(demand.length>1000){
			alert('岗位要求描述不能大于1000个字符');
			return false;
		}
	}
	function iResult(str){
		if(str==1){
			if(act=='add'){
				alert('添加成功!');
				event_link(baseUrl + lang +'/admin/system/job/');
				return false;
			}
			alert('修改成功!');
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
</head>
<body>
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
					<span>系统</span><span>></span><span>招贤纳士</span><span>></span><span><?=$act=='add'?'添加招聘岗位':'修改招聘岗位'?></span>
				</div>
				<ul name="menu1" class="tab">
					<li class="selected"><a href="javascript:;">招贤纳士</a></li>
			    </ul>
			</div>
			<div class="content_box">
			<div class="content link_target" align="left">
				<!--container-->
				<div class="content form_content" style="height: 298px;">
					<div class="div_box_setting">
						<form method="post" name="ModelForm" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/<?=$act=='add'?'addJob':'editJob/'.$data['id']?>" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
							<div class="div" style="margin-left:10px;" align="left">
								<table class="form_table">
									<colgroup>
										<col width="138px"><col>
									</colgroup>
									<tbody>
									  <tr>
										<th>岗位名称：</th>
										<td><input type="text" value="<?=isset($data['quarters'])?$data['quarters']:''?>" name="quarters" class="normal" maxlength="100"></td>
									  </tr>
									  <tr>
										<th>工作职责：</th>
										<td><textarea rows="6" style="height:110px;line-height:18px;" cols="" name="duty"><?=isset($data['duty'])?$data['duty']:''?></textarea></td>
									  </tr>
									  <tr>
										<th>岗位要求：</td>
										<td><textarea rows="6" style="height:110px;line-height:18px;" cols="" name="demand"><?=isset($data['demand'])?$data['demand']:''?></textarea></td>
									  </tr>
									  <tr>
										<th></th>
										<td>
											<button type="submit" class="submit"><span><?=$act=='add'?'添加招聘岗位':'修改招聘岗位'?></span></button>
											<button type="button" class="submit" onclick="history.back(-1);"><span>返回列表</span></button>
										</td>
									  </tr>
									 </tbody>
								</table>
							</div>
						</form>
						<iframe name="ajaxifr" style="display:none;"></iframe>
					</div>
				</div>
				<!--/container-->
			</div>
		</div>
		<div id="separator"></div>
	</div>
	<script type='text/javascript'>
		//隔行换色
		$(".list_table tr::nth-child(even)").addClass('even');
		$(".list_table tr").hover(function() {
			$(this).addClass("sel");
		}, function() {
			$(this).removeClass("sel");
		});
		$(function() {
			$('#headth th').each(function(i) {
				var width = $('#headth th:eq(' + i + ')').width();
				$('#conth tr:eq(0) td:eq(' + i + ')').width(width - 2);
			});
		});
	</script>
</body>
</html>
