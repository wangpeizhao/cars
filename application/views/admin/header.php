<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理 - <?=$title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		rows = 10,
		condition = [];
	$(function(){
		try{
			setData(1);

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
					del(ids);
				}
			});

			//文档回收站
			$(".operating #recycle").click(function(){
				event_link(baseUrl + lang +'/admin/'+_TYPE_+'/recycles');
			});

			//添加新闻资讯
			$(".operating #add").click(function(){
				event_link(baseUrl + lang +'/admin/'+_TYPE_+'/add');
			});

			//操作
			$("#list_table a").live('click',function(){
				try{
					var act = $(this).attr('act');
					var id = $(this).parent().attr('id');
					switch(act){
						case 'edit':
                          event_link(baseUrl + lang +'/admin/'+_TYPE_+'/edit/'+id+'.html');
							break;
						case 'del':
							if(confirm('是否把信息放到回收站内？')){
								del(id);
							}
							break;
					}
				}catch(e){
					alert(e.message);
				}
			});

		}catch(e){
			alert(e.message);
		}
	});

	function del(id){
		$.post(baseUrl + lang + '/admin/'+_TYPE_+'/del',{id:id}, function(data){
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
	}

	//搜索
	function doSearch(){
		try{
			var type = $('select[name="search"]').val(),
				keywords = $('input[name="keywords"]'),
				isError = false;
			keywordsVal = keywords.val();
			if(!validate(keywordsVal,'required')){
				keywords.val('');
				keywords.focus();
				keywords.val(keywordsVal);
				keywords.css('border','1px #FFABAB solid');
				alert('关键字不能为空');
				return isError;
			}
			if(type=='id'){
				keywordsVal = keywords.val();
				if(!validate(keywordsVal,'integer')){
					keywords.val('');
					keywords.focus();
					keywords.val(keywordsVal);
					keywords.css('border','1px #FFABAB solid');
					alert('ID格式不正确');
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
			"term_id":$('select[name="term_id"]').val(),
			"is_commend":$('select[name="is_commend"]').val(),
			"is_issue":$('select[name="is_issue"]').val(),
			"startTime":$('input[name="startTime"]').val(),
			"endTime":$('input[name="endTime"]').val()
		});
		setData(1);
	}
//-->
</script>
</head>
<body>
<div class="container">
  <div id="header">
    <div class="logo"> 
		<a href=""><img src="<?=LOGO?>" height="56"/></a> 
	</div>
	<!-- 引入菜单-->
    <div id="menu">
		<?php include('menu.php');?>
    </div>
	<!-- /引入菜单-->

	<script type="text/javascript">
	<!--
		var lang = '';//'/<?=_LANGUAGE_?>';
		$(function(){
			try{
				$("#loginout").click(function(){
					if(confirm('确定退出后台管理？')){
						window.location.href="<?=WEB_DOMAIN?>/admin/login/logout";
						if(isIE()==6 || isIE()==7 || isIE()==8){
							window.location.reload();
						}
					}
				});
			}catch(e){
				alert(e.message);
			}
		});
	//-->
	</script>
    <p>
	  <a href="javascript:;" id="loginout">退出管理</a> 
	  <a href="<?=WEB_DOMAIN?>/admin">后台首页</a> 
	  <a href="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>" target='_blank'>网站首页</a> 
	  <a>您好:<?php $userInfo=$this->session->userdata('adminLoginInfo');?>
			<label class='bold'><?=isset($userInfo['username'])?$userInfo['username']:''?></label>
			 ，当前身份
			<label class='bold'><?=isset($userInfo['grouptitle'])?$userInfo['grouptitle']:''?></label>
    </a>
	</p>
  </div>
  <div id="info_bar">
    <!-- <label class="navindex"><a href="#">快速导航管理</a></label> -->
    <span class="nav_sec"> </span>
  </div>
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->