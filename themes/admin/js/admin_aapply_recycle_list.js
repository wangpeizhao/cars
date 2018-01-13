function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl + lang + '/admin/aapply/recycle',
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
            html += '	<td><a href="' + baseUrl + lang + '/products/sort/' + data[i]['slug'] + '" target="_blank">' + (data[i]['term_name']) + '</a></td>';
            html += '	<td><a href="' + baseUrl + lang + '/activity/info/' + data[i]['proId'] + '" target="_blank">' + (data[i]['title']) + '</a></td>';
            html += '	<td>' + data[i]['subject'] + '</td>';
            html += '	<td>' + data[i]['first_name'] + ','+data[i]['last_name'] + '</td>';
            html += '	<td>' + data[i]['email'] + '</td>';
            html += '	<td>' + data[i]['phone'] + '</td>';
            html += '	<td>' + data[i]['country']+ ',' + data[i]['state'] + ',' + data[i]['city'] + '</td>';
            html += '	<td>' + data[i]['address'] + '</td>';
            html += '	<td>' + data[i]['postal_code'] + '</td>';
            html += '	<td>' + data[i]['fax'] + '</td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
            html += '	<td id="' + data[i]['id'] + '"><a href="javascript:;" act="recover"><img class="operator" src="' + site_url + '/themes/admin/images/icon_recover.gif" alt="还原" title="还原" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="彻底删除" title="彻底删除" /></a></td>';
            html += '</tr>';
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        //$(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}