  <!-- 引入头部-->
  <?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
  <?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
  <script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_add_edit.js"></script>
  <script type="text/javascript">
    function checkForm(){
      if(!$.trim($('input[name="title"]').val())){
          alert('标题不能为空');
          $('input[name="title"]').focus();
          return false;
      }

      if(!$.trim($('select[name="term_id"]').val())){
          alert('所属分类不能为空');
          $('select[name="term_id"]').focus();
          return false;
      }

      // if(!$.trim($('textarea[name="content"]').val())){
      //     alert('详细内容不能为空');
      //     return false;
      // }
  }

  $(function (){
    $('textarea[name="summary"]').blur(function(){
      var val = $(this).val();
      if(!$.trim(val)){
        return false;
      }
      var SEODescription = $('textarea[name="SEODescription"]').val();
      if(!$.trim(SEODescription)){
        $('textarea[name="SEODescription"]').val(val);
      }
    });

    $('input[name="tags"]').blur(function(){
      var val = $(this).val();
      if(!$.trim(val)){
        return false;
      }
      var SEOKeywords = $('input[name="SEOKeywords"]').val();
      if(!$.trim(SEOKeywords)){
        $('input[name="SEOKeywords"]').val(val);
      }
    });
    
  });
  </script>
  <style type="text/css">
    .tags label{margin-left:0;margin-right:10px;}
  </style>
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span><?=$_title_?></span><span>></span><span>修改<?=$_title_?></span></div>
      <ul name="menu1" class="tab">
        <li class="selected" id="li_1"><a onclick="select_tab('1')" href="javascript:;">修改<?=$_title_?></a></li>
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN?>/admin/news/edit" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					        <col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>所属分类：</th>
                    <td>
                      <select name="term_id" class="auto">
            						<option value="" selected>- 请选择分类 -</option>
            						<?php 
                          $term_id = $data['term_id'];
                          include('terms.php');
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>标题：</th>
                    <td><input type="text" alt="<?=$_title_?>标题不能为空" placeholder="标题" pattern="\S" value="<?=isset($data['title'])?$data['title']:''?>" name="title" class="normal"><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>摘要：</th>
                    <td><textarea rows="" cols="" name="summary" placeholder="摘要"><?=isset($data['summary'])?$data['summary']:''?></textarea></td>
                  </tr>
                  <tr>
                    <th>来源：</th>
                    <td><input type="text" placeholder="来源" value="<?=isset($data['from'])?$data['from']:''?>" maxlength="30" name="from" class="normal"></td>
                  </tr>
                  <tr>
                    <th>作者：</th>
                    <td><input type="text" placeholder="作者" value="<?=isset($data['author'])?$data['author']:''?>" maxlength="30" name="author" class="normal"></td>
                  </tr>
                  <tr>
                    <th>标签：</th>
                    <td>
                      <?php 
                      $_terms = array();
                      $_tags = !empty($data['tags'])?explode(",",$data['tags']):array();
                      ?>
                      <?php if(!empty($tags['childs'])){?>
                        <?php foreach($tags['childs'] as $item){$_terms[$item['id']] = $item['name'];?>
                          <p class="tags">
                            <label><input type="checkbox" value="<?=$item['id']?>"<?=in_array($item['id'],$_tags)?' checked':''?>><b><span><?=$item['name']?></span></b></label>:
                            <?php if(empty($item['childs'])){continue;}?>
                            <?php foreach($item['childs'] as $_item){$_terms[$_item['id']] = $_item['name'];?>
                              <label><input type="checkbox" value="<?=$_item['id']?>"<?=in_array($_item['id'],$_tags)?' checked':''?>><span><?=$_item['name']?></span></label>,
                            <?php }?>
                          </p>
                        <?php }?>
                      <?php }?>
                      <p><a href="javascript:;" style="color:blue;">+新增标签</a></p>
                      <?php 
                      $tagsStr = array();
                      if($_tags){
                        $tagsStr = array_values(array_intersect_key($_terms,array_flip(array_values($_tags))));
                      }?>
                      <input type="text" placeholder="标签,多个用','隔开" value="<?=$tagsStr?implode(',',$tagsStr):''?>" readonly name="_tags" class="normal">
                      <input type="hidden" placeholder="标签,多个用','隔开" value="<?=isset($data['tags'])?$data['tags']:''?>" readonly name="tags" class="normal">
                      <script type="text/javascript">
                        $(function(){
                          $('p.tags input:checkbox').click(function(){
                            var tags = [];
                            var tagsIds = [];
                            $('p.tags input:checkbox').each(function(k,v){
                              if($(v).prop('checked')){
                                tags.push($(v).parent().find('span').text());
                                tagsIds.push($(v).val());
                              }
                            });
                            var _tags = tags.join(',');
                            var _tagsIds = tagsIds.join(',');
                            $('input[name="_tags"]').val(_tags);
                            $('input[name="tags"]').val(_tagsIds);
                            if(!$.trim($('input[name="SEOKeywords"]').val())){
                              $('input[name="SEOKeywords"]').val(_tags);
                            }
                            
                          });
                        });
                      </script>
                    </td>
                  </tr>
                  <tr>
                    <th>关键字：</th>
                    <td><input type="text" placeholder="关键字,多个用','隔开" value="<?=isset($data['keywords'])?$data['keywords']:''?>" maxlength="255" name="keywords" class="normal"></td>
                  </tr>
                  <tr>
                    <th>详细内容：</th>
                    <td><textarea name="content" placeholder="内容"><?=str_replace('LWWEB_LWWEB_DEFAULT_URL',site_url(''),isset($data['content'])?html_entity_decode($data['content']):'');?></textarea>
                      <script>
            						CKEDITOR.replace('content', {});
            					</script>
                    </td>
                  </tr>
                  <tr>
                    <th>SEO关键词：</th>
                    <td><input type="text" placeholder="SEO关键词" value="<?=isset($data['SEOKeywords'])?$data['SEOKeywords']:''?>" name="SEOKeywords" class="normal"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td><textarea placeholder="SEO描述" rows="" cols="" name="SEODescription"><?=isset($data['SEODescription'])?$data['SEODescription']:''?></textarea></td>
                  </tr>
                  <tr>
                    <th>排序(大->小)：</th>
                    <td><input type="number" placeholder="排序" name="sort" class="normal" value="<?=isset($data['sort'])?$data['sort']:''?>" maxlength="10"> <font color="#999">排序:大到小降序排序</font></td>
                  </tr>
                  <tr>
                    <th>设置浏览次数：</th>
                    <td><input type="number" placeholder="浏览次数" value="<?=isset($data['views'])?$data['views']:''?>" name="views" class="normal"></td>
                  </tr>
                  <tr>
                    <th>新闻缩略图：<br>显示于首页的新闻频道：</th>
                    <td class="f chooseImage">
                      <div class="thumbImage">
            						<a style="margin:0 0 3px 1px;" target="_blank" class="thumb" href="javascript:;">
                          <img class="popover" 
                          _src="<?=isset($data['thumb'])?WEB_DOMAIN.'/'.$data['thumb']:'/themes/admin/images/tv-expandable.gif'?>" 
                          src="<?=!empty($data['thumb_tiny'])?WEB_DOMAIN.'/'.$data['thumb_tiny']:'/themes/admin/images/tv-expandable.gif'?>">
                        </a>
                        <a href="javascript:;" class="del" title="删除"></a>
                      </div>
          						<div class="clear"></div>
          						<input type="hidden" name="thumb" value="<?=isset($data['thumb'])?$data['thumb']:''?>">
          						<a href="javascript:;" class="choose">选择缩略图</a> <span>仅支持格式：jpg、jpeg、gif和png！</span>
                      <!-- 引入图片弹出层-->
                      <?php include('popover.php');?>
                      <!-- /引入图片弹出层-->
                    </td>
                  </tr>
                  <tr>
                    <th>设为推荐：</th>
                    <td>
                      <label class="attr"><input type="checkbox" value="1" name="is_commend" <?php if(1==$data['is_commend']){?>checked<?php }?>>是(<font color="#999">设为推荐:显示于左侧栏资讯推荐</font>)</label>
                    </td>
                  </tr>
                  <tr>
                    <th>是否发布：</th>
                    <td>
					             <label class="attr"><input type="checkbox" value="1" name="is_issue" <?php if(1==$data['is_issue']){?>checked<?php }?>>是(<font color="#999">打“√”提交后即可发布</font>)</label>
                    </td>
                  </tr>
				          <tr>
                    <th></th>
                    <td>
          						<button type="submit" class="submit"><span>提交</span></button>
          						<button type="button" class="submit" onclick="history.back(-1);"><span>返回</span></button>
          						<input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
          					</td>
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
