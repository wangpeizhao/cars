<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
  <?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <script type="text/javascript" src="<?=site_url('')?>/themes/common/js/parsley.js"></script>
  <link rel="stylesheet" href="<?=site_url('')?>/themes/common/css/parsley.css">
  <script type="text/javascript">
    function checkForm(){
      // var term_id = $('select[name="term_id"]').val();
      // if(!$.trim(term_id)){
      //   alert('分类不能为空');
      //   return false;
      // }
      // var URL = $('input[name="url"]').val();
      // if(!$.trim(URL)){
      //   alert('URL不能为空');
      //   return false;
      // }
    }

    function iResultAlter(str,status){
        if(status==0){
            alert(str);
            return false;
        }
        alert(str);
        $('input[name="url"]').val('');
        $('button:submit').blur();
    }

  </script>
  <style type="text/css">
    .error{color: #ff0000;display: inline-block;}
    .pwdLen{display: inline-block;}
    .pwdLen span{height: 25px;line-height: 25px;border-top:6px solid #ccc;padding:0 25px;margin-right: 4px;color: #999;}
    .ruo{border-top:6px solid #ff6600 !important;}
    .zho{border-top:6px solid #009999 !important;}
    .qia{border-top:6px solid #009966 !important;}

    .m_add ul li .error {
      padding-left: 0px;
    }
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
          <form method="post" name="ModelForm" action="" data-parsley-validate="" novalidate target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					        <col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>分类：</th>
                    <td>
                      <select name="term_id" class="auto" required>
                        <option value="" selected>- 请选择分类 -</option>
                        <?php 
                          include('terms.php');
                        ?>
                      </select>
                      <label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th>作者：</th>
                    <td>
                      <input type="text" title="作者" required placeholder="作者" pattern="\S" value="<?=ADMIN_USERNAME?>" name="author" class="normal">
                      <label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th>URL：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>URL不能为空" required placeholder="URL" pattern="\S" value="" name="url" class="normal">
                      <label style="color:red;">*</label>
                    </td>
                  </tr>
				          <tr>
                    <th></th>
                    <td>
          						<button type="submit" class="submit"><span>提交</span></button>
          						<button type="button" class="submit" onclick="history.back(-1);"><span>返回</span></button>
          						<input type="hidden" name="id" value="0">
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
