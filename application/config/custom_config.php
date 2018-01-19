<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | custom_config
  |--------------------------------------------------------------------------
  | Fzhao
  | 2012/12/3
 */

$config['custom'] = array(
    'classify' => array(
        'activities' => '活动体系',
        'newsInfo' => '新闻资讯',
        'company' => '关于公司',
        'courses' => '课程体系',
    ),
    'products' => array(//预设第三分类，键值(ID)不能重复，必须为数字，且小于999的整数
        '1' => '贴标机',
        '2' => '旋盖机',
        '3' => '灌装机',
    ),
);
$config['email_config'] = array(
    'protocol' => 'smtp',
    'charset' => 'utf-8',
    'smtp_host' => 'smtp.163.com',
    'smtp_user' => 'italentedu',
    'smtp_pass' => 'Zheng2014',
    'smtp_port' => 25,
    'mailtype' => 'html',
    'crlf' => '\r',
    'newline' => '\r',
    'smtp_timeout' => 3
);
$config['from_mail'] = "italentedu@163.com";
$config['cs_phone_address_max'] = 5;
$config['from_title'] = '王培照';


$config['cs_sms_name'] = 'zheng';
$config['cs_sms_pwd'] = '123456';
$config['title'] = '人才协会网找回密码邮件';
$config['sms_text'] = '感谢您使用人才协会网
				验证码：%validate_num%
				请前往人才协会网操作下一步';
/* End of file custom_config.php */
/* Location: ./application/config/custom_config.php */
