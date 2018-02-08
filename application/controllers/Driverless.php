<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Driverless extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '无人驾驶';
    }
    
    public function index() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //carousels
        $data['carousels'] = $this->news->getLinks('driverless');
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        
        //terms
        $terms = $this->news->getTermByTaxonomy('news');
        $_terms = $this->news->getSpecifyTermByNews($terms,'driverless');
        $data['terms'] = $_terms;
        //main lists
        $pageSize = getPageSize();
        $page = getPages();
        $mainData = $this->news->getMainLists($pageSize,$page,$_terms['id']);
        $data['mainLists'] = $mainData['data'];
        $data['total'] = $mainData['total'];
        $this->view('driverless', $data);
    }

}
