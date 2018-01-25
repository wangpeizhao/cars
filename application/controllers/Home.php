<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Client_Controller {
    

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/home_model', 'admin');
        $this->load->model('default/tag_model', 'tag');
        $this->title = '首页';
    }
    
    public function index() {
        $data = array();
        //carousels
        $data['carousels'] = $this->admin->getLinks();
        
        //news
        $news = $this->admin->getData(array(
            'fields' => 'a.id,a.title',
            'table' => 'news a',
            'conditions' => array('a.isHidden' => '0', 'a.lang' => _LANGUAGE_,'is_commend'=>'1','is_issue'=>'1'),
            '_order' => array(array('sort'=>'desc'),array('views'=>'desc'),array('id'=>'desc')),
            'limit' => array(5,0)
        ));
        $data['news'] = $news;
        
        //about us
        $type = 'contact_about';
        $file = 'application/views/default/dynamic/' . _LANGUAGE_ . '_' . $type . '_htm_html.php';
        $data['about'] = '';
        if (file_exists($file)) {
            $about = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
            $data['about'] = trim(str_replace('&nbsp;','',strip_tags($about)));
        }
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        $this->view('home',$data);
    }

}
