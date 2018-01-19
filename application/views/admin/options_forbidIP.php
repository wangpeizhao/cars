<script type="text/javascript">
	$(function(){
		$('input[name="isOpen"]').click(function(){
			if($('input[name="isOpen"]:checked').val()){
				$('textarea[name="IPs"]').removeAttr("disabled");
				$('input[name="link_id"]').val('');
			}else{
				$('textarea[name="IPs"]').attr("disabled","disabled");
				$('input[name="link_id"]').val('');
			}
		});

		$('textarea[name="IPs"]').focus(function(){
			if($('textarea[name="IPs"]').val()=='请输入需要禁止访问的IP,多个请用";"隔开'){
				$('textarea[name="IPs"]').val('');
			}
		});

		$('textarea[name="IPs"]').blur(function(){
			if($('textarea[name="IPs"]').val()==''){
				$('textarea[name="IPs"]').val('请输入需要禁止访问的IP,多个请用";"隔开');
			}
		});
	});
	function _checkIPs(){
		try{
			if($('input[name="isOpen"]:checked').val()){
				var ips = $('textarea[name="IPs"]').val();
					ips = ips.charAt(ips.length - 1)==';'?ips:ips+';';
				if(!validate(ips,'ips')){
					alert('“IP列”格式不正确,多个时请用" ; "(半角分号)隔开');
					return false;
				}
			}
		}catch(e){
			alert(e.message);
		}
	}
</script>
<form method="post" action="<?=WEB_DOMAIN?>/admin/system/prohibitIp" target="ajaxifr" onsubmit="return _checkIPs();">
	<table class="form_table">
		<colgroup>
			<col width="138px"><col>
		</colgroup>
		<tbody>
		  <tr>
			<th>禁止访问的IP：</th>
			<td>
			  <label class="attr cursor" style="margin-left:0px;">
				<input type="checkbox" value="1" name="isOpen" id="isOpen"/>开启(<font color="#999">打“√”需要填写“IP列”,多个请用" ; "(半角分号)隔开</font>)
			  </label>
			  <p class="hide" id="IPtxt"><textarea name="IPs" id="IPs" rows="" cols="">请输入需要禁止访问的IP,多个请用";"隔开</textarea></p>
			</td>
		  </tr>
		  <tr id="ipBtn" class="hide">
			<th>&nbsp;</th>
			<td><button type="submit" class="submit"><span>提交保存</span></button></td>
		  </tr>
		 </tbody>
	</table>
</form>