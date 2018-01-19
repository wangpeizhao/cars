<script type="text/javascript">
	function _checkCache(){
		try{
			if($('select[name="cache"]').val()=='0'){
				if(!confirm('关闭缓存将会清除所有缓存文件！是否继续？')){
					return false;
				}
			}
		}catch(e){
			alert(e.message);
		}
	}
</script>
<form id="cache_form" method="post" action="<?=WEB_DOMAIN?>/admin/system/cacheTime" target="ajaxifr" onsubmit="return _checkCache();">
	<table class="form_table">
		<colgroup>
			<col width="138px"><col>
		</colgroup>
		<tbody>
		  <tr>
			<th>开启缓存：</th>
			<td>
			  <select name="cache" class="auto">
				<option value="0" selected>关闭缓存</option>
				<option value="1">01分钟</option>
				<option value="2">02分钟</option>
				<option value="5">05分钟</option>
				<option value="10">10分钟</option>
				<option value="30">30分钟</option>
				<option value="60">60分钟</option>
				<option value="720">12个钟</option>
				<option value="1440">1天</option>
				<option value="2880">2天</option>
				<option value="10080">1周</option>
			  </select>
			</td>
		  </tr>
		  <tr id="cacheBtn" class="hide">
			<th>&nbsp;</th>
			<td><button type="submit" class="submit"><span>提交保存</span></button></td>
		  </tr>
		 </tbody>
	</table>
</form>