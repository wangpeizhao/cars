<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Server extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view('admin/server');
    }

    public function phpinfo() {
        $this->view('admin/phpinfo');
    }

}