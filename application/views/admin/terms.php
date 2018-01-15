<?php if(!empty($terms)){
	foreach($terms as $item){?>
	<?php if(empty($item['sunTerm'])){continue;}
		foreach($item['sunTerm'] as $sunItem){?>
			<option value="<?=$sunItem['id']?>" <?=!empty($term_id) && $sunItem['id']==$term_id?'selected':''?> style="color:#ff6600;"><?=$sunItem['name']?></option>
			<?php if(!empty($sunItem['grandson'])){continue;}?>
				<?php foreach($sunItem['grandson'] as $son_key=>$son){?>
					<option value="<?=$son['id']?>" <?=!empty($term_id) && $son['id']==$term_id?'selected':''?>> &nbsp;&nbsp;&nbsp;&nbsp;<?=$son['name']?></option>
	<?php }}}}?>