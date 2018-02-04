<?php include('header.php');?>
<?=css_url('home.css')?>
<!-- ^contenter -->
<div id="app">
    <div class="pagewrap">
        <!-- ^main -->
        <div class="mainlib">
            <?=!empty($content)?$content:''?>
        </div><!-- $main --><!-- ^right side --><div class="rightlib">
            <?php include('sidebar.php');?>
        </div>
        <!-- $right side -->

    </div>

</div>
<!-- $contenter -->

<!-- ^footer -->
<?php include('footer.php');?>