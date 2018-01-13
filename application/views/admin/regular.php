<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-权限管理-权限资源</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';
	$(function(){
		try{
			//保存权限
			$(".saveRegular").click(function(){
				if(!$('select[name="roleId"]').val()){
					alert('请选择“角色分类”');
					$('select[name="roleId"]').focus();
					return false;
				}
				var idDOM = $(".regular .selected :checkbox");
				var regular = [];
				for(var i=0;i<idDOM.length;i++){
					if(idDOM[i].checked){
						regular.push(idDOM.eq(i).val());
					}
				}
				doRegular(regular);
			});

		}catch(e){
			alert(e.message);
		}
	});



/**
 * Created by Administrator on 2016/09/19.
 */

$(function(){
	//控制字数--字数超过5，显示提示框
	// var btn = $(".btn_perm");
	// $.each(btn,function(){
	// 	var text = $(this).text();
	// 	if(text.length > 5){
	// 		$(this).popover({
	// 	      trigger: 'hover',
	// 	      placement: 'top', 
	// 	      html: 'true',
	// 	      content: function(){
	// 	      	return text;
	// 	      },
	// 	      animation: false
	// 	    });
	// 	}
	// });
	
	//全部展开
	$(".js-see-all").click(function(){
		$(".form_table").find(".js-content").toggleClass("dn").removeAttr("style");

		if($(this).text()=="展开全部"){
			$(this).text("收起全部");
			$(".js-toggle").addClass('dbi').removeClass('addImg').html('收起');
		}else{
			$(this).text("展开全部");
			$(".js-toggle").removeClass('dbi').addClass('addImg').html('');
		}
		var toggle = $(".form_table").find(".js-toggle");
		//遍历所有级的下一级，判断是否需要展开符号
		$.each(toggle,function(){
			var content = $(this).parent().siblings(".js-content");
			var lev = content.children();
			$.each(lev,function(){
				var _children = $(this).children(".js-content").children()
				if(_children.length !== 0){
					$(this).children().find(".js-toggle").html("收起");
					if(_children.length < 2){
						//_children.css("min-height",'64px')
					}
				}else{
					$(this).children().find(".js-toggle").html("");
				}
			});
		})
	});

	//展开收起
	$(".js-toggle").click(function(){
		var content = $(this).parent().siblings(".js-content");
		if(content.is(":hidden")){
			$(this).html("收起").removeClass('addImg');
		}else{
			$(this).html("").addClass('addImg');
		}
		
		content.slideToggle("3000");
		$(this).toggleClass("dbi");
		var lev = content.children();
		if($(this).html() !=="收起"){
			$(this).parent().parent().find(".js-content").slideUp("2000");
			$(this).parent().parent().find(".js-toggle").removeClass("dbi");
		}
		//遍历下一级，有下级菜单则显示“展开符号”
		$.each(lev,function(){
			var _children = $(this).children(".js-content").children();
			if(_children.length !== 0){
				$(this).children().find(".js-toggle").addClass('addImg').html("");
				if(_children.length < 2){
					//_children.css("min-height",'64px')
				}
			}else{
				$(this).children().find(".js-toggle").removeClass('addImg').html("");
			}
		});
	});


	//选中效果
	$(".selected").click(function(){
		$(this).children('i').toggleClass("dn");
		var chkItem = $(this).children('input');
		var icon = $(this).children('i');
		var par = $(this).parent().parent();
		var uplevel = par.parent();
		var allicon = uplevel.find('i');
		var _parent = $(this).parents(".js-content").siblings().find("input[type='checkbox']");
		var parBtn = _parent.siblings('i');
		var param = $(this).children('input').attr("param");
		if(icon.hasClass("dn")){//取消选中时，下级自动取消
			chkItem.attr("checked", false);
			par.find('input[type="checkbox"]').attr("checked", false);
			par.find('i').addClass("dn");
			//“查看列表”取消则同级取消
			if(param == '_p_=_list_'){//若查看列表权限取消，则同级的其他权限也取消
				var samelevel = par.siblings();
				$.each(samelevel,function(){
					$(this).find('input[type="checkbox"]').attr("checked", false);
					$(this).find('i').addClass("dn");
				})
			}
			//判断同级是否全都未选中
			var choosed = false;
			$.each(allicon,function(){
				if($(this).hasClass("dn") == false){
					choosed = true;
					return false;
				}
			});
			//若同级全都取消选中，则上级自动取消勾选
			if(choosed == false){
				uplevel.siblings().find('i').addClass("dn");
				uplevel.siblings().find('input[type="checkbox"]').attr("checked", false);
			}
			
		}else{//选中时，下级以及相关联的上级自动选中
			chkItem.attr("checked", true);
			par.find('input[type="checkbox"]').attr("checked", true);
			par.find('i').removeClass("dn");
			//关联的上级选中
			_parent.attr("checked",true);
			parBtn.removeClass("dn");
			//任一选中同级别的“查看列表”选中
			if(param != '_p_=_list_'){//若查看列表权限取消，则同级的其他权限也取消
				var samelevel = par.siblings();
				$.each(samelevel,function(){
					var c_param = $(this).children().children().find('input').attr("param");
					if(c_param == '_p_=_list_'){
						$(this).find('input[type="checkbox"]').attr("checked", true);
						$(this).find('i').removeClass("dn");
					}
				})				
			}
		};
	});
});






	function doRegular(regular){
		$.post(baseUrl + lang + "/admin/system/regular",{regular:regular,roleId:$('select[name="roleId"]').val()}, function(data){
			if (data.done === true) {
				alert('提交成功,下次登录生效');
				location.reload();
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				alert('提交失败，请重试');
				return false;
			}
		},"json");
	}

	function iResult(str){
		if(str==1){
			alert('修改成功!');
			//event_link(baseUrl + lang +'/admin/system/compay/');
		}else{
			alert(str);
			return false;
		}
	}
//-->
</script>
<style type="text/css">
	input{
		margin-top:-1px;
		margin-bottom:1px;
		_margin-top:-4px;
		_margin-right:0px;
	}
	/* 第一层 */
.first_level{border-bottom: 1px solid #dfdfdf;position: relative;}
.first_level:last-child{border-bottom: 0;}
.first_left{width: 12%;float: left;padding-bottom: 5px;}
.first_right{width:86%;float: left;border-left: 1px solid #dfdfdf;}
.selected{position: relative;display: inline-block;}
.selected i{position: absolute;bottom:5px;right: 0px;font-size: 12px;color: #FFF;cursor: pointer;background: #20a3fe;width: 14px;height: 14px;line-height: 14px;text-align: center;font-weight: bold;}
.btn_perm{background: #fff;padding:4px 5px;border: 1px solid #ccc;outline:none;color: #333;margin:10px 0px 5px 10px;cursor: pointer;width:80px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
.toggle{display: inline-block;color: #20a3fe;text-align: center;cursor: pointer; vertical-align: middle;max-width:100px;}
a.addImg{display: inline-block;width: 24px;height: 20px;background: url(/themes/admin/images/icon_add.gif) no-repeat scroll center center;}


/* 第二级 */
.second_level{position: relative;width: 100%;border-bottom: 1px solid #dfdfdf;}
.second_level:last-child{border-bottom: 0;}
.second_left{width: 14%;float: left;padding-bottom: 5px;}
.second_right{width: 84%;float: left;border-left: 1px solid #dfdfdf;}

/* 第三级 */
.third_level{position: relative;width: 100%;border-bottom: 1px solid #dfdfdf;}
.third_level:last-child{border-bottom: 0;}
.third_left{width: 16%;float: left;padding-bottom: 5px;}
.third_right{width: 82%;float: left;border-left: 1px solid #dfdfdf;}

/* 第四级 */
.fourth_level{position: relative;width: 100%;border-bottom: 1px solid #dfdfdf;}
.fourth_level:last-child{border-bottom: 0;}
.fourth_left{width: 20%;float: left;padding-bottom: 5px;}
.fourth_right{width: 78%;float: left;border-left: 1px solid #dfdfdf;}

/* 第五级 */
.fifth_level{position: relative;width: 100%;border-bottom: 1px solid #dfdfdf;}
.fifth_level:last-child{border-bottom: 0;}
.fifth_left{width: 25%;float: left;padding-bottom: 5px;}
.fifth_right{width: 73%;float: left;border-left: 1px solid #dfdfdf;}

/* 第六级 */
.sixth_level{position: relative;width: 100%;padding-bottom: 10px;}
.components{padding-bottom: 10px;}
.components li{float: left;}
.components li .btn_perm{margin:10px 0px 0px 10px !important;width:75px !important;}
.components li i{right:0 !important;bottom:-4px !important;}
.sixth_right{width: 78%;float: left;border-left: 1px solid #dfdfdf;} 
.dn {
    display: none;
}
.see_all{
	margin-left: 10px;
    cursor: pointer;
}
.roles{
	height:28px;
	border-radius: 4px;
	margin: 0 10px;
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
		<div class="position"><span>系统</span><span>></span><span>权限管理</span><span>></span>权限资源</div>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content">
		<table class="form_table" style="margin-top:0px;">
			<colgroup>
				<col width="108px"><col>
			</colgroup>
			<tbody>
			  <tr>
				<th>角色分类：</th>
				<td>
				  <select name="roleId" class="roles" onchange="javascript:event_link(baseUrl + lang +'/admin/system/regular?roleId='+this.value);">
					<option value="">-请选择-</option>
					<?php if($roles){
						foreach($roles as $key=>$item){
							if(!$item['supervisor']){?>
					<option value="<?=$item['groupid']?>"<?=$roleId == $item['groupid']?' selected':''?>><?=$item['grouptitle']?></option>
					<?php }}}?>
				  </select> <font color="#ff6600">(超级管理员拥有所有权限) - <font color="#0066ff">(下次登录生效，包括当前用户)</font></font>
				</td>
			  </tr>
			  <tr>
				<th>展开/收起：</th>
				<td>
				  <a href="javascript:;" class="js-see-all see_all" style="color: #20a3fe;">展开全部</a>
				</td>
			  </tr>
			  <tr>
				<th>权限分配：</th>
				<td style="padding:0 10px;">
					<div class="regular" style="border-top:1px solid #dfdfdf;">
						<?=$permissions_lists?>
					</div>
				</td>
			  </tr>
			  <tr>
				<th></th>
				<td>
					<button type="submit" class="submit saveRegular" style="padding:5px 10px;*padding:0px;line-height:100%;margin-left: 10px;height:28px;">保存权限</button>
				</td>
			  </tr>
			</tbody>
		  </table>
        </div>
      </div>
      <!--/container-->
      <!-- 引入底部-->
      <?php include('footer.php');?>
	  <!-- /引入底部-->
