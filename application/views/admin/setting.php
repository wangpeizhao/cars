<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理 - 后台首页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="<?=site_url('')?>/themes/admin/css/admin.css" />
<script charset="UTF-8" src="<?=site_url('')?>/themes/common/js/jquery-1.4.4.min.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/common.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/admin.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/highcharts.js"></script>
<script type='text/javascript' src="<?=site_url('')?>/themes/common/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
<!--
	var baseUrl = '<?=WEB_DOMAIN?>',
		Ttype = '今天',
		time = 0;
	$(function(){
		try{
			$("a").live('click',function(){
				$(this).blur();
			});
			$("ul.tab li").live('click',function(){
				index = $(this).index();
				if(index==1){
					$(".report_form .date a").eq(0).click();
				}
				$(this).addClass('selected').siblings().removeClass('selected');
				$(".div_box_setting").find("div.div").eq(index).siblings().hide();
				$(".div_box_setting").find("div.div").eq(index).fadeIn();
			});

			$(".report_form .date a").click(function(){
				var type = $(this).attr('t');
				time = $(this).attr('time');
				changeDate(time,type);
				$(this).addClass('strong').siblings().removeClass('strong');
				Ttype = $(this).text();
				$("#date1").removeClass('cStrong');
				$("#downTtype").val('点击下载'+Ttype+'访问IPS');
				$('input[name="downTtype"]').val(time);
				$('input[name="type"]').val(type);
			});

			$("#close").live('click',function() {
				isIE()==6 || isIE()==7?$(".bg").hide():$(".bg").fadeOut("slow");
				if($(".lay").is(':visible')){$(".lay").fadeOut("slow");}
			});
		}catch(e){
			alert(e.message);
		}
	});

	function choosedDay(Ctime){
		$(".report_form .date a").removeClass('strong');
		$("#date1").addClass('cStrong');
		Ttype = Ctime;
		changeDate(js_strto_time(Ctime),'h');
		$("#downTtype").val('点击下载'+Ttype+'访问IPS');
		$('input[name="downTtype"]').val(js_strto_time(Ctime));
		$('input[name="type"]').val('h');
		time = js_strto_time(Ctime);
	}

	function changeDate(time,type){
		try{
			$.post(baseUrl + lang + "/admin/system/ipChart",{time:time,type:type}, function(data){
				if (data.done === true) {
					$("#count").html(data.data.count);
					return showChart(data.data);
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

	function showChart(data){
		var chart,
			categories = data.categories;
		var colors = Highcharts.getOptions().colors;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                zoomType: 'xy',
				borderWidth:1
            },
            title: {
                text: '“访问IP” 时段统计报表 ('+Ttype+')'
            },
            subtitle: {
                text: '独立IP UV(unique visitor)'
            },
            xAxis: [{
                categories: data.categories,
				labels: {
					rotation: -60,
					align: 'right'
				}
            }],
            yAxis: [{
                labels: {
                    formatter: function() {
                        return this.value;
                    },
                    style: {
                        color: '#F77804'
                    }
                },
                title: {
                    text: '访问IP：（单位：次）',
                    style: {
                        color: '#F77804'
                    }
                },
                gridLineWidth: 0
    
            }],
			plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
								var title = 'IP详细 <font style="font-weight:normal;font-size:12px;color:#0066ff;">'+Ttype+'('+categories[this.x]+')</font> IP访问量：'+this.y;
								var data = {'title':title};
								//alert(this.x);
								//alert(categories[this.x]);
								showIps(data,categories[this.x]);
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>独立IP访问视图</b><br><b>'+Ttype+'</b>('+this.x+')<br><b>独立IP访问量：</b>'+this.y+'<br>点击图柱显示IP详细';
                }
            },
            series: [{
                name: '('+Ttype+')'+'访问IP(单位:次)',
                color: '#F77804',
                type: 'column',
				yAxis: 0,
				data: data.data
    
            }],
            exporting: {
                enabled: false
            }
		});
	}

	function showIps(data,val){
		if(isIE()==6 || isIE()==7 || isIE()==8){
			$(".bg,.lay").show();
		}else{
			$(".bg,.lay").fadeIn();
		}
		$(".layTitle").html(data.title);
		$(".IpList div").html('<img src="<?=site_url('')?>/themes/admin/images/loading.gif" title="Loading..."/>');
		$('input[name="time"]').val(time);
		$('input[name="val"]').val(val);//alert(time);alert(val);
		$.post(baseUrl + lang + "/admin/system/ipChart",{time:time,val:val,act:'get'}, function(data){
			$(".IpList div").html('');
			if (data.done === true) {
				$.each(data.data,function(k,v){
					$(".IpList div").append('<font color="#0066ff">'+v.ip+'</font>  '+v.create_time+'<br>');
					$('.IpList').scrollTop($(".IpList div").height());//DIV滚动条置底
				});
			}else if(data.msg){
				alert(data.msg);
				return false;
			}else{
				$(".IpList div").append('<font color="#ff0000">没有ip记录</font>');
				return false;
			}
		},"json");
	}
//-->
</script>
<style type="text/css">
	.report_form{padding:10px 0 30px 20px; width:730px;background:#fff; min-height:394px; _height:394px; _overflow:visible;border-left:1px solid #dcdcdc;}
	.reportformBox{width:750px;}
	.tjTips{border:1px #F9C943 solid;clear: both;font-size: 12px;height: auto;margin-bottom: 10px;margin-top: 10px;padding: 6px;background:#FFFFEE;width:736px;}
	.date{background: url("<?=site_url('')?>themes/common/images/date_bg.gif") repeat-x scroll 50% top transparent;border-left: 1px solid #DFE7EB;border-right: 1px solid #DFE7EB;clear: both;height: 41px;line-height: 41px;margin: 0 0 0px;width: 748px;}
	.date a{ margin:10px 2px 0 10px; padding:2px 6px; border:1px solid #E4E4E4; text-decoration:none!important;border-color:#ff6600; }
	.date a:hover { border-color:#ff6600; }
	.date a.strong{ border-color:#ff0000; background:#ff6600;color:#fff;font-weight:bold;}
	.contentTJ{width: 748px;height:300px;}
	.td{border:solid #e1e5ee; border-width:0px 1px 1px 0px;}
	.table{border:solid #e1e5ee; border-width:1px 0px 0px 1px;}
	.mar_top7{margin-top:7px;}
	.color_969 {color: #969595;}
	.changDate{ margin:10px 2px 0 10px; padding:2px 6px; border:1px solid #E4E4E4; text-decoration:none!important;border-color:#ff6600;height:17px;line-height:17px;width:172px; }
	.cStrong{border-color:#ff0000; background:#ff6600;color:#fff;font-weight:bold;}
</style>
</head>
<body>
	<div class="container">
		<!-- 引入头部-->
		<?php include('header.php');?>
		<!-- /引入头部-->
		<!-- 引入二级菜单-->
		<?php include('submenu.php');?>
		<!-- /引入二级菜单-->
		<div id="admin_right">
			<div class="headbar">
				<div class="position">
					<span>系统</span><span>></span><span>权限管理</span><span>></span><span>后台首页</span>
				</div>
				<ul name="menu1" class="tab">
					<li class="selected"><a href="javascript:;">网站概况</a></li>
					<li><a href="javascript:;">查看访问IP</a></li>
			    </ul>
			</div>
			<div class="content_box">
			<div class="content link_target" align="left">
				<!--container-->
				<div class="content form_content" style="height: 298px;">
					<div class="div_box_setting">
						<div class="div" style="margin-left:10px;" align="left">
							<div class="clear"></div>
							<table width="45%" cellspacing="0" cellpadding="5" style="float:left" class="border_table_org">
								<thead>
									<tr><th><span class="l">项目</span><span class="r">数量</span></th></tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table width="100%" class="list_table2">
												<colgroup>
													<col width="125px">
													<col>
													<col width="80px">
												</colgroup>
												<tbody>
													<tr><th>管理员</th><td colspan="2"><b class="f14 red3"><?=$data['admin']?></b> </td></tr>
													<tr><th>产品数量</th><td colspan="2"><b class="f14 red3"><?=$data['products']?></b> </td></tr>
													<tr><th>新闻数量</th><td colspan="2"><b class="f14 red3"><?=$data['news']?></b> </td></tr>
													<tr><th>服务与支持</th><td colspan="2"><b class="f14 red3"><?=$data['services']?></b> </td></tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="div" style="display:none;">
							<div class="fleft report_form m_manage" style="width: 750px;">
								<div class="reportformBox" style="width: 750px;">
									<h2 style="border-bottom: 2px #B7CFE6 solid;">
										“访问IP” 时段统计报表<font id="timeTnterval" style="font-size: 12px; font-weight: Normal;"></font>
									</h2>
									<div class="date" style="width: 748px;">
										<a t="h" time="<?=strtotime(date("Y-m-d",time()))?>" href="javascript:;" class="a strong" title="点击查看今天独立IP">今天</a> 
										<a t="h" time="<?=strtotime(date("Y-m-d",time()-3600*24))?>" href="javascript:;" class="a" title="点击查看昨天独立IP">昨天</a> 
										<a t="d" time="<?=strtotime(date("Y-m-d",time()-3600*24*7))?>" href="javascript:;" class="a" title="点击查看最近七天独立IP">最近七天</a> 
										<a t="d" time="<?=strtotime(date("Y-m-d",time()-3600*24*30))?>" href="javascript:;" class="a" title="点击查看最近30天独立IP">最近30天</a> 
										<a t="d" time="<?=strtotime(date("Y-m"))-3600*24?>" href="javascript:;" class="a" title="点击查看本月独立IP">本月</a> 
										<input type="text" name="date" style="width:85px" class="date_bg changDate cursor" id="date1" onFocus="WdatePicker({startDate:'%y-%M-%d',dateFmt:'yyyy-MM-dd',minDate:'2010-01-01',maxDate:'<?=date("Y-m-d")?>',onpicking:function(dp){choosedDay(dp.cal.getNewDateStr());}})" value="<?=date('Y-m-d')?>" title="点击查看指定日期独立IP"/>
									</div>
									<div class="contentbox">
										<table
											style="font-weight: bold; font-size: 14px; line-height: 25px;">
											<tr>
												<td><span class="spread">独立IP分布图</span>&nbsp;&nbsp;访问量：<font color="#0066ff" id="count">0</font></td>
												<td>
													<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/ipChart" target="ajaxifr">
														<input type="hidden" name="downTtype" value="<?=strtotime(date("Y-m-d",time()))?>">
														<input type="hidden" name="act" value="down">
														<input type="hidden" name="type" value="">
														<input class="submit" id="downTtype" type="submit" value="" style="margin-left:20px;font-weight:normal;font-size:12px;" onfocus="this.blur();"/>
													</form>
												</td>
											</tr>
										</table>
										<div style="border: 1px solid #DFE7EB; height: 300px;">
											<div class="contentTJ order_container" id="container"></div>
										</div>
									</div>
								</div>
								<div class="c"></div>
							</div>
						</div>
					</div>
				</div>
				<!--/container-->
			</div>
		</div>
		<div id="separator"></div>
	</div>
	<script type='text/javascript'>
		//隔行换色
		$(".list_table tr::nth-child(even)").addClass('even');
		$(".list_table tr").hover(function() {
			$(this).addClass("sel");
		}, function() {
			$(this).removeClass("sel");
		});
		$(function() {
			$('#headth th').each(function(i) {
				var width = $('#headth th:eq(' + i + ')').width();
				$('#conth tr:eq(0) td:eq(' + i + ')').width(width - 2);
			});
		});
	</script>
	<div class="bg hide"></div>
	<div class="lay hide" align="center">
		<table style="margin-top:5px;">
			<tr>
				<td class="layTop">
					<span class="layTitle l" style="font-size:14px;padding-left:0px;"></span>
					<span class="r cursor" id="close"><a href="javascript:;" class="closeBtn" style="top:5px;"></a></span>
				</td>
			</tr>
			<tr>
				<td align="left">
					<div style="padding:0px;margin:0px;">
						<div style="padding:0px;" class="Comment">
							<div class="Comment_item" style="padding:0px;margin-top:10px;width:510px;">
								<table id="user_info" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#e1e5ee">
									<div class="IpList"><div align="left"></div></div>
								</table>
								<table width="100%" border="0" align="left" id="Btn">
									<tr>
										<td align="center">
										<iframe name="ajaxifr" style="display:none;"></iframe>
										<form method="post" action="<?=WEB_DOMAIN.(_LANGUAGE_=='en'?'/en':'')?>/admin/system/ipChart" target="ajaxifr">
											<input type="hidden" name="time">
											<input type="hidden" name="val">
											<input type="hidden" name="act" value="down">
											<input class="submit" type="submit" id="down_ip_list" value="下载" onfocus="this.blur();"/>
											<input class="submit" type="button" id="close" value="关闭" onfocus="this.blur();"/>
										</form>
										<input type="hidden" name="act">
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>
