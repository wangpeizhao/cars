function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/job',
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
            html += '	<td>' + data[i]['quarters'] + '</td>';
            html += '	<td>' + data[i]['duty'] + '</td>';
            html += '	<td>' + data[i]['demand'] + '</td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
			html += '	<td id="' + data[i]['id'] + '"><a href="'+baseUrl + lang+'/admin/system/editJob/'+ data[i]['id']+'" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="编辑" title="编辑" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}