<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Php_Query {
    
    private $url;
    private $domHtml;
    private $pq;

    //构造函数
    function __construct($config) {
        $this->url = $config['url'];
        $this->domHtml = $config['domHtml'];
        include 'phpQuery/phpQuery.php';
        phpQuery::newDocumentFileHTML($this->url);
    }
    
    public function init(){
        
    }
    
    public function pq($obj){
        return pq($obj);
    }

}