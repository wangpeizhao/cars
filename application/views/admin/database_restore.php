<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type="text/javascript">
<!--
	$(function(){
		try{
			$("a").click(function(){
				 this.blur();
			});
			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			$("#list_table a").live('click',function(){
				var act = $(this).attr('act');
				var file = $(this).parent().parent().find('td').eq(0).find('input').val();
				var fileName = [];
					fileName.push(file);
				$('input[name="filePath"]').val('uploads/backup/'+file);
				switch(act){
					case 'icon_down':
						//if(confirm('是否需要压缩打包再下载?')){
						//	$('input[name="zip"]').val('1');
						//}else{
						//	$('input[name="zip"]').val('');
						//}
                        $("#downLoadDatabase").submit();
						break;
					case 'del':
						if(confirm('确定要彻底删除该备份文档？不再回收的哦！')){
							delBackup(fileName);
						}
						break;
				}
			});

			//批量删除
			$("#deleteAll").click(function(){
				if(dumpAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var fileName = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							fileName.push(idDOM.eq(i).val());
						}
					}
					delBackup(fileName);
				}
			});

			//备份数据库
			$("#backUp").click(function(){
				event_link(baseUrl + lang + "/admin/upload/backup");
			});

			//还原
			$("#restore").click(function(){
				if(recoverAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var fileName = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							fileName.push(idDOM.eq(i).val());
						}
					}
					restore(fileName);
				}
			});

			$("#import").toggle(
				function () {
					$(".inputfile").slideDown();
				},
				function () {
					$(".inputfile").slideUp();
				}
			);

		}catch(e){
			alert(e.message);
		}
	});

	function restore(fileName){
		try{
			
			$.post(baseUrl + lang + "/admin/upload/restore",{fileName:fileName}, function(data){
				if (data.done === true) {
					isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
					isIE()==6?$(".restoreLay").show():$(".restoreLay").fadeIn("slow");
					$(".restoreLay span.tips").html('正在还原,请稍后(切勿关闭页面)...');
					$(".restoreLay img").attr('src','<?=site_url()?>/themes/admin/images/loading.gif');
					$(".restoreLay span.tips").html('<font color="#339900">还原成功,正在跳转...</font>');
					$(".restoreLay img").attr('src','<?=site_url()?>/themes/admin/images/success.png');
					//alert('还原成功');
					setTimeout(function(){
						isIE()==6?$(".bg").hide():$(".bg").fadeOut("slow");
						isIE()==6?$(".restoreLay").hide():$(".restoreLay").fadeOut("slow");
						event_link(baseUrl + lang + "/admin/upload/backup");
					},'2000');
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('还原失败，请重试');
					return false;
				}
			},"json");
		}catch(e){
			alert(e.message);
		}
	}

	function delBackup(fileName){
		try{
			$.post(baseUrl + lang + "/admin/upload/delBackup",{fileName:fileName}, function(data){
				if (data.done === true) {
					alert('删除成功');
					event_link(baseUrl + lang + "/admin/upload/restore");
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

	function backUp(tables){
		$.post(baseUrl + lang + "/admin/upload/backup",{tables:tables}, function(data){
			if (data.done === true) {
				if(confirm('已备份成功，是否需要马上下载到本地？')){
                  $('input[name="filePath"]').val(data.data);
                  $("#downLoadDatabase").submit();
				}else{
					return false;
				}
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				alert('提交失败，请重试');
				return false;
			}
		},"json");
	}

	function fileResult(str){
		if(str==1){
			
		}else if(str==2){
			document.getElementById("file").outerHTML = document.getElementById("file").outerHTML;
			$("#progress_bar").fadeOut(function(){
				$("#progress_bar").attr('src','<?=site_url()?>/themes/common/images/right_g.gif');
				$("#progress_bar").fadeIn();
			});
			alert('导入成功');
			event_link(baseUrl + lang + "/admin/upload/restore");
		}else{
			alert(str);
			return false;
		}
	}

	function checkForm(){
		if(!$.trim($('input[name="file"]').val())){
			alert('请先选择数据库文件');
			return false;
		}
		$("#progress_bar").fadeIn();
	}
	function iResultAlter(str,status){
		if(status){
			
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span>数据库管理</span><span>></span><span>还原数据库</span></div>
      <div class="operating">
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
		<a href="javascript:;" id="restore"><button class="operating_btn" type="button"><span class="import">还原</span></button></a>
		<a href="javascript:;" id="import"><button class="operating_btn" type="button"><span class="import">本地导入</span></button></a>
		<a href="javascript:;" id="backUp"><button class="operating_btn" type="button"><span class="backup">备份</span></button></a>
	  </div>
    </div>
    <div class="content setting_form">
	  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;" align="left">
		<tr class="field">
			<th width="5%">选择</th>
			<th width="40%">备份文件名</th>
			<th width="10%">占用空间</th>
			<th width="15%">备份时间</th>
			<th width="15%">操作</th>
			<th width="15%">&nbsp;</th>
		</tr>
		<?php if(isset($files) && !empty($files)){foreach($files as $key => $item){?>
		<tr<?=$key%2==0?' class="even"':''?>>
			<td><input class="cursor" type="checkbox" value="<?php echo isset($item['filename'])?$item['filename']:"";?>" /></td>
			<td><?php echo isset($item['filename'])?$item['filename']:"";?></td>
			<td><?php echo isset($item['filesize'])?$item['filesize']:"";?></td>
			<td><?php echo isset($item['filetime'])?$item['filetime']:"";?></td>
			<td>
				<a href="javascript:;" act="icon_down"><img class="operator" src="<?=site_url('')?>/themes/admin/images/icon_down.gif" alt="下载" title="下载" /></a>
				<a href="javascript:;" act="del"><img class="operator" src="<?=site_url('')?>/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a>
			</td>
			<td>&nbsp;</td>
		</tr>
		<?php }}?>
	</table>
	<iframe name="ajaxifr" style="display:none;"></iframe>
	<form method="post"  id="downLoadDatabase" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/downloadsql" target="ajaxifr">
		<input type="hidden" name="filePath"><input type="hidden" name="zip">
	</form>
	<div class="inputfile" style="display: none;margin:20px;" align="left">
		<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/import" enctype="multipart/form-data" target="ajaxifr" onSubmit="return checkForm();">
			<input type="file" id="file" size="30" name="file">
			<input type="submit" class="button" style="margin-left:10px;" value="导入" onfocus="this.onblur();">
			<img src="<?=site_url()?>/themes/common/images/progress_bar.gif" style="margin-left:3px;display:none;position:relative;top:3px;" id="progress_bar" alt="正在上传...">
			<input type="hidden" value="file" name="inputDOM">
			<p style="padding-top:5px;" class="uploadTips">最大可上传<?=ini_get("upload_max_filesize");?>文件，仅支持<font color="#0099ff">'sql'</font>格式图像文件。</p>
		</form>
		<iframe name="ajaxifr" style="display:none;"></iframe>
	</div>
	<div class="bg hide"></div>
	<div class="restoreLay hide" align="center">
		<img src="<?=site_url('')?>/themes/admin/images/loading.gif">
		<span class="tips">正在导入,请稍后...</span>
	</div>
	<!--/container-->
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->