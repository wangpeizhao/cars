<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-新闻资讯-添加新闻资讯</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';
	function select_tab(curr_tab){
		$("form[name='ModelForm'] > div").hide();
		$("#table_box_"+curr_tab).show();
		$("ul[name=menu1] > li").removeClass('selected');
		$('#li_'+curr_tab).addClass('selected');
	} 

	function iResult(str){
		if(str==1){
			alert('添加成功!');
            //event_link(baseUrl + lang +'/admin/system/addProducts');
			event_link(baseUrl + lang +'/admin/system/newsInfo');
		}else{
			alert(str);
			return false;
		}
	}

	function checkForm(){
		if(!$.trim($('input[name="title"]').val())){
			alert('产品名称不能为空');
			$('input[name="title"]').focus();
			return false;
		}

		if(!$.trim($('select[name="term_id"]').val())){
			//alert('产品所属分类');
			//$('select[name="term_id"]').focus();
			//return false;
		}

		if(!$.trim($('textarea[name="content"]').val())){
			alert('产品详细内容不能为空');
			return false;
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
      <div class="position"><span>系统</span><span>></span><span>新闻资讯</span><span>></span><span>添加新闻资讯</span></div>
      <ul name="menu1" class="tab">
        <li class="selected" id="li_1"><a onclick="select_tab('1')" href="javascript:;">添加新闻资讯</a></li>
        <!-- <li id="li_2" class=""><a onclick="select_tab('2')" href="javascript:;">描述</a></li>
		<li id="li_3"><a onclick="select_tab('3')" href="javascript:;">营销选项</a></li> -->
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addNews" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					<col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>新闻资讯标题：</th>
                    <td><input type="text" alt="产品名称不能为空" pattern="\S" value="" name="title" class="normal"><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th>所属分类：</th>
                    <td><select name="term_id" class="auto">
						<option value="" selected>-请选择分类-</option>
						<?php if(!empty($product_term)){
							foreach($product_term as $item){?>
						<option value="<?=$item['id']?>"><?=$item['name']?></option>
							<?php if(!empty($item['sunTerm'])){
								foreach($item['sunTerm'] as $sunItem){?>
									<option value="<?=$sunItem['id']?>"> &nbsp;&nbsp;<?=$sunItem['name']?></option>
						<?php }}}}?>
                    </select></td>
                  </tr>
                  <tr>
                    <th>设置浏览次数：</th>
                    <td><input type="text" value="" name="views" class="normal"></td>
                  </tr>
                  <tr>
                    <th>新闻资讯摘要：</th>
                    <td><textarea rows="" cols="" name="summary"></textarea></td>
                  </tr>
                  <tr>
                    <th>详细内容：</th>
                    <td><textarea name="content"></textarea>
                      <script>
						CKEDITOR.replace( 'content', {
							//filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
							//filebrowserImageBrowseUrl: '/ckfinder/ckfinder.html?Type=Images',
							//filebrowserFlashBrowseUrl: '/ckfinder/ckfinder.html?Type=Flash',
							//filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
							//filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
							//filebrowserFlashUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
						});
					</script>
                    </td>
                  </tr>
                  <tr>
                    <th>SEO关键词：</th>
                    <td><input type="text" value="" name="SEOKeywords" class="normal"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td><textarea rows="" cols="" name="SEODescription"></textarea></td>
                  </tr>
                  <tr>
                    <th>设为推荐：</th>
                    <td>
                      <label class="attr"><input type="checkbox" value="1" name="is_commend">是(<font color="#999">设为推荐:若内容包含图片，则会推送至首页切图中</font>)</label>
                    </td>
                  </tr>
                  <tr>
                    <th>是否发布：</th>
                    <td>
					  <label class="attr"><input type="checkbox" value="1" name="is_issue" checked>是(<font color="#999">打“√”提交后即可发布</font>)</label>
                    </td>
                  </tr>
				  <tr>
                    <th></th>
                    <td><button type="submit" class="submit"><span>发布新闻资讯</span></button></td>
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
