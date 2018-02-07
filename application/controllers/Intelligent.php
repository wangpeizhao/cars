<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Intelligent extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'home');
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '智能';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //carousels
        $carousels = $this->home->getLinks('intelligent');
        $data['carousels_left'] = $carousels?array(array_shift($carousels)):array();
        $data['carousels_top'] = $carousels?array_slice($carousels,0,2):array();
        if($carousels){
            $carousels = array_slice($carousels,2);
        }
        $rands = array();
        
        get_array_rands($carousels,$rands,3);
        $data['rands'] = $rands;
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('intelligent', $data);
    }

}
