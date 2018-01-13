function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/productsRecycleList',
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
            html += '	<td><a href="' + baseUrl + lang + '/products/sort/' + data[i]['slug'] + '" target="_blank">' + data[i]['term_name'] + '</a></td>';
            html += '	<td><a href="' + baseUrl + lang + '/products/info/' + data[i]['id'] + '.html" target="_blank">' + (data[i]['image_url'] ? '<a href="' + site_url + data[i]['image_url'] + '" target="_blank" style="color:#339900;">[图]</a>': '') + data[i]['title'] + '</a></td>';
            html += '	<td>' + data[i]['summary'] + '</td>';
            html += '	<td>' + data[i]['username'] + '</td>';
            //html += '	<td>' + data[i]['views'] + '</td>';
            html += '	<td>' + (parseInt(data[i]['is_commend']) == 1 ? '<font color="#339900">是</font>': '<font color="#ff0000">否</font>') + '</td>';
            html += '	<td>' + (parseInt(data[i]['is_issue']) == 1 ? '<font color="#339900">是</font>': '<font color="#ff0000">否</font>') + '</td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
            html += '	<td id="' + data[i]['id'] + '"><a href="javascript:;" act="recover"><img class="operator" src="' + site_url + '/themes/admin/images/icon_recover.gif" alt="还原" title="还原" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="彻底删除" title="彻底删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}