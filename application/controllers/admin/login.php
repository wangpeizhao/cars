<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/system_model');
    }

    //登录界面
    function index() {
        if ($this->session->userdata('adminLoginInfo')) {
            redirect('/admin/index/index', 'refresh');
        }
        if (IS_POST) {
            $data['username'] = trim($this->input->post('username', true));
            $data['password'] = trim($this->input->post('password', true));
            $captcha = trim($this->input->post('captcha', true));
            if ($this->session->userdata('VerifyCode') != strtolower($captcha)) {
                //$this->json_error('验证码错误');
            }
            $userInfo = $this->system_model->checkAdmin($data);
            if (empty($userInfo)) {
                $this->json_error('登录账号或密码错误！');
            } else {
                $_SESSION['authorized'] = $userInfo['authorized'];
                unset($userInfo['password'], $userInfo['authorized']);
                $this->session->set_userdata('adminLoginInfo', $userInfo);
            }
            $this->json_success('登录成功');
        } else {
            $this->load->helper('url');
            $data['url'] = $this->input->get('url');
            $data['sitesName'] = $this->getOptionValue('sitesName');
            $this->load->view('admin/login', $data);
        }
    }

    /**
     * vCode
     * 简介：生成验证码
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/17
     */
    function vCode() {
        $this->SetCode(4, 12, 60, 24);
    }

    /**
     * checkVCode
     * 简介：验证验证码
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/17
     */
    function checkVCode() {
        echo json_encode(array('done' => true, 'msg' => 1));
        return;
        $vCode = $this->input->post('vCode');
        if (!$this->session->userdata('VerifyCode')) {
            echo json_encode(array('done' => false, 'msg' => '验证码已过期'));
            return;
        }
        if ($this->session->userdata('VerifyCode') == strtolower($vCode)) {
            echo json_encode(array('done' => true, 'msg' => 1));
        } else {
            echo json_encode(array('done' => true, 'msg' => '验证码错误'));
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

    //登出后台
    function logout() {
        //unset($_SESSION['userInfo']);
//        $this->load->library('session');
        $this->session->unset_userdata('adminLoginInfo');
        redirect('/admin/login', 'refresh');
    }

}
