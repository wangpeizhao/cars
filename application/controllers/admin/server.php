<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Server extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view('admin/database_server',array('title'=>'查看服务器配置'));
    }

    public function phpinfo() {
        $this->view('admin/phpinfo');
    }

}