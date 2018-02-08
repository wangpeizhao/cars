<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '首页';
    }
    
    public function index() {
        $data = array();
        //carousels && banners
        $data['carousels'] = $this->news->getLinks();
        $second_banner = $this->news->getLinks('second_banner');
        $rands = array();
        get_array_rands($second_banner, $rands,3);
        $data['rands'] = $rands;
        
        //main lists
        $pageSize = getPageSize();
        $page = getPages();
        $term_id = 0;
        $slug = trim($this->input->get('slug',true));
        if($slug){
            $term = $this->news->getTermByTaxonomy($slug);
            $term && $term_id = $term['id'];
        }
        $mainData = $this->news->getMainLists($pageSize,$page,$term_id);
        $data['mainLists'] = $mainData['data'];
        $data['total'] = $mainData['total'];
        
        //about us - news
        $news = $this->admin->getData(array(
            'fields' => 'a.id,a.title',
            'table' => 'news a',
            'conditions' => array('a.isHidden' => '0', 'a.lang' => _LANGUAGE_,'is_commend'=>'1','is_issue'=>'1'),
            '_order' => array(array('sort'=>'desc'),array('praises'=>'desc'),array('id'=>'desc')),
            'limit' => array(5,0)
        ));
        $data['news'] = $news;
        
        //about us
        $type = 'us_about';
        $file = 'application/views/default/dynamic/' . _LANGUAGE_ . '_' . $type . '_htm_html.php';
        $data['about'] = '';
        if (file_exists($file)) {
            $about = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
            $data['about'] = trim(str_replace('&nbsp;','',strip_tags($about)));
        }
        
        //快讯
        $data['newsflash'] = $this->news->get_newsflash();
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('home',$data);
    }

}
