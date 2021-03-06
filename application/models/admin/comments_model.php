<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Comments_model
 * 简介：留言后台管理数据库模型
 * 返回：Boole
 * 作者：Parker
 * 时间：2018-01-13
 * 
 */
class Comments_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'comments';
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
    function lists($cond, $isHidden = '0') {
        $conditions = array(array('p.isHidden' => $isHidden));
        $like = array();
        if (!empty($cond['search']) && trim($cond['keywords'])) {
            if(in_array($cond['search'],array('username','phone','email','user_ip','declare'))){
                $like[] = array($cond['search'] => $cond['keywords']);
            }
            if(in_array($cond['search'],array('id'))){
                $conditions[] = array($cond['search'] => $cond['keywords']);
            }
        } 
        
        $fields = array('type','is_public','is_shield');
        foreach($fields as $item){
            if (isset($cond[$item]) && $cond[$item] !== '') {
                $conditions[] = array('p.'.$item => $cond[$item]);
            }
        }

        if (isset($cond['replyContent']) && $cond['replyContent'] === '0') {
            $conditions[] = array('p.replyContent' => null);
        }else
        if (isset($cond['replyContent']) && $cond['replyContent'] === '1') {
            $conditions[] = array('p.replyContent!=' => '');
        }
        if (!empty($cond['startTime'])) {
            $conditions[] = array('p.update_time >=' => $cond['startTime']);
        }
        if (!empty($cond['endTime'])) {
            $conditions[] = array('p.update_time <=' => $cond['endTime']);
        }

        $data = $this->getData(array(
            'fields' => 'p.*',
            'table' => $this->table.' p',
            '_conditions' => $conditions,
            'order' => array('p.'.$this->primary_key, 'desc'),
            'limit' => array($cond['rows'], $cond['rows'] * ($cond['currPage'] - 1)),
            'likes' => $like
        ));
        $count = $this->getData(array(
            'table' => $this->table.' p',
            '_conditions' => $conditions,
            'count' => true,
            'likes' => $like
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
        return $this->lists($data, '1');
    }

    /**
     * getRowById
     * 简介：根据Title读取信息
     * 参数：$id int
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-15
     */
    function getRowByTitle($title, $conditions = array()) {
        if (!$title) {
            return null;
        }
        $_conditions = array(array('isHidden' => '0'));
        if ($conditions) {
            $_conditions = array_merge($_conditions, $conditions);
        }
        $result = $this->getData(array(
            'fields' => '*',
            'table' => $this->table,
            '_conditions' => $_conditions,
            'row' => true
        ));
        return $result;
    }

}
