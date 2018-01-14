<?php if(!empty($terms)){
	foreach($terms as $item){?>
	<?php if(!empty($item['sunTerm'])){
		foreach($item['sunTerm'] as $sunItem){?>
			<option value="<?=$sunItem['id']?>" style="color:#ff6600;"><?=$sunItem['name']?></option>
			<?php if(!empty($sunItem['grandson'])){?>
				<?php foreach($sunItem['grandson'] as $son_key=>$son){?>
			<option value="<?=$son['id']?>"> &nbsp;&nbsp;&nbsp;&nbsp;<?=$son['name']?></option>
				<?php }?>
			<?php }?>
<?php }}}}?>