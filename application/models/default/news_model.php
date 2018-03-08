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
            'fields' => 'link_url,link_name,link_image,link_target,link_description',
            'table' => 'links',
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('link_type' => $type)),
            '_order' => array(array('link_sort' => 'desc'), array('id' => 'desc'))
        ));
        if($result){
            foreach($result as &$item){
                $item['target'] = $item['link_target']=='_blank'?' target="_blank"':'';
            }
        }

        return $result;
    }
    
    public function getRelatedNews($data){
        if(empty($data['tags'])){
            return false;
        }
        $_conditions = array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('is_issue' => '1'),array('id!='=>$data['id']));
        $tags = $data['tags'];
        $news = $this->getData(array(
            'fields' => 'id,title',
            'table' => $this->table,
            '_conditions' => $_conditions,
            'where' => "CONCAT(',',tags,',') regexp CONCAT('(,',replace('".$tags."',',','|'), '),')",
            '_order' => array(array('sort' => 'desc'), array('id' => 'desc')),
            'limit' => array(5,0)
        ));
        return $news;
    }
    
    public function getInterestedNews($data){
        if(empty($data['keywords'])){
            return false;
        }
        $_conditions = array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('is_issue' => '1'),array('id!='=>$data['id']));
        $keywords = $data['keywords'];
        $news = $this->getData(array(
            'fields' => 'id,title,thumb',
            'table' => $this->table,
            '_conditions' => $_conditions,
            'where' => "CONCAT(',',keywords,',') regexp CONCAT('(,',replace('".$keywords."',',','|'), '),')",
            '_order' => array(array('sort' => 'desc'), array('id' => 'desc')),
            'limit' => array(5,0)
        ));
        return $news;
    }
    
    public function getPrev($news){
        if(!$news){
            return false;
        }
        $_conditions = array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('is_issue' => '1'));
        $_conditions[] = array('id<'=>$news['id']);
        $_conditions[] = array('term_id' => $news['term_id']);
        $_news = $this->getData(array(
            'fields' => 'id,title,tags',
            'table' => $this->table,
            '_conditions' => $_conditions,
            '_order' => array(array('sort' => 'desc'), array('id' => 'desc')),
            'row' => TRUE
        ));
        return $_news;
    }
    
    public function getNext($news){
        if(!$news){
            return false;
        }
        $_conditions = array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('is_issue' => '1'));
        $_conditions[] = array('id>'=>$news['id']);
        $_conditions[] = array('term_id' => $news['term_id']);
        $_news = $this->getData(array(
            'fields' => 'id,title,tags',
            'table' => $this->table,
            '_conditions' => $_conditions,
            '_order' => array(array('sort' => 'desc'), array('id' => 'desc')),
            'row' => TRUE
        ));
        return $_news;
    }
    
    public function checkRecord($type,$id,$today = true){
        if(!$id || !$type){
            return false;
        }
        $ip = real_ip(); 
        $datetime = date('Y-m-d H:i:s',_TIME_ - 3600*24);
        $conditions = array(array('isHidden'=>'0'),array('oid' => $id),array('ip' => $ip),array('type'=>$type));
        if($today){
            $conditions[] = array('create_time>'=>$datetime);
        }
        $record = $this->getData(array(
            'fields' => 'id,create_time',
            'table' => 'records',
            '_conditions' => $conditions,
            'row' => TRUE
        ));
        if(!$record){
            return false;
        }
        $timeLater = '';
        $differ = 3600 * 24 - _TIME_ + strtotime($record['create_time']);
        $hour = intval(($differ)/3600);
        if($hour>0){
           $timeLater = $hour.'小时';
        }else if(intval($differ/60)>1){
           $timeLater = intval($differ/60).'分钟';
        }else{
            $timeLater = $differ.'秒';
        }
        $record['timeLater'] = $timeLater;
        return $record;
    }
    
    public function getHotNews($rows = 10){
        $_conditions = array(array('isHidden' => '0'), array('lang' => _LANGUAGE_), array('is_issue' => '1'));
        $news = $this->getData(array(
            'fields' => 'id,term_id,title,thumb,create_time',
            'table' => $this->table,
            '_conditions' => $_conditions,
            '_order' => array(array('views' => 'desc'), array('id' => 'desc')),
            'limit' => array($rows,0)
        ));
        $ids = array_column($news,'term_id');
        if($ids){
            $_terms = $this->getData(array(
                'fields' => 'id,name',
                'table' => 'term',
                '_conditions' => array(array('isHidden'=>'0'),array('lang'=>_LANGUAGE_)),
                'ins' => array(array('id'=>$ids))
            ));
            $terms = array_column($_terms, 'name','id');
            foreach($news as &$item){
                $item['term_name'] = !empty($terms[$item['term_id']])?$terms[$item['term_id']].' | ':'';
                $item['timeLine'] = TimeLine(strtotime($item['create_time']));
            }
        }
        return $news;
    }
    
    public function getSpecifyTermByNews($terms,$slug){
        if(!$terms || !$slug){
            return false;
        }
        if(empty($terms['childs'])){
            return false;
        }
        $_terms = array();
        foreach($terms['childs'] as $item){
            if($item['slug'] != $slug || empty($item['childs'])){
                continue;
            }
            $_terms = $item;
            break;
        }
        return $_terms;
    }
    
    public function getMainLists($pageSize,$page,$term_id = 0){
        $conditions = array(array('isHidden' => '0'),array('lang' => _LANGUAGE_),array('is_issue'=>'1'));
        $where = '';
        if($term_id){
            $termIds = $this->getData(array(
                'fields' => 'id',
                'table' => 'term',
                '_conditions' => array(array('parent' => $term_id),array('isHidden'=>'0')),
            ));
            $term_ids = array_column($termIds,'id');
            $where .= "(term_id = ".$term_id."";
            $term_ids && $where .= " OR term_id IN (".implode(",",$term_ids).")";
            $where .= ")";
        }
        
        //排除快讯 terms
        $terms = $this->getTermByTaxonomy('news');
        $term = $this->getSpecifyTermByNews($terms,'newsflash');
        if(!$term){
            return false;
        }
        $_term_id = $term['id'];
        $_term_ids = array_column($term['childs'],'id');
        $where .= $where?' AND ':'';
        $where .= "(term_id != ".$_term_id."";
        $_term_ids && $where .= " and term_id NOT IN (".implode(",",$_term_ids).")";
        $where .= ")";
        
        $lists = $this->getData(array(
            'fields' => 'id,term_id,title,from,author,tags,keywords,summary,views,thumb,praises,create_time',
            'table' => 'news',
            '_conditions' => $conditions,
            'where' => $where,
            '_order' => array(array('sort'=>'desc'),array('id'=>'desc')),
            'limit' => array($pageSize, $pageSize * ($page - 1)),
        ));
//        ww($this->last_query());
        if($lists){
            $this->load->model('default/tag_model', 'tag');
            foreach($lists as &$item){
                $item['tags'] = $this->tag->get_tags($item['tags']);
                $item['timeLine'] = TimeLine(strtotime($item['create_time']));
            }
        }
        
        $total = $this->getData(array(
            'table' => 'news',
            '_conditions' => $conditions,
            'where' => $where,
            '_order' => array(array('sort'=>'desc'),array('id'=>'desc')),
            'count' => true
        ));
        return array('data'=>$lists,'total' => $total);
    }
    
    public function get_newsflash($pageSize = 10,$page = 1){
        $conditions = array(array('isHidden' => '0'),array('lang' => _LANGUAGE_),array('is_issue'=>'1'));
        //terms
        $terms = $this->getTermByTaxonomy('news');
        $term = $this->getSpecifyTermByNews($terms,'newsflash');
        if(!$term){
            return false;
        }
        $where = '';
        $term_id = $term['id'];
        $term_ids = array_column($term['childs'],'id');
        $where .= "(term_id = ".$term_id."";
        $term_ids && $where .= " OR term_id IN (".implode(",",$term_ids).")";
        $where .= ")";
        $lists = $this->getData(array(
            'fields' => 'id,title,from,author,tags,keywords,summary,create_time',
            'table' => 'news',
            '_conditions' => $conditions,
            'where' => $where,
            '_order' => array(array('id'=>'desc')),
            'limit' => array($pageSize, $pageSize * ($page - 1)),
        ));
//        ww($this->last_query());
        if($lists){
            $this->load->model('default/tag_model', 'tag');
            foreach($lists as &$item){
//                $item['tags'] = $this->tag->get_tags($item['tags']);
                $item['timeLine'] = TimeLine(strtotime($item['create_time']));
            }
        }
        return $lists;
    }

}
