$(function(){
		//导航下拉
		//$('#nav li').eq(0).addClass('hover active');
		var slide = false;
		$('#nav li').hover(function() {
			var _S = $(this);
			if(_S.find('> dl').css('display') == "none"){
				_S.find('> dl').slideDown(200);
				slide = true;
				_S.addClass('active');
			}
			_S.addClass('on');
		}, function() {
			var _S = $(this);
			if(slide == true){
				_S.find('> dl').slideUp();
				slide = false;
				setTimeout(function(){
					_S.removeClass('active');
				},500);
			}
			_S.removeClass('on');
		});
		//var drag = new Drag("note", {
		//	mxContainer: "noteContainer",
		//	Handle: "noteHandle",
		//	Limit: true
		//});
		$("a").click(function(){
			$(this).blur();
		});
		if(isIE()){
			$("a").attr("hidefocus",true);
		}
		$("a.close").live('click',function(){
			$("#note").fadeOut('500');
			if(isIE()<10){
				$(".note_bg").hide();
			}else{
				$(".note_bg").fadeOut('500');
			}
		});
		$("#content ul li.btn input").live('click',function(){
			this.blur();
		});
		$(".doNote").click(function(){
			$("#note").fadeIn();
			setTimeout(function(){
				$("img#loadding_note").addClass('hide');
				$("img.vCode_note").removeClass('hide');
				$("img.vCode_note").click();
			},2000);
			if(isIE()<10){
				$(".note_bg").show();
			}else{
				$(".note_bg").fadeIn();
			}
		});
		$(".noteBtn").click(function(){
			var $this = $("#note #content ul li");
			var phone = $this.find('.phone').val();
			var email = $this.find('.email').val();
			var nText = $this.find('.nText').val();
			var vCode = $this.find('#vCode').val();
			if(vCode.search(/^\w{4}$/)==-1){
				$this.find('.tips').html('<font color="#ff0000">验证码错误.</font>');
				$this.find('#vCode').val(vCode);
				$this.find('#vCode').focus();
				return false;
			}
			if(phone.search(/(^(([0\+]\d{2,3}[-| ])?(0\d{2,3})[-| ])?(\d{6,8})([-| ](\d{3,}))?$)|(^((\+86)?(86)?(0))?1[3|4|5|6|7|8][0-9]{9}$)/)==-1){
				$this.find('.tips').html('<font color="#ff0000">您的电话号码有误.</font>');
				$this.find('.phone').val(phone);
				$this.find('.phone').focus();
				return false;
			}
			if(email.search(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))){2,6}$/i)==-1){
				$this.find('.tips').html('<font color="#ff0000">您的电子邮件有误.</font>');
				$this.find('.email').val(email);
				$this.find('.email').focus();
				return false;
			}
			if(nText=='Your text here.'){
				$this.find('.nText').val('');
				$this.find('.nText').focus();
				$this.find('.tips').html('<font color="#ff6600">请描述您的问题.</font>');
				return false;
			}
			if(nText.length<5 || nText.length>500){
				$this.find('.nText').val(nText);
				$this.find('.nText').focus();
				$this.find('.tips').html('<font color="#ff6600">问题描述请保持在5~500个字符之间.</font>');
				$this.find('.nText').scrollTop($this.find('.nText').scrollHeight);//DIV滚动条置底
				return false;
			}
			$this.find('.tips').html('<font color="#0066ff">正在提交...</font>');
			try{
				$.post(domain+ "/index/comment",{phone:phone,email:email,declare:nText,vCode:vCode}, function(data){
					if(data.done === true){
						$this.find('.tips').html('<font color="#339900">提交成功...</font>');
						setTimeout(function(){
							$("#note a.close").click();
							$("#note .nText").val('');
							$this.find('#vCode').val('');
							$this.find('.tips').html('');
							$("img#loadding_note").removeClass('hide');
							$("img.vCode_note").addClass('hide');
							//$this.find('.nText').val('Your text here.');
						},1000);
					}else if(data.msg){
						$this.find('.tips').html('<font color="#ff6600">'+data.msg+'</font>');
					}else{
						$this.find('.tips').html('<font color="#ff6600">提交失败,请重试</font>');
					}
				},"json");
			}catch(e){
				alert(e.message);
			}
		});
	});
	
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

	function checkKey(){
		if($("#global_search").val()=='输入搜索内容'){
			alert('请先输入搜索内容');
			$("#global_search").focus();
			return false;
		}
	}