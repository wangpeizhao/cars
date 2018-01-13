function setDataIndexNav(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/indexNav',
            data: {
                currPage: currPage,
                rows: rowsNav
            },
            dataType: "json",
            timeout: 30000,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('读取失败,请重新登陆')
            },
            success: function(data) {
                if (data.done === true) {
                    $('input[name="currentPage"]').val(currPage);
                    fillDataIndexNav(data.data.data, currPage);
                    //page_html(Math.ceil(data.data.count / rows), currPage);
                    //if (Math.ceil(data.data.count / rows) > 1) $('#pageLists').fadeIn();
                    //else $('#pageLists').html('')
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
function fillDataIndexNav(data, currPage) {
	$('select[name="link_parent"]').html('<option value="0" selected>-请先选择导航-</option>');
    try {
        var html = '';
        for (var i = 0; i < data.length; i++) {
            html += '<tr>';
			html += '	<td><a href="javascript:;" class="term expandable"><img src="' + site_url + '/themes/admin/images/tv-collapsable.gif" class="cursor" style="width:12px;height:12px;"/></a></td>';
            html += '	<td><font color="#ff6600"><b>' + (i + 1) + '</b></font></td>';
            html += '	<td>' + data[i]['link_name'] + '</td>';
            html += '	<td>' + '<a href="' + baseUrl + data[i]['link_url'] + '" target="_blank" style="color:#339900;">' + baseUrl + data[i]['link_url'] + '</a>' + '</td>';
            html += '	<td>' + data[i]['link_target'] + '</td>';
            html += '	<td>' + data[i]['link_rating'] + '</td>';
            html += '	<td id="' + data[i]['link_id'] + '"><a href="javascript:;" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="修改" title="修改" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="彻底删除" title="彻底删除" /></a></td>';
            html += '</tr>'
			var selectHtml = '<option value="' + data[i]['link_id'] + '">' + data[i]['link_name'] + '</option>';
            $('select[name="link_parent"]').append(selectHtml);
			if (data[i]['son_link'] && data[i]['son_link'].length > 0) {
                var son_link = data[i]['son_link'];
                for (var n = 0; n < son_link.length; n++) {
					html += '<tr>';
					html += '	<td style="padding-left:64px;"><a href="javascript:;" class="term expandable"><img src="' + site_url + '/themes/admin/images/tv-collapsable.gif" class="cursor" style="width:12px;height:12px;"/></a></td>';
					html += '	<td>' + (rows * (currPage - 1) + 1 + n) + '</td>';
					html += '	<td>' + son_link[n]['link_name'] + '</td>';
					html += '	<td>' + '<a href="' + baseUrl + son_link[n]['link_url'] + '" target="_blank" style="color:#339900;">' + baseUrl + son_link[n]['link_url'] + '</a>' + '</td>';
					html += '	<td>' + son_link[n]['link_target'] + '</td>';
					html += '	<td>' + son_link[n]['link_rating'] + '</td>';
					html += '	<td id="' + son_link[n]['link_id'] + '"><a href="javascript:;" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="修改" title="修改" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="彻底删除" title="彻底删除" /></a></td>';
					html += '</tr>'
				}
			}
        }
        var first_html = $("#list_table_indexNav").find('tr').eq(0).html();
        $("#list_table_indexNav").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}