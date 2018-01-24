<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-产品管理-添加产品</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/jquery-1.9.1.min.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/jquery-migrate-1.0.0.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/admin.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/common.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/parsley.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/ckeditor/ckeditor.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/jquery.sortable.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/jquery.json.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/DatePicker/WdatePicker.js"></script>
<script charset="UTF-8" src="<?=site_url('')?>themes/common/js/ckeditor_custom.js"></script>


<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';
	var contentList = [];
	function select_tab(curr_tab){
		$("form[name='ModelForm'] > div").hide();
		$("#table_box_"+curr_tab).show();
		$("ul[name=menu1] > li").removeClass('selected');
		$('#li_'+curr_tab).addClass('selected');
	} 

	function iResult(str){
		if(str==1){
			alert('添加成功!');
			event_link(baseUrl + lang +'/admin/system/products');
		}else{
			alert(str);
			return false;
		}
	}

	$(function(){
		try{
			CKEDITOR.replace('content',{
				customConfig: 'custom/config.js'
			});
		}catch(e){
			alert(e.message);
		}
	});

	function checkForm(){
		if(!$.trim($('input[name="title"]').val())){
			alert('产品名称不能为空');
			$('input[name="title"]').focus();
			return false;
		}

		if(!$.trim($('select[name="term_id"]').val())){
			alert('产品所属分类不能为空');
			$('select[name="term_id"]').focus();
			return false;
		}

		if(!$.trim($('textarea[name="content"]').val())){
			//alert('产品详细内容不能为空');
			//return false;
		}

		if($.trim($('input[name="views"]').val())){
			if(!validate($.trim($('input[name="views"]').val()),'integer')){
				alert('浏览次数只能为整数');
				$('input[name="views"]').focus();
				return false;
			}
		}
		var tid = $(".gbin1-list li.cur").attr('tid');
		ckeditor(tid,tid);
	}

	$(function(){
		try{
			if(isIE()==6 || isIE()==7 || isIE()==8){
				alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
				//return false;
			}
		}catch(e){
			alert(e.message);
		}
	});

	function dropPic(index){
		try{
			if(confirm('确定要删除该图片吗？')){
				$.post(baseUrl + lang +'/admin/system/dropPic',{index:index,id:id}, function(data){
					if (data.done === true) {
						alert('删除成功');
						$(".pp_view_"+index).remove();
					}else if(data.msg){
						alert(data.msg);
					}else{
						alert('提交失败，请重试');
					}
					return false;
				},"json");
			}
		}catch(e){
			alert(e.message);
		}
	}
//-->
</script>
<style type="text/css">
	a.ProductPic img{border:1px #fff solid;margin:5px;}
	a.ProductPic img:hover{border:1px #ff9900 solid;}
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
      <div class="position"><span>系统</span><span>></span><span>产品管理</span><span>></span><span>添加产品</span></div>
      <ul name="menu1" class="tab">
        <li class="selected" id="li_1"><a onclick="select_tab('1')" href="javascript:;">添加产品</a></li>
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addProducts" novalidate="true" enctype="multipart/form-data" target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					<col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>产品名称：</th>
                    <td><input type="text" placeholder="产品名称不能为空" pattern="\S" value="" name="title" class="normal"><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th>所属分类：</th>
                    <td><select name="term_id" class="auto">
										<option value="" >-- 请选择分类 --</option>
						<?php if(!empty($product_term)){
							foreach($product_term as $item){?>
							<?php if(!empty($item['sonTerm'])){
								foreach($item['sonTerm'] as $sunItem){?>
									<option value="<?=$sunItem['id']?>" style="color:#ff6600;"><?=$sunItem['name']?></option>
									<?php if(!empty($sunItem['grandson'])){?>
										<?php foreach($sunItem['grandson'] as $son_key=>$son){?>
											<option value="<?=$son['id']?>" > &nbsp;&nbsp;&nbsp;&nbsp;<?=$son['name']?></option>
										<?php }?>
									<?php }?>
						<?php }}}}?>
                    </select></td>
                  </tr>
                  <tr>
                    <th>产品摘要：</th>
                    <td><textarea rows="" cols="" name="summary"></textarea></td>
                  </tr>
                  <tr>
                    <th>活动栏目：</th>
                    <td>
						<input type="text" title="请输入活动栏目" maxlength="10" placeholder="请输入活动栏目" value="" class="middle add_block_txt">
						<button type="button" class="submit block_btn add_block_btn"><b>添加栏目</b></button> &nbsp; &nbsp; &nbsp;
						<input type="text" title="请修改活动栏目" maxlength="10" placeholder="请输入活动栏目" value="<?=_LANGUAGE_=='en'?'Description':'详细介绍'?>" class="middle edit_block_txt">
						<button type="button" class="submit block_btn edit_block_btn"><b>修改栏目</b></button>
						<span class="section_tip hide">新添加的栏目需要保存后方能拖拉排序</span>
                    </td>
                  </tr>
                  <tr>
                    <th><em>*</em>活动内容：</th>
                    <td>
						<div id="" class="block_list">
							<ul class="gbin1-list">
								<li class="cur" tid="0"><span><?=_LANGUAGE_=='en'?'Description':'详细介绍'?></span><a href="javascript:;" class="close"></a></li>
								<li class="" tid="1"><span><?=_LANGUAGE_=='en'?'Specifications':'产品规格'?></span><a href="javascript:;" class="close"></a></li>
							</ul>
							<div id="msg"></div>
						</div>
						<div class="clearfix"></div>
						<div id="" class="content_list">
							<textarea id="contents" name="contents" class="hide"></textarea>
							<textarea id="content" name="content"></textarea>
						</div>
                    </td>
                  </tr>
				  <?php 
					for($key=0;$key<4;$key++){
						if($key==0){?>
				  <tr>
                    <th>产品图片1：</th>
                    <td>
						<div style="border:1px #ddd solid; padding:5px;">
							<input type="file" value="" name="thumbPic1" class="normal" size="32">&nbsp;&nbsp;(<font color="#999">产品图片1用于列表显示，默认为产品描述中的第一张图片,列表只显示带有图片的产品。</font>)
						<br>图片描述：<input type="text" value="" name="caption1" class="normal" maxlength="100">&nbsp;&nbsp;(<font color="#999">图片描述将显示到产品图片放大镜上,可以为空,最大长度100字符</font>)
						</div>
					</td>
                  </tr>
				  <?php }else{?>
                  <tr>
                    <th>产品图片<?=$key+1?>：</th>
                    <td><div style="border:1px #ddd solid; padding:5px;">
						<input type="file" value="" name="thumbPic<?=$key+1?>" class="normal" size="32">&nbsp;&nbsp;(<font color="#999">将显示到产品详细页的图片放大镜中.<!-- <font color="#ff0000">width: 285px;height: 200px;最大350k.</font> --></font>)
						<br>图片描述：<input type="text" value="" name="caption<?=$key+1?>" class="normal" maxlength="100">&nbsp;&nbsp;(<font color="#999">图片描述将显示到产品图片放大镜上,可以为空,最大长度100字符</font>)
                    </div>
						</td>
                  </tr>
				  <?php }}?>
                  <tr>
                    <th>SEO关键词：</th>
                    <td><input type="text" value="" name="SEOKeywords" class="normal"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td><textarea rows="" cols="" name="SEODescription"></textarea></td>
                  </tr><!-- 
                  <tr>
                    <th>视频链接：</th>
                    <td><input type="text" name="video_url" class="normal" value="" maxlength="255">&nbsp;&nbsp;(<font color="#999">若不存在将直接调用首页左下角的视频</font>)</td>
                  </tr> --><!-- 
                  <tr>
                    <th>设置浏览次数：</th>
                    <td><input type="text" value="" name="views" class="normal"></td>
                  </tr> -->
                  <tr>
                    <th>设为推荐：</th>
                    <td>
                      <label class="attr"><input type="checkbox" value="1" name="is_commend">是(<font color="#999">设为推荐则会推送至首页</font>)</label>
                    </td>
                  </tr>
                  <tr>
                    <th>是否发布：</th>
                    <td>
					  <label class="attr"><input type="checkbox" value="1" name="is_issue" checked>是(<font color="#999">打“√”提交后即可发布</font>)</label>
                    </td>
                  </tr>
                  <tr>
                    <th>排序(正整数：大->小)：</th>
                    <td>
                      <label class="attr"><input type="number" value="0" name="sort"><font color="#999">排序(正整数：大->小)</font></label>
                    </td>
                  </tr>
				  <tr>
                    <th></th>
                    <td>
						<button type="submit" class="submit"><span>添加产品</span></button>
						<button type="button" class="submit" onclick="javascript:if(confirm('确定要放弃添加吗')){history.back(-1);};" style="margin-left:20px;"><span>返回列表</span></button>
					</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
          <iframe name="ajaxifr" style="display:none;"></iframe>
        </div>
      </div>
      <!--/container-->
      <!-- 引入底部-->
      <?php include('footer.php');?>
<!-- /引入底部-->
