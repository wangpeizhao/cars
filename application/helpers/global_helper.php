<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/*
 * Parker 2018/01/15
 */

// PHP二维数组排序
function array_sort($arr, $keys, $type = 'asc') {
    $Fzhao_keysVal = $new_array = array();
    foreach ($arr as $k => $v) {
        $Fzhao_keysVal [$k] = $v [$keys];
    }
    if ($type == 'asc') {
        asort($Fzhao_keysVal);
    } else {
        arsort($Fzhao_keysVal);
    }
    reset($Fzhao_keysVal);
    foreach ($Fzhao_keysVal as $k => $v) {
        $new_array [$k] = $arr [$k];
    }
    return $new_array;
}

// 编码转换
function conv($str) {
    if (!trim($str)) {
        return false;
    } else {
        $encode = mb_detect_encoding($str, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
        return mb_convert_encoding($str, 'UTF-8', $encode);
    }
}

//加密算法
function encryption($str = '') {
    return md5(sha1(hash('md4', $str) . 'cqy*wpz'));
}

// 清除HTML标签
function dumpHtml($str) {
    htmlspecialchars($str);
    return strip_tags($str);
}

// 读取内容中的第一张图片URL
function pregpic($html) {
    $arr = array();
    preg_match("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", html_entity_decode($html), $arr);
    return isset($arr [2]) ? $arr [2] : '';
}

/**
 * writeFile
 * 简介：写入文件，覆盖写入
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2012/12/11
 */
function writeFile($content, $file) {
    $fp = fopen($file, "w");
    flock($fp, LOCK_EX);
    ob_start();
    print_r($content);
    $result = fwrite($fp, ob_get_contents());
    ob_end_clean();
    flock($fp, LOCK_UN);
    fclose($fp);
    return $result;
}

/**
 * writeFile
 * 简介：删除字符串中的所有空格 其实是对trim函数的扩展 trim只能删除字符串两边的空格
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2012/12/11
 */
function clearBlank($str) {
    $space = array(" ", "　", "\t", "\n", "\r");
    $trim = array("", "", "", "", "");
    return str_replace($space, $trim, $str);
}

function changeImagePath($path, $type = 'tiny') {
    if (!$path) {
        return false;
    }
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $search = array('/images/', '.' . $ext);
    $replace = array('/' . $type . '/', '_thumb.' . $ext);
    return str_replace($search, $replace, $path);
}

/**
 * bytes
 * 简介：统计文件大小，以GB、MB、KB、B输出
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2012/12/21
 */
function bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $units[$i];
}

/**
 * kc_iconv
 * 简介：编码转换
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function kc_iconv($str, $outCode = 'UTF-8', $inCode = 'GBK') {
    if ($outCode == $inCode) {
        return $str;
    }
    /*
      if(function_exists('iconv')){
      $str=iconv($inCode,"$outCode//IGNORE",$str);
      }elseif(function_exists('mb_convert_encoding')){
      $str=mb_convert_encoding($str,$outCode,$inCode);
      }
      //return $str;
     * 
     */
    return preg_match('//u', $str) ? $str : iconv($inCode, $outCode, $str);
    //return mb_convert_encoding($str,$outCode,$inCode);
}

//检查Email合法性
function is_email($email) {
    $pattern = '/^([^,\\":<>\[\];\(\)@\s])+@[^\*|,\\":<>\[\]{}`\';\(\)&\$#%\^_\+=!~@\.\s]{1}(\w*\.{1}([^@\*|,\\":<>\[\]{}`\';\(\)&$#%\^+=!~\s\.])+)+$/';
    return preg_match($pattern, $email);
}

/**
 * 获得用户的真实IP地址
 */
function real_ip() {
    static $realip = NULL;
    if ($realip !== NULL) {
        return $realip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER ['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);

            /*
             * 取X-Forwarded-For中第一个非unknown的有效IP字符串
             */
            foreach ($arr as $ip) {
                $ip = trim($ip);

                if ($ip != 'unknown') {
                    $realip = $ip;

                    break;
                }
            }
        } elseif (isset($_SERVER ['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER ['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER ['REMOTE_ADDR'])) {
                $realip = $_SERVER ['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip [0]) ? $onlineip [0] : '0.0.0.0';
    return $realip;
}

/**
 * 递归方式的对变量中的特殊字符进行转义
 *
 * @access public
 * @param $value mix       	
 *
 * @return mix
 */
function addslashes_deep($value) {
    if (empty($value)) {
        return $value;
    } else {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

/**
 * 获取服务器的ip
 *
 * @access public
 *        
 * @return string
 *
 */
function real_server_ip() {
    static $serverip = NULL;

    if ($serverip !== NULL) {
        return $serverip;
    }

    if (isset($_SERVER)) {
        if (isset($_SERVER ['SERVER_ADDR'])) {
            $serverip = $_SERVER ['SERVER_ADDR'];
        } else {
            $serverip = '0.0.0.0';
        }
    } else {
        $serverip = getenv('SERVER_ADDR');
    }

    return $serverip;
}

function show_message() {
    $obj = & get_instance();
    $args = func_get_args();
    $data ['url'] = $args [0];
    $data ['msg'] = $args [1];
    $obj->load->view('show_msg', $data);
}

function json_result($retval = '', $msg = '', $jqremote = false) {
    if (!empty($msg)) {
        $msg = '操作成功！';
    }
    json_header();
    $json = json_encode(array('done' => true, 'msg' => $msg, 'retval' => $retval));
    if ($jqremote === false) {
        $jqremote = isset($_GET ['jsoncallback']) ? trim($_GET ['jsoncallback']) : false;
    }
    if ($jqremote) {
        $json = $jqremote . '(' . $json . ')';
    }

    echo $json;
}

function jsonError($msg = '', $retval = null, $jqremote = false) {
    if (!empty($msg)) {
        $msg = $msg;
    }
    $result = array('done' => false, 'msg' => $msg);
    if (isset($retval))
        $result ['retval'] = $retval;
    json_header();
    $json = json_encode($result);
    if ($jqremote === false) {
        $jqremote = isset($_GET ['jsoncallback']) ? trim($_GET ['jsoncallback']) : false;
    }
    if ($jqremote) {
        $json = $jqremote . '(' . $json . ')';
    }

    echo $json;
}

/*
 * 添加 - Fzhao json_error 修改为动态显示错误或警告信息
 */

function json_errorCode($msg = '', $retval = null, $jqremote = false) {
    if (!empty($msg)) {
        // $msg = '操作失败！';
    }
    $result = array('done' => false, 'msg' => $msg);
    if (isset($retval))
        $result ['retval'] = $retval;
    json_header();
    $json = json_encode($result);
    if ($jqremote === false) {
        $jqremote = isset($_GET ['jsoncallback']) ? trim($_GET ['jsoncallback']) : false;
    }
    if ($jqremote) {
        $json = $jqremote . '(' . $json . ')';
    }

    echo $json;
}

function myMail() {
    $this->load->library('email');
    $config['protocol'] = 'smtp';
    $config['charset'] = 'utf8';
    $config['smtp_host'] = 'smtp.qq.com';
    $config['smtp_user'] = '342823274@qq.com';
    $config['smtp_pass'] = 'wangpeizhao0615';
    $config['smtp_port'] = 25;
    $config['smtp_timeout'] = 5;
    $this->email->initialize($config);

    $this->email->from('italentedu@italentedu.com', '国际人才圈');
    $this->email->to('342823274@qq.com');
    $this->email->subject('Email Test');
    $this->email->message('Testing the email class.');
    $this->email->send();
    $result = $this->email->print_debugger();
    //www($result);
    return $result;
}

// ------------------------------------------------------------------------

/**
 * Base URL
 * 
 * Create a local URL based on your basepath.
 * Segments can be passed in as a string or an array, same as site_url
 * or a URL to a file can be passed in, e.g. to an image file.
 *
 * @access	public
 * @param string
 * @return	string
 */
if (!function_exists('_css_url')) {

    function _css_url($uri = '', $folder = 'default') {
        if (!$uri) {
            return '';
        }
        $CI = & get_instance();
        return $CI->config->base_url("/themes/" . $folder . "/css/" . $uri);
    }

}

if (!function_exists('_js_url')) {

    function _js_url($uri = '', $folder = 'default') {
        if (!$uri) {
            return '';
        }
        $CI = & get_instance();
        return $CI->config->base_url("/themes/" . $folder . "/js/" . $uri);
    }

}
if (!function_exists('css_url')) {

    function css_url($uri = '', $folder = 'default') {
        if (!$uri) {
            return '';
        }
        $uris = explode(",", $uri);
        $CI = & get_instance();
        $_string = '';
        foreach ($uris as $uri) {
            $_string .= "<link rel='stylesheet' type='text/css' href='" . $CI->config->base_url("/themes/" . $folder . "/css/" . $uri . '?' . _TIME_) . "'>" . PHP_EOL;
        }

        return $_string;
    }

}

if (!function_exists('js_url')) {

    function js_url($uri = '', $folder = 'default') {
        if (!$uri) {
            return '';
        }
        $uris = explode(",", $uri);
        $CI = & get_instance();
        $_string = '';
        foreach ($uris as $uri) {
            $_string .= "<script type=\"text/javascript\" src=\"" . $CI->config->base_url("/themes/" . $folder . "/js/" . $uri) . "\"></script>" . PHP_EOL;
        }
        return $_string;
    }

}

if (!function_exists('img_url')) {

    function img_url($uri = '', $folder = 'default') {
        if (!$uri) {
            return '';
        }
        $CI = & get_instance();
        $_string = $CI->config->base_url("/themes/" . $folder . "/images/" . $uri);
        return $_string;
    }

}

if (!function_exists('_site_url')) {

    function _site_url($uri = '') {
        $CI = & get_instance();
        $css_string = $CI->config->base_url() . (_LANGUAGE_ == 'en' ? 'en/' : '') . $uri;
        return $css_string;
    }

}
if (!function_exists('is_ajax')) {

    /**
     * [is_ajax 判断是否是ajax请求]
     * @return boolean [description]
     */
    function is_ajax() {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
            return true;
        } else {
            return false;
        }
    }

}

if (!function_exists('errorOutput')) {

    /**
     * [errorOutput json错误输出]
     * @param  string  $msg  [错误信息]
     * 
     */
    function errorOutput($msg = '获取失败') {
        $jsonArr = array(
            'code' => 400,
            'msg' => $msg,
            'result' => array(),
        );
        echo json_encode($jsonArr);
        die();
    }

}

if (!function_exists('successOutput')) {

    /**
     * [successOutput 用于json输出]
     * @param  array   $result [需要输出的数据]
     * @param  string  $msg    [输出状态信息]
     * @param  boolean $isDie  [是否终止]
     * @author  linjianyong
     */
    function successOutput($result, $msg = '', $isDie = true) {
        if (!is_array($result) && is_string($result) && !$msg) {
            $msg = $result;
            $result = null;
        }
        $jsonArr = array(
            'code' => 1,
            'msg' => $msg,
            'result' => $result,
        );
        echo json_encode($jsonArr);
        if ($isDie) {
            die();
        }
    }

}

if (!function_exists('safe_replace')) {

    /**
     * 安全过滤函数
     *
     * @param $string
     * @return string
     */
    function safe_replace($string) {
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        return $string;
    }

}

/**
 * 获取当前页面完整URL地址
 */
if (!function_exists('get_curr_url')) {

    function get_curr_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
        $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safe_replace($_SERVER['QUERY_STRING']) : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }

}

/**
 * 格式化POST的数据
 */
if (!function_exists('format_data')) {

    function format_data($data) {
        if (!$data) {
            return '';
        }
        $post = '{';

        foreach ($data as $k => $v) {
            if ($k === 'password' || $k === 'repassword') {
                $v = 'I am very smart!';
            }
            if (is_array($v)) {
                $post .= '&post(' . $k . ')=>' . format_data($v);
            } else {
                $post .= '&post(' . $k . ')=>[' . $v . ']';
            }
        }
        if (!empty($post)) {
            $post .= '}';
        }
        return $post;
    }

}

/**
 * 根据链接检查当前登录用户是否有权限访问该链接
 * @link URL description
 * @param String $link<p>
 * @param String $params
 * 链接：/admin/works/workflow.html
 * </p>
 *
 * @return  bool <b>TRUE</b> if <i>program</i> is successful.
 * @author Parker <date>
 */
if (!function_exists('checkPower')) {

    function checkPower($link = '', $params = '') {
        if (ISPREMIER && SUPERVISOR) {
            return true;
        }
        static $allAuthorized;
        if (isset($allAuthorized)) {
            $authorized = $allAuthorized;
        } else {
            //加载session
            $CI = & get_instance();
//            $CI->load->library('session');
            //获取权限列表
            $authorized = $CI->session->userData('PremierAuthorized');
            if (!$authorized) {
                $authorized = $CI->session->userdata('authorized');
            }
            if (!$authorized) {
                return false;
            }
            $allAuthorized = $authorized;
        }

        if (!$link) {
            return false;
        }
        //处理link,去除.html后缀   rtrim\ltrim 坚决不能用，会出现问题的
        if (stripos($link, '.html') !== false) {
            $link = substr($link, 0, -5);
        }
        if (!in_array($link . (!empty($params) ? '|' . $params : ''), $authorized)) {
            return false;
        }
        return true;
    }

}


/**
 * 读取参数,若为post则通过$this->input读取;或为get则$this->uri读取
 *
 * @return  bool <b>TRUE</b> if <i>program</i> is successful.
 * @author Parker <date>
 */
if (!function_exists('post_get')) {

    function post_get($index, $offset = 0, $filters = 'intval') {
        if (!$index) {
            return null;
        }
        $data = null;
        $CI = & get_instance();
        if (IS_POST) {
            $data = $CI->input->post($index, true);
        }
        if (!$data) {
            $n = $offset ? $offset : (in_array($CI->uri->segment(1), array('cn', 'en')) ? 5 : 4);
            $data = $CI->uri->segment($n);
        }
        if (empty($data)) {
            return null;
        }

        if (!$filters) {
            return trim($data);
        }
        $_filters = explode(',', $filters);
        foreach ($_filters as $filter) {
            if (function_exists($filter)) {
                $data = is_array($data) ? array_map($filter, $data) : $filter($data); // 参数过滤
            } else {
                $data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));
            }
        }
        return $data;
    }

}

/**
 * createFolder
 * 简介：创建新目录并设置其权限
 * 参数：String
 * 返回：Array
 * 作者：Fzhao
 * 时间：2012-11-20
 */
if (!function_exists('createFolder')) {

    function createFolder($path) {
        if (!file_exists($path)) {
            createFolder(dirname($path));
            mkdir($path, 0777);
        }
    }

}

if (!function_exists('getPages')) {

    /**
     * 获取当前页参数
     */
    function getPages($index = 'currentPage') {
        $CI = & get_instance();
        $pages = intval($CI->input->get($index, true));
        if (!$pages) {
            $pages = intval($CI->input->post($index, true));
        }
        return $pages ? $pages : 1;
    }

}
if (!function_exists('getPageSize')) {

    /**
     * 获取列表当中每一页的条数
     */
    function getPageSize($default = 10) {
        $CI = & get_instance();
        $pageSize = intval($CI->input->get('rows', true));
        if (!$pageSize) {
            $pageSize = intval($CI->input->post('rows', true));
        }
        return $pageSize ? $pageSize : $default;
    }

}
if (!function_exists('getConditions')) {

    /**
     * 获取参数
     */
    function getConditions() {
        $CI = & get_instance();
        $conditions = $CI->input->post('condition', true);
        if (empty($conditions) || !is_array($conditions)) {
            return null;
        }
        return $conditions[0];
    }

}

/**
 * 时间轴函数, Unix 时间戳
 * @param int $time 时间
 */
function TimeLine($time) {
    //$time = strtotime($time);
    $nowTime = time();
    $message = '';
    //一年前
    if (date('Y', $nowTime) != date('Y', $time)) {
        $message = date('Y年m月d日', $time);
    } else {
        //同一年
        $days = date('z', $nowTime) - date('z', $time);
        switch (true) {
            //一天内
            case (0 == $days):
                $seconds = $nowTime - $time;
                //一小时内
                if ($seconds < 3600) {
                    //一分钟内
                    if ($seconds < 60) {
                        if (30 > $seconds) {
                            $message = '刚刚';
                        } else {
                            $message = $seconds . '秒前';
                        }
                    } else {
                        $message = intval($seconds / 60) . '分钟前';
                    }
                } else {
                    $message = date('H', $nowTime) - date('H', $time) . '小时前';
                }
                break;
            //昨天
            case (1 == $days):
                $message = '昨天' . date('H:i', $time);
                break;
            //前天
            case (2 == $days):
                $message = '前天 ' . date('H:i', $time);
                break;
            //7天内
            case (7 > $days):
                $message = $days . '天前';
                break;
            //超过7天
            default:
                $message = date('n月j日 H:i', $time);
                break;
        }
    }
    return $message;
}

function get_style_class($link = '') {
    if(!$link){
        $link = $_SERVER['REQUEST_URI'];
    }
    $uri = trim(strtolower($link));
    $uris = explode("/", $uri);
    if ($uris) {
        $uris = array_slice($uris, 0, 2);
    }
    $class = $uris ? implode('_', $uris) : '';
    return $class;
}

function get_array_rands(&$carousels,&$rands,$num = 2){
    if(!$carousels){
        return false;
    }
    $_rands = array_rand($carousels,$num);
    if(!$_rands){
        return false;
    }
    foreach($carousels as $k=>$item){
        if(in_array($k,$_rands)){
            $rands[] = $item;
            unset($carousels[$k]);
            continue;
        }
    }
}