<?php include('header.php');?>
  <!-- /引入头部-->
  <!-- 引入二级菜单-->
  <?php include('submenu.php');?>
  <!-- /引入二级菜单-->
  <script type='text/javascript' src="<?=site_url('')?>/themes/common/js/ckeditor/ckeditor.js"></script>
  <script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin_add_edit.js"></script>
  <script type="text/javascript" src="<?=site_url('')?>/themes/common/js/parsley.js"></script>
  <link rel="stylesheet" href="<?=site_url('')?>/themes/common/css/parsley.css">
  <script type="text/javascript">
    function checkForm(){

    }
    //判断密码强弱程度
    function check_pwd() {
      var pwd = document.getElementById("newPwd").value;
      var reg1=/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9a-z]{6,20}$/;
      var reg2=/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,20}$/;
      var reg3=/(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[!@#$%^&*_])[0-9a-zA-Z!@#$%^&*_]{6,20}$/;
      if (pwd.length < 6) {
        $("#newPwd").siblings(".error").html("密码长度不能小于6位");
        $(".pwdtip").css("display", "none");
        $(".pwdLen span:eq(0)").removeClass("ruo");
        $(".pwdLen span:eq(1)").removeClass("zho");
        $(".pwdLen span:eq(2)").removeClass("qia");
        return false;
      } else{
        $(".pwdtip").css("display", "block");
        $("#newPwd").siblings(".error").html("");
        if (reg1.test(pwd)) {
          $(".pwdLen span:eq(0)").addClass("ruo");
          $(".pwdLen span:eq(1)").removeClass("zho");
          $(".pwdLen span:eq(2)").removeClass("qia");
          return true;
        }else if (reg2.test(pwd)) {
          $(".pwdLen span:eq(0)").addClass("ruo");
          $(".pwdLen span:eq(1)").addClass("zho");
          $(".pwdLen span:eq(2)").removeClass("qia");
          return true;
        }else if(reg3.test(pwd)){
          $(".pwdLen span:eq(0)").addClass("ruo");
          $(".pwdLen span:eq(1)").addClass("zho");
          $(".pwdLen span:eq(2)").addClass("qia");
          return true;
        }else{
          $("#newPwd").siblings(".error").html("请输入6-20位字符，包含字母和数字");
          $(".pwdtip").css("display", "none");
        }
        return true;
      }
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
          <form method="post" name="ModelForm" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/premier/edit" data-parsley-validate="" novalidate target="ajaxifr" onSubmit="return checkForm();">
            <div id="table_box_1" style="display: block;">
              <table class="form_table">
                <colgroup>
					        <col width="148px"><col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>所属角色：</th>
                    <td>
                      <select class="auto" name="role_id" required>
                        <option value="">- 选择角色 -</option>
                        <?php if($roles){
                          foreach($roles as $item){?>
                          <option value="<?=$item['id']?>"><?=$item['role_name']?></option>
                        <?php }
                        }?>
                      </select><label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>登录名：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>登录名不能为空" required placeholder="登录名" pattern="\S" value="" name="username" class="normal">
                      <label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>真实姓名：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>真实姓名不能为空" required placeholder="真实姓名" pattern="\S" value="" name="nickname" class="normal">
                      <label style="color:red;">*</label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>密码：</th>
                    <td>
                      <input type="password" name="password" id="newPwd" required class="newpwd normal" value="" placeholder="请输入6-20个字符,包含字母和数字" onkeyup="return check_pwd();" maxlength="20">
                      <label style="color:red;">*</label>
                      <div class="error"></div>
                      <div class="m_add pwdtip dn" style="margin-top: 15px;">
                        <div class="pwdLen">
                        <span>弱</span>
                        <span>中</span>
                        <span>强</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>确认密码：</th>
                    <td>
                      <input type="password" title="<?=$_title_?>确认密码" name="repassword" value="" class="rePwd normal" maxlength="20" required parsley-equalto="#newPwd" placeholder="请输入确认密码" parsley-error-message="两次密码不一致">
                      <label style="color:red;">*</label>
                      <div class="error"></div>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>所属部门：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>所属部门" placeholder="所属部门" value="" name="branch" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>Email：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>Email" placeholder="登录名" parsley-type="email" value="" name="email" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>电话号码：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>电话号码" placeholder="电话号码" parsley-type="phone" value="" name="phone" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>手机号码：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>手机号码" placeholder="手机号码" parsley-type="mobile" value="" name="mobile" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>QQ号码：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>QQ号码" placeholder="QQ号码" parsley-type="number" value="" name="qq" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>微信号码：</th>
                    <td>
                      <input type="text" title="<?=$_title_?>微信号码" placeholder="微信号码" value="" name="weixin" class="normal">
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>个人描述：</th>
                    <td>
                      <textarea title="<?=$_title_?>个人描述" placeholder="个人描述" value="" name="describe" class="normal"></textarea>
                      <label style="color:red;"></label>
                    </td>
                  </tr>
                  <tr>
                    <th><?=$_title_?>排序：</th>
                    <td>
                      <input type="number" title="<?=$_title_?>排序-越大越靠前" parsley-type="digits" placeholder="排序-越大越靠前" value="" name="sort" class="normal">
                      <label style="color:red;"></label>
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
