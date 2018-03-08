<!doctype html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=gbk" />
<title>您找的页面不存在</title>
<style type="text/css">
<!--
body {
	padding-right: 0px;
	padding-left: 35px;
	padding-bottom: 0px;
	margin: 0px;
	font: 12px arial, helvetica, sans-serif;
	color: #333;
	padding-top: 35px
}

a {
	color: #007ab7;
	text-decoration: none
}

a:hover {
	color: #007ab7;
	text-decoration: none
}

a:hover {
	color: #de1d6a
}

.hidehr {
	display: none
}

.img404 {
	padding-right: 0px;
	padding-left: 0px;
	background: url('<?= img_url('404_tips.gif')?>') no-repeat left top;
	float: left;
	padding-bottom: 0px;
	margin: 0px;
	width: 80px;
	padding-top: 0px;
	position: relative;
	height: 90px
}

h2 {
	padding-right: 0px;
	padding-left: 0px;
	font-siZe: 16px;
	float: left;
	padding-bottom: 25px;
	margin: 0px;
	width: 80%;
	line-height: 0;
	padding-top: 25px;
	border-bottom: #ccc 1px solid;
	position: relative
}

.content {
	clear: both;
	padding-right: 0px;
	padding-left: 0px;
	font-siZe: 13px;
	left: 80px;
	float: left;
	padding-bottom: 0px;
	margin: 0px;
	width: 80%;
	line-height: 19px;
	padding-top: 0px;
	position: relative;
	top: -30px
}

.content ul {
	padding-right: 35px;
	padding-left: 35px;
	padding-bottom: 20px;
	margin: 0px;
	padding-top: 10px
}

.show14 ul li {
	margin-bottom: 5px
}
-->
</style>
</head>
<body>
	<h1 class=hidehr>当前在网站上（<?=site_url('')?>）找不到你要的页面。
	</h1>
	<div class=img404></div>
	<h2>抱歉，找不到您要的页面……</h2>
	<div class=content>
		非常的抱歉，并没有找到您需要的页面。最可能的原因是：
		<ul>
			<li>地址栏的地址可能是错误的。
			<li>您的点击链接不存在或者存在错误，请联系管理员解决。</li>
		</ul>
		<strong>请点击下面链接返回首页或者继续浏览上一页面</strong>（<a href="<?=site_url('')?>"
			target="_blank">
			<?=site_url('')?>
		</a>）：
		<div class=show14>
			<ul>
				<li><a title="返回站长好站首页" href="<?=site_url('')?>">返回网站首页</a>
				<li><a title="返回上一个页面" href="javascript:history.back(-1)">返回上一页</a></li>
			</ul>
		</div>
		<span style="visibility: hidden"></span>
</body>
</html>