<script type="text/javascript">
	function _checkIndexBlock(){
		try{
			var select = $("form#indexBlock").find("select");
			if($.trim(select.eq(0).val())==''){
				alert('请选择“首页三竖块第一(左)”的分类');
				return false;
			}
			if($.trim(select.eq(1).val())==''){
				alert('请选择“首页三竖块第二(中)”的分类');
				return false;
			}
			if($.trim(select.eq(2).val())==''){
				alert('请选择“首页三竖块第三(右)”的分类');
				return false;
			}
		}catch(e){
			alert(e.message);
		}
	}
</script>
<form method="post" action="<?=WEB_DOMAIN.'/'._LANGUAGE_?>/admin/system/indexBlock" target="ajaxifr" id="indexBlock" onSubmit="return _checkIndexBlock();">
	<table class="list_table link_list_table field" border="0" align="left" cellpadding="0" cellspacing="2" bgcolor="#e1e5ee" style="line-height:60px;">
		<tr>
			<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第一(左)：</td>
			<td align="left" width="15%">
				<select name="indexBlockLeft">
					<option value="">-请选择-</option>
					<?php if(!empty($indexB[0]['sunTerm'])){
						foreach($indexB[0]['sunTerm'] as $item){
							if($item['slug']==$options['indexB']['indexL']){?>
					<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
					<?php }else{?>
					<option value="<?=$item['slug']?>"><?=$item['name']?></option>
					<?php }}}?>
				</select>
			</td>
			<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
		</tr>
		<tr>
			<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第二(中)：</td>
			<td align="left" width="15%">
				<select name="indexBlockMid">
					<option value="">-请选择-</option>
					<?php if(!empty($indexM[0]['sunTerm'])){
						foreach($indexM[0]['sunTerm'] as $item){
							if($item['slug']==$options['indexB']['indexM']){?>
					<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
					<?php }else{?>
					<option value="<?=$item['slug']?>"><?=$item['name']?></option>
					<?php }}}?>
				</select>
			</td>
			<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
		</tr>
		<tr>
			<td style="text-align:right;" width="15%"><span>*</span>首页三竖块第一(右)：</td>
			<td align="left" width="15%">
				<select name="indexBlockRight">
					<option value="">-请选择-</option>
					<?php if(!empty($indexB[0]['sunTerm'])){
						foreach($indexB[0]['sunTerm'] as $item){
							if($item['slug']==$options['indexB']['indexR']){?>
					<option value="<?=$item['slug']?>" selected><?=$item['name']?></option>
					<?php }else{?>
					<option value="<?=$item['slug']?>"><?=$item['name']?></option>
					<?php }}}?>
				</select>
			</td>
			<td width="70%" align="left" style="text-align:left;"><span style="color:#999;">请选择对应分类</span></td>
		</tr>
	</table>
	<div class="clear"></div>
	<p><button type="submit" class="submit"><span>提交保存</span></button></p>
</form>