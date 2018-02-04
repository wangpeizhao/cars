<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Intelligent extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/tag_model', 'admin');
        $this->load->model('default/news_model', 'news');
        $this->title = '智能';
    }
    
    public function index() {
        echo $this->title;return true;
        $tag = str_replace('.html','',strtolower(post_get('tag',2,'trim')));
        $this->verify($tag);
        $term = $this->admin->getTermRowBySlug($tag);
        $this->verify($term);
        $data = $term;
        $news = $this->admin->getNewsByTags(intval($term['id']));
        if($news){
            foreach($news as &$item){
                $item['tags'] = $this->admin->get_tags($item['tags']);
                $item['timeLine'] = TimeLine(strtotime($item['create_time']));
                $item['praises'] = 0;
            }
        }
        $data['news'] = $news;
        $data['title'] = $term['name'].$this->title;
        $data['hotTags'] = $this->admin->get_hot_tags(10);
        $data['hotTagsRSS'] = $this->admin->get_hot_tags(5);
        //热门文章
        $data['hotNews'] = $this->news->getHotNews(10);
        $this->view('tag',$data);
    }

}
