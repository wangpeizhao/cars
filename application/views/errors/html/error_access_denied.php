<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Access denied</title>
<script charset="UTF-8" src="http://www.cars.com//themes/common/js/jquery-1.7.2.min.js"></script>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
	text-align: center;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
<script type="text/javascript">
	function _window_location_href(url){
		window.location.href = url;
	}
	$(function(){
		setInterval(function(){
			var second = $('#second').text();
			$('#second').text(--second);
			if(second == '0'){
				url = $('#redirect').attr('href');
				if(url != ''){
					_window_location_href(url);
				}else{
					_window_location_href(document.referrer);
				}

			}
		},1000);
		$('#redirect').click(function(){
			var url = $(this).attr('href');
			if(url){
				_window_location_href(url);
			}else{
				if(window.history.back('-1')){
					window.history.back('-1');
				}else{
					_window_location_href(document.referrer);
				}
			}
			return false;
		})
	});
</script>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<p><?php echo $message; ?></p>
		<p><span id="second">3 </span>秒后自动返回</p>
		<p><a href="<?php echo $url ? $url : '';?>" id='redirect'>返回</a></p>
	</div>
</body>
</html>