<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}
/*
 * Parker 2018/01/15
 */


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