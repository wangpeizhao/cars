<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理 - 查看服务器配置</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type="text/javascript">
<!--
	var t=<?=time()?>;
	setInterval(function (){
	  t=t+1;
	  document.getElementById('theClock').innerHTML=timetodate(t,"yyyy-MM-dd hh:mm:ss 星期E");
	},1000);
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
					<span>系统</span><span>></span><span>服务器管理</span><span>></span><span>查看服务器配置</span>
				</div>
				<ul name="menu1" class="tab">
					<li class="selected"><a href="javascript:;">服务器配置</a></li>
			    </ul>
			</div>
			<div class="content_box">
			<div class="content link_target" align="left">
				<!--container-->
				<div class="content form_content" style="height: 298px;">
					<div class="div_box_setting">
						<div class="div" style="margin-left:10px;" align="left">
							<table class="form_table">
								<colgroup>
									<col width="138px"><col>
								</colgroup>
								<tbody>
								  <tr>
									<th>服务器时间：</th>
									<td><span id="theClock" style="font-family:'宋体';width:185px;line-height:30px;color:#0066ff;"><?=date("Y-m-d H:i:s")?> 星期&nbsp;&nbsp;</span> (如果您的系统显示时间与北京时间不符，请联系服务器管理员改正。)</td>
								  </tr>
								  <tr>
									<th>服务器域名：</th>
									<td height="30"><?php echo $_SERVER['SERVER_NAME']; ?></td>
								  </tr>
								  <tr>
									<th>服务器总类：</td>
									<td height="30"><?php echo @PHP_OS;?></td>
								  </tr>
								   <tr>
									<th>服务器类型：</th>
									<td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
								  </tr>
								  <tr>
									<th>服务器目录：</th>
									<td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
								  </tr>
								  <tr>
									<th>MYSQL版本号：</th>
									<td><?=$this->db->version();?></td>
								  </tr>
								  <tr>
									<th>更多服务器信息</th>
									<td>单击：<a target="_blank" href="<?=WEB_DOMAIN?>/admin/server/phpinfo" style="color:#ff6600;">phpinfo()</a></td>
								  </tr>
								 </tbody>
							</table>
						</div>
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
