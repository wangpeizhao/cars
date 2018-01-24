<script type="text/javascript">
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
	});
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
</script>
<form method="post" enctype="multipart/form-data" action="<?=WEB_DOMAIN?>/admin/system/options" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
  <table class="form_table">
	<colgroup>
		<col width="148px"><col>
	</colgroup>
	<tbody>
	  <tr>
		<th>网站名称：</th>
		<td>
			<input type="text" placeholder="网站名称不能为空" value="<?=isset($options['sitesName'])?$options['sitesName']:''?>" name="sitesName" class="normal" maxlength="80">
			<label style="color:red;">*</label>
			<span class="tip">网站名称</span>
		</td>
	  </tr>
	  <tr>
		<th>公司负责人：</th>
		<td>
			<input type="text" placeholder="公司负责人不能为空" value="<?=isset($options['companyLinkman'])?$options['companyLinkman']:''?>" name="companyLinkman" class="normal" maxlength="20">
			<label style="color:red;">*</label>
			<span class="tip">公司负责人</span>
		</td>
	  </tr>
	  <tr>
		<th>负责人手机：</th>
		<td>
			<input type="text" placeholder="负责人手机不能为空" value="<?=isset($options['companyMobile'])?$options['companyMobile']:''?>" name="companyMobile" class="normal" maxlength="12">
			<label style="color:red;">*</label>
			<span class="tip">负责人手机</span>
		</td>
	  </tr>
	  <tr>
		<th>公司名称：</th>
		<td>
			<input type="text" placeholder="公司名称不能为空" value="<?=isset($options['companyName'])?$options['companyName']:''?>" name="companyName" class="normal" maxlength="80">
			<label style="color:red;">*</label>
			<span class="tip">公司名称</span>
		</td>
	  </tr>
	  <tr>
		<th>公司电话：</th>
		<td>
			<input type="text" placeholder="请填写公司电话" value="<?=isset($options['companyPhone'])?$options['companyPhone']:''?>" name="companyPhone" class="normal" maxlength="80">
			<span class="tip">公司电话(可填写多个，用逗号隔开)</span>
		</td>
	  </tr>
	  <tr>
		<th>公司传真：</th>
		<td>
			<input type="text" placeholder="请填写公司传真" value="<?=isset($options['companyFax'])?$options['companyFax']:''?>" name="companyFax" class="normal" maxlength="80">
			<span class="tip">公司传真(可填写多个，用逗号隔开)</span>
		</td>
	  </tr>
	  <tr>
		<th>公司地址：</th>
		<td>
			<input type="text" placeholder="请填写公司地址" value="<?=isset($options['companyAddress'])?$options['companyAddress']:''?>" name="companyAddress" class="normal" maxlength="80">
			<span class="tip">公司地址(越详细越好)</span>
		</td>
	  </tr>
	  <tr>
		<th>公司邮编：</th>
		<td>
			<input type="text" placeholder="请填写公司邮编" pattern="^\d{6}$" value="<?=isset($options['companyZipCode'])?$options['companyZipCode']:''?>" name="companyZipCode" class="normal" maxlength="6">
			<span class="tip">公司邮编</span>
		</td>
	  </tr>
	  <tr>
		<th>公司E-mali：</th>
		<td>
			<input type="text" placeholder="请填写公司E-mali" value="<?=isset($options['companyEmail'])?$options['companyEmail']:''?>" name="companyEmail" class="normal" maxlength="80">
			<span class="tip">公司E-mali</span>
		</td>
	  </tr>
	  <tr>
		<th>公司服务QQ：</th>
		<td>
			<input type="text" placeholder="请填写公司服务QQ" value="<?=isset($options['companyQQ'])?$options['companyQQ']:''?>" name="companyQQ" class="normal" maxlength="80">
			<span class="tip">公司服务QQ</span>
		</td>
	  </tr>
	  <tr>
		<th>公司服务微信：</th>
		<td>
			<input type="text" placeholder="请填写公司服务微信" value="<?=isset($options['companyWeiXin'])?$options['companyWeiXin']:''?>" name="companyWeiXin" class="normal" maxlength="80">
			<span class="tip">公司服务微信</span>
		</td>
	  </tr>
	  <tr>
		<th>公司服务Skype：</th>
		<td>
			<input type="text" placeholder="请填写公司服务Skype" value="<?=isset($options['companySkype'])?$options['companySkype']:''?>" name="companySkype" class="normal" maxlength="80">
			<span class="tip">公司服务Skype</span>
		</td>
	  </tr>
	  <tr>
		<th>公司服务热线：</th>
		<td>
			<input type="text" placeholder="请填写公司免费服务热线" value="<?=isset($options['companyHotline'])?$options['companyHotline']:''?>" name="companyHotline" class="normal" maxlength="80">
			<span class="tip">公司免费服务热线(可填写多个，用逗号隔开)</span>
		</td>
	  </tr>
	  <tr>
		<th>首页keywords：</th>
		<td>
			<input type="text" placeholder="请填写首页keywords" value="<?=isset($options['IndexKeywords'])?$options['IndexKeywords']:''?>" name="IndexKeywords" class="normal" maxlength="100">
			<span class="tip">首页的keywords，多个请用空格或逗号隔开</span>
		</td>
	  </tr>
	  <tr>
		<th>首页description：</th>
		<td>
			<textarea rows="" placeholder="请填写首页description" cols="" name="IndexDescription"><?=isset($options['IndexDescription'])?$options['IndexDescription']:''?></textarea>
			<span class="tip">首页的详细描述</span>
		</td>
	  </tr>
	  <tr>
		<th>其它keywords：</th>
		<td>
			<input type="text" placeholder="请填写其它keywords" value="<?=isset($options['CommonKeywords'])?$options['CommonKeywords']:''?>" name="CommonKeywords" class="normal" maxlength="100">
			<span class="tip">其他页面的keywords，多个请用空格或逗号隔开</span>
		</td>
	  </tr>
	  <tr>
		<th>其它description：</th>
		<td>
			<textarea rows="" placeholder="请填写其它description" cols="" name="CommonDescription"><?=isset($options['CommonDescription'])?$options['CommonDescription']:''?></textarea>
			<span class="tip">其他页面的详细描述</span>
		</td>
	  </tr>
	  <tr>
		<th>首页视频链接：</th>
		<td>
			<textarea rows="" placeholder="请填写首页视频链接" cols="" name="VideoUrl"><?=isset($options['VideoUrl'])?$options['VideoUrl']:''?></textarea>
			<span class="tip">首页视频链接</span>
		</td>
	  </tr>
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
		  	<label class="attr cursor" style="margin-left:0px;">
		  		<input type="checkbox" value="1" name="closeSites" <?=isset($options['closeSites']) && $options['closeSites']==1?'checked':''?>>是(<font color="#999">打“√”需要填写“关闭原因”</font>)
		  	</label>
	  		<p class="<?=isset($options['closeSites']) && $options['closeSites']==1?'':'hide'?>" id="closeReason">
	  			<textarea name="closeReason" rows="" cols=""><?=isset($options['closeReason']) && $options['closeReason']?$options['closeReason']:'请描述关闭原因'?></textarea>
	  		</p>
		</td>
	  </tr>
	  <tr>
		<th></th>
		<td><button type="submit" class="submit"><span>保存网站设置</span></button></td>
	  </tr>
	</tbody>
  </table>
</form>