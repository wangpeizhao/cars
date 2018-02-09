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

  function checkTagForm(){
    var pid = $('.popup_content form select[name="pid"]').val();
    if(!pid){
      alert('请选择分类');
      return false;
    }
  }

  function iResultAlter(str,status){
    if(status==0){
        alert(str);
        return false;
    }
    alert('提交成功!');
    if(status == 3){
      var pid = $('.popup_content form select[name="pid"]').val();
      var txt = $('.popup_content form input[name="name"]').val();
      if(!pid){
        return false;
      }
      if($('#t_'+pid).length){
        var html = '<label><input type="checkbox" value="'+str+'"><span>'+txt+'</span><span>,</span></label>';
        $('#t_'+pid).append(html);
      }else{
        var html = '';
        html += '<p class="tags" id="t_'+pid+'">';
        html += '<label class="parent"><input type="checkbox" value="'+str+'"><b><span>'+txt+'</span><span></span></b></label>';
        html += '</p>';
        $('#tags').append(html);
      }
      $('.popup_bg').fadeOut();
    }else{
      window.history.back('-1');
      window.location.href = '/admin/news/index';
    }
  }

  function farmatTags(){
    $.post(baseUrl+ "/admin/news/add",{act:'getTags'}, function(data){
      if(data.code == '1'){
        $('#tags').html('');
        var html = '';
        var _data = data.result.childs;
        if(!_data){
          return false;
        }
        for(var i in _data){
          var v = _data[i];
          html += '<p class="tags">';
          html += '<label class="parent"><input type="checkbox" value="'+v.id+'"><b><span>'+v.name+'</span><span></span></b></label>';
          if(v.childs){
            for(var _i in v.childs){
              var _v = v.childs[_i];
              html += '<label><input type="checkbox" value="'+_v.id+'"><span>'+_v.name+'</span><span>,</span></label>';
            }
          }
          html += '</p>';
        }
        $('#tags').html(html);
        return true;
      }
      alert(data.msg);
      return false;
    },'json');
  }

  $(function(){
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
    p.tags label:last-child span:last-child{display: none;} 
  </style>
  <div id="admin_right">
    <div class="headbar">
      <div class="position"><span>系统</span><span>></span><span><?=$_title_?></span><span>></span><span>添加<?=$_title_?></span></div>
      <ul name="menu1" class="tab">
        <li class="selected" id="li_1"><a onclick="select_tab('1')" href="javascript:;">添加<?=$_title_?></a></li>
      </ul>
    </div>
    <div class="content_box">
      <div class="content link_target" align="left">
        <!--container-->
        <div class="content form_content" style="height: 298px;">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN?>/admin/news/add" novalidate="true" target="ajaxifr" onSubmit="return checkForm();">
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
                          include('terms.php');
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>标题：</th>
                    <td><input type="text" placeholder="标题" alt="产品名称不能为空" value="" name="title" class="normal" maxlength="80"><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>摘要：</th>
                    <td><textarea placeholder="摘要" rows="" cols="" name="summary"></textarea></td>
                  </tr>
                  <tr>
                    <th>来源：</th>
                    <td><input type="text" placeholder="来源" value="" maxlength="30" name="from" class="normal"></td>
                  </tr>
                  <tr>
                    <th>作者：</th>
                    <td><input type="text" placeholder="作者" value="" maxlength="30" name="author" class="normal"></td>
                  </tr>
                  <tr>
                    <th>标签：</th>
                    <td>
                      <div id="tags" style="height:220px;overflow:auto;">
                      <?php if(!empty($tags['childs'])){?>
                        <?php foreach($tags['childs'] as $item){?>
                          <p class="tags" id="t_<?=$item['id']?>">
                            <label class="parent"><input type="checkbox" value="<?=$item['id']?>"><b><span><?=$item['name']?></span><span></span></b></label>
                            <?php if(empty($item['childs'])){continue;}?>
                            <?php foreach($item['childs'] as $_item){?>
                              <label><input type="checkbox" value="<?=$_item['id']?>"><span><?=$_item['name']?></span><span>,</span></label>
                            <?php }?>
                          </p>
                        <?php }?>
                      <?php }?>
                      </div>
                      <p><a href="javascript:;" style="color:blue;" class="addTag">+新增标签</a></p>
                      <input type="text" placeholder="标签,多个用','隔开" value="" maxlength="255" readonly name="_tags" class="normal">
                      <input type="hidden" placeholder="标签,多个用','隔开" value="<?=isset($data['tags'])?$data['tags']:''?>" readonly name="tags" class="normal">
                      <script type="text/javascript">
                        $(function(){
                          $('p.tags input:checkbox').live('click',function(){
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
                            $('input[name="SEOKeywords"]').val(_tags);
                          });


                          var width = $(window).width();
                          var height = $(window).height();
                          var _width = 600+40;
                          var _height = 400+40;
                          var left = _width>width?0:((width-_width)/2);
                          var top = _height>height?0:((height-_height)/2);
                          $('.popup_content').css({'left':left,'top':top});

                          $('.popup_content .js-none,input:button.close').click(function(){
                            $('.popup_bg').fadeOut();
                          });

                          $('a.addTag').click(function(){
                            $('.popup_bg').fadeIn(function(){
                              $('.popup_content form input.reset').click();
                            });
                            var select = $('.popup_content form select[name="pid"]');
                            select.find('option:gt(1)').remove();
                            $('p.tags label.parent').each(function(){
                              var val = $(this).find('input').attr('value');
                              var txt = $(this).find('b span').text();
                              select.append('<option value="'+val+'">'+txt+'</option>');
                            });
                          });
                        });
                      </script>
                    </td>
                  </tr>
                  <tr>
                    <th>关键字：</th>
                    <td><input type="text" placeholder="关键字,多个用','隔开" value="" maxlength="255" name="keywords" class="normal"></td>
                  </tr>
                  <tr>
                    <th>详细内容：</th>
                    <td><textarea name="content" id="content"></textarea>
                      <script>
            						CKEDITOR.replace('content',{});
            					</script>
                    </td>
                  </tr>
                  <tr>
                    <th>SEO关键词：</th>
                    <td><input type="text" placeholder="SEO关键词" value="" name="SEOKeywords" class="normal" maxlength="100"></td>
                  </tr>
                  <tr>
                    <th>SEO描述：</th>
                    <td><textarea placeholder="SEO描述" rows="" cols="" name="SEODescription"></textarea></td>
                  </tr>
                  <tr>
                    <th>设置浏览次数：</th>
                    <td><input type="number" placeholder="浏览次数" name="views" class="normal" value="999" maxlength="10"></td>
                  </tr>
                  <tr>
                    <th>新闻宣传图片：<br>显示于首页的新闻频道：</th>
                    <td class="f chooseImage">
                      <div class="thumbImage">
                        <a style="margin:0 0 3px 1px;" target="_blank" class="thumb" href="javascript:;">
                          <img class="popover" 
                          _src="/themes/admin/images/tv-expandable.gif" 
                          src="/themes/admin/images/tv-expandable.gif">
                        </a>
                        <a href="javascript:;" class="del" title="删除"></a>
                      </div>
                      <div class="clear"></div>
                      <input type="hidden" name="thumb" value="">
                      <a href="javascript:;" class="choose">选择缩略图</a> <span>仅支持格式：jpg、jpeg、gif和png！</span>
                      <!-- 引入图片弹出层-->
                      <?php include('popover.php');?>
                      <!-- /引入图片弹出层-->
                    </td>
                  </tr>
                  <tr>
                    <th>排序(大->小)：</th>
                    <td><input type="number" placeholder="浏览次数" name="sort" class="normal" value="" maxlength="10"> <font color="#999">排序:大到小降序排序</font></td>
                  </tr>
                  <tr>
                    <th>设为推荐：</th>
                    <td>
                      <label class="attr"><input type="checkbox" value="1" name="is_commend">是(<font color="#999">设为推荐:显示于左侧栏资讯推荐</font>)</label>
                    </td>
                  </tr>
                  <tr>
                    <th>是否发布：</th>
                    <td>
					           <label class="attr"><input type="checkbox" value="1" name="is_issue" checked>是(<font color="#999">打“√”提交后即可发布</font>)</label>
                    </td>
                  </tr>
				          <tr>
                    <th></th>
                    <td>
                      <button type="submit" class="submit"><span>提交</span></button>
                      <button type="button" class="submit" onclick="history.back(-1);"><span>返回</span></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
          <iframe name="ajaxifr" style="display:none;"></iframe>
        </div>
      </div>

      <div class="popup_bg">
        <div class="popup_content">
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN?>/admin/classify/add" data-parsley-validate="" novalidate target="ajaxifr" onSubmit="return checkTagForm();">
            <span class="js-none"><i>×</i></span>
            <div class="title">
              <h1>添加标签</h1>
            </div>
          
          
            <div class="form">
              <table class="list_table" width="100%" border="0" cellpadding="0" cellspacing="1" style="line-height: 35px;">
                <tr>
                  <th width="20%">父级标签</th>
                  <td width="80%">
                    <select name="pid" class="normal" required>
                      <option value="">- 请选择 -</option>
                      <option value="<?=!empty($tags['id'])?$tags['id']:0?>">顶级菜单</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th>标签名称</th>
                  <td><input placeholder="分类名称" type="text" name="name" class="normal" style="width: 365px;" required></td>
                </tr>
                <tr>
                  <th>URL别名</th>
                  <td><input placeholder="URL别名" type="text" name="slug" class="normal" style="width: 365px;" required></td>
                </tr>
                <tr>
                  <th>标签描述</th>
                  <td><input placeholder="分类描述" type="text" name="description" class="normal" style="width: 365px;"></td>
                </tr>
                <tr>
                  <th>标签排序</th>
                  <td><input placeholder="分类排序" type="number" name="sort" class="normal" style="width: 365px;"></td>
                </tr>
                <tr>
                  <th>是否激活</th>
                  <td class="radioIsHidden">
                    <label style="width:50px;display:inline;"><input style="width:20px;" type="radio" checked name="isHidden" value="0">是</label> 
                        <label style="width:50px;display:inline;"><input style="width:20px;" type="radio" name="isHidden" value="1">否</label>
                  </td>
                </tr>
              </table>
            </div>

            <div class="btns">
              <table width="100%" border="0" align="center" id="Btn" style="text-align:center;margin-top:5px;">
                <tr>
                  <td valign="center">
                  <input type="hidden" name="id" value="0">
                  <input type="hidden" name="act" value="addNewsTag">
                  <input class="submit" type="submit" id="submit_menu" value="保存" onfocus="this.blur();"/>
                  <input class="submit close" type="button" value="关闭" onfocus="this.blur();"/>
                  <input class="submit close reset" type="reset" value="重置" onfocus="this.blur();"/>
                  </td>
                </tr>
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
