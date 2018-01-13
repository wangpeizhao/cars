<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha {

    public $authcode; //验证码
    private $width; //验证码图片宽
    private $height; //验证码图片高
    private $len; //验证码长度
    private $tilt; //验证码倾斜角度
    private $font; //字体文件
    private $str; //验证码基
    private $im; //生成图片的句柄
    private $type; //类型

    //构造函数

    function __construct($config) {
        $this->tilt = array(-50, 50); //验证码倾斜角度
        $this->font = FCPATH.DIRECTORY_SEPARATOR.'themes/default/font/AlteHaasGroteskBold.ttf'; //字体文件
        $this->width = $config['width'];
        $this->height = $config['heigh'];
        $this->len = $config['len'];
        $this->str = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $str_len = strlen($this->str) - 1;
        for ($i = 0; $i < $this->len; $i++) {
            $this->authcode .= $this->str{rand(0, $str_len)};
        }
        $this->type = $config['type']?$config['type']:'register';
    }

    //生成验证码图片
    private function imagecreate() {
        $this->im = imagecreatetruecolor($this->width, $this->height);
    }

    //干扰颜色
    private function ext_color() {
        return imagecolorallocate($this->im, rand(200, 255), rand(200, 255), rand(200, 255));
    }

    //生成干扰点
    private function ext_point() {
        for ($i = 0; $i < $this->width * 2; $i++) {
            imagesetpixel($this->im, rand(1, $this->width - 1), rand(1, $this->height - 1), $this->ext_color());
        }
    }

    //生成干扰线
    private function ext_line() {
        for ($i = 0; $i < $this->len; $i++) {
            $x1 = rand(1, $this->width - 1);
            $y1 = rand(1, $this->height - 1);
            $x2 = rand(1, $this->width - 1);
            $y2 = rand(1, $this->height - 1);
            imageline($this->im, $x1, $y1, $x2, $y2, $this->ext_color());
        }
    }

    //把验证码写入图片（不能和$this->imgstrfloat()同时使用）
    private function imgstr() {
        $old_x = 1;
        for ($i = 0; $i < $this->len; $i++) {
            $fontsize = rand(6, 9);      //字体大小
            $tmp_1 = $fontsize * 2.5;
            $tmp_2 = $i > 0 ? $tmp_1 : 0;
            $y = rand(1, $this->height / 2);
            $x = rand($old_x + $tmp_2, ($i + 1) * ($this->width) / $this->len - $tmp_1);
            $old_x = $x;
            $color = imagecolorallocate($this->im, rand(200, 255), rand(200, 255), rand(200, 255));
            imagestring($this->im, $fontsize, $x, $y, $this->authcode[$i], $color);
        }
    }

    //把验证码倾斜写入图片（注意这里不能和$this->imgstr()方法同时使用）
    private function imgstrfloat() {
        $old_x = 1;
        for ($i = 0; $i < $this->len; $i++) {
            $fontfloat = rand($this->tilt[0], $this->tilt[1]);
            $fontsize = rand(24, 26);        //字体大小
            $tmp_1 = $i > 0 ? $fontsize : 0;
            $y = rand($fontsize + 10, $this->height - 10);
            $x = rand($old_x + $tmp_1 + 10, ($i + 1) * ($this->width) / $this->len - $fontsize - 10);
            $old_x = $x;
            $color = imagecolorallocate($this->im, rand(200, 255), rand(200, 255), rand(200, 255));
            imagettftext($this->im, $fontsize, $fontfloat, $x, $y, $color, $this->font, $this->authcode[$i]);
        }
    }

    //输出验证码图片
    function createCaptcha() {
        $this->imagecreate();
        //$this->imgstr();
        $this->imgstrfloat();
        $this->ext_point();
        $this->ext_line();
        $token = 'QAZxsw!@#WASZXFJkgf';
        $_SESSION[$this->type.'Captcha'] = md5($token.strtolower($this->authcode));
        header('content-type:image/png');
        imagepng($this->im);
        imagedestroy($this->im);
    }

}

//$obj = new class_authcode(); //实例化对象，并设置验证码图片的宽、高和验证码的长度
//$obj->authcode; //获取验证码
//$obj->output(); //输出验证码图片