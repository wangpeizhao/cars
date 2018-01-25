<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tag_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'term';
        $this->primary_key = $this->dbPrimary($this->table);
    }

    public function getTermRowBySlug($slug) {
        if (!$slug) {
            return false;
        }
        $term = $this->getData(array(
            'fields' => 'id,name,parent,slug,taxonomy',
            'table' => $this->table,
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('slug' => $slug)),
            'row' => true
        ));
        return $term;
    }

    public function getNewsByTags($termId) {
        if (!$termId) {
            return false;
        }
        $currPage = getPages();
        $rows = getPageSize();
        $news = $this->getData(array(
            'fields' => 'id,term_id,title,from,author,tags,summary,views,is_commend,is_issue,thumb,update_time,create_time,uid',
            'table' => 'news',
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('thumb!=' => '')),
            'where' => "FIND_IN_SET('" . $termId . "',tags)>0",
            '_order' => array(array('sort' => 'desc'), array('create_time' => 'desc')),
            'limit' => array($rows, $rows * ($currPage - 1)),
        ));
//        ww($this->last_query());
        return $news;
    }

    public function get_tags($tags) {
        if (!$tags) {
            return false;
        }
        $term_ids = $tags ? explode(",", $tags) : array();
        if (!$term_ids) {
            return false;
        }
        $_tags = $this->getTermById($term_ids);
        return array_column($_tags, 'name', 'slug');
    }

    public function get_hot_tags($num = 10) {
        $tags = $this->getTermByTaxonomy('tags'); //ww($tags);
        $_hotTags = $this->_get_hot_tags($tags['childs']);
        $hotTags = array_sort($_hotTags, 'count', 'desc');
        return intval($num)?array_slice($hotTags,0,intval($num)):$hotTags;
    }

    private function _get_hot_tags($data) {
        static $tags = array();
        foreach ($data as $item) {//www($item);
            if (!$item) {
                continue;
            }
            $tag = array(
                'slug' => $item['slug'],
                'name' => $item['name'],
                'count' => $item['count'],
            );
            $item['childs'] && $this->_get_hot_tags($item['childs']);
            $tags[$item['id']] = $tag;
        }
        return $tags;
    }

}
