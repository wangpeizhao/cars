<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HybridPower extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'home');
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '混合动力';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //carousels
        $carousels = $this->home->getLinks('hybrid-power');
        $rands = array();
        
        get_array_rands($carousels,$rands,2);
        $data['rands'] = $rands;
        $data['carousels'] = $carousels;
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('hybridPower', $data);
    }

}
