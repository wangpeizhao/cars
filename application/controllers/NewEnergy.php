<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NewEnergy extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '新能源';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //carousels
        $data['carousels'] = $this->news->getLinks();
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        //terms
        $data['terms'] = $this->news->getTermByTaxonomy('new-energy');
        $this->view('newEnergy', $data);
    }

}
