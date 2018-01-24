<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/tag_model', 'admin');
        $this->title = '7×24h 快讯';
    }
    
    public function index() {
        $tag = post_get('tag',2);
        $this->verify($tag);
        $term = $this->admin->getTermRowByTaxonomy($tag);
        $this->verify($term);
        
        $data = array();
        $data['title'] = $this->title;
        $this->view('tag',$data);
    }

}
