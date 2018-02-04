<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Us extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/tag_model', 'tag');
        $this->load->model('default/news_model', 'news');
        $this->title = '我们';
    }
    
    public function about(){
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        
        $directory = 'application/views/default/dynamic/';
        $file = $directory . _LANGUAGE_ . '_us_about_htm_html.php';
        if (file_exists($file)) {
            $result = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
        } else {
            $result = 'Welcome,write something here.';
        }
        $data['content'] = $result;
        $this->view('us',$data);
    }
    
    public function contact(){
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        
        $directory = 'application/views/default/dynamic/';
        $file = $directory . _LANGUAGE_ . '_us_contact_htm_html.php';
        if (file_exists($file)) {
            $result = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
        } else {
            $result = 'Welcome,write something here.';
        }
        $data['content'] = $result;
        $this->view('us',$data);
    }
}
