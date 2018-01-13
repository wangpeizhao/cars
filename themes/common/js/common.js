function checkLP(baseUrl, model) {
    try {
        var result = false;
        $.post(baseUrl + "/admin/system/" + model, {
            act: 'checkLP'
        },
        function(data) {
            if (data.done === true) {
                return true
            } else if (data.msg) {
                alert(data.msg);
                return false
            } else {
                return false
            }
            return result
        },
        "json")
    } catch(e) {
        alert(e.message)
    }
}
function isIE() {
    if (document.all && navigator.userAgent.indexOf("MSIE") > 0) {
        if (navigator.userAgent.indexOf("MSIE 6.0") > 0) return '6';
        else if (navigator.userAgent.indexOf("MSIE 7.0") > 0) return '7';
        else if (navigator.userAgent.indexOf("MSIE 8.0") > 0) return '8';
        else if (navigator.userAgent.indexOf("MSIE 9.0") > 0) return '9';
        else return true
    }
    return false
}
function selectAll(idDOM) {
    $("#" + idDOM + " :checkbox").each(function() {
        if ($("#selectAll").attr('status') == 'uncheck') {
            $(this).attr('checked', true)
        } else {
            $(this).attr('checked', false)
        }
    });
    if ($("#selectAll").attr('status') == 'checked') {
        $("#selectAll").attr('status', 'uncheck')
    } else {
        $("#selectAll").attr('status', 'checked')
    }
}
function deleteAll(idDOM) {
    if (confirm('是否把信息放到回收站内？')) {
        if ($("#" + idDOM + " :checkbox:checked").length == 0) {
            alert('请您至少选择一项');
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}
function dumpAll(idDOM) {
    if (confirm('是否粉碎(彻底删除)所选信息？')) {
        if ($("#" + idDOM + " :checkbox:checked").length == 0) {
            alert('请您至少选择一项');
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}
function recoverAll(idDOM) {
    if (confirm('是否还原所选信息？')) {
        if ($("#" + idDOM + " :checkbox:checked").length == 0) {
            alert('请您至少选择一项');
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}
function backUpAll(idDOM) {
    if (confirm('是否备份所选数据表？')) {
        if ($("#" + idDOM + " :checkbox:checked").length == 0) {
            alert('请您至少选择一项');
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}
function optimizeAll(idDOM) {
    if (confirm('是否优化所选数据表？')) {
        if ($("#" + idDOM + " :checkbox:checked").length == 0) {
            alert('请您至少选择一项');
            return false
        } else {
            return true
        }
    } else {
        return false
    }
}
function in_array(val, arr) {
    var type = typeof val;
    if (type == 'string' || type == 'number') {
        for (var i in arr) {
            if (val == arr[i]) {
                return true
            }
        }
    }
    return false
}
function validate(value, pattern) {
    switch (pattern) {
    case 'required':
        pattern = /\S+/i;
        break;
    case 'email':
        pattern = /^\w+([-+.]\w+)*@\w+([-.]\w+)+$/i;
        break;
    case 'qq':
        pattern = /^[1-9][0-9]{4,}$/i;
        break;
    case 'id':
        pattern = /^\d{15}(\d{2}[0-9x])?$/i;
        break;
    case 'ip':
        pattern = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/i;
        break;
    case 'ips':
        pattern = /^((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]);))+$/i;
        break;
    case 'zip':
        pattern = /^\d{6}$/i;
        break;
    case 'phone':
        pattern = /^((\d{3,4})|\d{3,4}-)?\d{7,8}(-\d{3})*$/i;
        break;
    case 'mobi':
        pattern = /^[0-9]{8,20}$/i;
        break;
    case 'url':
        pattern = /^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))+(\/?\S*)?$/i;
        break;
    case 'integer':
        pattern = /^\d+$/i;
        break
    }
    if (value.search(pattern) == -1) {
        return false
    } else {
        return true
    }
}
function IsURL(str_url) {
    var strRegex = "^((https|http|ftp|rtsp|mms)?://)" + "?(([0-9a-zA-Z_!~*'().&=+$%-]+: )?[0-9a-zA-Z_!~*'().&=+$%-]+@)?" + "(([0-9]{1,3}\.){3}[0-9]{1,3}" + "|" + "([0-9a-zA-Z_!~*'()-]+\.)*" + "([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\." + "[a-zA-Z]{2,6})" + "(:[0-9]{1,4})?" + "((/?)|" + "(/[0-9a-zA-Z_!~*'().;?:@&=+$,%#-]+)+/?)$";
    var re = new RegExp(strRegex);
    return re.test(str_url)
}
function formSubmit(formName) {
    $('form[name="' + formName + '"]').submit()
}
function checkboxCheck(boxName, errMsg) {
    if ($('input[name="' + boxName + '"]:checked').length < 1) {
        alert(errMsg);
        return false
    }
    return true
}
function changeCaptcha(urlVal) {
    var radom = Math.random();
    if (urlVal.indexOf("?") == -1) {
        urlVal = urlVal + '/' + radom
    } else {
        urlVal = urlVal + '&random' + radom
    }
    $('#captchaImg').attr('src', urlVal)
}
function event_link(url) {
    window.location.href = url
}
function postShare(type, url, title) {
    url = url || "";
    url = encodeURIComponent(url);
    title = title || "";
    title = encodeURI(title);
    desURL = "";
    switch (type) {
    case 'qq':
        desURL = 'http://v.t.qq.com/share/share.php?url=' + url + '&appkey=&site=&pic=&title=' + title;
        break;
    case 'kaixin':
        desURL = "http://www.kaixin001.com/repaste/share.php?rtitle=" + title + "&rurl=" + url;
        break;
    case 'renren':
        desURL = "http://share.renren.com/share/buttonshare.do?title=" + title + "&link=" + url;
        break;
    case 'douban':
        desURL = "http://www.douban.com/recommend/?url=" + url + "&title=" + title;
        break;
    case 'xinlang':
        desURL = "http://v.t.sina.com.cn/share/share.php?title=" + title + "&url=" + url;
        break;
    default:
        break
    }
    if (desURL) {
        window.open(desURL, '', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes,status=no')
    }
}

function  timetodate(tim,dat){
	Date.prototype.pattern=function(fmt) {       
		var o = {        
			"M+" : this.getMonth()+1, //月份       
			"d+" : this.getDate(), //日      
			"h+" : this.getHours() == 0 ? 12 : this.getHours(), //小时       
			"H+" : this.getHours(), //小时       
			"m+" : this.getMinutes(), //分       
			"s+" : this.getSeconds(), //秒       
			"q+" : Math.floor((this.getMonth()+3)/3), //季度       
			"S" : this.getMilliseconds() //毫秒       
		};
		var week = {
			"0" : "\u65e5",       
			"1" : "\u4e00",       
			"2" : "\u4e8c",       
			"3" : "\u4e09",       
			"4" : "\u56db",       
			"5" : "\u4e94",       
			"6" : "\u516d"     
		};

		if(/(y+)/.test(fmt)){
			fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));       
		}
		if(/(E+)/.test(fmt)){
			fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[this.getDay()+""]);       
		}
		for(var k in o){
			if(new RegExp("("+ k +")").test(fmt)){       
			 fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));       
			}
		}
		return fmt;
	}
    return  new Date(parseInt(tim)*1000).pattern(dat);   //"yyyy/MM/dd,hh,mm,ss"    
}
function js_strto_time(str_time){
    var arr  = str_time.split(" ");
	var date = arr[0].split("-");
	if(arr[1]){
		var time = arr[1].split(":");
		var datum = new Date(Date.UTC(date[0],date[1]-1,date[2],time[0],time[1],time[2]));
	}else{
		var datum = new Date(Date.UTC(date[0],date[1]-1,date[2]));
	}
    return datum.getTime()/1000;
}