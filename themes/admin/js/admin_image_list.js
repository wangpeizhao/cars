function setData(currPage) {
	try {
		$.ajax({
			type: "POST",
			url: baseUrl+lang + '/admin/upload/index',
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
		if (data.length) {
			for (var i = 0; i < data.length; i++) {
				html += '<li class="images" title="' + data[i].file_orig + '" id="i_' + data[i].id + '"><a href="javascript:;" class="del cover" title="删除" _id="' + data[i].id + '"></a><span><img srcval="' + site_url + data[i].file_path + '" src="' + site_url + data[i].thumb + '"></span>';
				html += '<a>' + data[i].file_orig + '</a>';
				html += '<div class="cover_bg"></div><div class="cover" id="' + data[i].id + '"></div></li>'
			}
		}
		if (html == '') {
			html = '暂没图片，请先上传图片';
			$('.do_images').hide()
		} else {
			$('.do_images').show()
		}
		$("#images").html(html)
	} catch (e) {
		alert('fillData:' + e.message)
	}
}