<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Collect extends Fzhao_Controller {
    
    private $title = '';
    
    function __construct() {
        parent::__construct();
        $this->title = '采集数据';
    }

    /**
     * Collect
     * 简介：采集数据
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018/01/17
     */
    function index() {
        if (!IS_POST) {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $this->view('admin/collect', $data);
            return true;
        }
        $url = trim($this->input->post('url',true));
        if(!$url){
            $this->_doIframe('URL不能为空', 0);
        }
        $data = array();
//        $html = file_get_contents($url);
//        if(!$html){
//            $this->_doIframe('URL不存在', 0);
//        }
//        $regular = array(
//            'title' => '/<title>(.*)<\/title>/',
//            'from' => '/<div id="laiyuan">(.*)<\/span><\/div>/',
//        );
//        $data = array();
//        $arr=array();
//        preg_match_all($regular['title'],conv($html),$arr);
//        $data['title'] = !empty($arr[1][0])?clearBlank($arr[1][0]):'';
//        
//        preg_match_all($regular['from'],conv($html),$arr);
        $config = array(
            'url' => $url,
            'domHtml' => ''
        );
        $this->load->library('Php_Query',$config,'query');
        $data['title'] = $this->query->pq('title')->text();
        $data['from'] = $this->query->pq('span.laiyuan span')->text();
        $data['summary'] = clearBlank($this->query->pq('.simple p')->text());
        $data['tags'] = $this->query->pq('.hot_read a')->text();
        $data['imgs'] = array();
        $imgs = $this->query->pq('#articleC p img');
        if($imgs){
            for($i=0;$i<count($imgs);$i++){
                $data['imgs'][] = $imgs[1]->attr('src');
            }
        }
        $data['hrefs'] = $this->query->pq('#articleC p a')->html();
        ww($data);
    }

}
