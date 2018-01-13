<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * News_model
 * 简介：资讯后台管理数据库模型
 * 返回：Boole
 * 作者：Parker
 * 时间：2018-01-13
 * 
 */
class News_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'news';
        $this->primary_key = $this->dbPrimary($this->table);
    }

    /**
     * lists
     * 简介：列表
     * 参数：$data, $is_active
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function lists($params, $is_valid = '1') {
        $cond = isset($params['condition']) ? $params['condition'] : array();
        $conditions = array('p.is_valid' => $is_valid, 'p.lang' => _LANGUAGE_);
        $like = array();
        if (isset($cond['type']) && $cond['type']) {
            switch ($cond['type']) {
                case 'title':
                    $like[] = array('title', $cond['keywords']);
                    break;
                case 'summary':
                    $like[] = array('summary', $cond['keywords']);
                    break;
                case 'content':
                    $like[] = array('content', $cond['keywords']);
                    break;
                case 'id':
                    $conditions = array_merge($conditions, array('p.id' => $cond['keywords']));
                    break;
            }
        } else {
            if (isset($cond['term_id']) && $cond['term_id'] != '') {
                $conditions = array_merge($conditions, array('p.term_id' => $cond['term_id']));
            }
            if (isset($cond['is_commend']) && $cond['is_commend'] != '') {
                $conditions = array_merge($conditions, array('p.is_commend' => $cond['is_commend']));
            }
            if (isset($cond['is_issue']) && $cond['is_issue'] != '') {
                $conditions = array_merge($conditions, array('p.is_issue' => $cond['is_issue']));
            }
            if (isset($cond['startTime']) && $cond['startTime'] != '') {
                $conditions = array_merge($conditions, array('p.create_time >=' => $cond['startTime']));
            }
            if (isset($cond['endTime']) && $cond['endTime'] != '') {
                $conditions = array_merge($conditions, array('p.create_time <=' => $cond['endTime']));
            }
        }

        $data = $this->getData(array(
            'fields' => 'p.id,p.term_id,p.title,p.summary,p.is_valid,p.owner,p.views,p.from,p.author,p.is_commend,p.is_issue,p.create_time,t.name term_name,t.slug',
            'table' => 'news p',
            'join' => array('term t', 't.id=p.term_id'),
            'conditions' => $conditions,
            'order' => array('p.create_time', 'desc'),
            'limit' => array($params['rows'], $params['rows'] * ($params['currPage'] - 1)),
            'like' => $like
        ));//ww($this->last_query());
        $owners = array_column($data, 'owner');
        if($owners){
            $admin = $this->getData(array(
                'fields' => 'id,nickname',
                'table' => 'admin',
                '_conditions' => array(array('is_active' => '1')),
                'ins' => array(array('id'=>$owners)),
            ));
            $admins = array_column($admin, 'nickname' ,'id');
            foreach($data as &$item){
                $item['username'] = !empty($admins[$item['owner']])?$admins[$item['owner']]:'';
            }
        }
        $count = $this->getData(array(
            'table' => 'news p',
            'join' => array('term t', 't.id=p.term_id', 'admin a', 'a.id=p.owner'),
            'conditions' => $conditions,
            'count' => true,
            'like' => $like
        ));
        return array('data' => $data, 'count' => $count);
    }

    /**
     * recycles
     * 简介：回收站列表
     * 参数：
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recycles($data) {
        return $this->lists($data, 0);
    }

}
