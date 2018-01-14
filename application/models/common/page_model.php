<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Page_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 生成页码
     * 简介：生成页码
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/15
     */
    function createPages($total, $row, $thepage, $url = '', $postfix = '', $select = true) {
        $pages = ceil($total / $row);
        $prev = $thepage - 1 > 0 ? $thepage - 1 : 1;
        $next = $thepage + 1 <= $pages ? $thepage + 1 : $pages;
        if ($thepage == 1) {
            $str = '<a class="page-previous page-previous-disabled"></a>';
        } else {
            $str = '<a href="' . $url . $prev . $postfix . '" class="page-previous" title="上一页"></a>';
        }
        if ($pages <= 6) {
            $minpage = $pages;
        } else {
            $minpage = 6;
        }
        if ($thepage <= 5) {
            for ($p = 1; $p <= $minpage; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } else {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        } elseif ($pages - $thepage <= 4) {
            $str .= '<a href="' . $url . '1' . $postfix . '" title="1">1</a><a>..</a>';
            for ($p = $pages - 5; $p <= $pages; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } else {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        } else {
            $str .= '<a href="' . $url . '1' . $postfix . '" title="1">1</a><a>..</a>';
            for ($p = $thepage - 2; $p <= $thepage + 2; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } elseif ($p <= $pages) {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        }
        if ($pages - $thepage > 2 && $pages > 6) {
            $str .= '<a>..</a><a href="' . $url . $pages . $postfix . '" title="' . $pages . '">' . $pages . '</a>';
        }
        if ($thepage == $pages) {
            $str .= '<a class="page-next page-next-disabled"></a>';
        } else {
            $str .= '<a href="' . $url . $next . $postfix . '" class="page-next" title="下一页"></a>';
        }
        if ($select) {
            $str .= '<span class="select"><select ';
            $str .= "onchange='window.location=\"$url\"+this.value'>";
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $thepage) {
                    $str .= '<option value="' . $i . '" selected>' . $i . '页</option>';
                } else {
                    $str .= '<option value="' . $i . '">' . $i . '页</option>';
                }
            }
            $str .= '</select></span>';
        }
        return $str;
    }
}
