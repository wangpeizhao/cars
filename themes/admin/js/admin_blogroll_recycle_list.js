function setData(currPage) {
    try {
        $('input[name="currentPage"]').val(currPage);
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/blogroll/recycles',
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
            html += '   <td><input type="checkbox" value="' + data[i]['id'] + '" class="cursor"/></td>';
            html += '   <td>' + data[i]['id'] + '</td>';
            // html += '    <td>' + data[i]['link_term_name'] + '</td>';
            html += '   <td>' + data[i]['link_name'] + '</td>';
            html += '   <td><a href="' + data[i]['link_url'] + '" title="' + data[i]['link_url'] + '" target="_blank">' + data[i]['link_url'] + '</a></td>';
            html += '   <td>' + (data[i]['link_image'] ? '<a href="' + site_url + data[i]['link_image'] + '" target="_blank" style="color:#339900;">【图】</a>': '<font color="#0099ff">无</font>') + '</td>';
            html += '   <td>' + data[i]['link_target'] + '</td>';
            // html += '    <td>' + (data[i]['isHidden'] == '0' ? '<font color="#339900">是</font>': '<font color="red">否</font>') + '</td>';
            html += '   <td>' + data[i]['link_sort'] + '</td>';
            html += '   <td>' + data[i]['link_description'] + '</td>';
            html += '   <td>' + data[i]['administrator'] + '</td>';
            html += '   <td>' + data[i]['update_time'] + '</td>';
            html += '   <td id="' + data[i]['id'] + '"><a href="javascript:;" act="recover"><img class="operator" src="' + site_url + '/themes/admin/images/icon_recover.gif" alt="还原" title="还原" /></a><a act="dump" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}