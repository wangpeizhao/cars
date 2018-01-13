<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Aapply_model
 * 简介：活动后台管理数据库模型
 * 返回：Boole
 * 作者：Fzhao
 * 时间：2014-1-21
 * www($this->db->last_query());
 */
class Aapply_model extends Fzhao_Model {

  private $table;

  public function __construct() {
    parent::__construct();
    $this->table = 'inquires';
  }

  /**
   * add_courses
   * 简介：添加活动
   * 参数：新数据
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2014-1-21
   */
  function add($data) {
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  /**
   * getCoursesList
   * 简介：读取活动列表
   * 参数：
   * 返回：Array
   * 作者：Fzhao
   * 时间：2014-1-21
   */
  function get_list($data, $is_active = 1) {
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
      'table' => $this->table.' aa',
      'join' => array('products a', 'a.id=aa.proId', 'term t', 't.id=a.term_id'),
      'conditions' => $conditions,
      'order' => array('aa.create_time', 'desc'),
    ), $like), $limit)); //ww($this->db->last_query());
    $count = $this->getData(array_merge(array(
      'table' => $this->table.' aa',
      'join' => array('products a', 'a.id=aa.proId', 'term t', 't.id=a.term_id'),
      'conditions' => $conditions,
      'count' => true,
    ), $like));
    return array('data' => $_data, 'count' => $count);
  }

  /**
   * get_info_byI_id
   * 简介：根据ID读取活动信息
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2014-1-21
   */
  function get_info_by_id($id) {
    return $this->getData(array(
      'fields' => 'aa.*,t.name term_name,a.title',
      'table' => $this->table.' aa',
      'join' => array('products a', 'a.id=aa.proId', 'term t', 't.id=a.term_id'),
      'conditions' => array('aa.id' => $id, 'aa.is_active' => 1),
      'row' => true
    ));
  }

  /**
   * get_member
   * 简介：根据ID读取会员信息
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2014-2-26
   */
  function get_member($id = 0, $vip = 0) {
    $table = 'member';
    if ($vip) {
      $table = 'member_vip';
    }
  }

  /**
   * getProductsRecycleList
   * 简介：读取产品列表(回收站)
   * 参数：
   * 返回：Array
   * 作者：Fzhao
   * 时间：2012-11-11
   */
  function get_recycle_list($data) {
    return $this->get_list($data, 0);
  }

  /**
   * del
   * 简介：删除(放入回收站)活动
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012/12/6
   */
  function del($id) {
    $data = array(
      'is_active' => 0
    );
    if (is_array($id)) {
      $this->db->where_in('id', $id);
      return $this->db->update($this->table, $data);
    } else {
      return $this->db->update($this->table, $data, array('id' => $id));
    }
  }

  /**
   * recover
   * 简介：还原活动信息
   * 参数：$id int 新数据
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012/12/6
   */
  function recover($id) {
    $data = array(
      'is_active' => 1
    );
    if (is_array($id)) {
      $this->db->where_in('id', $id);
      return $this->db->update($this->table, $data);
    } else {
      return $this->db->update($this->table, $data, array('id' => $id));
    }
  }

  /**
   * dump
   * 简介：删除(彻底清除)活动信息
   * 参数：$id int 
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2014-1-22
   */
  function dump($id) {
    if (is_array($id)) {
      $this->db->where_in('id', $id);
      return $this->db->delete($this->table);
    } else {
      return $this->db->delete($this->table, array('id' => $id));
    }
  }

  /**
   * iUpdate
   * 简介：更改表信息
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2014-1-22
   */
  function iUpdate($data) {
    $this->db->set($data['field'], $data['val'], false);
    $this->db->where('id', $data['id']);
    return $this->db->update($data['table']);
  }

}