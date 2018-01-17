<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->

<script type="text/javascript">
<!--
	var link_type = 'indexPic',
		rows = 10,
		rowsNav = 50,
		email_type = 1;
	var menu_options = '';
	$(function(){
		
		$("a").live('click',function(){
			 this.blur();
		});

		
		$("ul.tab li").live('click',function(){
			index = $(this).index();
			if(index==1){
				if(!menu_options){
					_refresh_menus();
				}
			}
			if(index==2){
				setData(1);
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
			if(index == 5){
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
			if(index == 6){
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

	});

	

	function iResult(str){
		if(str==1 || !isNaN(str)){
			alert('保存成功!');
		}else if (str=='e'){
			alert('发送成功!');
			return false;
		}else{
			alert(str);
			return false;
		}
	}

	function iResultAlter(str,status){
	    if(status==0){
	        alert(str);
	        return false;
	    }
	    alert('操作成功!');
	    window.history.back('-1');
	}
//-->
</script>头部-->
  
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span>网站管理</span><span>></span><span>网站设置</span></div>
      <ul name="menu1" class="tab">
        <li class="selected"><a href="javascript:;">网站设置</a></li>
        <li><a href="javascript:;">菜单设置</a></li>
		<li><a href="javascript:;">广告切图</a></li>
		<li><a href="javascript:;">站点底部信息</a></li>
		<li><a href="javascript:;">首页三竖块</a></li>
		<li><a href="javascript:;">禁止IP访问</a></li>
		<li><a href="javascript:;">设置缓存时间</a></li>
		<li><a href="javascript:;">发送邮件</a></li>
      </ul>
    </div>
    <div class="content_box">
      	<div class="content link_target" align="left">
	        <!--container-->
	        <div class="content form_content" style="height: 298px;">
				<div class="div_box_setting">
					<!-- 基础信息 -->
					<div class="div" style="margin-left:10px;" align="left">
						<?php include('options_basic.php');?>
				 	</div>
				 	<!-- 菜单 -->
				 	<div class="div hide" style="margin:10px;" align="left">
						<?php include('options_menu.php');?>
				 	</div>
				 	<!-- 轮播 -->
				 	<div class="div hide" style="margin-left:10px;" align="left">
						<?php include('options_carousel.php');?>
					</div>
					<!-- 底部信息 -->
				 	<div class="div hide" style="padding:10px;">
						<?php include('options_footer.php');?>
				 	</div>
				 	<!-- 竖块 -->
					<div class="div hide" style="padding:10px;">
						<?php include('options_vertical.php');?>
					</div>
					<!-- 禁止IP -->
					<div class="div hide" style="padding:10px;">
						<?php include('options_forbidIP.php');?>
					</div>
					<!-- 设置缓存时间 -->
				 	<div class="div hide" style="padding:10px;">
						<?php include('options_cacheTime.php');?>
				 	</div>
				 	<div class="div hide" style="padding:0 10px 10px 10px;">
						<?php include('options_sendEmail.php');?>
				 	</div>
				</div>
				<input type="hidden" name="currentPage" value="1">
	        	<iframe name="ajaxifr" style="display:none;"></iframe>
	        </div>
      	</div>
		<div class="bg hide"></div>
<!--/container-->
<!-- 引入底部-->
<?php include('footer.php');?>
<!-- /引入底部-->
