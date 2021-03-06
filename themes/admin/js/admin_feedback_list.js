function setData(currPage) {
    try {
        $('input[name="currentPage"]').val(currPage);
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/news/feedback',
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
            html += '   <td>' + (rows * (currPage - 1) + 1 + i) + '</td>';
            html += '	<td><a href="/p/'+data[i]['oid']+'.html" target="_blank">' + data[i]['title'] + '</a></td>';
            html += '	<td>' + data[i]['ip'] + '</td>';
            html += '	<td style="line-height:20px;">' + data[i]['detail'] + '</td>';
            html += '	<td>' + data[i]['other'] + '</td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
            html += '   <td>无</td>';
            // html += '	<td id="' + data[i]['id'] + '"><a class="_edit" href="javascript:;" act="_edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="回复" title="回复" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>'
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}