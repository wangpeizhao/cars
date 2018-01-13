function RollOver(a,b){
	var obj=document.getElementById('index_banner_a_div');
	if(a=='Over'){
		//obj.style.width="0px";
		obj.parentNode.href=b;
		//window.setTimeout(index_banner_reSize,100);		
	}
}
function index_banner_reSize(){
	var obj=document.getElementById('index_banner_a_div');	
	var c=GetPosition(document.getElementById('index_banner_div'));
		obj.style.width=(c.width-20)+"px";
		obj.style.height=(c.height-50)+"px";
		obj.style.top=(c.y+10)+"px";
		obj.style.left=(c.x+10)+"px";
}
function GetPosition(element) {
    var result = new Object();
    result.x = 0;
    result.y = 0;
    result.width = 0;
    result.height = 0;
    if (element.offsetParent) {
        result.x = element.offsetLeft;
        result.y = element.offsetTop;
        var parent = element.offsetParent;
        while (parent) {
            result.x += parent.offsetLeft;
            result.y += parent.offsetTop;
            var parentTagName = parent.tagName.toLowerCase();
            if (parentTagName != "table" &&
                parentTagName != "body" &&
                parentTagName != "html" &&
                parentTagName != "div" &&
                parent.clientTop &&
                parent.clientLeft) {
                result.x += parent.clientLeft;
                result.y += parent.clientTop;
            }
            parent = parent.offsetParent;
        }
    }
    else if (element.left && element.top) {
        result.x = element.left;
        result.y = element.top;
    }
    else {
        if (element.x) {
            result.x = element.x;
        }
        if (element.y) {
            result.y = element.y;
        }
    }
    if (element.offsetWidth && element.offsetHeight) {
        result.width = element.offsetWidth;
        result.height = element.offsetHeight;
    }
    else if (element.style && element.style.pixelWidth && element.style.pixelHeight) {
        result.width = element.style.pixelWidth;
        result.height = element.style.pixelHeight;
    }
    return result;
}


var imglist ="";

var linklist="";



	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0130/20130130041615843.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=15');




	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0130/20130130030717639.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=15');




	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0130/20130130041833657.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=15');




	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0131/20130131035339295.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=185');




	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0131/20130131035555259.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=189');




	if(imglist!=''){

		imglist+='|';

		linklist+='|';

	}

	imglist+=escape('/uploadfile/2013/0131/20130131035454204.jpg');

	linklist+=escape('http://www.cimic.com/index.php?m=content&c=index&a=lists&catid=184');





		var html ='<div id="index_banner_div"  style="width:1000px;height:396px"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
			html+='id="index_banner" style="width:1000px;height:396px" ';
			html+='codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab"> ';
			html+='<param name="movie" value="/statics/images/../cn/index_banner.swf?fheight=396" /> ';
			html+='<param name="quality" value="high" /> ';
			html+='<param name="wmode" value="transparent"> ';
			html+='<param name="bgcolor" value="#ffffff" /> ';
			html+='<param name="allowScriptAccess" value="sameDomain" /> ';
			html+='<param name="flashvars" value="imglist='+imglist+'&linklist='+linklist+'" /> ';			
			html+='<embed src="/statics/images/../cn/index_banner.swf?fheight=396" quality="high" bgcolor="#ffffff" ';
				html+='style="width:1000px;height:396px"  name="index_banner" align="middle" ';
				html+='play="true" ';
				html+='loop="false" ';
				html+='quality="high" ';
				html+='wmode="transparent" ';
				html+='flashvars="imglist='+imglist+'&linklist='+linklist+'" ';
				html+='allowScriptAccess="sameDomain" ';
				html+='type="application/x-shockwave-flash" ';
				html+='pluginspage="http://www.adobe.com/go/getflashplayer"> ';
			html+='</embed> ';
		html+='</object></div><a HREF="/" id="index_banner_a" target="_blank"><div id="index_banner_a_div" style="background: #EEF8F9;left:0;top:0;width:100px;height:100px;position:absolute;filter:alpha(opacity=0);opacity:0.00;-moz-opacity:0.00;z-index:110;"></div></a>';

		document.write(html);
		window.setTimeout(index_banner_reSize,2000);
		