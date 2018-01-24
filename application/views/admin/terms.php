<?php if(!empty($terms)){?>
    <option value="<?=$terms['id']?>" <?=!empty($term_id) && $terms['id']==$term_id?'selected':''?> style="color:#ff6600;"><?=$terms['name']?></option>
    <?php if(!empty($terms['childs'])){?>
        <?php foreach($terms['childs'] as $_item){?>
            <option value="<?=$_item['id']?>" <?=!empty($term_id) && $_item['id']==$term_id?'selected':''?>> &nbsp;&nbsp;&nbsp;&nbsp;<?=$_item['name']?></option>
        <?php } ?>
    <?php } ?>
<?php } ?>
