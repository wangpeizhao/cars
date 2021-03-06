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
        //carousels
        $carousels = $this->news->getLinks('industry');
        $data['carousels'] = $carousels?array(array_shift($carousels)):array();
        $rands = array();
        
        get_array_rands($carousels,$rands,2);
        $data['rands'] = $rands;
        
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        
        //terms
        $terms = $this->news->getTermByTaxonomy('news');
        $_terms = $this->news->getSpecifyTermByNews($terms,'industry');
        $data['terms'] = $_terms;
        //main lists
        $pageSize = getPageSize();
        $page = getPages();
        $mainData = $this->news->getMainLists($pageSize,$page,$_terms['id']);
        $data['mainLists'] = $mainData['data'];
        $data['total'] = $mainData['total'];
        $this->view('industry', $data);
    }

}
