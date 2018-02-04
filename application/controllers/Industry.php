<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Industry extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/news_model', 'news');
        $this->load->model('default/tag_model', 'tag');
        $this->title = '业界';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('industry', $data);
    }

}
