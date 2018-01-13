function setData(currPage) {
    try {
        $.ajax({
            type: "POST",
            url: baseUrl+lang + '/admin/system/classify',
            data: {
               // currPage: currPage,
               // rows: rows
            },
            dataType: "json",
            timeout: 30000,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('读取失败,请重新登陆')
            },
            success: function(data) {
                try {
                    if (data.done === true) {
                        $('input[name="currentPage"]').val(currPage);
                        fillData(data.data.data.data, currPage,data.data.subclass);
                        page_html(Math.ceil(data.data.data.count / rows), currPage);
                        if (Math.ceil(data.data.count / rows) > 1) $('#pageLists').fadeIn();
                        else $('#pageLists').html('')
                    } else if (data.msg) {
                        alert(data.msg);
                        return false
                    } else {
                        alert('读取错误,请重新登陆')
                    }
                } catch(e) {
                    alert(e.message)
                }
            }
        })
    } catch(e) {
        alert(e.message)
    }
}
function fillData(data, currPage,subclass) {
    try {
        var html = addHtml = selectHtml = addGHtml = '';
        var sunTerm = grandson = [];
        var date = new Date();
        var time = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
        for (var i = 0; i < data.length; i++) {
            html += '<tr term_id="' + data[i]['id'] + '" pid="0" class="termList">';
            html += '	<td style="padding-left:10px;text-align:left;"><a href="javascript:;" class="term expandable"><img src="' + site_url + '/themes/admin/images/'+(data[i]['id']==FirstId?'tv-collapsable':'tv-expandable')+'.gif" class="cursor" style="width:12px;height:12px;"/></a></td>';
            html += '	<td><font color="#ff6600"><b>' + (i + 1) + '</b></font></td>';
            html += '	<td title="可编辑" act="name"><span>' + (data[i]['name']?data[i]['name']:'空') + '</span></td>';
            html += '	<td>' + (data[i]['slug']?data[i]['slug']:'空') + '</td>';
            html += '	<td title="可编辑" act="description"><span>' + (data[i]['description']?data[i]['description']:'空') + '</span></td>';
            // html += '	<td title="' + (data[i]['banner'] ? '可查看源图': '') + '">' + (data[i]['banner'] ? '<a id="banner_' + data[i]['id'] + '" href="' + site_url + '/' + data[i]['banner'] + '" target="_blank">' + '<font color="blue">[图]</font>' + '</a>': '<a href="javascript:;" class="uploadBannerA" term_id="' + data[i]['id'] + '">马上上传</a>') + '</td>';
            // html += '	<td>' + (data[i]['username']?data[i]['username']:'-') + '</td>';
            html += '   <td title="可编辑" act="sort"><span>' + data[i]['sort'] + '</span></td>';
            html += '	<td>' + data[i]['count'] + '</td>';
            html += '	<td title="可编辑" act="is_valid" val="' + data[i]['is_valid'] + '"><span class="img">' + (data[i]['is_valid'] == 1 ? '<img src="' + site_url + '/themes/admin/images/positive_enabled.gif"/>': '<img src="' + site_url + '/themes/admin/images/positive_disabled.gif"/>') + '</span></td>';
            html += '	<td>' + data[i]['create_time'] + '</td>';
            html += '	<td>-<a act="del" p="p" href="javascript:;" class="hide"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
            html += '</tr>';
            selectHtml = '<option value="' + data[i]['id'] + '" ' + (data[i]['banner'] ? 'style="color:blue;"': '') + '>' + data[i]['name'] + '</option>';
            $('select[name="taxonomy"]').append(selectHtml);
            addHtml = '';
            if (data[i]['sunTerm'] && data[i]['sunTerm'].length > 0) {
                sunTerm = data[i]['sunTerm'];
                for (var n = 0; n < sunTerm.length; n++) {
                    html += '<tr term_id="' + sunTerm[n]['id'] + '" pid="' + sunTerm[n]['parent'] + '" class="termList sunTerm Term_' + sunTerm[n]['parent']+(sunTerm[n]['parent']==FirstId?'':' hide')+'" style="color:#f60;">';
                    html += '	<td style="padding-left:32px;text-align:left;"><a href="javascript:;" class="term expandable"><img src="' + site_url + '/themes/admin/images/'+(sunTerm[n]['id']==SecondId?'tv-collapsable':'tv-expandable')+'.gif" class="cursor" style="width:12px;height:12px;"/></a></td>';
                    html += '	<td>' + (rows * (currPage - 1) + 1 + n) + '</td>';
                    html += '	<td title="可编辑" act="name"><span>' + (sunTerm[n]['name']?sunTerm[n]['name']:'空') + '</span></td>';
                    html += '	<td>' + (sunTerm[n]['slug']?sunTerm[n]['slug']:'空') + '</td>';
                    html += '	<td title="可编辑" act="description"><span>' + (sunTerm[n]['description']?sunTerm[n]['description']:'空') + '</span></td>';
                    // html += '	<td title="' + (sunTerm[n]['banner'] ? '可查看源图': '') + '">' + (sunTerm[n]['banner'] ? '<a id="banner_' + sunTerm[n]['id'] + '" href="' + site_url + '/' + sunTerm[n]['banner'] + '" target="_blank">' + '<font color="blue">[图]</font>' + '</a>': '<a href="javascript:;" class="uploadBannerA" term_id="' + sunTerm[n]['id'] + '">马上上传</a>') + '</td>';
                    // html += '	<td>' + (sunTerm[n]['username']?sunTerm[n]['username']:'-') + '</td>';
                    html += '   <td title="可编辑" act="sort"><span>' + sunTerm[n]['sort'] + '</span></td>';
                    html += '	<td>' + sunTerm[n]['count'] + '</td>';
                    html += '	<td title="可编辑" act="is_valid" val="' + sunTerm[n]['is_valid'] + '"><span class="img">' + (sunTerm[n]['is_valid'] == 1 ? '<img src="' + site_url + '/themes/admin/images/positive_enabled.gif"/>': '<img src="' + site_url + '/themes/admin/images/positive_disabled.gif"/>') + '</span></td>';
                    html += '	<td>' + sunTerm[n]['create_time'] + '</td>';
                    html += '	<td><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
                    html += '</tr>';
					if(sunTerm[n]['grandson']){
						grandson = sunTerm[n]['grandson'];
						for (var gn = 0; gn < grandson.length; gn++) {
							html += '<tr term_id="' + grandson[gn]['id'] + '" pid="' + grandson[gn]['parent'] + '" class="termList grandsonTerm gs_' + grandson[gn]['parent'] + ' gsTerm_' + data[i]['id'] +(grandson[gn]['parent']==SecondId?'':' hide')+ '" style="color:#f60;">';
							html += '	<td style="padding-left:54px;text-align:left;"><a href="javascript:;" class="term default"><img src="' + site_url + '/themes/admin/images/tv-item.gif" class="default" style="width:12px;height:12px;"/></a></td>';
							html += '	<td><font color="#0066ff">' + (rows * (currPage - 1) + 1 + gn) + '</font></td>';
							//html += '	<td title="可编辑" act="name">'+(grandson[gn]['taxonomy']=='products'?getSunclass(subclass,grandson[gn]['subclass']):'')+'<span>' + grandson[gn]['name'] + '</span></td>';
                            html += '   <td title="可编辑" act="name">'+'<span>' + grandson[gn]['name'] + '</span></td>';
							html += '	<td>' + (grandson[gn]['slug']?grandson[gn]['slug']:'空') + '</td>';
							html += '	<td title="可编辑" act="description"><span>' + (grandson[gn]['description']?grandson[gn]['description']:'空') + '</span></td>';
							// html += '	<td title="' + (grandson[gn]['banner'] ? '可查看源图': '') + '">' + (grandson[gn]['banner'] ? '<a id="banner_' + grandson[gn]['id'] + '" href="' + site_url + '/' + grandson[gn]['banner'] + '" target="_blank">' + '<font color="blue">[图]</font>' + '</a>': '<a href="javascript:;" class="uploadBannerA" term_id="' + grandson[gn]['id'] + '">马上上传</a>') + '</td>';
							// html += '	<td>' + (grandson[gn]['username']?grandson[gn]['username']:'-') + '</td>';
                            html += '   <td title="可编辑" act="sort"><span>' + grandson[gn]['sort'] + '</span></td>';
							html += '	<td>' + grandson[gn]['count'] + '</td>';
							html += '	<td title="可编辑" act="is_valid" val="' + grandson[gn]['is_valid'] + '"><span class="img">' + (grandson[gn]['is_valid'] == 1 ? '<img src="' + site_url + '/themes/admin/images/positive_enabled.gif"/>': '<img src="' + site_url + '/themes/admin/images/positive_disabled.gif"/>') + '</span></td>';
							html += '	<td>' + grandson[gn]['create_time'] + '</td>';
							html += '	<td><a act="del" href="javascript:;"><img class="operator" src="' + site_url + '/themes/admin/images/icon_del.gif" alt="删除" title="删除" /></a></td>';
							html += '</tr>';
						}
					}
					addGHtml = '<tr class="grandsonTerm gs_' + sunTerm[n]['id'] +(sunTerm[n]['id']==SecondId?'':' hide')+ ' gs_add_' + sunTerm[n]['id'] + '">';
					addGHtml += '	<td style="padding-left:54px;text-align:left;"><a><img style="width: 12px; height: 12px;" src="' + site_url + '/themes/admin/images/icon_add.gif"></a></td>';
					addGHtml += '	<td>-</td>';
					//addGHtml += '	<td>'+(sunTerm[n]['taxonomy']=='products'?getSunclass(subclass,''):'<input type="text"></td>');
                    addGHtml += '   <td><input type="text"></td>';
					addGHtml += '	<td><input type="text"></td>';
					addGHtml += '	<td><input type="text" class="middle"></td>';
					// addGHtml += '	<td>-</td>';
					// addGHtml += '	<td>' + uername + '</td>';
                    addGHtml += '   <td><input type="text"></td>';
					addGHtml += '	<td>-</td>';
					addGHtml += '	<td><select class="is_valid"><option value="1" selected>是<option value="0">否</select></td>';
					addGHtml += '	<td>' + time + '<input type="hidden" value="' + time + '"></td>';
					addGHtml += '	<td> - </td>';
					addGHtml += '</tr>';
					addGHtml += '<tr class="grandsonTerm gs_' + sunTerm[n]['id'] +(sunTerm[n]['id']==SecondId?'':' hide')+ '" pid="' + sunTerm[n]['id'] + '" ppid="' + data[i]['id'] + '" taxonomy="' + data[i]['taxonomy'] + '">';
					addGHtml += '	<td colspan="12" align="left" class="Btn">';
					addGHtml += '		<input type="button" class="submit cursor addGrandsonTermBtn" value=" 提交第三分类 " onfocus="this.blur();" style="margin-left:49px;padding:0px 5px;margin-bottom:10px;border:0px;height: 29px;width: 100px;"/>';
					addGHtml += '	</td>';
					addGHtml += '</tr>'
					html += addGHtml;
					addGHtml = '';
                    if (addHtml == '') {
                        addHtml += '<tr class="sunTerm Term_' + sunTerm[n]['parent'] + (sunTerm[n]['parent']==FirstId?'':' hide')+' add_' + sunTerm[n]['parent'] + '">';
                        addHtml += '	<td style="padding-left:32px;text-align:left;"><a><img style="width: 12px; height: 12px;" src="' + site_url + '/themes/admin/images/icon_add.gif"></a></td>';
                        addHtml += '	<td>-</td>';
                        addHtml += '	<td><input type="text"></td>';
                        addHtml += '	<td><input type="text"></td>';
                        addHtml += '	<td><input type="text" class="middle"></td>';
                        // addHtml += '	<td>-</td>';
                        // addHtml += '	<td>' + uername + '</td>';
                        addHtml += '    <td><input type="text"></td>';
                        addHtml += '	<td>-</td>';
                        addHtml += '	<td><select><option value="1" selected>是<option value="0">否</select></td>';
                        addHtml += '	<td>' + time + '<input type="hidden" value="' + time + '"></td>';
                        addHtml += '	<td> - </td>';
                        addHtml += '</tr>';
                        addHtml += '<tr class="sunTerm Term_' + sunTerm[n]['parent'] + (sunTerm[n]['parent']==FirstId?'':' hide')+'">';
                        addHtml += '	<td colspan="12" align="left"><a href="javascript:;" style="margin-left:23px;color:#0099ff;float:left;padding-left:5px;" class="addSetting" pid="' + sunTerm[n]['parent'] + '">[+]添加子分类</a></td>';
                        addHtml += '</tr>';
                        addHtml += '<tr class="sunTerm Term_' + sunTerm[n]['parent'] + (sunTerm[n]['parent']==FirstId?'':' hide')+'" pid="' + sunTerm[n]['parent'] + '" taxonomy="' + data[i]['taxonomy'] + '">';
                        addHtml += '	<td colspan="12" align="left" class="Btn">';
                        addHtml += '		<input type="button" class="submit cursor addSunTermBtn" value=" 提交第二分类 " onfocus="this.blur();" style="margin-left:27px;padding:0px 5px;margin-bottom:10px;border:0px;height: 29px;width: 100px;"/>';
                        addHtml += '	</td>';
                        addHtml += '</tr>'
                    }
                    selectHtml = '<option value="' + sunTerm[n]['id'] + '" ' + (sunTerm[n]['banner'] ? 'style="color:blue;"': '') + '> &nbsp;&nbsp;-' + sunTerm[n]['name'] + '</option>';
                    $('select[name="taxonomy"]').append(selectHtml)
                }
                html += addHtml;
            } else {
                addHtml = '';
                addHtml += '<tr class="sunTerm Term_' + data[i]['id'] + ' hide add_' + data[i]['id'] + '">';
                addHtml += '	<td style="padding-left:32px;text-align:left;"><a><img style="width: 12px; height: 12px;" src="' + site_url + '/themes/admin/images/icon_add.gif"></a></td>';
                addHtml += '	<td>-</td>';
                addHtml += '	<td><input type="text"></td>';
                addHtml += '	<td><input type="text"></td>';
                addHtml += '	<td><input type="text" class="middle"></td>';
                // addHtml += '	<td>-</td>';
                // addHtml += '	<td>' + uername + '</td>';
                addHtml += '    <td><input type="text"></td>';
                addHtml += '	<td>-</td>';
                addHtml += '	<td><select><option value="1" selected>是<option value="0">否</select></td>';
                addHtml += '	<td>' + time + '<input type="hidden" value="' + time + '"></td>';
                addHtml += '	<td><a href="javascript:;" class="delete">删除</a></td>';
                addHtml += '</tr>';
                addHtml += '<tr class="sunTerm Term_' + data[i]['id'] + ' hide">';
                addHtml += '	<td colspan="12" align="left"><a href="javascript:;" style="margin-left:23px;color:#0099ff;float:left;padding-left:5px;" class="addSetting" pid="' + data[i]['id'] + '">[+]添加子分类</a></td>';
                addHtml += '</tr>';
                addHtml += '<tr class="sunTerm Term_' + data[i]['id'] + ' hide" pid="' + data[i]['id'] + '" taxonomy="' + data[i]['taxonomy'] + '">';
                addHtml += '	<td colspan="12" align="left" class="Btn">';
                addHtml += '		<input type="button" class="submit cursor addSunTermBtn" value="提交第二分类" onfocus="this.blur();" style="margin-left:27px;padding:0px 2px;margin-bottom:10px;border:0px;height: 29px;width: 100px;"/>';
                addHtml += '	</td>';
                addHtml += '</tr>';
                html += addHtml;
            }
        }
        var first_html = $("#list_table").find('tr').eq(0).html();
        $("#list_table").html('<tr class="field">' + first_html + '</tr>' + html);
        $(".list_table tr::nth-child(even)").addClass('even')
    } catch(e) {
        alert(e.message)
    }
}

function getSunclass(subclass,id){
    if(!subclass){
        return '-';
    }
	var html = '<select name="subclass" class="subclass '+(id?'hide':'')+'">';
	var subclassVal = '';
	for(var i in subclass){
		subclassVal=!id?(subclassVal?subclassVal:subclass[i]):'';
		if(id==i){
			html += '<option value="'+i+'" selected>'+subclass[i]+'</option>';
		}else{
			html += '<option value="'+i+'">'+subclass[i]+'</option>';
		}
	}
	html += '</select>'+(subclassVal?'<input type="hidden" value="'+subclassVal+'">':'');
	return html;
}