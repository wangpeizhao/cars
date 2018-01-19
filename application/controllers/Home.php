<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->title = '首页';
    }
    
    public function index() {
        $data = array();
        $data['carousels'] = $this->admin->getLinks();
        $this->view('home',$data);
    }

}
