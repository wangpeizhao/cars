<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class News_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'news';
        $this->primary_key = $this->dbPrimary($this->table);
    }

    /**
     * getLinks
     * 简介：getLinks
     * 参数：$type 
     * 返回：Array
     * 作者：Parker
     * 时间：2018/01/13
     */
    public function getLinks($type = 'indexPic') {
        $result = $this->getData(array(
            'fields' => 'link_url,link_name,link_image,link_target',
            'table' => 'links',
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('link_type' => $type)),
            '_order' => array(array('link_sort' => 'desc'), array('id' => 'desc'))
        ));

        return $result;
    }

}
