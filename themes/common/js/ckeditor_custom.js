$(function(){
	try{
		if(isIE()==6 || isIE()==7){
			alert('您的浏览器版本过低，无法兼容此操作，请选择版本更高的浏览器！');
			return false;
		}
		if($(".gbin1-list li").length>0){
			$('.gbin1-list').sortable().bind('sortupdate', function() {
			var new_contentList = [];
			var lis = $("ul.gbin1-list li");
			for(var i=0;i<lis.length;i++){
				var tid = lis.eq(i).attr('tid');
				for(var n in contentList){
					if(contentList[n].tid==tid){
						new_contentList.push(contentList[n]);
						break;
					}
				}
			}
			contentList = new_contentList;
			$('#msg').html('position changed successful').fadeIn(200).delay(2000).fadeOut(200);
			});
		}
		$(".gbin1-list").on('click','li',function(){
			var tid = $(".gbin1-list li.cur").attr('tid');
			$(this).siblings().removeClass('cur');
			$(this).addClass('cur');
			var txt = $(".gbin1-list li.cur span").text();
			$(".edit_block_txt").val(txt);
			var cid = $(".gbin1-list li.cur").attr('tid');
			ckeditor(tid,cid);
		});
		$(".gbin1-list").on('click','li a.close',function(){
			if(confirm('确定要删除该项吗？')){
				var tid = $(".gbin1-list li.cur").attr('tid');
				var cid = $(".gbin1-list li").eq(0).attr('tid');
				for(var i in contentList){
					if(contentList[i].tid==tid){
						contentList.splice(i,1);
						break;
					}
				}
				for(var i in contentList){
					if(contentList[i].tid && contentList[i].content){
						CKEDITOR.instances.content.destroy();
						$('#content').val(contentList[i].content);
						CKEDITOR.replace('content',{
							customConfig: 'custom/config.js'
						});
						break;
					}
				}
				$(this).parent().remove();
				$(".gbin1-list li").eq(0).addClass('cur');
			}
		});
		$(".add_block_btn").click(function(){
			var txt = $(".add_block_txt").val();
			var tid = $(".gbin1-list li.cur").attr('tid');
			if(!$.trim(txt)){
				alert('请输入活动栏目');
				$(".add_block_txt").focus();
				return false;
			}
			$(".add_block_txt").val('');
			$(".edit_block_txt").val(txt);
			$(".gbin1-list li").removeClass('cur');

			var cids = [];
			var lis = $(".gbin1-list li");
			var cid_str = '';
			for(var t=0;t<lis.length;t++){
				var v = parseInt(lis.eq(t).attr('tid'));
				cid_str += cid_str?(','+v):v;
			}
			cids = cid_str.split(',');
			var cid = Math.max.apply(null, cids);
			cid = cid+1;
			//最大值
			var html = '<li class="cur" tid="'+cid+'"><span>'+txt+'</span><a href="javascript:;" class="close"></a></li>';
			$("ul.gbin1-list").append(html);
			$(".section_tip").fadeIn();
			ckeditor(tid,cid);
		});
		$(".edit_block_btn").click(function(){
			var txt = $(".edit_block_txt").val();
			if(!$.trim(txt)){
				alert('请输入活动栏目');
				$(".edit_block_txt").focus();
				return false;
			}
			$(".gbin1-list li.cur span").text(txt);
		});
		$(".edit_block_txt").val($(".gbin1-list li.cur span").text());
	}catch(e){
		alert(e.message);
	}
});

function ckeditor(tid,cid){
	var content = CKEDITOR.instances.content.getData();
	CKEDITOR.instances.content.setData('<span></span>');
	var exist = false;
	var tab = '';
	var lis = $("ul.gbin1-list li");
	for(var i=0;i<lis.length;i++){
		if(lis.eq(i).attr('tid')==tid){
			tab = lis.eq(i).find('span').text();
			break;
		}
	}
	for(var i in contentList){
		if(contentList[i].tid==tid){
			exist = true;
			contentList[i].content = content;
			contentList[i].tab = tab;
			break;
		}
	}

	for(var i in contentList){
		if(typeof(contentList[i].tid)=="undifined" || ($.trim(contentList[i].tab)=='' && $.trim(contentList[i].tab)=='') ){
			contentList.splice(i,1);
		}else if(parseInt(contentList[i].tid) == parseInt(cid)){
			CKEDITOR.instances.content.destroy();//alert(contentList[i].content);
			$('#content').val(contentList[i].content);
			CKEDITOR.replace('content',{
				customConfig: 'custom/config.js'
			});
			break;
		}
	}
	if(exist===false){
		if(typeof(tid)=="undifined" || !tid){
			tid = '';
		}
		contentList.push({
			'tid' : tid,
			'tab' : tab,
			'content' : content,
		});
	}
	$('#contents').val($.toJSON(contentList));
	CKEDITOR.instances.content.focus();
}