<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理-留言管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
  <script type="text/javascript">
  <!--
	function upload(imgPath){
		window.returnValue = imgPath;
		window.close();
	}

$(function() {
    //上传/更新菜品
    $("#uploadDish").toggle(
    function() {
        $(".downloadDish").slideUp('slow', 
        function() {
            $(".uploadDish").slideDown('slow');

        });

    },
    function() {
        $(".uploadDish").slideUp('slow', 
        function() {
            //$(".downloadDish").slideDown('slow');
            });

    }
    );
    //下载菜品
    $("#downloadDish").toggle(
    function() {
        $(".uploadDish").slideUp('slow', 
        function() {
            $(".downloadDish").slideDown('slow');

        });

    },
    function() {
        $(".downloadDish").slideUp('slow', 
        function() {
            //$(".uploadDish").slideDown('slow');
            });

    }
    );

});
function checkRid() {
    if ($.trim($('input[name="rid"]').val())) {
        var pre = /^\d{1,5}$/;
        if (!pre.test($('input[name="rid"]').val())) {
            alert('请输入餐厅id(1~5位整数)');
            $('input[name="rid"]').focus();
            return false;

        }

    } else {
        alert('请输入餐厅id(1~5位整数)');
        $('input[name="rid"]').focus();
        return false;

    }

}
function checkFile() {
    if (!$("#dish_file").val()) {
        alert('请先选择CSV格式菜品文件');
        return false;

    } else {
        $(".uploadTips").html('<img src="/themes/client/new_images/bar.gif"><br><font color="#339900">系统正在导入，请勿离开(刷新)页面!</font>');
        return true;

    }

}
function uploadTip() {
    var error = [];
    error[1] = '导入成功,总共成功导入 ' + arguments[1] + ' 行数据；';
    error[0] = '导入错误，请重试';
    error[ - 1] = '上传文档太大,请重新选择,\n最大可上传文件10M！';
    error[ - 2] = '上传文档格式不正确,请重新选择,\n支持格式：csv！';
    error[ - 3] = arguments[0] == -3 ? '第 ' + arguments[1] + ' 行的菜品存在重复值\n请修改后再上传': '';
    error[ - 4] = arguments[0] == -4 ? '第 ' + arguments[1] + ' 行的菜品的“菜品附加”格式有误\n请修改后再上传': '';
    error[ - 5] = '上传文档不符合格式标准要求\n请修改后再上传';
    error[ - 6] = '文件名命名有错，请以餐厅ID为文件名！';
    if (arguments[0] == 1) {
        $(".uploadTips").html('<font color="#339900">导入成功！<br>总共成功导入 <font color="#0099ff">' + arguments[1] + '</font> 行数据；<a href="http://ewugu.com/info/index/' + arguments[2] + '" target="_blank">点击进入餐厅浏览菜品</a></font>');
        document.getElementById("dish_file").outerHTML = document.getElementById("dish_file").outerHTML;
        setTimeout(function() {
            //window.location.href=base_url+'merchant/mcenter/dishCategory';
            },
        '3000');

    } else {
        $(".uploadTips").html('最大可上传文件10M，仅支持CSV格式文件。<br><font color="#0099ff">导入前请再次确认文档内容是否符合格式标准</font>');

    }
    alert(typeof(error[arguments[0]]) != "undefined" ? error[arguments[0]] : error[0]);

}
  //-->
  </script>

 </head>
 <body>
  <img src="http://avatar.csdn.net/D/3/E/1_21aspnet.jpg">
  <input type="button" value="上传" onclick="upload('http://avatar.csdn.net/D/3/E/1_21aspnet.jpg');">

		<iframe style="display:none;" name="ajaxifr"></iframe>
		<div class="c"></div>
        <div style="font-size:18px;margin:20px 0 0 16px;" class="name">
			<strong>菜品管理</strong>
			<hr>
			<div class="c"></div>
			<strong class="fleft">
				<input type="submit" id="downloadDish" class="submit" value="下载菜单">
			</strong>
			<strong style="margin-left:10px;" class="fleft">
				<input type="button" id="uploadDish" class="submit" value="导入菜单">
			</strong>
		</div>
		<div class="c"></div>
		<div style="font-size: 12px; margin: 20px 0px 0px 16px; display: block;" class="hide uploadDish">
			<form onsubmit="return checkFile();" action="http://ewugu.com/admin/upload/uploadDish" target="ajaxifr" enctype="multipart/form-data" method="post">
				<input type="file" id="dish_file" size="30" name="dish_file">
				<input type="submit" class="uploadButton" value="导入">
				<input type="hidden" value="dish_file" name="inputDOM">
				<p style="padding-top:5px;" class="uploadTips">最大可上传文件10M，仅支持CSV格式文件。<br><font color="#0099ff">导入前请再次确认文档内容是否符合格式标准</font></p>
			</form>
		</div>
		<div style="font-size: 12px; margin: 20px 0px 0px 16px; display: none;" class="hide downloadDish">
			<form onsubmit="return checkRid();" action="http://ewugu.com/admin/upload/downloadDish" target="ajaxifr" method="post">
				<input type="text" onblur="if(this.value=='')this.value='请输入餐厅id(1~5位整数)'" onfocus="if(this.value=='请输入餐厅id(1~5位整数)')this.value=''" pattern="^\d{1,5}$" style="color:#666" value="请输入餐厅id(1~5位整数)" maxlength="5" size="21" name="rid">
				<input type="submit" class="submit downloadDishButton" value="下载">
			</form>
		</div>

 </body>
</html>
