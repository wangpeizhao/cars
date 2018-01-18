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
				_del(ids);
			}
		});

		//批量删除
		$("#dumpAll").click(function(){
			if(dumpAll('list_table')){
				var idDOM = $("#list_table"+" :checkbox");
				var ids = [];
				for(var i=0;i<idDOM.length;i++){
					if(idDOM[i].checked){
						ids.push(idDOM.eq(i).val());
					}
				}
				_dump(ids);
			}
		});

		//批量还原
		$("#recoverAll").click(function(){
			if(recoverAll('list_table')){
				var idDOM = $("#list_table"+" :checkbox");
				var ids = [];
				for(var i=0;i<idDOM.length;i++){
					if(idDOM[i].checked){
						ids.push(idDOM.eq(i).val());
					}
				}
				_recover(ids);
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
						if(typeof(click_list_table_a_edit)=='function'){
							click_list_table_a_edit(id);
							return true;
						}
                      	event_link(baseUrl + lang +'/admin/'+_TYPE_+'/edit/'+id+'.html');
						break;
					case 'del':
						if(typeof(click_list_table_a_del)=='function'){
							click_list_table_a_del(id);
							return true;
						}
						if(confirm('确定要把'+(_TITLE_?_TITLE_:'信息')+'放到回收站内？')){
							_del(id);
						}
						break;
					case 'recover':
						if(confirm('确定要还原该'+(_TITLE_?_TITLE_:'信息')+'吗？')){
							_recover(id);
						}
						break;
					case 'dump':
						if(confirm('确定要彻底删除该'+(_TITLE_?_TITLE_:'信息')+'？不能回撤的哦！')){
							_dump(id);
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

		$('.searchbar input:reset').click(function(){
			setTimeout(function(){
				setData($('input[name="currentPage"]').val());
			},100);
		});

	}catch(e){
		alert(e.message);
	}
});

function iResult(str){
	if(str==1){
		alert('提交成功!');
		window.history.back('-1');
	}else if(str=='2'){
		alert('提交成功!');
		window.location.reload();
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

function _del(id){
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

function _dump(id){
	try{
		$.post(baseUrl + lang + '/admin/'+_TYPE_+'/dump',{id:id}, function(data){
			if (data.done === true) {
				alert('已彻底粉碎该'+(_TITLE_?_TITLE_:'信息'));
				setData($('input[name="currentPage"]').val());
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				alert('提交失败，请重试');
				return false;
			}
		},"json");
	}catch(e){
		alert(e.message);
	}
}

function _recover(id){
	try{
		$.post(baseUrl + lang + '/admin/'+_TYPE_+'/recover',{id:id}, function(data){
			if (data.done === true) {
				alert('已还原该'+(_TITLE_?_TITLE_:'信息'));
				setData($('input[name="currentPage"]').val());
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				alert('提交失败，请重试');
				return false;
			}
		},"json");
	}catch(e){
		alert(e.message);
	}
}

//搜索
function doSearch(){
	setData(1);
}