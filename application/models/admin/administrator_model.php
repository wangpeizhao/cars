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
class Administrator_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'admin';
        $this->primary_key = $this->dbPrimary($this->table);
    }

    /**
     * lists
     * 简介：列表
     * 参数：$cond,$isHidden
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-16
     */
    function lists($cond,$isHidden = '0') {
        $data = $this->getData(array(
            'fields' => 'a.*,g.role_name',
            'table' => 'admin a',
            'join' => array('admin_role g', 'a.role_id=g.id'),
            'conditions' => array('g.isHidden' => $isHidden),
            'orders' => array('a.sort', 'desc'),
            'limit' => array($cond['rows'], $cond['rows'] * ($cond['currPage'] - 1)),
        ));
        $count = $this->getData(array(
            'table' => 'admin a',
            'join' => array('admin_role g', 'a.role_id=g.id'),
            'conditions' => array('g.isHidden' => $isHidden),
            'count' => true,
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
    function getRowByTitle($title,$conditions) {
        if (!$title) {
            return null;
        }
        $_conditions = array(array('isHidden' => '0'));
        if($conditions){
            $_conditions = array_merge($_conditions,$conditions);
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
