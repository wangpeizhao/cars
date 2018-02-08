<ul>
    <?php if(!empty($mainLists)){
        foreach($mainLists as $_k=>$item){?>
    <li>
        <div class="am-cf inner_li inner_li_abtest">
            <a href="/p/<?=$item['id']?>.html" target="_blank">
                <div class="img_box">
                    <div href="/p/<?=$item['id']?>.html" target="_blank">
                        <img src="<?=WEB_DOMAIN.'/'.$item['thumb']?>" alt="<?=$item['title']?>" class="load-img fade">
                    </div>
                </div>
                <div class="intro">
                    <h3><?=$item['title']?></h3>
                    <div class="abstract"><?=$item['summary']?></div>
                </div>
            </a>
            <div class="info">
                <div class="info-list info-list-abtest">
                    <div class="user-info">
                        <a href="javascript:;" target="_blank" class="name"><?=$item['author']?></a>
                        <span class="oblique_line"> • </span>
                    </div>
                    <div class="time-div">
                        <!-- <span class="time" title="<?=$item['create_time']?>"><?=$item['create_time']?></span> -->
                        <span class="time h5_time" title="<?=$item['timeLine']?>"><?=$item['timeLine']?></span>
                    </div>
                </div>
                <div class="tags-list">
                    <?php if(!empty($item['tags'])){?>
                        <?php foreach($item['tags'] as $k=>$_item){?>
                            <span><a href="/tag/<?=$k?>.html" target="_blank"><?=$_item?></a></span><span class="separate">，</span>
                        <?php }?>
                    <?php }?>
                </div>
                <div class="comments-list">
                    <span class="comments"><?=$item['praises']>$item['views']?$item['praises'].'赞':$item['views'].'浏览'?></span>
                </div>
            </div>
        </div>
    </li>
    <?php if($_k && ($_k+1)%5 == 0){?>
    <li>
        <div class="am-cf inner_li info-flow-monographic-wrapper">
            <div class="mark">专题</div>
            <div class="info-flow-monographic-inner">
                <a href="/topics/1696?version=new" target="_blank">
                    <div class="img-pad" style="background-image: url('https://pic.36krcnd.com/201801/18035501/iqzl0lqi8scmzigx!heading');"></div>
                    <div class="background-cover"></div>
                    <div class="info">
                        <div class="title">飞机上终于可以玩手机了，空中Wi-Fi市场潜力究竟有多大？</div>
                        <div class="desc">1月15日，中国民用航空局网站发布了《机上便携式电子设备(PED)使用评估指南》，开放机上便携式电子设备的使用禁令，随后东航、海南航空等纷纷响应，其中东航能够提供空中WiFi服务的空中互联机队规模已达到74架，未来“空中Wi-Fi”这块蛋糕会有多大？将催生多少价值的新市场？</div>
                    </div>
                </a>
            </div>
        </div>
    </li>
    <?php }?>
    <?php }}?>
</ul>