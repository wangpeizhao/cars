<?php include('header.php');?>
<!-- /引入头部-->
<!-- 引入二级菜单-->
<?php include('submenu.php');?>
<!-- /引入二级菜单-->
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
	var index =0;
	var _index = 0;
	var _id = '<?=$id?>';
	var _title = document.title;
	$(function(){
		try{
			if(isIE()==6 || isIE()==7 || isIE()==8){
				alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
				//return false;
			}
			// var firstA = $('ul.tab li').eq(_id).find('a');
			// $(".position #term").html(firstA.text());
			// document.title=_title+' - '+firstA.text();
			// $('input[name="term"]').val(firstA.attr('term'));
			// getContent(firstA.attr('term'),_id);

			$("a").live('click',function(){
				 this.blur();
			});

			$("ul.tab li").live('click',function(){
				if(isIE()==6 || isIE()==7 || isIE()==8){
					alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
					return false;
				}
				$(".position #term").html($(this).text());
				document.title=_title+' - '+$(this).text();
				$(this).addClass('selected').siblings().removeClass('selected');
				$('input[name="term"]').val($(this).find('a').attr('term'));
				index = $(this).index();
				$('input[name="index"]').val(index);
				getContent($(this).find('a').attr('term'),index);
				//
				$(".div_box").find("div.div #editor .editor").eq(index).siblings().hide();
				$(".div_box").find("div.div #editor .editor").eq(index).fadeIn();
			});
			$('ul.tab li').eq(_id).click();
		}catch(e){
			alert(e.message);
		}
	});

	function checkForm(){
		if(!$.trim($('textarea[name="content_'+index+'"]').val())){
			alert('详细内容不能为空');
			return false;
		}
	}

	function getContent(term,index){
		try{
			_index = index;
			$.post(baseUrl + lang + "/admin/contact",{term:term,act:'get'}, function(data){
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

	function iResultAlter(str,status){
		alert(str);
	    if(status==0){
	        return false;
	    }
	    window.location.href = '/admin/contact/index/'+_index;
	}
//-->
</script>
  <div id="admin_right">
    <div class="headbar">
	<?php if(!empty($terms)){
			foreach($terms as $item){?>
      <div class="position"><span>系统</span><span>></span><span><?=$item['name']?></span><span>></span><span id="term"></span></div>
      <ul class="tab" style="margin-top:0px;">
		<?php if(!empty($item['sunTerm'])){$sunTerm=$item['sunTerm'];
			foreach($item['sunTerm'] as $key=>$term){?>
				<li><a href="javascript:;" term="<?=$term['taxonomy'].'_'.$term['slug']?>"><?=$term['name']?></a></li>
			<?php }}?>
	  </ul>
	  <?php break;}}?>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
		<div class="div_box">
			<div class="div" style="margin-left:10px;" align="left">
				<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/contact" target="ajaxifr" onSubmit="return checkForm();">
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
