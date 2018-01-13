<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-关于公司-公司简介</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';
	var index =0;
	$(function(){
		try{
			if(isIE()==6 || isIE()==7 || isIE()==8){
				alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
				//return false;
			}
			var firstA = $('ul.tab li').eq(0).find('a');
			$(".position #term").html(firstA.text());
			document.title='后台管理-关于公司-'+firstA.text();
			$('input[name="term"]').val(firstA.attr('term'));
			getContent(firstA.attr('term'),0);

			$("a").live('click',function(){
				 this.blur();
			});

			$("ul.tab li").live('click',function(){
				if(isIE()==6 || isIE()==7 || isIE()==8){
					alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
					return false;
				}
				$(".position #term").html($(this).text());
				document.title='后台管理-关于公司-'+$(this).text();
				$(this).addClass('selected').siblings().removeClass('selected');
				$('input[name="term"]').val($(this).find('a').attr('term'));
				index = $(this).index();
				$('input[name="index"]').val(index);
				getContent($(this).find('a').attr('term'),index);
				//
				$(".div_box").find("div.div #editor .editor").eq(index).siblings().hide();
				$(".div_box").find("div.div #editor .editor").eq(index).fadeIn();
			});
		}catch(e){
			alert(e.message);
		}
	});
	function iResult(str){
		if(!isNaN(str)){
			alert('修改成功!');
			//event_link(baseUrl + lang +'/admin/system/compay/');
		}else{
			alert('修改失败？检查下读写权限！');
			return false;
		}
	}

	function checkForm(){
		if(!$.trim($('textarea[name="content_'+index+'"]').val())){
			alert('详细内容不能为空');
			return false;
		}
	}

	function getContent(term,index){
		try{
			$.post(baseUrl + lang + "/admin/company",{term:term,act:'get'}, function(data){
				if(data.done===true){
					$('textarea[name="content_'+index+'"]').val('');
					$('textarea[name="content_'+index+'"]').val(data.data);
					CKEDITOR.replace('content_'+index,{
						customConfig: 'custom/config.js'
					});
				}else if(data.msg){
					alert(data.msg);
					return false;
				}else{
					alert('提交失败');
					return false;
				}
			},"json");
		}catch(e){
			alert(e.message);
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
	<?php if(!empty($terms)){
			foreach($terms as $item){?>
      <div class="position"><span>系统</span><span>></span><span><?=$item['name']?></span><span>></span><span id="term"></span></div>
      <ul class="tab" style="margin-top:0px;">
		<?php if(!empty($item['sunTerm'])){$sunTerm=$item['sunTerm'];
			foreach($item['sunTerm'] as $key=>$term){
				if($key==0){?>
					<li class="selected"><a href="javascript:;" term="<?=$term['slug']?>"><?=$term['name']?></a></li>
				<?php }else{?>
					<li><a href="javascript:;" term="<?=$term['slug']?>"><?=$term['name']?></a></li>
			<?php }}}?>
	  </ul>
	  <?php break;}}?>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
		<div class="div_box">
			<div class="div" style="margin-left:10px;" align="left">
				<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/company" target="ajaxifr" onSubmit="return checkForm();">
					<table class="form_table">
						<colgroup>
							<col width="108px"><col>
						</colgroup>
						<tbody>
						  <tr>
							<th>详细内容：</th>
							<td id="editor">
							  
							  <?php if(!empty($sunTerm)){
								foreach($sunTerm as $key=>$item){?>
								<div class="editor<?=$key!=0?' hide':''?>">
								<textarea name="content_<?=$key?>"></textarea>
								</div>
								<?php }}?>
							  
							</td>
						  </tr>
						  <tr>
							<th></th>
							<td>
								<input type="hidden" name="term">
								<input type="hidden" name="index" value="0">
								<button type="submit" class="submit"><span>提交保存</span></button>
							</td>
						  </tr>
						</tbody>
					  </table>
				</form>
			</div>
		</div>
          <iframe name="ajaxifr" style="display:none;"></iframe>
        </div>
      </div>
      <!--/container-->
      <!-- 引入底部-->
      <?php include('footer.php');?>
	  <!-- /引入底部-->
