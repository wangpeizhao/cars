function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/commentRecycleList',
            data: {
                currPage: currPage,
                rows: rows,
                condition: condition
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
            html += '<tr id="' + data[i]['id'] + '">';
            html += '	<td><input type="checkbox" value="' + data[i]['id'] + '" class="cursor"/></td>';
            html += '	<td>' + (rows * (currPage - 1) + 1 + i) + '</td>';
            html += '	<td>' + data[i]['username'] + '</td>';
            html += '	<td>' + data[i]['phone'] + '</td>';
            html += '	<td>' + data[i]['email'] + '</td>';
            html += '	<td>' + data[i]['user_ip'] + '</td>';
            html += '	<td>' + data[i]['declare'] + '</td>';
            //html += '	<td>' + (data[i]['is_public'] == 0 ? '<font color="#ff6600">否</font>': '<font color="#339900">是</font>') + '</td>';
            html += '	<td>' + (data[i]['is_shield'] == 0 ? '<font color="#ff6600">否</font>': '<font color="#339900">是</font>') + '</td>';
            html += '	<td>' + (data[i]['replyContent'] ? data[i]['replyContent'] : '') + '</td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
            html += '	<td id="' + data[i]['id'] + '"><a href="javascript:;" act="recover"><img class="operator" src="' + site_url + '/themes/admin/images/icon_recover.gif" alt="还原" title="还原" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}