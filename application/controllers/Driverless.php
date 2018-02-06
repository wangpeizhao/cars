<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Driverless extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'home');
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '无人驾驶';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //carousels
        $data['carousels'] = $this->home->getLinks('driverless');
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('driverless', $data);
    }

}
