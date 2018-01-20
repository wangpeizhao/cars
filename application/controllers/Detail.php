<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->title = '首页';
    }
    
    public function index() {
        $data = array();
        
        $this->view('detail',$data);
    }

}
