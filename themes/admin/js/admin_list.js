function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/adminList',
            data: {
                currPage: currPage,
                rows: rows
            },
            dataType: "json",
            timeout: 30000,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('读取失败,请重新登陆')
            },
            success: function(data) {
                if (data.done === true) {
                    $('input[name="currentPage"]').val(currPage);
                    fillData(data.data.data, currPage);
                    page_html(Math.ceil(data.data.count / rows), currPage);
                    if (Math.ceil(data.data.count / rows) > 1) $('#pageLists').fadeIn();
                    else $('#pageLists').html('')
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
            html += '<tr>';
            html += '	<td><input type="checkbox" value="' + data[i]['id'] + '" class="cursor"/></td>';
            html += '	<td>' + (rows * (currPage - 1) + 1 + i) + '</td>';
            html += '	<td>' + data[i]['username'] + '</td>';
            html += '	<td>' + data[i]['nickname'] + '</td>';
            html += '	<td>' + data[i]['grouptitle'] + '</td>';
            html += '	<td>' + data[i]['branch'] + '</td>';
            html += '	<td>' + data[i]['email'] + '</td>';
            html += '	<td>' + data[i]['mobile'] + '</td>';
            html += '	<td>' + (data[i]['login_ip'] != null ? data[i]['login_ip'] : '127.0.0.1') + '</td>';
            html += '	<td>' + (data[i]['is_active'] == 1 ? '<font color="#339900">是</font>': '<font color="#ff3300">否</font>') + '</td>';
            html += '	<td>' + data[i]['sort'] + '</td>';
            html += '	<td>' + (data[i]['last_login_time'] ? data[i]['last_login_time'] : '0000-00-00 00:00:00') + '</td>';
            html += '	<td uid="' + data[i]['id'] + '">'+((data[i]['id']==2 && !uid)?'- -':'<a href="javascript:;" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="编辑" title="编辑" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a>')+'</td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}