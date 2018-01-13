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

    private $table;
    private $primary_key;

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
    function lists($data, $is_active = '1') {
        $cond = isset($data['condition']) ? $data['condition'] : array();
        $conditions = array('aa.is_active' => $is_active);
        $like = array();
        if (isset($cond['type']) && $cond['type']) {
            switch ($cond['type']) {
                case 'title':
                    $like = array('like' => array('a.title', $cond['keywords']));
                    break;
                case 'first_name':
                    $like = array('like' => array('aa.first_name', $cond['keywords']));
                    break;
                case 'last_name':
                    $like = array('like' => array('aa.last_name', $cond['keywords']));
                    break;
                case 'id':
                    $conditions = array_merge($conditions, array('a.id' => $cond['keywords']));
                    break;
                case 'emal':
                    $conditions = array_merge($conditions, array('aa.emal' => $cond['keywords']));
                    break;
                case 'phone':
                    $conditions = array_merge($conditions, array('aa.phone' => $cond['keywords']));
                    break;
                case 'subject':
                    $conditions = array_merge($conditions, array('aa.subject' => $cond['keywords']));
                    break;
                case 'message':
                    $conditions = array_merge($conditions, array('aa.message' => $cond['keywords']));
                    break;
            }
        } else {
            if (isset($cond['term_id']) && $cond['term_id'] != '') {
                $conditions = array_merge($conditions, array('a.term_id' => $cond['term_id']));
            }
            if (isset($cond['startTime']) && $cond['startTime'] != '') {
                $conditions = array_merge($conditions, array('aa.create_time >=' => $cond['startTime']));
            }
            if (isset($cond['endTime']) && $cond['endTime'] != '') {
                $conditions = array_merge($conditions, array('aa.create_time <=' => $cond['endTime']));
            }
        }
        $limit = array();
        if (isset($data['rows']) && $data['rows'] && isset($data['currPage']) && $data['currPage']) {
            $limit = array('limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)));
        }
        $_data = $this->getData(array_merge(array_merge(array(
            'fields' => 't.name term_name,a.title,aa.*,t.slug',
            'table' => $this->table . ' aa',
            'join' => array('products a', 'a.id=aa.proId', 'term t', 't.id=a.term_id'),
            'conditions' => $conditions,
            'order' => array('aa.create_time', 'desc'),
                                ), $like), $limit)); //ww($this->db->last_query());
        $count = $this->getData(array_merge(array(
            'table' => $this->table . ' aa',
            'join' => array('products a', 'a.id=aa.proId', 'term t', 't.id=a.term_id'),
            'conditions' => $conditions,
            'count' => true,
                        ), $like));
        return array('data' => $_data, 'count' => $count);
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

    /**
     * getRowById
     * 简介：根据ID读取信息
     * 参数：$id int
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function getRowById($id) {
        if (!$id) {
            return null;
        }
        $result = $this->getData(array(
            'fields' => '*',
            'table' => $this->table,
            'conditions' => array($this->primary_key => $id, 'lang' => _LANGUAGE_),
            'row' => true
        ));
        return $result;
    }

    /**
     * add
     * 简介：添加
     * 参数：$data array
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function add($data) {
        return $this->dbInsert($this->table, $data, true);
    }

    /**
     * edit
     * 简介：编辑
     * 参数：$data array
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function edit($data, $id) {
        return $this->dbUpdate($this->table, $data, array($this->primary_key => $id, 'lang' => _LANGUAGE_));
    }

    /**
     * del
     * 简介：删除(放入回收站)
     * 参数：$id mixed
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function del($id) {
        if (!$id) {
            return false;
        }
        $data = array(
            'is_active' => 0
        );
        if (!is_array($id)) {
            $id = array($id);
        }
        return $this->dbUpdateIn($this->table, $data, array('lang' => _LANGUAGE_), array($this->primary_key => $id));
    }

    /**
     * recover
     * 简介：还原
     * 参数：$id mixed
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recover($id) {
        if (!$id) {
            return false;
        }
        $data = array(
            'is_active' => 1
        );
        if (!is_array($id)) {
            $id = array($id);
        }
        return $this->dbUpdateIn($this->table, $data, array('lang' => _LANGUAGE_), array($this->primary_key => $id));
    }

    /**
     * dump
     * 简介：删除(彻底清除)
     * 参数：$id mixed 
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function dump($id) {
        if (!$id) {
            return false;
        }
        if (!is_array($id)) {
            $id = array($id);
        }
        $this->dbDeleteIn($this->table, array('lang' => _LANGUAGE_), array($this->primary_key => $id));
    }

}
