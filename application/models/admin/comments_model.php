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
        if (!empty($cond['type']) && trim($cond['keywords'])) {
            switch ($cond['type']) {
                case 'username':
                    $like[] = array('username' => $cond['keywords']);
                    break;
                case 'phone':
                    $like[] = array('phone' => $cond['keywords']);
                    break;
                case 'email':
                    $like[] = array('email' => $cond['keywords']);
                    break;
                case 'user_ip':
                    $conditions[] = array('user_ip' => $cond['keywords']);
                    break;
                case 'declare':
                    $conditions[] = array('declare' => $cond['keywords']);
                    break;
            }
        } else {
            if (isset($cond['type']) && $cond['type'] !== '') {
                $conditions[] = array('p.type' => $cond['type']);
            }
            if (isset($cond['is_public']) && $cond['is_public'] !== '') {
                $conditions[] = array('p.is_public' => $cond['is_public']);
            }
            if (isset($cond['is_shield']) && $cond['is_shield'] !== '') {
                $conditions[] = array('p.is_shield' => $cond['is_shield']);
            }
            if (isset($cond['replyContent']) && $cond['replyContent'] === '0') {
                $conditions[] = array('p.replyContent' => null);
            }else
            if (isset($cond['replyContent']) && $cond['replyContent'] === '1') {
                $conditions[] = array('p.replyContent!=' => '');
            }
            if (!empty($cond['startTime'])) {
                $conditions[] = array('p.iTime >=' => strtotime($cond['startTime']));
            }
            if (!empty($cond['endTime'])) {
                $conditions[] = array('p.iTime <=' => strtotime($cond['endTime']));
            }
        }

        $data = $this->getData(array(
            'fields' => 'p.*',
            'table' => 'comments p',
            '_conditions' => $conditions,
            'order' => array('p.iTime', 'desc'),
            'limit' => array($cond['rows'], $cond['rows'] * ($cond['currPage'] - 1)),
            'likes' => $like
        ));
        if($data){
            foreach($data as &$item){
                $item['create_time'] = date('Y-m-d H:i:s',$item['iTime']);
            }
        }
        $count = $this->getData(array(
            'table' => 'comments p',
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
