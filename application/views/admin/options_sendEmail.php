<script type="text/javascript">
	$(function(){
		$('input[name="email_type"]').click(function(){
			email_type = $(this).val();
			if(email_type==1){
				$('#email_address').show();
			}else{
				$('#email_address').hide();
			}
		});
	});
	function _checkEmail(){
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
</script>
<form id="cache_form" method="post" action="<?=WEB_DOMAIN?>/admin/system/sendemail" target="ajaxifr" onsubmit="return _checkEmail();">
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