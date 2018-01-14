<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Fzhao 2012/8/22
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
    return md5(sha1(hash('md4', $str) . 'hlh*wpz'));
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
    ob_start();
    print_r($content);
    $result = fwrite($fp, ob_get_contents());
    ob_end_clean();
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
function delSpace($str) {
    $space = array(" ", "　", "\t", "\n", "\r");
    $trim = array("", "", "", "", "");
    return str_replace($space, $trim, $str);
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
 * setZoneBitCode
 * 简介：汉字转区位码
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function setZoneBitCode($str) {
    if (is_array($str)) {
        $code = array();
        foreach ($str as $key => $item) {
            $code[$key] = ZoneBitCode($item);
        }
        return $code;
    }
    return ZoneBitCode($str);
}

/**
 * ZoneBitCode
 * 简介：一个汉字转区位码 如果是英文或数字直接返回不做处理
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function ZoneBitCode($str) {
    if (preg_match("/^[a-z0-9 ]+$/i", $str)) {
        return $str;
    } else {
        $str1 = substr($str, 0, 2);
        $str_qwm = sprintf("%02d%02d", ord($str1[0]) - 160, ord($str1[1]) - 160);
        $str2 = substr($str, 2, 4);
        $str_qwm .= sprintf("%02d%02d", ord($str2[0]) - 160, ord($str2[1]) - 160);
        return $str_qwm;
    }
}

/**
 * getCharacter
 * 简介：汉字转区位码
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function getCharacter($str) {
    $arr = explode(' ', $str);
    $Tstr = array();
    if (!empty($arr)) {
        foreach ($arr as $item) {
            if (preg_match("/^[0-9]+$/i", $item)) {
                $strs = '';
                $strs = chr(substr(substr($item, 0, 4), 0, 2) + 160) . chr(substr(substr($item, 0, 4), 2, 2) + 160);
                $strs .= chr(substr(substr($item, 4, 8), 0, 2) + 160) . chr(substr(substr($item, 4, 8), 2, 2) + 160);
                $Tstr[] = $strs;
            } else {
                $Tstr[] = $item;
            }
        }
    }
    return !empty($Tstr) ? implode('', $Tstr) : $str;
}

/**
 * binaryParticiple
 * 简介：二元分词
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function binaryParticiple($str) {
    $str = preg_replace("/[\x80-\xff]{2}/", "\\0" . chr(0x00), $str);
    //拆分的分割符
    $search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】",);
    //替换所有的分割符为空格
    $str = str_replace($search, ' ', $str);
    //用正则匹配半角单个字符或者全角单个字符,存入数组$ar
    preg_match_all("/[\x80-\xff]?./", $str, $ar);
    $ar = $ar[0];
    //去掉$ar中ASCII为0字符的项目
    for ($i = 0; $i < count($ar); $i++)
        if ($ar[$i] != chr(0x00))
            $ar_new[] = $ar[$i];
    $ar = $ar_new;
    unset($ar_new);
    $oldsw = 0;
    //把连续的半角存成一个数组下标,或者全角的每2个字符存成一个数组的下标
    for ($ar_str = '', $i = 0; $i < count($ar); $i++) {
        $sw = strlen($ar[$i]);
        if ($i > 0 and $sw != $oldsw)
            $ar_str.=" ";
        if ($sw == 1)
            $ar_str.=$ar[$i];
        else
        if (isset($ar[$i + 1]) && strlen($ar[$i + 1]) == 2)
            $ar_str.=$ar[$i] . $ar[$i + 1] . ' ';
        elseif ($oldsw == 1 or $oldsw == 0)
            $ar_str.=$ar[$i];
        $oldsw = $sw;
    }
    //去掉连续的空格
    $ar_str = trim(preg_replace("# {1,}#i", " ", $ar_str)); //$ar_str = "Monkey s 二元 元分 分词"
    //返回拆分后的结果
    return explode(' ', $ar_str);
}

/**
 * wordSegment
 * 简介：中文分词函数
 * 参数：NULL
 * 返回：Array
 * 作者：Fzhao
 * 时间：2013-1-1
 */
function wordSegment($str) {
    $search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】",);
    $str = str_replace($search, ' ', $str);
    //英文单词3个以上，中文2词
    preg_match_all('#([A-Za-z0-9]{3,}|([\xC0-\xFF][\x80-\xBF]+)+)#', $str, $array, PREG_PATTERN_ORDER);
    $ss = array();
    if (!empty($array[1])) {
        foreach ($array[1] as $val) {
            //英文单词
            if (preg_match('/\w+/', $val)) {
                $ss[] = $val;
            } else {//中文字符
                preg_match_all('#[\xC0-\xFF][\x80-\xBF]+#', $val, $out, PREG_PATTERN_ORDER);
                if (!empty($out[0])) {
                    $count = count($out[0]);
                    for ($i = 0; $i < $count - 1; $i++) {
                        $t = $out[0][$i] . $out[0][$i + 1];
                        $t = kc_iconv($t, 'GBK', 'UTF-8');
                        $ss[] = sprintf("%02d%02d%02d%02d", ord($t{0}) - 160, ord($t{1}) - 160, ord($t{2}) - 160, ord($t{3}) - 160);
                    }
                }
            }
        }
    }
    return empty($ss) ? '' : implode(' ', array_unique($ss));
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
 * 将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 *
 * @access public
 * @param $str string
 *       	 待转换字串
 *       	
 * @return string $str 处理后字串
 */
function make_semiangle($str) {
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z', '（' => '(', '）' => ')', '［' => '[', '］' => ']', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']', '「' => '[', '」' => ']', '『' => '[', '』' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-', '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.', '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|', '＂' => '"', '＇' => '`', '｀' => '`', '｜' => '|', '〃' => '"', '　' => ' ');

    return strtr($str, $arr);
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

/**
 *
 *
 * 发送邮件方法
 *
 * @param $TO 发送的目标邮箱       	
 * @param $SUBJECT 文件名       	
 * @param $CONTENT 正文       	
 * @param $FROM 发送人邮箱       	
 * @param $RETURN_PATH 返回邮箱       	
 * @param $TO_ENCODING 发出时的编码       	
 * @param $FROM_ENCODING 返回时的编码       	
 */
function iMail($TO, $SUBJECT, $CONTENT, $FROM, $RETURN_PATH = "", $TO_ENCODING = "GBK", $FROM_ENCODING = "UTF-8") {
    if (is_array($FROM)) {
        $FROM_ADDR = $FROM [0];
        $FROM_NAME = $FROM [1];
    } else {
        $FROM_ADDR = $FROM_NAME = $FROM;
    }
    $FROM_NAME = mb_encode_mimeheader($FROM_NAME, $TO_ENCODING, $FROM_ENCODING);
    $HEADERS = "From: {$FROM_NAME}<{$FROM_ADDR}>\n";
    $HEADERS .= "Reply-To: {$FROM_NAME}<{$FROM_ADDR}>\n";
    if ($RETURN_PATH != "") {
        if (is_array($RETURN_PATH)) {
            $RETURN_PATH_ADDR = $RETURN_PATH [0];
            $RETURN_PATH_NAME = $RETURN_PATH [1];
        } else {
            $RETURN_PATH_ADDR = $RETURN_PATH_NAME = $RETURN_PATH;
        }
        $RETURN_PATH_NAME = mb_encode_mimeheader($RETURN_PATH_NAME, $TO_ENCODING, $FROM_ENCODING);
        $HEADERS .= "Return-Path: {$RETURN_PATH_NAME}<{$RETURN_PATH_ADDR}>\n";
    }
    $SUBJECT = mb_convert_encoding($SUBJECT, $TO_ENCODING, $FROM_ENCODING);
    $CONTENT = mb_convert_encoding($CONTENT, $TO_ENCODING, $FROM_ENCODING);
    // return mb_send_mail($TO, $SUBJECT, $CONTENT, $HEADERS);
    //return mail ( $TO, $SUBJECT, $CONTENT, $HEADERS );
    return myMail($TO, $SUBJECT, $CONTENT, $HEADERS);
}

function myMail($TO, $SUBJECT, $CONTENT, $HEADERS) {
    $this->load->library('email');
    $config['protocol'] = 'smtp';
    $config['charset'] = 'utf8';
    $config['smtp_host'] = 'smtp.qq.com';
    $config['smtp_user'] = '342823274@qq.com';
    $config['smtp_pass'] = 'wangpeizhao0615';
    $config['smtp_port'] = 25;
    $config['smtp_timeout'] = 5;
    www($config);
    $this->email->initialize($config);

    $this->email->from('italentedu@italentedu.com', '国际人才圈');
    $this->email->to('342823274@qq.com');
    $this->email->subject('Email Test');
    $this->email->message('Testing the email class.');
    $this->email->send();
    $result = $this->email->print_debugger();
    //www($result);
    return $result;
    /*
      require 'application/ch/third_party/email/class.phpmailer.php';
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Host = "smtp.qq.com";
      $mail->SMTPAuth = true;
      $mail->Username = '342823274@qq.com';
      $mail->Password = 'wangpeizhao0615';
      $mail->Port=587;
      $mail->From ='italentedu@italentedu.com';
      $mail->AddAddress($TO,'国际人才圈');
      $mail->Subject = $SUBJECT;
      $mail->FromName = "www.italentedu.com";
      $mail->Body ="尊敬的66ka会员：".$CONTENT." 您好: 您在 ".date("Y年m月d日 H:i:s")." 提交了找回密码的申请，如果您确定该申请，请点击以下链接：(该链接在12小时内有效)如果该链接无法点击，请直接拷贝以上链接到浏览器(例如IE)地址栏中访问。如果您错误地收到了此电子邮件，您无需执行任何操作!此为自动发送邮件，请勿直接回复！如有疑问欢迎联系我们：(QQ:1290394971).".$HEADERS;
      return $mail->Send();
     */
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
if (!function_exists('css_url')) {

    function css_url($uri = '', $folder = 'default') {
        $CI = & get_instance();
        $css_string = "<link rel='stylesheet' type='text/css' href='" . $CI->config->base_url("/themes/" . $folder . "/css/" . $uri) . "'>";
        return $css_string;
    }

}

if (!function_exists('js_url')) {

    function js_url($uri = '', $folder = 'default') {
        $CI = & get_instance();
        $css_string = "<script type=\"text/javascript\" src=\"" . $CI->config->base_url("/themes/" . $folder . "/js/" . $uri) . "\"></script>";
        return $css_string;
    }

}

if (!function_exists('img_url')) {

    function img_url($uri = '', $folder = 'default') {
        $CI = & get_instance();
        $css_string = $CI->config->base_url("/themes/" . $folder . "/images/" . $uri);
        return $css_string;
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
    function successOutput($result = array(), $msg = '', $isDie = true) {
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
        } else {
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
            $this->createFolder(dirname($path));
            mkdir($path, 0777);
        }
    }

}

if (!function_exists('getPages')) {

    /**
     * 获取当前页参数
     */
    function getPages() {
        $CI = & get_instance();
        $pages = intval($CI->input->get('currentPage', true));
        if (!$pages) {
            $pages = intval($CI->input->post('currentPage', true));
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