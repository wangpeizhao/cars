<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-网站管理-网站设置</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_link_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/admin/js/admin_indexNav_list.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/page.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		site_url = '<?=site_url('')?>',
		link_type = 'indexPic',
		rows = 10,
		rowsNav = 50,
		email_type = 1;
	var menu_options = '';
	$(function(){
		$('input[name="closeSites"]').click(function(){
			if($('input[name="closeSites"]:checked').val()){
				$("#closeReason").slideDown();
				$('input[name="link_id"]').val('');
			}else{
				$("#closeReason").slideUp();
				$('input[name="link_id"]').val('');
			}
		});

		$('input[name="isOpen"]').click(function(){
			if($('input[name="isOpen"]:checked').val()){
				$('textarea[name="IPs"]').removeAttr("disabled");
				$('input[name="link_id"]').val('');
			}else{
				$('textarea[name="IPs"]').attr("disabled","disabled");
				$('input[name="link_id"]').val('');
			}
		});

		$('textarea[name="IPs"]').focus(function(){
			if($('textarea[name="IPs"]').val()=='请输入需要禁止访问的IP,多个请用";"隔开'){
				$('textarea[name="IPs"]').val('');
			}
		});

		$('textarea[name="IPs"]').blur(function(){
			if($('textarea[name="IPs"]').val()==''){
				$('textarea[name="IPs"]').val('请输入需要禁止访问的IP,多个请用";"隔开');
			}
		});

		$("a").live('click',function(){
			 this.blur();
		});

		
		$("ul.tab li").live('click',function(){
			index = $(this).index();
			if(index==2){
				setData(1);
			}
			if(index==1){
				if(!menu_options){
					_refresh_menus();
				}
				// setDataIndexNav(1);
				/*
				$.post(baseUrl+ "/admin/system/indexNav",{act:'checkLP'}, function(data){
					if (data.done === true) {
						setDataIndexNav(1);
					}else if(data.msg){
						alert(data.msg);
						return false;
					}else{
						alert('提交失败，请重试');
						return false;
					}
					//return result;
				},"json");
				*/
			}
			if(index == 3){
				try{
					if(isIE()==6 || isIE()==7 || isIE()==8){
						alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
						return false;
					}
					if($.trim($('textarea[name="content"]').val())==''){
						$.post(baseUrl+lang+ "/admin/company/editFooter",{act:'get'}, function(data){
							if(data.done===true){
								$('textarea[name="content"]').val(data.data);
								CKEDITOR.replace('content');
							}else if(data.msg){
								alert(data.msg);
								$(".div_box_setting").find("div.div").eq(index).fadeOut();
								return false;
							}else{
								alert('提交失败');
								return false;
							}
						},"json");
					}
				}catch(e){
					alert(e.message);
				}
			}
			if(index == 4){
				try{
					if($.trim($('textarea[name="IPs"]').text())=='请输入需要禁止访问的IP,多个请用";"隔开'){
						$.post(baseUrl+lang+ "/admin/system/prohibitIp",{act:'get'}, function(data){
							if(data.done===true){
								$('textarea[name="IPs"]').val(data.data.IPs?data.data.IPs:'');
								if(data.data.isOpen==1){
									$("#isOpen").attr("checked",true);//打勾
									$('textarea[name="IPs"]').removeAttr("disabled");
								}else{
									$("#isOpen").attr("checked",'');//不打勾
									$('textarea[name="IPs"]').attr("disabled","disabled");
								}
								$("#IPtxt,#ipBtn").slideDown();
							}else if(data.msg){
								alert(data.msg);
								$(".div_box_setting").find("div.div").eq(index).fadeOut();
								return false;
							}else{
								alert('提交失败');
								return false;
							}
						},"json");
					}
				}catch(e){
					alert(e.message);
				}
			}
			if(index == 5){
				$.post(baseUrl+lang+ "/admin/system/cacheTime",{act:'get'}, function(data){
					if (data.done === true) {
						$('select[name="cache"] option').each(function(){
							if($(this).val()==data.data.cacheTime){
								$(this).attr("selected",true);
							}
						});
						$("#cacheBtn").removeClass('hide');
						return true;
					}else if(data.msg){
						alert(data.msg);
						return false;
					}else{
						alert('提交失败，请重试');
						return false;
					}
				},"json");
			}
			$(this).addClass('selected').siblings().removeClass('selected');
			$(".div_box_setting").find("div.div").eq(index).siblings().hide();
			$(".div_box_setting").find("div.div").eq(index).fadeIn();
		});

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
			/*
			$.post(baseUrl+lang+ "/admin/system/addLink",{act:'checkLP'}, function(data){
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

		// $("a.addNav").toggle(
		// 	function () {
		// 		$(".addNavTxt,.addNavBtn").fadeIn('slow');
		// 		$(".addNavBtn input.doAddNav").val('添加菜单信息');
		// 		$('input[name="link_name"]').val('');
		// 		$('input[name="link_url"]').val('http://www.');
		// 		$('input[name="link_rating"]').val('99');
		// 		$('textarea[name="link_description"]').val('');
		// 		$('input[name="link_id"]').val('');
		// 		$(this).text('[-]添加菜单');
		// 	},
		// 	function () {
		// 		$(".addNavTxt,.addNavBtn").fadeOut('slow');
		// 		$(this).text('[+]添加菜单');
		// 	}
		// );
		$("a.addNav").click(function(){
			$('.menu_lay select[name="pid"]').val('');
			$('.menu_lay input[name="title"]').val('');
			$('.menu_lay input[name="link"]').val('');
			$('.menu_lay input[name="sort"]').val('');
			$('.menu_lay input[name="parameter"]').val('');
			$('.menu_lay select[name="link_target"]').val('');
			isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
			$('.menu_lay').fadeIn();
			$('.layTitle').html('[+]添加菜单');
			$('.menu_lay input[name="act"]').val('add');
		});

		$('#submit_menu').click(function(){
			var parms = {};
			parms.pid = $.trim($('.menu_lay select[name="pid"]').val());
			parms.title = $.trim($('.menu_lay input[name="title"]').val());
			parms.link = $.trim($('.menu_lay input[name="link"]').val());
			parms.parameter =$.trim( $('.menu_lay input[name="parameter"]').val());
			parms.sort = $.trim($('.menu_lay input[name="sort"]').val());
			parms.show = $('.menu_lay input[name="show"]:checked').val();
			parms.act = $.trim($('.menu_lay input[name="act"]').val());
			parms.menu_id = $.trim($('.menu_lay input[name="menu_id"]').val());
			parms.link_target = $.trim($('.menu_lay select[name="link_target"]').val());
			var plat = 'client';
			if($('input[name="plat"]').attr('checked')){
				plat = 'admin';
			}
			parms.plat = plat;
			if(parms.pid === ''){
				alert('请选择父级菜单');
				return false;
			}
			if(!parms.title){
				alert('请填写菜单名称');
				$('input[name="title"]').focus();
				return false;
			}
			if(!parms.link){
				alert('请填写菜单链接');
				$('input[name="link"]').focus();
				return false;
			}
			var _url = parms.act=='edit'?'/admin/system/menuEdit':'/admin/system/menuAdd';
			$.post(baseUrl+lang+ _url,{act:'post',parms:parms}, function(data){
				if (data.code == 1) {
					alert(data.msg);
					_refresh_menus();
					$(".close").click();
					return false;
				}else{
					alert(data.msg);
					return false;
				}
			},"json");
		});


		$(".close").live('click',function() {
			isIE()==6?$(".bg").hide():$(".bg").fadeOut("slow");
			if($(".lay").is(':visible')){$(".lay").fadeOut("slow");}
		});

		$(".doAddNav").live('click',function(){
			try{
				var link_name = $('input[name="link_name"]');
				var link_url = $('input[name="link_url"]');
				var link_target = $('select[name="link_target"]');
				var link_parent = $('select[name="link_parent"]');
				var link_rating = $('input[name="link_rating"]');
				var data = [];
				if(!$.trim(link_name.val())){
					alert('请先输入菜单名称');
					link_name.focus();
					return false;
				}
				if(!$.trim(link_url.val())){
					alert('请先输入菜单URL地址');
					link_url.focus();
					return false;
				}else if(!IsURL($.trim(link_url.val()))){
					alert('输入的菜单URL地址格式不正确');
					var Tlink_url = link_url.val();
					link_url.val('');
					link_url.focus();
					link_url.val(Tlink_url);
					return false;
				}
				if(!$.trim(link_target.val())){
					alert('请先选择菜单打开方式');
					link_target.focus();
					return false;
				}
				if(!$.trim(link_rating.val())){
					alert('请先输入菜单排序');
					link_rating.focus();
					return false;
				}else if(!validate($.trim(link_rating.val()),'integer')){
					alert('输入的菜单排序格式不正确,只能为整数');
					link_rating.focus();
					return false;
				}
				data.push({'link_parent':($.trim(link_parent.val())),'link_name':($.trim(link_name.val())),'link_url':($.trim(link_url.val())),'link_target':($.trim(link_target.val())),'link_rating':($.trim(link_rating.val()))});
				if(parseInt($('input[name="link_id"]').val())>0){
					var id=parseInt($('input[name="link_id"]').val());
					$.post(baseUrl+lang+ "/admin/system/editIndexNav",{id:id,data:data}, function(data){
						if (data.done === true) {
							alert('修改成功');
							link_name.val('');
							link_url.val('http://www.');
							link_parent.val('');
							link_target.val('');
							link_rating.val('99');
							$(".addNavBtn input.doAddNav").val('添加菜单信息');
							$('input[name="link_id"]').val('');
							setDataIndexNav(1);
						}else if(data.msg){
							alert(data.msg);
							return false;
						}else{
							alert('提交失败，请重试');
							return false;
						}
					},"json");
				}else{
					$.post(baseUrl+lang+ "/admin/system/addNav",{data:data}, function(data){
						if (data.done === true) {
							alert('添加成功');
							link_name.val('');
							link_url.val('http://www.');
							link_parent.val('');
							link_target.val('');
							link_rating.val('99');
							setDataIndexNav(1);
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

		//操作
		$("#list_table_indexNav a").live('click',function(){
			try{
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'add':
						addIndexNav($(this));
						break;
					case 'edit':
						editIndexNav($(this));
						break;
					case 'del':
						if(confirm('确定要彻底删除本菜单？不能回撤！')){
							delIndexNav(id);
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

		$('input[name="email_type"]').click(function(){
			email_type = $(this).val();
			if(email_type==1){
				$('#email_address').show();
			}else{
				$('#email_address').hide();
			}
		});

		$('input[name="plat"]').click(function(){
			_refresh_menus();
		});

	});

	function _refresh_menus(){
		var plat = 'client';
		if($('input[name="plat"]').attr('checked')){
			plat = 'admin';
		}
		$.post(baseUrl+lang+ "/admin/system/options",{act:'get',plat:plat}, function(data){
			if (data.code == 1) {
				menu_options = data.result.select_menus;
				$('.menu select[name="pid"] option:gt(1)').remove();
				$('.menu select[name="pid"]').append(menu_options);
				var menus_lists = data.result.menus_lists;
				$('#list_table_indexNav tbody tr').remove();
				$('#list_table_indexNav tbody').append(menus_lists);
			}
		},"json");
	}

	function checkEmail(){
		if(email_type==1){
			var emails = $('input[name="email_address"]').val();
			if($.trim(emails)){
				var email_arr = emails.split(',');
				for(var n in email_arr){
					if(!validate(email_arr[n],'email')){
						alert('您输入了不合法的Email地址，请先确认');
						$('input[name="email_address"]').focus();
						return false;
					}
				}
			}else{
				alert('请先输入收件人Email');
				$('input[name="email_address"]').focus();
				return false;
			}
		}
		if(!$.trim($('input[name="email_subject"]').val())){
			alert('请先输入邮件标题');
			$('input[name="email_subject"]').focus();
			return false;
		}
		if(!$.trim($('textarea[name="email_content"]').val())){
			alert('请先输入邮件内容');
			$('textarea[name="email_content"]').focus();
			return false;
		}
	}

	function addIndexNav(_S){
		var _S_ = _S.parent();
		$('.menu_lay select[name="pid"]').val(_S_.attr('id'));
		$('.menu_lay input[name="title"]').val('');
		$('.menu_lay input[name="link"]').val('');
		$('.menu_lay input[name="parameter"]').val('');
		$('.menu_lay input[name="sort"]').val('');
		$('.menu_lay select[name="link_target"]').val('');
		$('.menu_lay input[name="act"]').val('add');
		isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
		$('.menu_lay').fadeIn();
		$('.layTitle').html('[+]添加菜单');
	}

	function editIndexNav(_S){
		var _S_ = _S.parent().parent();
		$('.menu_lay select[name="pid"]').val(_S_.attr('_pid'));
		$('.menu_lay input[name="title"]').val(_S_.attr('_title'));
		$('.menu_lay input[name="link"]').val(_S_.attr('_link'));
		$('.menu_lay input[name="parameter"]').val(_S_.attr('_parameter'));
		$('.menu_lay input[name="sort"]').val(_S_.attr('_sort'));
		$('.menu_lay select[name="link_target"]').val(_S_.attr('_link_target'));
		$('.menu_lay input[name="show"]').each(function(){
			if($(this).val()==_S_.attr('_show')){
				$(this).attr("checked",true);
			}
		});
		$('.menu_lay input[name="act"]').val('edit');
		$('.menu_lay input[name="menu_id"]').val(_S.parent().attr('id'));
		isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
		$('.menu_lay').fadeIn();
		$('.layTitle').html('编辑菜单');
	}

	function delIndexNav(id){
		$.post(baseUrl+lang+ "/admin/system/menuDel",{id:id}, function(data){
			if (data.done === true) {
				alert('删除成功');
				$("#"+id).parent().remove();
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				alert('删除失败，请重试');
				return false;
			}
		},"json");
	}

	function iResult(str){
		if(str==1 || !isNaN(str)){
			alert('保存成功!');
			//$('#tlogo').attr('src','');
			//$('#tlogo').attr('src','<?=site_url('')?>themes/common/images/logo.gif');
			//event_link(baseUrl+lang+'/admin/system/options');
		}else if (str=='e'){
			alert('发送成功!');
			return false;
		}else{
			alert(str);
			return false;
		}
	}

	function checkForm(){
		var sitesName = $('input[name="sitesName"]');
		if(!$.trim(sitesName.val())){
			alert('网站名称不能为空');
			//sitesName.css('border','1px solid #E35C00');
			sitesName.focus();
			return false;
		}
		
		var companyLinkman = $('input[name="companyLinkman"]');
		if(!$.trim(companyLinkman.val())){
			alert('公司负责人不能为空');
			//companyName.css('border','1px solid #E35C00');
			companyName.focus();
			return false;
		}
		
		var companyMobile = $('input[name="companyMobile"]');
		if(!$.trim(companyMobile.val())){
			alert('负责人手机不能为空');
			//companyName.css('border','1px solid #E35C00');
			companyName.focus();
			return false;
		}
		
		var companyName = $('input[name="companyName"]');
		if(!$.trim(companyName.val())){
			alert('公司名称不能为空');
			//companyName.css('border','1px solid #E35C00');
			companyName.focus();
			return false;
		}

		var companyZipCode = $('input[name="companyZipCode"]');
		if($.trim(companyZipCode.val())){
			if(!validate($.trim(companyZipCode.val()),'zip')){
				alert('公司邮编格式不正确');
				//companyZipCode.css('border','1px solid #E35C00');
				companyZipCode.select();
				return false;
			}
		}

		var companyEmail = $('input[name="companyEmail"]');
		if($.trim(companyEmail.val())){
			if(!validate($.trim(companyEmail.val()),'email')){
				alert('公司邮箱格式不正确');
				//companyEmail.css('border','1px solid #E35C00');
				companyEmail.select();
				return false;
			}
		}

		if($('input[name="closeSites"]:checked').val()){
			var closeReason = $('textarea[name="closeReason"]');
			if(!$.trim(closeReason.val()) || $.trim(closeReason.val())=='请描述关闭原因'){
				alert('关闭网站原因不能为空');
				//closeReason.css('border','1px solid #E35C00');
				closeReason.select();
				return false;
			}
		}
	}

	function checkIndexB(){
		try{
			var select = $("form#indexBlock").find("select");
			if($.trim(select.eq(0).val())==''){
				alert('请选择“首页三竖块第一(左)”的分类');
				return false;
			}
			if($.trim(select.eq(1).val())==''){
				alert('请选择“首页三竖块第二(中)”的分类');
				return false;
			}
			if($.trim(select.eq(2).val())==''){
				alert('请选择“首页三竖块第三(右)”的分类');
				return false;
			}
			/*
			$.post(baseUrl+lang+ "/admin/system/indexBlock",{act:'checkLP'}, function(data){
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
		}catch(e){
			alert(e.message);
		}
	}

	function checkIPs(){
		try{
			if($('input[name="isOpen"]:checked').val()){
				var ips = $('textarea[name="IPs"]').val();
					ips = ips.charAt(ips.length - 1)==';'?ips:ips+';';
				if(!validate(ips,'ips')){
					alert('“IP列”格式不正确,多个时请用" ; "(半角分号)隔开');
					return false;
				}
			}
		}catch(e){
			alert(e.message);
		}
	}

	function checkCache(){
		try{
			if($('select[name="cache"]').val()=='0'){
				if(!confirm('关闭缓存将会清除所有缓存文件！是否继续？')){
					return false;
				}
			}
			/*
			$.post(baseUrl+lang+ "/admin/system/cache",{act:'checkLP'}, function(data){
				if (data.done === true) {
					//$("#cache_form").submit();
					return true;
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('提交失败，请重试');
					return false;
				}
			},"json");
			*/
		}catch(e){
			alert(e.message);
		}
	}

	function fileResult(str){
		if(str==1 || str==2){
			document.getElementById("link_image").outerHTML = document.getElementById("link_image").outerHTML;
			$('.addLinks input[name="link_name"]').val('');
			$('.addLinks input[name="link_url"]').val('http://www.');
			$('.addLinks input[name="link_rating"]').val('99');
			$('.addLinks textarea[name="link_description"]').val('');
			//$('.addLinks input[name="link_target"]').attr("checked",'_blank');
			//$('.addLinks input[name="link_visible"]').attr("checked",'1');
			$(".addLinks input:radio[name='link_target']").each(function(){
				if($(this).val()=='_blank'){
					$(this).attr("checked",true);
				}
			});
			$("input:radio[name='link_visible']").each(function(){
				if($(this).val()==1){
					$(this).attr("checked",true);
				}
			});
			if(str==1){
				alert('添加成功');
			}else{
				alert('修改成功');
			}
			$("#addLink").click();
			$("#addLink").html('[+]添加该类切图');
			setData($('input[name="currentPage"]').val());
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
<style type="text/css">
	.menu_action a{
		margin-right: 10px!important;
		display: inline-block;
		width:12px;
		height:12px;
	}
	.menu_action a.menu_add{
		background: url('/themes/admin/images/icon_add.gif') no-repeat scroll center center;
	}
	.menu_action a.menu_edit{
		background: url('/themes/admin/images/icon_edit.gif') no-repeat scroll center center;
	}
	.menu_action a.menu_del{
		background: url('/themes/admin/images/icon_del.gif') no-repeat scroll center center;
	}
	.mFirst font{
		display: block;
		padding-left: 0;
		text-align: left;
		margin-left: 20px;
	}
	.mSecond font{
		display: block;
		padding-left: 25px;
		text-align: left;
		margin-left: 20px;
	}
	.mThird font{
		display: block;
		padding-left: 50px;
		text-align: left;
		margin-left: 20px;
	}
	.mFourth font{
		display: block;
		padding-left: 75px;
		text-align: left;
		margin-left: 20px;
	}
	.mFifth font{
		display: block;
		padding-left: 100px;
		text-align: left;
		margin-left: 20px;
	}
	.mSixth font{
		display: block;
		padding-left: 125px;
		text-align: left;
		margin-left: 20px;
	}
	.mSeventh font{
		display: block;
		padding-left: 150px;
		text-align: left;
		margin-left: 20px;
	}
	.mEighth font{
		display: block;
		padding-left: 175px;
		text-align: left;
		margin-left: 20px;
	}
	.mNinth font{
		display: block;
		padding-left: 200px;
		text-align: left;
		margin-left: 20px;
	}
	.mTenth font{
		display: block;
		padding-left: 225px;
		text-align: left;
		margin-left: 20px;
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
      <div class="position"><span>系统</span><span>></span><span>网站管理</span><span>></span><span>网站设置</span></div>
      <ul name="menu1" class="tab">
        <li class="selected"><a href="javascript:;">网站设置</a></li>
        <li><a href="javascript:;">菜单设置</a></li>
		<li><a href="javascript:;">广告切图</a></li>
		<li><a href="javascript:;">站点底部信息</a></li>
		<!-- <li><a href="javascript:;">首页三竖块</a></li> -->
		<li><a href="javascript:;">禁止IP访问</a></li>
		<li><a href="javascript:;">设置缓存时间</a></li>
		<!-- <li><a href="javascript:;">发送邮件</a></li> -->
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
			<div class="div_box_setting">
				<div class="div" style="margin-left:10px;" align="left">
				  <form method="post" enctype="multipart/form-data" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/options" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
					  <table class="form_table">
						<colgroup>
							<col width="148px"><col>
						</colgroup>
						<tbody>
						  <tr>
							<th>网站名称：</th>
							<td><input type="text" title="网站名称不能为空" value="<?=isset($options['sitesName'])?$options['sitesName']:''?>" name="sitesName" class="normal" maxlength="80"><label style="color:red;">*</label><span class="tip">网站名称</span></td>
						  </tr>
						  <tr>
							<th>公司负责人：</th>
							<td><input type="text" title="公司负责人不能为空" value="<?=isset($options['companyLinkman'])?$options['companyLinkman']:''?>" name="companyLinkman" class="normal" maxlength="20"><label style="color:red;">*</label><span class="tip">公司负责人</span></td>
						  </tr>
						  <tr>
							<th>负责人手机：</th>
							<td><input type="text" title="负责人手机不能为空" value="<?=isset($options['companyMobile'])?$options['companyMobile']:''?>" name="companyMobile" class="normal" maxlength="12"><label style="color:red;">*</label><span class="tip">负责人手机</span></td>
						  </tr>
						  <tr>
							<th>公司名称：</th>
							<td><input type="text" title="公司名称不能为空" value="<?=isset($options['companyName'])?$options['companyName']:''?>" name="companyName" class="normal" maxlength="80"><label style="color:red;">*</label><span class="tip">公司名称</span></td>
						  </tr>
						  <tr>
							<th>公司电话：</th>
							<td><input type="text" title="请填写公司电话" value="<?=isset($options['companyPhone'])?$options['companyPhone']:''?>" name="companyPhone" class="normal" maxlength="80"><span class="tip">公司电话(可填写多个，用逗号隔开)</span></td>
						  </tr>
						  <tr>
							<th>公司传真：</th>
							<td><input type="text" title="请填写公司传真" value="<?=isset($options['companyFax'])?$options['companyFax']:''?>" name="companyFax" class="normal" maxlength="80"><span class="tip">公司传真(可填写多个，用逗号隔开)</span></td>
						  </tr>
						  <tr>
							<th>公司地址：</th>
							<td><input type="text" title="请填写公司地址" value="<?=isset($options['companyAddress'])?$options['companyAddress']:''?>" name="companyAddress" class="normal" maxlength="80"><span class="tip">公司地址(越详细越好)</span></td>
						  </tr>
						  <tr>
							<th>公司邮编：</th>
							<td><input type="text" title="请填写公司邮编" pattern="^\d{6}$" value="<?=isset($options['companyZipCode'])?$options['companyZipCode']:''?>" name="companyZipCode" class="normal" maxlength="6"><span class="tip">公司邮编</span></td>
						  </tr>
						  <tr>
							<th>公司E-mali：</th>
							<td><input type="text" title="请填写公司E-mali" value="<?=isset($options['companyEmail'])?$options['companyEmail']:''?>" name="companyEmail" class="normal" maxlength="80"><span class="tip">公司E-mali</span></td>
						  </tr>
						  <tr>
							<th>公司服务QQ：</th>
							<td><input type="text" title="请填写公司服务QQ" value="<?=isset($options['companyQQ'])?$options['companyQQ']:''?>" name="companyQQ" class="normal" maxlength="80"><span class="tip">公司服务QQ</span></td>
						  </tr>
						  <tr>
							<th>公司服务微信：</th>
							<td><input type="text" title="请填写公司服务微信" value="<?=isset($options['companyWeiXin'])?$options['companyWeiXin']:''?>" name="companyWeiXin" class="normal" maxlength="80"><span class="tip">公司服务微信</span></td>
						  </tr>
						  <tr>
							<th>公司服务Skype：</th>
							<td><input type="text" title="请填写公司服务Skype" value="<?=isset($options['companySkype'])?$options['companySkype']:''?>" name="companySkype" class="normal" maxlength="80"><span class="tip">公司服务Skype</span></td>
						  </tr>
						  <tr>
							<th>公司服务热线：</th>
							<td><input type="text" title="请填写公司免费服务热线" value="<?=isset($options['companyHotline'])?$options['companyHotline']:''?>" name="companyHotline" class="normal" maxlength="80"><span class="tip">公司免费服务热线(可填写多个，用逗号隔开)</span></td>
						  </tr>
						  <tr>
							<th>首页keywords：</th>
							<td><input type="text" title="请填写首页keywords" value="<?=isset($options['IndexKeywords'])?$options['IndexKeywords']:''?>" name="IndexKeywords" class="normal" maxlength="100"><span class="tip">多个请用空格或逗号隔开</span></td>
						  </tr>
						  <tr>
							<th>首页description：</th>
							<td><textarea rows="" title="请填写首页description" cols="" name="IndexDescription"><?=isset($options['IndexDescription'])?$options['IndexDescription']:''?></textarea><span class="tip">公司的详细描述</span></td>
						  </tr>
						  <!-- <tr>
							<th>首页视频链接：</th>
							<td><textarea rows="" title="请填写首页视频链接" cols="" name="VideoUrl"><?=isset($options['VideoUrl'])?$options['VideoUrl']:''?></textarea><span class="tip">首页视频链接</span></td>
						  </tr> -->
						  <tr>
							<th>网站LOGO：</th>
							<td>
								<input type="file" name="logo" id="logo" size="30">
								<?=isset($logo) && $logo?'<br><a href="'.site_url('/').$logo.'" target="_blank"><img src="'.site_url('/').$logo.'" size="30" id="tlogo"/></a>':''?>
								<br>
								<span class="tip">logo需按比标准上传：<!-- <font color="#ff3300">width:194px; height: 56px;</font> -->，上传时请设计好长、宽、高！最大支持512k，支持格式：'gif'</span>,</td>
						  </tr>
						  <tr>
							<th>关闭网站：</th>
							<td>
							  <label class="attr cursor" style="margin-left:0px;"><input type="checkbox" value="1" name="closeSites" <?=isset($options['closeSites']) && $options['closeSites']==1?'checked':''?>>是(<font color="#999">打“√”需要填写“关闭原因”</font>)</label>
							  <p class="<?=isset($options['closeSites']) && $options['closeSites']==1?'':'hide'?>" id="closeReason"><textarea name="closeReason" rows="" cols=""><?=isset($options['closeReason']) && $options['closeReason']?$options['closeReason']:'请描述关闭原因'?></textarea></p>
							</td>
						  </tr>
						  <tr>
							<th></th>
							<td><button type="submit" class="submit"><span>保存网站设置</span></button></td>
						  </tr>
						</tbody>
					  </table>
				  </form>
			 </div>
			 <div id="" class="div hide" style="margin:10px;" align="left">
				<!-- <form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/addIndexNav" target="ajaxifr"> -->
				<table id="list_table_indexNav" class="list_table" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;width:100%;">
					<thead>
						<tr class="field">
							<th width="25%" style="text-align: left;padding-left:10px">菜单名称</th>
							<th width="10%">排序</th>
							<th width="10%">是否显示</th>
							<th width="10%">打开方式</th>
							<th width="25%">菜单链接</th>
							<th width="10%">附加参数</th>
							<th width="10%">操作</th>
						</tr>
					</thead>
					<tbody>
						<!-- <tr><td colspan="12" align="center" style="padding:20px;"><img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/></td></tr> -->
					</tbody>
				</table>
				<div class="clear"></div>
				<table class="list_table" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee" style="line-height: 25px;width:100%;">
					<colgroup>
						<col width="15%"></col>
						<col width="5%"></col>
						<col width="20%"></col>
						<col width="30%"></col>
						<col width="14%"></col>
						<col width="8%"></col>
						<col width="8%"></col>
					</colgroup>
					<tr>
						<td align="left" colspan="7">
							<a class="addNav" style="color:#0099ff;float:left;padding-left:5px;" href="javascript:;">[+]添加菜单</a>
							<label><input type="checkbox" name="plat" value="admin">后台菜单</label>
						</td>
					</tr>
					<tr class="hide addNavTxt">
						<td colspan="2">
							<select name="link_parent">
								<option value="0" selected>-请先选择菜单-</option>
							</select>
						</td>
						<td><input class="normal" type="text" name="link_name" maxlength="20" title="请填写菜单名称" placeholder="请填写菜单名称" value=""/></td>
						<td><input class="normal" type="text" name="link_url" maxlength="255" title="请填写菜单URL地址" placeholder="请填写菜单URL地址" value="http://www."/></td>
						<td>
							<select name="link_target">
								<option value="" selected>-请选择-</option>
								<option value="_self"> 当前窗口中打开</option>
								<option value="_parent"> 父类窗口集中打开</option>
								<option value="_top"> 整个窗口中打开</option>
								<option value="_blank"> 新的窗口中打开</option>
							</select>
						</td>
						<td><input class="small" type="text" name="link_rating" title="请填写菜单排序" maxlength="2" value="99"/></td>
						<td>-</td>
					</tr>
					<tr class="hide even addNavBtn">	
						<td align="left" class="Btn" colspan="7"><input type="hidden" name="link_id">
							<input type="button" style="padding:0 10px;margin-bottom:10px;border:0px;height: 29px;" onfocus="this.blur();" value=" 添加菜单信息 " class="submit cursor l doAddNav">
							<span style="color:#999;display:block;float:left;text-align:left;">设置排序等级，越大越靠前.<font color="#ff3300">只能是数字</font><br>_blank：新窗口或新标签。_top：不包含框架的当前窗口或标签。_parent：同一窗口或标签。</span>
						</td>
					</tr>
				</table>
				<div class="clear"></div>
				<!-- </form> -->
			 </div>
			 <div id="" class="div hide" style="margin-left:10px;" align="left">
				<div id="" class="index_pic" style="margin-top:10px;width:100%;">
					<!-- <div style="margin:10px 0;">
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
					</div> -->
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
			 </div>
			 <div class="div hide" style="padding:10px;">
				<form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/company/editFooter" target="ajaxifr">
					<textarea name="content"></textarea>
					<p><button type="submit" class="submit"><span>提交保存</span></button></p>
				</form>
			 </div>
			 <!-- <div class="div hide" style="padding:10px;">
				<form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/indexBlock" target="ajaxifr" id="indexBlock" onSubmit="return checkIndexB();">
					<table class="list_table link_list_table field" border="0" align="left" cellpadding="0" cellspacing="2" bgcolor="#e1e5ee" style="line-height:60px;">
						<tr>
							<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第一(左)：</td>
							<td align="left" width="15%">
								<select name="indexBlockLeft">
									<option value="">-请选择-</option>
									<?php if(!empty($indexB[0]['sunTerm'])){
										foreach($indexB[0]['sunTerm'] as $item){
											if($item['slug']==$options['indexB']['indexL']){?>
									<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
									<?php }else{?>
									<option value="<?=$item['slug']?>"><?=$item['name']?></option>
									<?php }}}?>
								</select>
							</td>
							<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
						</tr>
						<tr>
							<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第二(中)：</td>
							<td align="left" width="15%">
								<select name="indexBlockMid">
									<option value="">-请选择-</option>
									<?php if(!empty($indexM[0]['sunTerm'])){
										foreach($indexM[0]['sunTerm'] as $item){
											if($item['slug']==$options['indexB']['indexM']){?>
									<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
									<?php }else{?>
									<option value="<?=$item['slug']?>"><?=$item['name']?></option>
									<?php }}}?>
								</select>
							</td>
							<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
						</tr>
						<tr>
							<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第一(右)：</td>
							<td align="left" width="15%">
								<select name="indexBlockRight">
									<option value="">-请选择-</option>
									<?php if(!empty($indexB[0]['sunTerm'])){
										foreach($indexB[0]['sunTerm'] as $item){
											if($item['slug']==$options['indexB']['indexR']){?>
									<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
									<?php }else{?>
									<option value="<?=$item['slug']?>"><?=$item['name']?></option>
									<?php }}}?>
								</select>
							</td>
							<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
						</tr>
					</table>
					<div class="clear"></div>
					<p><button type="submit" class="submit"><span>提交保存</span></button></p>
				</form>
			 </div> -->
			 <div class="div hide" style="padding:10px;">
				<form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/prohibitIp" target="ajaxifr" onsubmit="return checkIPs();">
					<table class="form_table">
						<colgroup>
							<col width="138px"><col>
						</colgroup>
						<tbody>
						  <tr>
							<th>禁止访问的IP：</th>
							<td>
							  <label class="attr cursor" style="margin-left:0px;">
								<input type="checkbox" value="1" name="isOpen" id="isOpen"/>开启(<font color="#999">打“√”需要填写“IP列”,多个请用" ; "(半角分号)隔开</font>)
							  </label>
							  <p class="hide" id="IPtxt"><textarea name="IPs" id="IPs" rows="" cols="">请输入需要禁止访问的IP,多个请用";"隔开</textarea></p>
							</td>
						  </tr>
						  <tr id="ipBtn" class="hide">
							<th>&nbsp;</th>
							<td><button type="submit" class="submit"><span>提交保存</span></button></td>
						  </tr>
						 </tbody>
					</table>
				</form>
			 </div>
			 <div class="div hide" style="padding:10px;">
				<form id="cache_form" method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/cacheTime" target="ajaxifr" onsubmit="return checkCache();">
					<table class="form_table">
						<colgroup>
							<col width="138px"><col>
						</colgroup>
						<tbody>
						  <tr>
							<th>开启缓存：</th>
							<td>
							  <select name="cache" class="auto">
								<option value="0" selected>关闭缓存</option>
								<option value="1">01分钟</option>
								<option value="2">02分钟</option>
								<option value="5">05分钟</option>
								<option value="10">10分钟</option>
								<option value="30">30分钟</option>
								<option value="60">60分钟</option>
								<option value="720">12个钟</option>
								<option value="1440">1天</option>
								<option value="2880">2天</option>
								<option value="10080">1周</option>
							  </select>
							</td>
						  </tr>
						  <tr id="cacheBtn" class="hide">
							<th>&nbsp;</th>
							<td><button type="submit" class="submit"><span>提交保存</span></button></td>
						  </tr>
						 </tbody>
					</table>
				</form>
			 </div>
			 <div class="div hide" style="padding:0 10px 10px 10px;">
				<form id="cache_form" method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/sendemail" target="ajaxifr" onsubmit="return checkEmail();">
					<table class="form_table">
						<colgroup>
							<col width="138px"><col>
						</colgroup>
						<tbody>
						  <tr>
							<th>请选择发送类型：</th>
							<td>
							  <label class="attr cursor block"><input type="radio" value="1" name="email_type" required checked>自定义发送邮件(单发邮件)</label>
							  <label class="attr cursor block"><input type="radio" value="2" name="email_type" required>向所有产品底盘的用户发群发邮件</label>
							</td>
						  </tr>
						  <tr id="email_address">
							<th>收件人Email：</th>
							<td class="f"><input type="text" value="" name="email_address" placeholder="地址可以是单个或多个(用','分隔)" class="normal" style="width:400px;"></td>
						  </tr>
						  <tr>
							<th>邮件标题：</th>
							<td class="f"><input type="text" value="" name="email_subject" placeholder="系统会自动追加‘-来自国际人才圈’" class="normal" maxlength="255"style="width:400px;"></td>
						  </tr>
						  <tr>
							<th>邮件内容：</th>
							<td class="f"><textarea name="email_content"></textarea></td>
						  </tr>
						  <tr id="cacheBtn">
							<th>&nbsp;</th>
							<td><button type="submit" class="submit"><span>确定发送</span></button></td>
						  </tr>
						 </tbody>
					</table>
				</form>
			 </div>
		</div>
		<input type="hidden" name="currentPage" value="1">
        <iframe name="ajaxifr" style="display:none;"></iframe>
        </div>
      </div>
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
		<div class="bg hide"></div>
      <!--/container-->
      <!-- 引入底部-->
      <?php include('footer.php');?>
	  <!-- /引入底部-->
