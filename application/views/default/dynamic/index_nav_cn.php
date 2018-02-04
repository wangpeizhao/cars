<li><a class="nav__" href="http://www.cars.com/" >首页</a></li>
<li><a class="nav__tu" href="http://www.cars.com/tu" >业界</a></li>
<li><a class="nav__nf" href="http://www.cars.com/nf" >7×24h 快讯</a></li>
<li><a class="nav__nev" href="http://www.cars.com/nev" >新能源</a></li>
<li><a class="nav__pev" href="http://www.cars.com/pev" >纯电动</a></li>
<li><a class="nav__hp" href="http://www.cars.com/hp" >混合动力</a></li>
<li><a class="nav__dl" href="http://www.cars.com/dl" >无人驾驶</a></li>
<li class="parent"><a class="nav__in" href="http://www.cars.com/in" >智能</a><em></em><dl><dd><a href="http://www.cars.com/vr">VR虚拟现实</a></dd><dd><a href="http://www.cars.com/ar">AR增强现实</a></dd><dd><a href="http://www.cars.com/ai">AI人工智能</a></dd><dd><a href="http://www.cars.com/uav">UAV无人机</a></dd></dl></li>
<li class="parent"><a class="nav_#" href="javascript:;" >我们</a><em></em><dl><dd><a href="http://www.cars.com/about">关于我们</a></dd><dd><a href="http://www.cars.com/contact">联系我们</a></dd></dl></li>
<script>
  $(function(){
    var _class = '<?=get_style_class()?>';
    $('a.nav_'+_class).parent().addClass('hover active');
  });
</script>