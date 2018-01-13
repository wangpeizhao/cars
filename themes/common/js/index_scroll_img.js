jQuery.easing['jswing'] = jQuery.easing['swing'];
jQuery.extend(jQuery.easing, {
    def: 'easeOutQuad',
    easeInOutExpo: function(x, t, b, c, d) {
        if (t == 0) return b;
        if (t == d) return b + c;
        if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
        return c / 2 * ( - Math.pow(2, -10 * --t) + 2) + b;
    }
});
var num = imgs.length;
//Êý×Öµã»÷
var current = 0;
var ClickTime = true;
var theInt = null;
$(function() {
    for (i = 0; i < num; i++) {
        $("#PicNum").append("<li>" + text[i] + "<span></span></li>");
		//$("#PicNum2").append("<div class=\"Text\">" + (i + 1) + "</div>");
    }
	$("#PicNum li").bind('mouseenter',function(){
		clearInterval(theInt);
	});
	$("#PicNum li").bind('mouseleave',function(){
		HuanDeng();
	});
    $("#PicNum li").eq(0).addClass("cur");
    $("#PicNum li").each(function(i) {
        ClickTime = true;
        $(this).click(function() {
            if (i > current && ClickTime == true) {
                ClickTime = false;
                operate(i,'c');
            }
            if (i < current && ClickTime == true) {
                ClickTime = false;
                operate(i,'c');
            }
        });
    });
    $('#ScrollImg tr').append(generate(0));
	mouse();
    HuanDeng = function() {
        clearInterval(theInt);
        theInt = setInterval(function() {
			var p = current;
            p++;
            if (p < num && ClickTime == true) {
                operate(p,'');
            } else if (p >= num && ClickTime == true) {
                operate(0,'');
            }
            ClickTime = false;
        },
        4000);
    }
    HuanDeng();

});

function generate(i) {
    return '<td><a href="' + link[i] + '" class="scroll_img"><img src="' + imgs[i] + '" width="780"></a></td>';
}
function operate(i,type) {
	var speed = 1000;
	var width = 770;
    $('#ScrollImg tr').append(generate(i));
	mouse();
    $("#ScrollImg").animate({
        marginLeft: "-" + width + "px"
    },{
        queue: false,
        duration: speed,
        easing: "easeInOutExpo",
        complete: function() {
            $('#ScrollImg td').eq(0).remove();
            $('#ScrollImg').css("margin-left", 0);
            ClickTime = true;
        }

    });
	
	if(type){
		$("#PicNum li").removeClass("cur").animate({ "margin-left": "10px" }, 0, function () {});
		$("#PicNum li").eq(i).addClass("cur").animate({ "margin-left": "0px" }, 0, '',function () {});
	}else{
		$("#PicNum li").removeClass("cur").animate({ "margin-left": "10px" }, 300, function () {});
		setTimeout(function() {
			$("#PicNum li").eq(i).addClass("cur").animate({ "margin-left": "0px" }, 30, '',function () {});
		},1000);
	}
    current = i;
}
function mouse(){
	$("a.scroll_img").bind('mouseenter',function(){
		clearInterval(theInt);
	});
	$("a.scroll_img").bind('mouseleave',function(){
		HuanDeng();
	});
}