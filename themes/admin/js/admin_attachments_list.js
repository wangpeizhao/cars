function setData(currPage) {
    try {
        $('input[name="currentPage"]').val(currPage);
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/attachments/index',
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
            html += '	<td>' + data[i]['id'] + '</td>';
            html += '	<td>' + data[i]['term_name'] + '</td>';
            html += '	<td>' + data[i]['file_orig'] + '</td>';
            html += '   <td>' + data[i]['file_name'] + '</td>';
            html += '   <td>' + data[i]['file_ext'] + '</td>';
            html += '   <td>' + data[i]['file_type'] + '</td>';
            html += '   <td>' + data[i]['file_size'] + 'kb</td>';
            html += '	<td>' + data[i]['file_path'] + '</td>';
            html += '	<td>' + (data[i]['is_image'] == '1' ? '<font color="#339900">是</font><span>('+data[i]['image_width']+'*'+data[i]['image_height']+')</span>': '<font color="red">否</font>') + '</td>';
            html += '	<td>' + data[i]['administrator'] + '</td>';
            html += '	<td>' + data[i]['update_time'] + '</td>';
            html += '	<td id="' + data[i]['id'] + '"><a href="javascript:;" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="编辑" title="编辑" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}