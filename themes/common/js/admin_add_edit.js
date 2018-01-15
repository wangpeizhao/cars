function select_tab(curr_tab){
    $("form[name='ModelForm'] > div").hide();
    $("#table_box_"+curr_tab).show();
    $("ul[name=menu1] > li").removeClass('selected');
    $('#li_'+curr_tab).addClass('selected');
} 

function iResult(str){
    if(str==1){
        alert('修改成功!');
        //event_link(baseUrl + lang +'/admin/system/newsInfo');
        window.history.back('-1');
    }else{
        alert(str);
        return false;
    }
}

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

    if(!$.trim($('textarea[name="content"]').val())){
        alert('详细内容不能为空');
        return false;
    }
}

$(function(){
    try{
        if(isIE()==6 || isIE()==7 || isIE()==8){
            alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
            //return false;
        }
        $('.chooseImage a.del').click(function(){
            // $('.chooseImage a.thumb').attr('href','');
            $('.chooseImage a img').attr('src','/themes/admin/images/tv-expandable.gif');
            $('.chooseImage a img').attr('_src','/themes/admin/images/tv-expandable.gif');
            $('.chooseImage input[name="thumb"]').val('');
            $('.chooseImage a.del').hide();
        });
        $('a.choose').click(function(){
            var iWidth=730;                          //弹出窗口的宽度; 
            var iHeight=500;                         //弹出窗口的高度; 
            //获得窗口的垂直位置 
            var iTop = (window.screen.availHeight - 30 - iHeight) / 2; 
            //获得窗口的水平位置 
            var iLeft = (window.screen.availWidth - 10 - iWidth) / 2; 
            window.open(baseUrl+"/admin/upload/uploadImage?act=specify", '', 'menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width='+iWidth+'px,height='+iHeight+'px,top=' + iTop + ',left=' + iLeft);
        });
    }catch(e){
        alert(e.message);
    }
});