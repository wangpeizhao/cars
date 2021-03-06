function setData(currPage) {
    try {
        $('input[name="currentPage"]').val(currPage);
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/comments/index',
            data: $('form[name="_Form_"]').serialize(),
            dataType: "json",
            timeout: 30000,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('读取失败,请重新登陆')
            },
            success: function(data) {
                if (data.done === true) {
                    fillData(data.data.data, currPage);
                    page_html(Math.ceil(data.data.count / rows), currPage);
                    if (Math.ceil(data.data.count / rows) > 1) $('#pageLists').fadeIn();
                    else $('#pageLists').html('');
                    $('.headbar .searchbar span.total').remove();
                    $('.headbar .searchbar').append('<span style="color:#666;" class="total">总('+data.data.count+')行</span>');
                } else if (data.msg) {
                    alert(data.msg);
                    return false
                } else {
                    alert('读取错误,请重新登陆')
                }
            }
        })
    } catch(e) {
        alert(e.message)
    }
}
function fillData(data, currPage) {
    try {
        var html = '';
        for (var i = 0; i < data.length; i++) {
            html += '<tr id="' + data[i]['id'] + '">';
            html += '	<td><input type="checkbox" value="' + data[i]['id'] + '" class="cursor"/></td>';
            html += '	<td>' + (rows * (currPage - 1) + 1 + i) + '</td>';
            html += '	<td>' + data[i]['username'] + '</td>';
            html += '	<td>' + data[i]['phone'] + '</td>';
            html += '	<td>' + data[i]['email'] + '</td>';
            html += '	<td>' + data[i]['user_ip'] + '</td>';
            html += '	<td>' + data[i]['declare'] + '</td>';
            html += '   <td>';
            if(data[i]['attachments']){
                var attachments = data[i]['attachments'].split(',');
                for(var n in attachments){
                    html += '   <a href="javascript:;" class="imgPopover" style="margin-bottom: 5px;"><img src="' + baseUrl+attachments[n] + '" _src="' + baseUrl+attachments[n] + '" class="popover"></a>';
                }
            }
            html += '   </td>';
            html += '	<td>' + (data[i]['is_public'] == 0 ? '<font color="#ff6600">否</font>': '<font color="#339900">是</font>') + '</td>';
            html += '	<td>' + (data[i]['is_shield'] == 0 ? '<font color="#ff6600">否</font>': '<font color="#339900">是</font>') + '</td>';
            html += '	<td>' + (data[i]['replyContent'] ? data[i]['replyContent'] : '') + '</td>';
            html += '	<td>' + data[i]['update_time'] + '</td>';
            html += '	<td id="' + data[i]['id'] + '"><a class="_edit" href="javascript:;" act="_edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="回复" title="回复" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}