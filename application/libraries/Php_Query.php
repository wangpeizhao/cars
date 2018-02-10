<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use QL\QueryList;

class Php_Query {

    private $url;
    private $domHtml;
    private $pq;
    private $isDImage;

    //构造函数
    function __construct($config) {
        $this->url = $config['url'];
        $this->domHtml = $config['domHtml'];
        $this->isDImage = false;
        include 'phpQuery/phpQuery.php';
        include 'phpQuery/QueryList.php';
//        include 'phpQuery/DImage.php';
//        phpQuery::$defaultCharset = "gbk";
    }

    private function _init($rules,$html) {
        $range = '';
        if(!empty($rules['range'])){
            $range = $rules['range'];
            unset($rules['range']);
        }
        //获取采集对象
        $this->pq = QueryList::Query($html?$html:$this->url, $rules, $range);
    }
    
    //输出结果
    private function _data(){
        $data = conv($this->pq->data);
        if($this->isDImage){
            $data = $this->pq->getData(function($item) {
                $item['content'] = $this->_dImages($item['content']);
                return $item;
            });
        }
        return $data;
    }
    
    private function _rule($rules,$html){
        //下载图片
        if(!empty($rules['DImage'])){
            $this->isDImage = $rules['DImage'];
            unset($rules['DImage']);
        }
        if (!$this->pq) {
            $this->_init($rules,$html);
        }
    }

    public function pq($rules) {
        $this->_rule($rules);
        return $this->_data();
    }

    //setQuery 重新设置选择器，不会再次重复的取抓取一遍目标页面源码，用于重复采集同一个页面多处的内容
    public function sq($rules,$html = '') {
//        ww(QueryList::Query($this->url, $rules)->data);
        $this->_rule($rules,$html);
        $this->pq->setQuery($rules);
        return $this->_data();
    }
    
    //图片本地化
    public function DImage($content) {
        return _dImages($content);
    }
    
    private function _dImages($content){
        if(PHP_VERSION<'7.0'){
            return $content;
        }
        if (!$content) {
            return false;
        }
        $image_path = $this->_set_dir_path();
        $data = QueryList::run('DImage', [
            //html内容
            'content' => $content,
            //图片保存路径
            'image_path' => $image_path,
            //网站根目录全路径
            'www_root' => dirname(__FILE__),
            //补全HTML中的图片路径,可选，默认为空
            'base_url' => WEB_DOMAIN,
            //图片链接所在的img属性，可选，默认src
            //多个值的时候用数组表示，越靠前的属性优先级越高
            'attr' => array('data-src','src'),
            //回调函数，用于对图片的额外处理，可选，参数为img的phpQuery对象
            'callback' => function($imgObj){
//                    $imgObj->attr('alt','xxx');
                $imgObj->removeAttr('class');
                $imgObj->removeAttr('style');
                $imgObj->removeAttr('width');
                $imgObj->removeAttr('height');
            }
        ]);
        return $data;
    }
    
    private function _set_dir_path(){
        $directory = implode("/", array(date('Y'), date('m'), date('d')));
        $path = 'uploads/' . $directory . '/dimages';
        createFolder($path);
        return $path;
    }

}
