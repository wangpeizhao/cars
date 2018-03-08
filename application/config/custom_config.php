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


$config['uninterested'] = array(
    '1' => '广告软文',
    '2' => '重复、旧闻',
    '3' => '文章质量差',
    '4' => '文字、图片、视频等展示问题',
    '5' => '标题夸张、文不对题',
    '6' => '与事实不符',
    '7' => '低俗色情',
    '8' => '欺诈或恶意营销',
    '9' => '疑似抄袭',
    '10' => '其他问题，我要吐槽'
);
$config['feedback'] = array(
    '1' => '网站功能问题反馈',
    '2' => '内容问题反馈',
    '3' => '侵权投诉',
    '4' => '产品合作',
    '5' => '商务、媒体合作',
    '6' => '公众平台相关问题',
    '7' => '广告合作与投诉',
    '8' => '其他'
);
/* End of file custom_config.php */
/* Location: ./application/config/custom_config.php */
