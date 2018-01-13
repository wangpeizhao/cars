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

	function iResult(str){
		if(str==1){
			alert('添加成功!');
			event_link(baseUrl + lang +'/admin/system/newsInfo');
		}else{
			alert(str);
			return false;
		}
	}

	function checkForm(){
		if(!$.trim($('input[name="title"]').val())){
			alert('新闻资讯标题不能为空');
			$('input[name="title"]').focus();
			return false;
		}

		if(!$.trim($('select[name="term_id"]').val())){
			alert('所属分类不能为空');
			$('select[name="term_id"]').focus();
			return false;
		}

		if(!$.trim($('textarea[name="content"]').val())){
			//alert('详细内容不能为空');
			//return false;
		}

		if($.trim($('input[name="views"]').val())){
			if(!validate($.trim($('input[name="views"]').val()),'integer')){
				alert('浏览次数只能为整数');
				$('input[name="views"]').focus();
				return false;
			}
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
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addNewsInfo" enctype="multipart/form-data" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					<col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>新闻资讯标题：</th>
                    <td><input type="text" alt="产品名称不能为空" value="" name="title" class="normal" maxlength="80"><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th>所属分类：</th>
                    <td><select name="term_id" class="auto">
          						<option value="" selected>-请选择分类-</option>
          						<?php if(!empty($terms)){
          					foreach($terms as $item){?>
          					<?php if(!empty($item['sunTerm'])){
          						foreach($item['sunTerm'] as $sunItem){?>
          							<option value="<?=$sunItem['id']?>" style="color:#ff6600;"><?=$sunItem['name']?></option>
          				<?php }}}}?>
                    </select></td>
                  </tr>
                  <tr>
                    <th>新闻资讯摘要：</th>
                    <td><textarea rows="" cols="" name="summary"></textarea></td>
                  </tr>
                  <tr>
                    <th>来源：</th>
                    <td><input type="text" value="" maxlength="30" name="from" class="normal"></td>
                  </tr>
                  <tr>
                    <th>作者：</th>
                    <td><input type="text" value="" maxlength="30" name="author" class="normal"></td>
                  </tr>
                  <tr>
                    <th>详细内容：</th>
                    <td><textarea name="content" id="content"></textarea>
                      <script>
            						CKEDITOR.replace('content',{});
            					</script>
                    </td>
                  </tr>
                  <tr>
                    <th>SEO关键词：</th>
                    <td><input type="text" value="" name="SEOKeywords" class="normal" maxlength="100"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td><textarea rows="" cols="" name="SEODescription"></textarea></td>
                  </tr><!-- 
                  <tr>
                    <th>设置浏览次数：</th>
                    <td><input type="text" name="views" class="normal" value="999" maxlength="10"></td>
                  </tr> -->
                  <!-- <tr>
                    <th>新闻宣传图片<br>显示于首页的新闻频道：</th>
                    <td class="f">
						          <input type="file" name="news_img" class="normal"> <span>支持图片大小为小于100K<font color="#ff6600">72px*72px</font>！图片格式：jpg、jpeg、gif和png！</span>
                    </td>
                  </tr> -->
                  <tr>
                    <th>排序(大->小)：</th>
                    <td><input type="number" name="sort" class="normal" value="" maxlength="10"> <font color="#999">排序:大到小降序排序</font></td>
                  </tr>
                  <tr>
                    <th>设为推荐：</th>
                    <td>
                      <label class="attr"><input type="checkbox" value="1" name="is_commend">是(<font color="#999">设为推荐:显示于左侧栏资讯推荐</font>)</label>
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
