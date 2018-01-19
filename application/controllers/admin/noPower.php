<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Denied extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
    }

    //登录界面
    function index() {
        $this->load->view('admin/noPower');
    }

}