<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsflash extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/news_model', 'admin');
        $this->load->model('default/tag_model', 'tag');
        $this->title = '7×24h 快讯';
    }
    
    public function index() {
        $data = array();
        $data['title'] = $this->title;
        $this->view('newsflash',$data);
    }
    
    public function detail(){
        $id = post_get('id',2);
        $this->verify($id);
        $data = $this->admin->getRowById($id);
        $this->verify($data);
        $data['term_name'] = $this->admin->getTermById(intval($data['term_id']),'name');
        $data['praises'] = 0;
        $this->load->model('default/tag_model', 'tag');
        $data['tags'] = $this->tag->get_tags($data['tags']);
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        $this->view('detail',$data);
    }

}
