<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-分类管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_classify_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<style type="text/css">
	.setting_form input{border: 1px solid #999;
		display: block;
		font-size: 12px;
		height: 22px;
		line-height: 22px;
		padding-left:3px;
		border-radius: 4px;
		width: 90%;
	}
	.l_10{
		padding-left:10px;text-align:left;
	}
	.l_22{
		padding-left:22px;text-align:left;
	}
	
	.l_34{
		padding-left:34px;text-align:left;
	}
</style>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10000000,
		uername = '<?=isset($userInfo['username'])?$userInfo['username']:'请先登录'?>',
		value='',
		FirstId = SecondId = 0,
		addClassify = '<?php if(!empty($classify)){foreach($classify as $key=>$item){?><option value="<?=$key?>">&nbsp;<?=$item?></option><?php }}?>';
	$(function(){
		try{
			setData(1);

			$("a").live('click',function(){
				 this.blur();
			});
			//添加子分类
			$(".addSetting").live('click',function(){
				try{
					var date = new Date();
					var time = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+' '+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
					var pid = $(this).attr('pid');
					var html = '';
					html += '<tr class="sunTerm Term_'+pid+' add_'+pid+'">';
					html += '	<td style="padding-left:32px;text-align:left;"><a><img style="width: 12px; height: 12px;" src="'+site_url+'/themes/admin/images/icon_add.gif"></a></td>';
					html += '	<td>-</td>';
					html += '	<td><input type="text" name="" value=""></td>';
					html += '	<td><input type="text" name="" value=""></td>';
					html += '	<td><input type="text" name="" value="" class="middle"></td>';
					html += '	<td>-</td>';
					html += '	<td><input type="text" name="" value=""></td>';
					html += '	<td>'+uername+'</td>';
					html += '	<td>-</td>';
					html += '	<td><select><option value="1" selected>是<option value="0">否</select></td>';
					html += '	<td>'+time+'<input type="hidden" value="'+time+'"></td>';
					html += '	<td><a href="javascript:;" class="delete">删除</a></td>';
					html += '</tr>';
					$(this).parent().parent().before(html);
				}catch(e){
					alert(e.message);
				}
			});
			
			//移除添加子分类
			$(".setting_form .sunTerm .delete").live('click',function(){
				$(this).parent().parent().fadeOut(function(){
					$(this).remove();
				});
			});
			
			//提交第二分类
			$(".addSunTermBtn").live('click',function(){
				try{
					var data = [],list = [],pid=0,taxonomy='';
					pid = $(this).parent().parent().attr('pid');
					taxonomy = $(this).parent().parent().attr('taxonomy');
					list =  $(this).parent().parent().parent().find('tr.add_'+pid);
					FirstId = pid;
					if(list.length==0){
						alert('您输入 0 行，提交失败！');
						return false;
					}
					for(var ii=0;ii<list.length;ii++){
						aid = list.eq(ii).find('input');
						if(!$.trim(aid.eq(0).val())){
							alert('分类名称不能为空值，请先输入');
							data.splice(0);//消除data
							return false;
						}
						data.push({"name":aid.eq(0).val(),"slug":aid.eq(1).val(),"description":aid.eq(2).val(),"taxonomy":taxonomy,"sort":aid.eq(3).val(),"create_time":aid.eq(4).val(),"is_valid":list.eq(ii).find('select').val(),"parent":pid});
						var slug = $('.Term_'+pid);
						for(var i=0;i<slug.length;i++){
							if(aid.eq(1).val()==slug.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}
						var ppid = $(this).parent().parent().attr('ppid');
						var slug2 = $('.gsTerm_'+ppid);
						for(var i=0;i<slug2.length;i++){
							if(aid.eq(1).val()==slug2.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}
					}
					if(data.length>0){
						try{
							$.post(baseUrl + lang + "/admin/system/addTerm",{data:data}, function(data){
								if (data.done === true) {
									alert('提交成功');
									setData(1);
									//event_link(baseUrl + lang +'/admin/system/classify');
								}else if(data.msg){
									alert(data.msg);
								}else{
									alert('提交失败，请重试');
									return false;
								}
							},"json");
						}catch(e){
							alert(e.message);
						}
					}
				}catch(e){
					alert(e.message);
				}
			});

			//提交第三分类
			$(".addGrandsonTermBtn").live('click',function(){
				try{
					var data = [],list = [],pid=0,ppid=0,taxonomy='';
					pid = $(this).parent().parent().attr('pid');
					ppid = $(this).parent().parent().attr('ppid');
					taxonomy = $(this).parent().parent().attr('taxonomy');
					list =  $(this).parent().parent().parent().find('tr.gs_add_'+pid);
					FirstId = ppid;
					SecondId = pid;
					if(list.length==0){
						alert('您输入 0 行，提交失败！');
						return false;
					}
					for(var ii=0;ii<list.length;ii++){
						aid = list.eq(ii).find('input');
						if(!$.trim(aid.eq(0).val()) || !$.trim(aid.eq(1).val()) || !$.trim(aid.eq(2).val())){
							alert('第'+(parseInt(ii+1))+'行有空值，请先输入');
							data.splice(0);//消除data
							return false;
						}
						data.push({"name":aid.eq(0).val(),"slug":aid.eq(1).val(),"description":aid.eq(2).val(),"taxonomy":taxonomy,"create_time":aid.eq(3).val(),"is_valid":list.eq(ii).find('select.is_valid').val(),"parent":pid,'subclass':list.eq(ii).find('select.subclass').val()});
						var slug = $('.Term_'+pid);
						for(var i=0;i<slug.length;i++){
							if(aid.eq(1).val()==slug.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}
						var ppid = $(this).parent().parent().attr('ppid');
						var slug2 = $('.gsTerm_'+ppid);
						for(var i=0;i<slug2.length;i++){
							if(aid.eq(1).val()==slug2.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}
					}
					if(data.length>0){
						try{
							$.post(baseUrl + lang + "/admin/system/addTerm",{data:data}, function(data){
								if (data.done === true) {
									alert('提交成功');
									setData(1);
									//event_link(baseUrl + lang +'/admin/system/classify');
								}else if(data.msg){
									alert(data.msg);
								}else{
									alert('提交失败，请重试');
									return false;
								}
							},"json");
						}catch(e){
							alert(e.message);
						}
					}
				}catch(e){
					alert(e.message);
				}
			});
			
			//删除分类
			$("#list_table tr.termList td a").live('click',function(){
				if($(this).attr('act')=='del'){
					var p = '';
					var term_id = $(this).parent().parent().attr('term_id');
					if(term_id){
						try{
							$this = $(this);
							if($this.attr('p')=='p'){
								p='p';
								if(!confirm('确定要删除吗？此操作不可撤回!\n确定删除后，其子类将被删除，并且与其相关的文档/文章的分类将需要重新设置分类！\n确定要删除吗？')){
									return false;
								}
							}else{
								if(!confirm('确定要删除吗？此操作不可撤回!\n确定删除后，与其相关的文档/文章的分类将需要重新设置分类！\n确定要删除吗？')){
									return false;
								}
							}
							$.post(baseUrl + lang + "/admin/system/delTerm",{term_id:term_id,p:p}, function(data){
								if (data.done === true) {
									//alert('删除成功');
									$this.parent().parent().fadeOut();
									if(p){
										$this.parent().parent().siblings('.Term_'+term_id).fadeOut();
									}
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
				}
			});

			//展开、关闭分类
			$(".termList a.term").live('click',function(){
				try{
					if(!$(this).hasClass('add')){
						var $this = $(this).parent().parent();
						var term_id = $this.attr('term_id');
						if($this.hasClass('sunTerm')){
							if($this.next().is(':visible')){
								$this.parent().find('.gs_'+term_id).fadeOut();
								$(this).find('img').attr('src',site_url+'/themes/admin/images/tv-expandable.gif');
							}else{
								var t_id = $this.attr('pid');
								$this.siblings('.grandsonTerm').hide();
								$this.siblings('.gs_'+term_id).fadeIn();
								$(this).find('img').attr('src',site_url+'/themes/admin/images/tv-collapsable.gif');
								$this.siblings().find('td a.expandable img').attr('src',site_url+'/themes/admin/images/tv-expandable.gif');
								$('#t_'+t_id).find('img').attr('src',site_url+'/themes/admin/images/tv-collapsable.gif');
							}

						}else{
							if($this.next().is(':visible')){
								$this.parent().find('.Term_'+term_id).fadeOut();
								if(!$(this).hasClass('default')){
									$(this).find('img').attr('src',site_url+'/themes/admin/images/tv-expandable.gif');
									$this.parent().find('.grandsonTerm').fadeOut();
								}
							}else{
								$this.siblings('.sunTerm').hide();
								$this.siblings('.Term_'+term_id).fadeIn();
								$(this).find('img').attr('src',site_url+'/themes/admin/images/tv-collapsable.gif');
								$this.siblings().find('td a.expandable img').attr('src',site_url+'/themes/admin/images/tv-expandable.gif');
							}
						}
					}
				}catch(e){
					alert(e.message);
				}
			});

			//点击修改分类信息
			$("#list_table .termList td span").live('click',function(){
				try{
					if(!$(this).hasClass('img')){
						if($(this).prev().hasClass('subclass')){
							$(this).fadeOut(function(){
								$(this).prev().fadeIn();
								$(this).prev().focus();
							});
							return false;
						}
						var $this = $(this).parent();
						$this.html('<input type="text" value="'+$this.text()+'">');
						$this.find('input').select();
						value = $(this).text();
					}
				}catch(e){
					alert(e.message);
				}
			});

			//select
			$('select[name="subclass"]').live('change',function(){
				if(!$(this).hasClass('hide')){
					return false;
				}
				var $this = $(this).parent();
				var term_id = $this.parent().attr('term_id');
				var act = 'subclass';
				var thisVal = $(this).val();
				try{
					$.post(baseUrl + lang + "/admin/system/editTerm",{term_id:term_id,act:act,val:thisVal}, function(data){
						if (data.done === true) {
							//$this.find('span').after('<font color="#339900">√</font>');
							$this.find('select').fadeOut(function(){
								$this.find('span').fadeIn();
							});
							$this.find('span').css('color','#339900');
							$this.find('span').html(data.data);
							//$this.find('span').css('font-weight','bold');
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
			});

			//select
			$('select[name="subclass"]').live('blur',function(){
				if(!$(this).hasClass('hide')){return false;}
				var $this = $(this).parent();
				$this.find('select').fadeOut(function(){
					$this.find('span').fadeIn();
				});
			});

			//blur修改分类信息
			$("#list_table .termList td input").live('blur',function(){
				try{
					var $this = $(this).parent();
					var term_id = $this.parent().attr('term_id');
					var act = $this.attr('act');
					var thisVal = $(this).val();
					if(act=='slug'){
						var pre = /^[\sa-zA-Z0-9_-]+$/;
						if(!pre.test($(this).val())){
							alert('英文标题只能由大小写英文、数字、空格、中划线-和下划线_组成');
							return false;
						}
						var slug = $('.Term_'+$this.parent().attr('pid'));
						for(var i=0;i<slug.length;i++){
							if(thisVal==slug.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}
						
						var slug2 = $('.gsTerm_'+$this.parent().attr('ppid'));
						for(var i=0;i<slug2.length;i++){
							if(thisVal==slug2.eq(i).find('td').eq(3).find('span').text()){
								alert('URL友好名必须唯一，请重新填写');
								return false;
							}
						}

					}
					if($this.attr('act')){
						$this.html('<span act="slug">'+thisVal+'</span>');
					}else{
						$this.html('<span>'+thisVal+'</span>');
					}
					if(value!=$(this).val()){
						try{
							$.post(baseUrl + lang + "/admin/system/editTerm",{term_id:term_id,act:act,val:thisVal}, function(data){
								if (data.done === true) {
									//$this.find('span').after('<font color="#339900">√</font>');
									$this.find('span').css('color','#339900');
									//$this.find('span').css('font-weight','bold');
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
				}catch(e){
					alert(e.message);
				}
			});

			//点击修改分类信息-是否激活
			$("#list_table .termList td span img").live('click',function(){
				try{
					var $this = $(this).parent().parent();
					var $_this = $(this);
					var act = $this.attr('act');
					var val = parseInt($this.attr('val'))==1?0:1;
					if(act=='is_valid'){
						var term_id = $this.parent().attr('term_id');
						var pid = $this.parent().attr('pid');
						if(parseInt(pid)==0){
							if(!confirm('此操作同样会作用于其子分类，是否继续')){
								return false;
							}
						}
						$.post(baseUrl + lang + "/admin/system/editTerm",{term_id:term_id,pid:pid,val:val,act:act}, function(data){
							if (data.done === true) {
								if(val==1){
									$_this.attr('src',site_url+'/themes/admin/images/positive_enabled.gif');
									$this.attr('val','1');
									if(parseInt(pid)==0){
										$(".Term_"+term_id+" td span.img img").attr('src',site_url+'/themes/admin/images/positive_enabled.gif');
									}
								}else{
									$_this.attr('src',site_url+'/themes/admin/images/positive_disabled.gif');
									$this.attr('val','0');
									if(parseInt(pid)==0){
										$(".Term_"+term_id+" td span.img img").attr('src',site_url+'/themes/admin/images/positive_disabled.gif');
									}
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
				}catch(e){
					alert(e.message);
				}
			});

			//删除Banner
			$("#del_banner").click(function(){
				if(confirm('确定要删除该Banner吗？')){
					var tid = $('select[name="taxonomy"]').val();
					var src = $("#banner_"+tid).attr('href');
					$.post(baseUrl + lang + "/admin/system/delBanner",{term_id:tid,src:src}, function(data){
						if (data.done === true) {
							$("#banner_src").parent().slideUp('slow',function(){
								$("#banner_src").attr('src','');
								$("#banner_"+tid).parent().append('<a href="javascript:;" class="uploadBannerA" term_id="' + tid + '">马上上传</a>');
								$("#banner_"+tid).remove();
								alert('删除成功');
							});
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

			//上传Banner
			$('#uploadBanner').toggle(
				function () {
					$(".uploadBanner").slideDown('slow');
					$(this).text('[-]上传/修改Banner');
				},
				function () {
					$(".uploadBanner").slideUp('slow');
					$(this).text('[+]上传/修改Banner');
				}	
			);

			//检测所选分类是否存在Banner
			$('select[name="taxonomy"]').live('change',function(){
				var tid = $(this).val();
				if($("#banner_"+tid).length>0){
					$("#banner_src").attr('src',$("#banner_"+tid).attr('href'));
					$("#path").val($("#banner_"+tid).attr('href'));
					$("#banner_src").parent().slideDown('slow',function(){
						if(!confirm('该分类已存在Banner！是否是要修改替换？')){
							$('select[name="taxonomy"]').val('');
						}
					});
				}else{
					$("#banner_src").parent().slideUp('slow',function(){
						$("#banner_src").attr('src','');
					});
				}
			});

			//
			$('.addBtn').live('click',function(){
				try{
					var data = [];
					var aid = $(".addClassify").find('input');
					if(!$.trim(aid.eq(0).val()) || !$.trim(aid.eq(1).val()) || !$.trim(aid.eq(2).val())){
						alert('有空值，请先输入');
						return false;
					}
					if(!$.trim($('select[name="classfiy"]').val())){
						alert('请先选择分类');
						return false;
					}
					data.push({"name":aid.eq(0).val(),"slug":aid.eq(1).val(),"description":aid.eq(2).val(),"taxonomy":$('select[name="classfiy"]').val(),"sort":aid.eq(3).val(),"create_time":aid.eq(4).val(),"is_valid":$('select[name="is_valid"]').val(),"parent":0});
					if(data.length>0){
						try{
							$.post(baseUrl + lang + "/admin/system/addTerm",{data:data}, function(data){
								if (data.done === true) {
									alert('提交成功');
									event_link(baseUrl + lang +'/admin/system/classify');
								}else if(data.msg){
									alert(data.msg);
								}else{
									alert('提交失败，请重试');
									return false;
								}
							},"json");
						}catch(e){
							alert(e.message);
						}
					}
				}catch(e){
					alert(e.message);
				}
			});

			$('.uploadBannerA').live('click',function(){
				var term_id = $(this).attr('term_id');
				$("#uploadBanner").text('[-]上传/修改Banner');
				$(".uploadBanner").slideDown('slow');
				$('select[name="taxonomy"]').val(term_id);
				$('#banner').click();
			});

		}catch(e){
			alert(e.message);
		}
	});

	//提交banner
	function checkFile(){
		if(!$('select[name="taxonomy"]').val()){
			alert('请先选择分类');
			$('select[name="taxonomy"]').focus();
			return false;
		}
		if(!$("#banner").val()){
			alert('请先选择Banner图片,注意格式选择');
			$('#banner').click();
			return false;
		}
        /*
		$.post(baseUrl + lang + "/admin/system/addBanner",{act:'checkLP'}, function(data){
			if (data.done === true) {
				result=true;
			}else if(data.msg){
				alert(data.msg);
				result=false;
			}else{
				result=false;
			}
			return result;
		},"json");
        */
	}

	function fileResult(str){
		if(str==1){
			//document.getElementById("banner").outerHTML = document.getElementById("banner").outerHTML;
			//$('select[name="taxonomy"]').val('');
			alert('提交成功!');
			event_link(baseUrl + lang +'/admin/system/classify');
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
<style type="text/css">
	input.middle{
		width:97%;
	}
</style>
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
      <div class="position"><span>系统</span><span>></span><span>分类管理</span><span>></span><span>分类管理</span></div>
      <div class="operating"> 
	    <!-- <a href="javascript:;" id="import"><button class="operating_btn" type="button"><span class="import">返回列表</span></button></a>  -->
		<a href="javascript:;" id="selectAll" status="uncheck"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a> 
		<!-- <a href="javascript:;" id="deleteAll"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a> 
		<a href="javascript:;" id="recoverAll"><button class="operating_btn" type="button"><span class="recover">批量还原</span></button></a>  -->
	  </div>
    </div>
    <div class="content setting_form">
	  <table id="list_table" class="list_table settingList" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field">
			<th width="10%">选择</th>
			<th width="5%">编号</th>
			<th width="15%">分类名称</th>
			<th width="10%">URL别名</th>
			<th width="20%">分类描述</th>
			<!-- <th width="5%">Banner</th> -->
			<!-- <th width="12%">管理员</th> -->
			<th width="5%">分类排序</th>
			<th width="5%">文章数量</th>
			<th width="5%">是否激活</th>
			<th width="15%">创建时间</th>
			<th width="10%">删除</th>
		</tr>
		<tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr>
	</table>
	<div id="pageLists" class="pageLists clearfix hide"></div>
	<table class="list_table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;">
		<tr class="field" id="settingList">
			<td colspan="0" align="left" width="75%"><!-- <a href="javascript:;" style="color:#0099ff;float:left;padding-left:5px;" id="uploadBanner">[+]上传/修改Banner</a> --><font color="#ff6600" style="float:left;display:block"><b>提示：</b>点击相应项文字可直接进行修改</font></td>
		</tr>
	</table>
	<iframe name="ajaxifr" style="display:none;"></iframe>
	<!-- <form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addBanner" enctype="multipart/form-data" target="ajaxifr" onsubmit="return checkFile();">
		<div class="hide uploadBanner" style="text-align:left;margin-top:10px;line-height:25px;">
			<p><select name="taxonomy">
				<option value="" selected>-请先选择分类-</option>
			</select></p>
			<p class="hide"><img src="" id="banner_src"><br><a href="javascript:;" id="del_banner"><font color="#ff6600">删除该Banner</font></a></p>
			<p id="Btn" style="margin-top:5px;"><input type="file" name="banner" size="30" id="banner"><input type="hidden" name="path" id="path">
			<input type="submit" class="submit cursor" value="导入" onfocus="this.blur();" style="margin-bottom:10px;border:0px;padding-left:0px;"/></p>
			<p class="uploadTips" style="padding-top:5px;color:#666;">最大上传图片100k，支持格式'jpg','jpeg','gif','png'。<br><font color="#0099ff">上传时请确认图片长宽，标准格式为：width*height = 770px*125px</font><br><font color="#ff6600">若二级分类没Banner将直接继承父级Banner</font></p>
		</div>
	</form> -->
	<input type="hidden" name="currentPage" value="1">
	<!--/container-->
<!-- 引入底部-->
	<?php include('footer.php');?>
<!-- /引入底部-->