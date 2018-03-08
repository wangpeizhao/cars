<div class="hot_contact_right pad_inner">
    <ul class="pages-fun">
        <li class="contact-fun" data-role="contract-btn"><a href="javascript:;"><em class="contact-icon icon"></em>联系我们</a></li>
        <li class="back-top" data-role="go_top" style=""><a href="javascript:void(0)" style="display: block;line-height:90px;">Top</a></li>
        <li><a href="<?=site_url('/')?>" style="display: block;line-height:90px;">回到首页</a></li>
    </ul>
</div>

<div class="popup_bg" style="display: none;">
    <div class="us-pop" style="">
        <!-- <form method="post" name="ModelForm" action="" data-parsley-validate="" novalidate target="ajaxifr" onSubmit="return checkForm();"> -->
            <span class="js-none"><em>×</em></span>
            <div class="wrapper">
                <div class="us-tt">联系我们</div>
                <div class="box box-problem _clear">
                    <h4>您想要解决的问题（必填）</h4>
                    <ul class="radio-sel">
                        <li data-id="1" class="clk"><span class="radio-icon"><input type="radio"></span>网站功能问题反馈</li>
                        <li data-id="2" class=""><span class="radio-icon"><input type="radio"></span>内容问题反馈</li>
                        <li data-id="3" class=""><span class="radio-icon"><input type="radio"></span>侵权投诉</li>
                        <!-- <li data-id="4" class=""><span class="radio-icon"><input type="radio"></span>产品合作</li> -->
                        <!-- <li data-id="5" class=""><span class="radio-icon"><input type="radio"></span>商务、媒体合作</li> -->
                        <!-- <li data-id="6" class=""><span class="radio-icon"><input type="radio"></span>公众平台相关问题</li> -->
                        <li data-id="7" class=""><span class="radio-icon"><input type="radio"></span>广告合作与投诉</li>
                        <li data-id="8" class=""><span class="radio-icon"><input type="radio"></span>其他</li>
                    </ul>
                </div>
                <div class="box box-need _clear">
                    <h4>请在下方说明您的需求（必填）</h4>
                    <div class="write-need"><textarea placeholder="请详细描述" name="detail"></textarea></div>
                    <span style="color:#ff0000;display: none" class="err-info">必须填写</span>
                </div>
                <div class="box box-pic _clear">
                    <span class="tt">添加图片：</span><form id="mt-file-upload">
                        <span class="add-file"><input type="file" class="myFile" name="file" accept="image/jpg,image/jpeg,image/png"><a href="#" class="file-btn">点击上传</a></span>
                        <div class="preview" data-role="img-pool">
                        </div>
                    </form>
                    <span class="err-info upload-err-info">图片最多上传5张</span>          
                </div>
                <div class="box box-mode _clear">
                    <span class="tt">您的联系方式：</span><input type="text" name="contact" class="input-mode" placeholder="微信/QQ/手机/邮箱，方便工作人员与您取得联系。">
                </div>
                <div class="box box-mode _clear">
                    <span class="tt">验证码：</span>
                    <img src="<?=site_url('')?>themes/common/images/loadding.gif" id="loadding_note" style="width:16px;height:16px;margin:3px 0 0 5px;">
                    <img src="" onclick="this.src='/home/vCode?'+Math.round(Math.random()*1000000)" id="_vCode" style="display:none;margin-left:4px;">
                    <input type="text" name="vCode" class="input-mode" placeholder="请输入验证码" style="width:100px;" maxlength="4">
                </div>
                <div class="btns">
                    <input type="hidden" name="tid" value="1">
                    <input type="hidden" name="attachments" value="">
                    <input type="button" class="us-btn us-btn-ok" value="提交">
                </div>
                <p class="tips" style="font-size:12px;margin-top:5px;"></p>
            </div>
        <!-- </form> -->
        <iframe name="ajaxifr" style="display:none;"></iframe>
    </div>
</div>
<script type='text/javascript'>
    $(function(){
        var domain = '<?=site_url()?>';
        var width = $(window).width();
        var height = $(window).height();
        var _width = 808+40;
        var _height = 600+40;
        var left = _width>width?0:((width-_width)/2);
        var top = 100;
        $('.us-pop').css({'left':left,'top':top});

        $('.us-pop .js-none,input:button.close').click(function(){
            $('.popup_bg').fadeOut();
        });

        
        $('.contact-fun').click('click',function(){
            $('.popup_bg').fadeIn(function(){
                setTimeout(function(){
                    $('#_vCode').click();
                    $('#loadding_note').fadeOut(function(){
                        $('#_vCode').fadeIn();
                    });
                },200);
            });
        });

        $('.us-pop .box .radio-sel li').click(function(){
            var data_id = $(this).attr('data-id');
            $('input[name="tid"]').val(data_id);
            $(this).addClass('clk').siblings().removeClass('clk');
        });

        $('.us-btn-ok').click(function(){
            $(this).blur();
            var _P = $('.popup_bg');
            try{
                var tid = _P.find('input[name="tid"]').val();
                var attachments = _P.find('input[name="attachments"]').val();
                var vCode = _P.find('input[name="vCode"]').val();
                var contact = _P.find('input[name="contact"]').val();
                var detail = _P.find('textarea[name="detail"]').val();
                if(!$.trim(detail)){
                    alert('请填写描述');
                    return false;
                }
                if($.trim(detail).length<10){
                    alert('描述不能低于10个字符');
                    return false;
                }
                if(!$.trim(contact)){
                    alert('请填写联系方式');
                    return false;
                }
                if(!$.trim(vCode)){
                    alert('请填写验证码');
                    return false;
                }
                _P.find('p.tips').html('<font color="#0066ff">正在提交...</font>');
                $.post(domain+ "/home/comment",{tid:tid,attachments:attachments,detail:detail,contact:contact,vCode:vCode}, function(data){
                    if(data.code == 1){
                        _P.find('p.tips').html('<font color="#339900">提交成功！感谢您的反馈，我们会尽快处理...</font>');
                        setTimeout(function(){
                            $('.popup_bg').fadeOut(function(){
                                _P.find('p.tips').html('');
                                _P.find('input[name="tid"]').val('1');
                                _P.find('input[name="attachments"]').val('');
                                _P.find('input[name="contact"]').val('');
                                _P.find('input[name="vCode"]').val('');
                                _P.find('textarea[name="detail"]').val('');
                                _P.find(".preview").val('');
                            });
                            
                        },500);
                    }else if(data.msg){
                        _P.find('.tips').html('<font color="#ff6600">'+data.msg+'</font>');
                    }else{
                        _P.find('.tips').html('<font color="#ff6600">提交失败,请重试</font>');
                    }
                },"json");
            }catch(e){
                alert(e.message);
            }
        });
    });

    function checkForm(){

    }
</script>