function setData(currPage) {
	try {
		$.ajax({
			type: "POST",
			url: baseUrl+lang + '/admin/upload/uploadVideo',
			data: {
				currPage: currPage,
				rows: rows,
				act: 'get'
			},
			dataType: "json",
			timeout: 30000,
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert('读取失败,请重新登陆')
			},
			success: function(data) {
				if (data.done === true) {
					$('input[name="currentPage"]').val(currPage);
					fillData(data.data.files, currPage);
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
		if (typeof(data.fullName) != 'undefined') {
			for (var i = 0; i < data.fullName.length; i++) {
				html += '<li id="i_' + i + '"><embed align="middle" allowfullscreen="true" allowscriptaccess="always" height="120" quality="high" srcval="' + site_url + data.fullName[i] + '" src="' + site_url + data.fullName[i] + '" type="application/x-shockwave-flash" width="150"></embed><a title="' + data.fileName[i] + '">' + data.fileName[i] + '</a><div class="cover_bg2"></div><div class="cover_bg"></div><div class="cover" title="删除" id="' + i + '"></div></li>'
			}
		}
		if (html == '') {
			html = '暂没视频，请先上传视频';
			$('.do_images').hide()
		} else {
			$('.do_images').show()
		}
		$("#images").html(html)
	} catch (e) {
		alert('fillData:' + e.message)
	}
}