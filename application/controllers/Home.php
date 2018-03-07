<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Client_Controller {

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '首页';
    }

    public function index() {
        $data = array();
        //carousels && banners
        $data['carousels'] = $this->news->getLinks();
        $second_banner = $this->news->getLinks('second_banner');
        $rands = array();
        get_array_rands($second_banner, $rands, 3);
        $data['rands'] = $rands;

        //main lists
        $pageSize = getPageSize();
        $page = getPages();
        $term_id = 0;
        $slug = trim($this->input->get('slug', true));
        if ($slug) {
            $term = $this->news->getTermByTaxonomy($slug);
            $term && $term_id = $term['id'];
        }
        $mainData = $this->news->getMainLists($pageSize, $page, $term_id);
        $data['mainLists'] = $mainData['data'];
        $data['total'] = $mainData['total'];

        //about us - news
        $news = $this->admin->getData(array(
            'fields' => 'a.id,a.title',
            'table' => 'news a',
            'conditions' => array('a.isHidden' => '0', 'a.lang' => _LANGUAGE_, 'is_commend' => '1', 'is_issue' => '1'),
            '_order' => array(array('sort' => 'desc'), array('praises' => 'desc'), array('id' => 'desc')),
            'limit' => array(5, 0)
        ));
        $data['news'] = $news;

        //about us
        $type = 'us_about';
        $file = 'application/views/default/dynamic/' . _LANGUAGE_ . '_' . $type . '_htm_html.php';
        $data['about'] = '';
        if (file_exists($file)) {
            $about = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
            $data['about'] = trim(str_replace('&nbsp;', '', strip_tags($about)));
        }

        //快讯
        $data['newsflash'] = $this->news->get_newsflash();
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('home', $data);
    }

    /**
     * comment
     * 简介：留言
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2018-03-07
     */
    public function comment() {
        if (!IS_POST) {
            show_404();
        }
        $this->_checkVCode();
        $tid = intval($this->input->post('tid', true));
        $detail = trim($this->input->post('detail', true));
        $contact = trim($this->input->post('contact', true));
        if(!$detail){
            errorOutput('请填写描述');
        }
        if(strlen($detail)<10){
            errorOutput('描述不能低于10个字符');
        }
        if(!$contact){
            errorOutput('请填写联系方式');
        }
        $data = array();
        if(strpos($contact,'@')!==false){
            $data['email'] = $contact;
        }else{
            $data['phone'] = $contact;
        }
        $data['tid'] = $tid;
        $data['declare'] = $detail;
        $data['user_ip'] = real_ip();
        $data['create_time'] = _DATETIME_;
        $data['update_time'] = _DATETIME_;
//        if (isset($_SESSION['memberInfo']) && $_SESSION['memberInfo']) {
//            $data['create_user'] = $_SESSION['memberInfo']['id'];
//            $data['username'] = $_SESSION['memberInfo']['nickname'];
//        }
        $result = $this->admin->dbInsert('comments',$data);
        if ($result) {
            successOutput('提交成功');
        } else {
            errorOutput('提交失败');
        }
    }

    /**
     * vCode
     * 简介：生成验证码
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-24
     */
    public function vCode() {
        $this->SetCode(4, 12, 70, 30, 'VerifyCodeComment');
    }

    /**
     * checkVCode
     * 简介：验证验证码
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-24
     */
    private function _checkVCode() {
        $vCode = $this->input->post('vCode',true);
        if (!$this->session->userdata('VerifyCodeComment')) {
            errorOutput('验证码已过期');
        }
        if ($this->session->userdata('VerifyCodeComment') != strtolower($vCode)) {
            errorOutput('验证码不正确');
        }
    }

    // 生成验证码
    private function SetCode($num = 4, $size = 20, $width = 0, $height = 0, $vCode_key = 'VerifyCode') {
        !$width && $width = $num * $size * 4 / 5 + 20;
        !$height && $height = $size + 10;
        // 去掉了 0 1 O l 等
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        $code = '';
        for ($i = 0; $i < $num; $i ++) {
            $code .= $str [mt_rand(0, strlen($str) - 1)];
        }
        // 画图像
        $im = imagecreatetruecolor($width, $height);
        // 定义要用到的颜色
        $back_color = imagecolorallocate($im, 235, 236, 237);
        $boer_color = imagecolorallocate($im, 170, 204, 238);
        $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        // 画背景
        imagefilledrectangle($im, 0, 0, $width, $height, $back_color);
        // 画边框
        imagerectangle($im, 0, 0, $width - 1, $height - 1, $boer_color);
        // 画干扰线
        for ($i = 0; $i < 5; $i ++) {
            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
        }
        // 画干扰点
        for ($i = 0; $i < 50; $i ++) {
            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
        }
        // 画验证码
        @imagefttext($im, $size + 1, 5, 8, $size + 10, $text_color, 'themes/common/font/font.ttf', $code);
        //$_SESSION ["VerifyCode"] = strtolower($code);
        $this->session->unset_userdata($vCode_key);
        $this->session->set_userdata($vCode_key, strtolower($code));
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png;charset=gb2312");
        imagepng($im);
        imagedestroy($im);
    }

}
