function page_html($pages, $thepage) {
    try {
        if ($pages == 1 && $thepage == 1) {
            return false
        }
        var $prev = $thepage - 1 > 0 ? $thepage - 1: 1;
        var $next = $thepage + 1 <= $pages ? $thepage + 1: $pages;
        var $str = '';
        var $minpage = 0;
        if ($thepage == 1) {
            $str = '<a href="javascript:;" class="page-previous default">上一页</a>'
        } else {
            $str = '<a href="javascript:;" onclick="setData(' + $prev + ')" class="page-previous">上一页</a>'
        }
        if ($pages <= 6) {
            $minpage = $pages
        } else {
            $minpage = 6
        }
        if ($thepage <= 5) {
            for (var $p = 1; $p <= $minpage; $p++) {
                if ($p == $thepage) {
                    $str += '<a href="javascript:;" class="on default">' + $p + '</a>'
                } else {
                    $str += '<a href="javascript:;" onclick="setData(' + $p + ')">' + $p + '</a>'
                }
            }
        } else if ($pages - $thepage <= 4) {
            $str += '<a href="javascript:;" onclick="setData(1">1</a><a href="javascript:;" class="default">..</a>';
            for (var $p = $pages - 5; $p <= $pages; $p++) {
                if ($p == $thepage) {
                    $str += '<a href="javascript:;" class="on default">' + $p + '</a>'
                } else {
                    $str += '<a href="javascript:;" onclick="setData(' + $p + ')">' + $p + '</a>'
                }
            }
        } else {
            $str += '<a href="javascript:;" onclick="setData(1)">1</a><a href="javascript:;" class="default">..</a>';
            for (var $p = $thepage - 2; $p <= $thepage + 2; $p++) {
                if ($p == $thepage) {
                    $str += '<a href="javascript:;" class="on default">' + $p + '</a>'
                } else if ($p <= $pages) {
                    $str += '<a href="javascript:;" onclick="setData(' + $p + ')">' + $p + '</a>'
                }
            }
        }
        if ($pages - $thepage > 2 && $pages > 6) {
            $str += '<a href="javascript:;" class="default">..</a><a href="javascript:;" onclick="setData(' + $pages + ')">' + $pages + '</a>'
        }
        if ($thepage == $pages) {
            $str += '<a href="javascript:;" class="page-next default">下一页</a>'
        } else {
            $str += '<a href="javascript:;" onclick="setData(' + $next + ')" class="page-next">下一页</a>'
        }
        $str += '&nbsp;&nbsp; <span class="select"> 跳转到 <select ';
        $str += "onchange='setData(this.value)'>";
        for (var $i = 1; $i <= $pages; $i++) {
            if ($i == $thepage) {
                $str += '<option value="' + $i + '" selected>' + $i + '</option>'
            } else {
                $str += '<option value="' + $i + '">' + $i + '</option>'
            }
        }
        $str += '</select> 页</span>';
        $('#pageLists').html('<div class="a">'+$str+'</div>')
    } catch(e) {
        alert('page_html:' + e.message)
    }
}