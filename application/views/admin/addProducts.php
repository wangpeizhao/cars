<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-课程体系-添加课程</title>
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
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>';

	function iResult(str){
		if(str==1){
			alert('添加成功!');
			//event_link(baseUrl + lang +'/admin/system/addProducts');
			event_link(baseUrl + lang +'/admin/system/products');
		}else{
			alert(str);
			return false;
		}
	}

	function checkForm(){
		var infos = [];
		var tr_infos = $("table.d_t_a_c_p tr.info");
		for(var i=0;i<tr_infos.length;i++){
			var _S = tr_infos.eq(i).find('td');
			infos.push({
				'date_start' : _S.find('input[name="date_start"]').val(),
				'date_end' : _S.find('input[name="date_end"]').val(),
				'time' : _S.find('input[name="time"]').val(),
				'address' : _S.find('input[name="address"]').val(),
				'period' : _S.find('input[name="period"]').val(),
				'price' : _S.find('input[name="price"]').val(),
				'price_vip' : _S.find('input[name="price_vip"]').val(),
			});
		}
		$("textarea#basic_info").val($.toJSON(infos));
		var tid = $(".gbin1-list li.cur").attr('tid');
		ckeditor(tid,tid);
	}
	
	$(function(){
		try{
			if(isIE()==6 || isIE()==7 || isIE()==8){
				alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
				//return false;
			}
			$("a.add_fields").click(function(){
				var html = $(this).parent().prev().find('tr').eq(0).clone().show().addClass('info');
				$(this).parent().prev().find('tbody').append(html);
			});
			$("a.del_fields").bind('click',function(){
				var inputs = $(this).parent().parent().find("input");
				var num = inputs.length;
				for(var n=0;n<num;n++){
					if($.trim(inputs.eq(n).val())!=''){
						if(confirm('值不为空,您确定要放弃吗？')){
							$(this).parent().parent().remove();
							return false;
						}
					}
				}
			});
			$('.gbin1-list').sortable().bind('sortupdate', function() {
				$('#msg').html('position changed successful').fadeIn(200).delay(2000).fadeOut(200);
			});
			$(".gbin1-list").on('click','li',function(){
				var tid = $(".gbin1-list li.cur").attr('tid');
				$(this).siblings().removeClass('cur');
				$(this).addClass('cur');
				var txt = $(".gbin1-list li.cur span").text();
				$(".edit_block_txt").val(txt);
				var cid = $(".gbin1-list li.cur").attr('tid');
				ckeditor(tid,cid);
			});
			$(".gbin1-list").on('click','li a.close',function(){
				if(confirm('确定要删除该项吗？')){
					$(this).parent().remove();
					$(".gbin1-list li").eq(0).addClass('cur');
				}
			});
			$(".add_block_btn").click(function(){
				var txt = $(".add_block_txt").val();
				var tid = $(".gbin1-list li.cur").attr('tid');
				if(!$.trim(txt)){
					alert('请输入活动栏目');
					$(".add_block_txt").focus();
					return false;
				}
				$(".add_block_txt").val('');
				$(".edit_block_txt").val(txt);
				$(".gbin1-list li").removeClass('cur');
				var cid = $(".gbin1-list li").length;
				var html = '<li class="cur" tid="'+cid+'" draggable="true"><span>'+txt+'</span><a href="javascript:;" class="close"></a></li>';
				$("ul.gbin1-list").append(html);
				$(".section_tip").fadeIn();
				ckeditor(tid,cid);
			});
			$(".edit_block_btn").click(function(){
				var txt = $(".edit_block_txt").val();
				if(!$.trim(txt)){
					alert('请输入活动栏目');
					$(".edit_block_txt").focus();
					return false;
				}
				$(".gbin1-list li.cur span").text(txt);
			});
			CKEDITOR.replace('content',{
				customConfig: 'custom/config.js'
			});
		}catch(e){
			alert(e.message);
		}
	});

	function ckeditor(tid,cid){
		var contentList = [];
		var content = CKEDITOR.instances.content.getData();
		var contents = $('#contents').val();
		CKEDITOR.instances.content.setData('<span></span>');
		if($.trim(contents)){
			contentList = $.evalJSON(contents);
		}
		var exist = false;
		for(var i in contentList){
			if(contentList[i].tid==tid){
				exist = true;
				contentList[i].content = content;
			}
			if(parseInt(contentList[i].tid) == parseInt(cid)){
				//setTimeout(function(){
					CKEDITOR.instances.content.destroy();
					$('#content').val(contentList[i].content);
					CKEDITOR.replace('content',{
						customConfig: 'custom/config.js'
					});
					/*CKEDITOR.instances['content'].setData(contentList[i].content);*/
				//},500);
				
			}
		}
		if(exist===false){
			contentList.push({
				'tid' : tid,
				'content' : content,
			});
		}
		$('#contents').val($.toJSON(contentList));
		CKEDITOR.instances.content.focus();
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
      <div class="position"><span>系统</span><span>></span><span>课程体系</span><span>></span><span>添加课程</span></div>
      <ul name="menu1" class="tab">
        <li class="selected" id="li_1"><a onclick="select_tab('1')" href="javascript:;">添加课程</a></li>
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="Parker" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/addProducts" parsley-validate novalidate enctype="multipart/form-data" target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					<col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th><em>*</em>课程名称：</th>
                    <td class="f">
						<input type="text" title="课程名称不能为空" required parsley-maxlength="80" placeholder="请输入课程名称" value="" name="title" class="normal">
                    </td>
                  </tr>
                  <tr>
                    <th>所属行业：</th>
                    <td class="f"><select name="term_id" class="auto" required>
					<option value="" selected>-请选择行业-</option>
					<option value="2">教育类</option>
						<!-- <?php if(!empty($product_term)){
					foreach($product_term as $item){?>
					<?php if(!empty($item['sunTerm'])){
						foreach($item['sunTerm'] as $sunItem){?>
							<option value="<?=$sunItem['id']?>" style="color:#ff6600;"><?=$sunItem['name']?></option>
							<?php if(!empty($sunItem['grandson'])){?>
								<?php foreach($sunItem['grandson'] as $son_key=>$son){?>
							<option value="<?=$son['id']?>"> &nbsp;&nbsp;&nbsp;&nbsp;<?=$son['name']?></option>
								<?php }?>
							<?php }?>
				<?php }}}}?> -->
                    </select></td>
                  </tr>
                  <tr>
                    <th>参加对象：</th>
                    <td class="f"><input type="text" name="target" class="normal" placeholder="请输入参加对象" required/></td>
                  </tr>
                  <tr>
                    <th>Level：</th>
                    <td class="f"><input type="text" name="level" class="normal" placeholder="请输入演讲者Level" required/></td>
                  </tr>
                  <tr>
                    <th>日期、时间、地点<br>课时、价格：</th>
                    <td>
						<table class="d_t_a_c_p">
							<tbody>
							  <tr class="hide">
								<td valign="top"><input type="text" name="date_start" id="date_start" class="small" onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',minDate:'<?=date("Y-m-d")?>'})"></td>
								<td valign="top"><input type="text" name="date_end" class="small" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'date_start\')}',dateFmt:'yyyy-MM-dd'})"></td>
								<td valign="top"><input type="text" name="time" class="small" parsley-regexp="^([0-1][0-9]|[2][0-3]):([0-5][0-9])$" placeholder="格式:00:00"></td>
								<td valign="top"><input type="text" name="address" class="middle"></td>
								<td valign="top"><input type="text" name="period" class="small"></td>
								<td valign="top"><input type="text" name="price" class="small" parsley-regexp="^\d{1,10}(\.\d{1,2})?$"></td>
								<td valign="top"><input type="text" name="price_vip" class="small" parsley-regexp="^\d{1,10}(\.\d{1,2})?$"></td>
								<td style="text-align:center"><a href="javascript:;" class="del_fields"><img src="<?=site_url('')?>themes/admin/images/del_g_16x16.png"></a></td>
							  </tr>
							  <tr>
								<th style="text-align:center;padding:0px;">开始日期</th>
								<th style="text-align:center;padding:0px;">结束日期</th>
								<th style="text-align:center;padding:0px;">时间</th>
								<th style="text-align:center;padding:0px;">地点</th>
								<th style="text-align:center;padding:0px;">课时</th>
								<th style="text-align:center;padding:0px;">价格</th>
								<th style="text-align:center;padding:0px;">会员优惠价格</th>
								<th style="text-align:center;padding:0px;">操作</th>
							  </tr>
							  <tr class="info">
								<td valign="top"><input type="text" name="date_start" id="date_start" class="small" required onfocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',minDate:'<?=date("Y-m-d")?>'})"></td>
								<td valign="top"><input type="text" name="date_end" class="small" required onfocus="WdatePicker({minDate:'#F{$dp.$D(\'date_start\')}',dateFmt:'yyyy-MM-dd'})"></td>
								<td valign="top"><input type="text" name="time" class="small" required parsley-regexp="^([0-1][0-9]|[2][0-3]):([0-5][0-9])$" placeholder="格式:00:00"></td>
								<td valign="top"><input type="text" name="address" class="middle" required></td>
								<td valign="top"><input type="text" name="period" class="small" required></td>
								<td valign="top"><input type="text" name="price" class="small" required parsley-regexp="^\d{1,10}(\.\d{1,2})?$"></td>
								<td valign="top"><input type="text" name="price_vip" class="small" required parsley-regexp="^\d{1,10}(\.\d{1,2})?$"></td>
								<td style="text-align:center">&nbsp;</td>
							  </tr>
							</tbody>
						</table>
						<p><a href="javascript:;" class="add_fields"><img src="<?=site_url('')?>themes/admin/images/add.gif"></a></p>
						<textarea id="basic_info" name="basic_info" class="hide"></textarea>
					</td>
                  </tr>
                  <tr>
                    <th>活动栏目：</th>
                    <td>
						<input type="text" title="请输入活动栏目" maxlength="10" placeholder="请输入活动栏目" value="" class="middle add_block_txt">
						<button type="button" class="submit block_btn add_block_btn"><b>添加栏目</b></button> &nbsp; &nbsp; &nbsp;
						<input type="text" title="请修改活动栏目" maxlength="10" placeholder="请输入活动栏目" value="" class="middle edit_block_txt">
						<button type="button" class="submit block_btn edit_block_btn"><b>修改栏目</b></button>
						<span class="section_tip hide">新添加的栏目需要保存后方能拖拉排序</span>
                    </td>
                  </tr>
                  <tr>
                    <th>详细内容：</th>
                    <td>
						<div id="" class="block_list">
							<ul class="gbin1-list">
								<li class="cur" tid="0"><span>javascript</span><a href="javascript:;" class="close"></a></li>
								<li tid="1"><span>PHP</span><a href="javascript:;" class="close"></a></li>
								<li tid="2"><span>MYSQL</span><a href="javascript:;" class="close"></a></li>
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
                  <tr>
                    <th>SEO关键词：</th>
                    <td class="f"><input type="text" value="" name="SEOKeywords" class="normal" maxlength="100"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td class="f"><textarea rows="" cols="" name="SEODescription"></textarea></td>
                  </tr>
                  <tr>
                    <th>设置状态：</th>
                    <td>
                      <label class="attr cursor block"><input type="radio" value="1" name="is_commend" required>置顶 (<font color="#999">列表最为靠前并显示“TOP”图标</font>)</label>
                      <label class="attr cursor block"><input type="radio" value="1" name="is_commend" required>推荐 (<font color="#999">将会推送至首页，列表排序次于【置顶】并显示“荐”图标</font>)</label>
                      <label class="attr cursor block"><input type="radio" value="1" name="is_commend" required checked>最新 (<font color="#999">列表排序次于【推荐】并显示“New”图标</font>)</label>
                      <label class="attr cursor block"><input type="radio" value="1" name="is_commend" required>热点 (<font color="#999">列表排序次于【最新】并显示“Hot”图标</font>)</label>
                    </td>
                  </tr>
				  <tr>
                    <th></th>
                    <td><button type="submit" class="submit" onclick="javascript:$('form[name=Parker]').parsley('validate');"><span>发布产品</span></button></td>
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
