function setData(currPage,url) {
	try {
		$.ajax({
			type: "POST",
			url: baseUrl+lang + (url?url:'/admin/system/links'),
			data: {
				currPage: currPage,
				rows: rows,
				link_type: link_type
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
	} catch (e) {
		alert(e.message)
	}
}

function fillData(data, currPage) {
	try {
		var html = '';
		for (var i = 0; i < data.length; i++) {
			html += '<tr id="' + data[i]['link_id'] + '">';
			html += '	<td><input type="checkbox" value="' + data[i]['link_id'] + '" class="cursor"/></td>';
			html += '	<td>' + (rows * (currPage - 1) + 1 + i) + '</td>';
			html += '	<td>' + data[i]['link_name'] + '</td>';
			html += '	<td><a href="' + data[i]['link_url'] + '" title="' + data[i]['link_url'] + '" target="_blank">' + data[i]['link_url'] + '</a></td>';
			html += '	<td>' + (data[i]['link_image'] ? '<a href="' + site_url + '/' + data[i]['link_image'] + '" target="_blank" style="color:#339900;">【图】</a>' : '<font color="#0099ff">无</font>') + '</td>';
			html += '	<td>' + data[i]['link_target'] + '</td>';
			html += '	<td>' + (data[i]['link_visible'] == 1 ? '<font color="#339900">是</font>' : '<font color="red">否</font>') + '</td>';
			html += '	<td>' + data[i]['link_rating'] + '</td>';
			html += '	<td>' + data[i]['link_description'] + '</td>';
			html += '	<td>' + data[i]['username'] + '</td>';
			html += '	<td>' + data[i]['link_updated'] + '</td>';
			html += '	<td id="' + data[i]['link_id'] + '"><a href="javascript:;" act="edit"><img class="operator" src="' + site_url + '/themes/admin/images/icon_edit.gif" alt="编辑" title="编辑" /></a><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
			html += '</tr>'
		}
		var first_html = $("#list_table").find('tr').eq(0).html();
		$("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
		$(".list_table tr::nth-child(even)").addClass('even')
	} catch (e) {
		alert(e.message)
	}
}