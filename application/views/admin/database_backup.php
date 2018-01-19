<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type="text/javascript">
<!--
	$(function(){
		try{
			//全选/全否选
			$("#selectAll").click(function(){
				selectAll('list_table');
			});

			//备份数据库
			$("#backUp").click(function(){
				if(backUpAll('list_table')){
					var idDOM = $("#list_table"+" :checkbox");
					var tables = [];
					for(var i=0;i<idDOM.length;i++){
						if(idDOM[i].checked){
							tables.push(idDOM.eq(i).val());
						}
					}
					backUp(tables);
				}
			});

			$("#backupFile").click(function(){
				event_link(baseUrl + lang + "/admin/upload/restore");
			});

		}catch(e){
			alert(e.message);
		}
	});

	function backUp(tables){
		$.post(baseUrl + lang + "/admin/upload/backup",{tables:tables}, function(data){
			if (data.done === true) {
				$('input[name="filePath"]').val(data.data);
				if(confirm('已备份成功，是否需要马上下载到本地？')){
                  $("#downLoadDatabase").submit();
                  /*
					$.post(baseUrl + lang + "/admin/upload/downloadsql",{act:'checkLP'}, function(data){
						if (data.done === true) {
							$("#downLoadDatabase").submit();
						}else if(data.msg){
							alert(data.msg);
							return false;
						}else{
							alert('提交失败，请重试');
							return false;
						}
					},"json");
                    */
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
			
		}else{
			alert(str);
			return false;
		}
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
      <div class="position"><span>系统</span><span>></span><span>数据库管理</span><span>></span><span>备份数据库</span></div>
      <div class="operating"> 
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<a href="javascript:;" id="backUp"><button class="operating_btn" type="button"><span class="backup">备份</span></button></a>
		<a href="javascript:;" id="backupFile"><button class="operating_btn" type="button"><span class="grade">备份文件</span></button></a>
	  </div>
    </div>
    <div class="content setting_form">
	  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;" align="left">
		<tr class="field">
			<th width="5%">选择</th>
			<th width="10%">数据库表</th>
			<th width="5%">记录条数</th>
			<th width="10%">数据表类型</th>
			<th width="10%">占用空间</th>
			<th width="10%">碎片</th>
			<th width="10%">编码</th>
			<th width="15%">创建时间</th>
			<th width="30%">说明</th>
		</tr>
		<?php foreach($tableInfo as $key => $item){?>
		<tr<?=$key%2==0?' class="even"':''?>>
			<td><input class="cursor" type="checkbox" value="<?php echo isset($item['Name'])?$item['Name']:"";?>" /></td>
			<td style="padding-left:10px;text-align:left;"><?php echo isset($item['Name'])?$item['Name']:"";?></td>
			<td><?php echo isset($item['Rows'])?$item['Rows']:0;?></td>
			<td><?php echo isset($item['Engine'])?$item['Engine']:(isset($item['Type'])?$item['Type']:"");?></td>
			<td><?php echo $item['Data_length']>=1024 ? ($item['Data_length']>>10).' KB':$item['Data_length'].' B';?></td>
			<td><?php echo isset($item['Data_free'])?$item['Data_free']:0;?></td>
			<td><?php echo isset($item['Collation'])?$item['Collation']:"N/A";?></td>
			<td><?php echo isset($item['Create_time'])?$item['Create_time']:"";?></td>
			<td><?php echo isset($item['Comment'])?$item['Comment']:"";?></td>
			<td>&nbsp;</td>
		</tr>
		<?php }?>
	</table>
	<iframe name="ajaxifr" style="display:none;"></iframe>
	<form method="post"  id="downLoadDatabase" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/upload/downloadsql" target="ajaxifr">
		<input type="hidden" name="filePath">
	</form>
	<!--/container-->
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->