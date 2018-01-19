$(function (){

    //操作
    $("#list_table_indexNav a").live('click',function(){
        try{
            var act = $(this).attr('act');
            var id = $(this).parent().attr('id');
            switch(act){
                case 'add':
                    _add_menu($(this));
                    break;
                case 'edit':
                    _edit_menu($(this));
                    break;
                case 'del':
                    if(confirm('确定要彻底删除本菜单？不能回撤！')){
                        _del_menu(id);
                    }
                    break;
            }
        }catch(e){
            alert(e.message);
        }
    });

    $("a.addMenu").click(function(){
        $('.menu_lay select[name="pid"]').val('');
        $('.menu_lay input[name="title"]').val('');
        $('.menu_lay input[name="link"]').val('');
        $('.menu_lay input[name="sort"]').val('');
        $('.menu_lay input[name="parameter"]').val('');
        $('.menu_lay select[name="link_target"]').val('');
        isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
        $('.menu_lay').fadeIn();
        $('.layTitle').html('[+]添加菜单');
        $('.menu_lay input[name="act"]').val('add');
    });

    $('#submit_menu').click(function(){
        var parms = {};
        parms.pid = $.trim($('.menu_lay select[name="pid"]').val());
        parms.title = $.trim($('.menu_lay input[name="title"]').val());
        parms.link = $.trim($('.menu_lay input[name="link"]').val());
        parms.parameter =$.trim( $('.menu_lay input[name="parameter"]').val());
        parms.sort = $.trim($('.menu_lay input[name="sort"]').val());
        parms.show = $('.menu_lay input[name="show"]:checked').val();
        parms.act = $.trim($('.menu_lay input[name="act"]').val());
        parms.menu_id = $.trim($('.menu_lay input[name="menu_id"]').val());
        parms.link_target = $.trim($('.menu_lay select[name="link_target"]').val());
        var plat = 'client';
        if($('input[name="plat"]').attr('checked')){
            plat = 'admin';
        }
        parms.plat = plat;
        if(parms.pid === ''){
            alert('请选择父级菜单');
            return false;
        }
        if(!parms.title){
            alert('请填写菜单名称');
            $('input[name="title"]').focus();
            return false;
        }
        if(!parms.link){
            alert('请填写菜单链接');
            $('input[name="link"]').focus();
            return false;
        }
        var _url = parms.act=='edit'?'/admin/menu/edit':'/admin/menu/add';
        $.post(baseUrl+lang+ _url,{act:'post',parms:parms,id:parms.menu_id}, function(data){
            if (data.code == 1) {
                alert(data.msg);
                _refresh_menus();
                $(".close").click();
                return false;
            }else{
                alert(data.msg);
                return false;
            }
        },"json");
    });
});

function _refresh_menus(){
    var plat = 'client';
    if($('input[name="plat"]').attr('checked')){
        plat = 'admin';
    }
    $.post(baseUrl+lang+ "/admin/menu/index",{plat:plat}, function(data){
        if (data.code == 1) {
            menu_options = data.result.select_menus;
            $('.menu select[name="pid"] option:gt(1)').remove();
            $('.menu select[name="pid"]').append(menu_options);
            var menus_lists = data.result.menus_lists;
            $('#list_table_indexNav tbody tr').remove();
            $('#list_table_indexNav tbody').append(menus_lists);
        }else{
            alert(data.msg);
            return false;
        }
    },"json");
}

function _add_menu(_S){
    var _S_ = _S.parent();
    $('.menu_lay select[name="pid"]').val(_S_.attr('id'));
    $('.menu_lay input[name="title"]').val('');
    $('.menu_lay input[name="link"]').val('');
    $('.menu_lay input[name="parameter"]').val('');
    $('.menu_lay input[name="sort"]').val('');
    $('.menu_lay select[name="link_target"]').val('');
    $('.menu_lay input[name="act"]').val('add');
    isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
    $('.menu_lay').fadeIn();
    $('.layTitle').html('[+]添加菜单');
}

function _edit_menu(_S){
    var _S_ = _S.parent().parent();
    $('.menu_lay select[name="pid"]').val(_S_.attr('_pid'));
    $('.menu_lay input[name="title"]').val(_S_.attr('_title'));
    $('.menu_lay input[name="link"]').val(_S_.attr('_link'));
    $('.menu_lay input[name="parameter"]').val(_S_.attr('_parameter'));
    $('.menu_lay input[name="sort"]').val(_S_.attr('_sort'));
    $('.menu_lay select[name="link_target"]').val(_S_.attr('_link_target'));
    $('.menu_lay input[name="show"]').each(function(){
        if($(this).val()==_S_.attr('_show')){
            $(this).attr("checked",true);
        }
    });
    $('.menu_lay input[name="act"]').val('edit');
    $('.menu_lay input[name="menu_id"]').val(_S.parent().attr('id'));
    isIE()==6?$(".bg").show():$(".bg").fadeIn("slow");
    $('.menu_lay').fadeIn();
    $('.layTitle').html('编辑菜单');
}

function _del_menu(id){
    $.post(baseUrl+lang+ "/admin/menu/del",{id:id}, function(data){
        if (data.code == 1) {
            alert('删除成功');
            $("#"+id).parent().remove();
        }else if(data.msg){
            alert(data.msg);
            return false;
        }else{
            alert('删除失败，请重试');
            return false;
        }
    },"json");
}