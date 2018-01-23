<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsflash extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->title = '7×24h 快讯';
    }
    
    public function index() {
        $data = array();
        $data['title'] = $this->title;
        $this->view('newsflash',$data);
    }

}
