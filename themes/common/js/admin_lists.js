var rows = 10,condition = [];

$(function(){
	try{
		setData(1);

		//全选/全否选
		$("#selectAll").click(function(){
			selectAll('list_table');
		});

		//批量删除
		$("#deleteAll").click(function(){
			if(deleteAll('list_table')){
				var idDOM = $("#list_table"+" :checkbox");
				var ids = [];
				for(var i=0;i<idDOM.length;i++){
					if(idDOM[i].checked){
						ids.push(idDOM.eq(i).val());
					}
				}
				del(ids);
			}
		});

		//文档回收站
		$(".operating #recycle").click(function(){
			event_link(baseUrl + lang +'/admin/'+_TYPE_+'/recycles');
		});

		//添加新闻资讯
		$(".operating #add").click(function(){
			event_link(baseUrl + lang +'/admin/'+_TYPE_+'/add');
		});

		//操作
		$("#list_table a").live('click',function(){
			try{
				var act = $(this).attr('act');
				var id = $(this).parent().attr('id');
				switch(act){
					case 'edit':
                      event_link(baseUrl + lang +'/admin/'+_TYPE_+'/edit/'+id+'.html');
						break;
					case 'del':
						if(confirm('是否把信息放到回收站内？')){
							del(id);
						}
						break;
				}
			}catch(e){
				alert(e.message);
			}
		});

		$('#startTime').click(function(){
			WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'2018-01-01 00:00:00',maxDate:_DATETIME_});
		});

		$('#endTime').click(function(){
			WdatePicker({minDate:'#F{$dp.$D(\'startTime\')}',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:_DATETIME_});
		});

	}catch(e){
		alert(e.message);
	}
});

function iResult(str){
	if(str==1){
		alert('修改成功!');
		window.history.back('-1');
	}else{
		alert(str);
		return false;
	}
}

function iResultAlter(str, status) {//#5cb85c;
    if (status == 0) {
      alert(str);
      return false;
    }
    //2 Warning;1 Success;0 Error
    var color = status == 2 ? '#f0ad4e' : (status == 0 ? '#d9534f' : '#5cb85c');
    $('span.validatePrompt').css('color', color).html(str).fadeIn();
    setTimeout(function () {
      $('span.validatePrompt').fadeOut();
    }, 2000);
    alert(str);
    window.location.reload();
    return true;
}

function del(id){
	$.post(baseUrl + lang + '/admin/'+_TYPE_+'/del',{id:id}, function(data){
		if (data.done === true) {
			alert('已放入回收站');
			setData($('input[name="currentPage"]').val());
		}else if(data.msg){
			alert(data.msg);
			return false;
		}else{
			alert('提交失败，请重试');
			return false;
		}
	},"json");
}

//搜索
function doSearch(){
	setData(1);
}