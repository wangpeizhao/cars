<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * params 
 * return 数据库操作
 * author Fzhao 2012-11-8
 */
class Fzhao_Model extends CI_Model {

    public $table;
    public $primary_key;

    public function __construct() {
        parent::__construct();
    }

    /**
     * 查看执行的SQL
     * @link 
     * @param <p>
     * NULL
     * </p>
     *
     * @return  String <b>SQL-Statement</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function last_query() {
        return $this->db->last_query();
    }

    /**
     * Change database
     * @link URL description
     * @param String $database<p>
     * Database Name
     * </p>
     *
     * @return  String <b>Table Name</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbChangeDatabase($database = NULL) {
        if ($database) {
            $this->db = $this->load->database($database, true);
        } else {
            $this->db = $this->load->database();
        }
    }

    /**
     * 执行SQL,返回影响行数
     * @link 
     * @param <p>
     * NULL
     * </p>
     *
     * @return  String <b>SQL-Statement</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbQuery($sql) {
        //only allow SELECT Statements
        //黑名单
//    if (preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|TRUNCATE|LOAD DATA|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $sql)) {
//      return false;
//    }
        //白名单
        if (!preg_match('/^\s*"?(SELECT)\s+/i', $sql)) {
            return false;
        }
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    /**
     * Enable transaction
     * @link
     * @param Bool $debug<p>
     * 是否开启测试模式
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function trans_start($debug = false) {
        $this->db->trans_start($debug);
    }

    /**
     * Commit transaction
     * @link URL description
     * @param <p>
     * NULL
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function trans_complete() {
        $this->db->trans_complete();
    }

    /**
     * Commit transaction
     * @link URL description
     * @param <p>
     * NULL
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function trans_rollback() {
        $this->db->trans_rollback();
    }

    /**
     * trans_status
     * @link
     * @param <p>
     * NULL
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function trans_status() {
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return true;
    }

    /**
     * Insert data,插入数据库表
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbInsert($table, $data, $insert_id = false) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        $result = $this->db->insert($table, $data);
        if ($insert_id) {
            return $this->db->insert_id();
        }
        return $result;
    }

    /**
     * Insert data,批量插入数据库表
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbInsertBatch($table, $data) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        $result = $this->db->insert_batch($table, $data);
        return $result;
    }

    /**
     * Insert Or Update,根据Mysql的新特性 ON DUPLICATE KEY(INSERT INTO … ON DUPLICATE KEY UPDATE)
     * 先执行前面的Insert,如果主键重复，则执行后面的UPDATE
     * @link URL description
     * @param String $sql<p>
     * SQl-Statement
     * </p>
     *
     * @return  Array <b>select result</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbInsertOrUpdate($table, $insert, $update) {
        if (empty($table)) {
            trigger_error("Table Name Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (empty($insert)) {
            trigger_error("Insert Data Cannot empty!", E_USER_ERROR);
            return false;
        } else {
            $insert = new_addslashes($insert);
        }
        if (empty($update)) {
            trigger_error("Update Data Cannot empty!", E_USER_ERROR);
            return false;
        } else {
            $update = new_addslashes($update);
        }
        $sql = "INSERT INTO " . $this->dbprefix($table) . "(" . implode(",", array_keys($insert)) . ") VALUES ('" . implode("','", array_values($insert)) . "')";
        $sql .= " ON DUPLICATE KEY ";
        $sql .= "UPDATE ";
        foreach ($update as $k => $item) {
            $sql .= $k . " = '" . $item . "',";
        }
        $sql = rtrim($sql, ",") . ";";
        $rows = $this->db->query($sql);
        return $rows;
    }

    /**
     * Update data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbUpdate($table, $data, $conditions) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions) {
            trigger_error("dbUpdate Conditions Cannot empty!", E_USER_ERROR);
            return false;
        }
        return $this->db->update($table, $data, $conditions);
    }

    /**
     * Update data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbUpdateIn($table, $data, $conditions, $in) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions && !$in) {
            trigger_error("dbUpdateIn Conditions And In Cannot empty!", E_USER_ERROR);
            return false;
        }
        list($key, $val) = each($in);
        $this->db->where_in($key, $val);
        return $this->db->update($table, $data, $conditions);
    }

    /**
     * Update data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbUpdateIns($table, $data, $conditions, $ins) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions && !$ins) {
            trigger_error("dbUpdateIns Conditions And Ins Cannot empty!", E_USER_ERROR);
            return false;
        }
        foreach ($ins as $in) {
            list($key, $val) = each($in);
            $this->db->where_in($key, $val);
        }
        return $this->db->update($table, $data, $conditions);
    }

    /**
     * Set data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbSet($table, $data, $conditions) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions) {
            trigger_error("dbSet Conditions Cannot empty!", E_USER_ERROR);
            return false;
        }
        $this->db->where($conditions);
        $this->db->set(key($data), current($data), FALSE);
        return $this->db->update($table);
    }

    /**
     * Delete data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbDelete($table, $conditions) {
        if (empty($conditions)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions) {
            trigger_error("dbDelete Conditions Cannot empty!", E_USER_ERROR);
            return false;
        }
        return $this->db->delete($table, $conditions);
    }

    /**
     * Delete data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbDeleteIn($table, $conditions, $in) {
        if (empty($conditions)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions && !$in) {
            trigger_error("dbDeleteIn Conditions And In Cannot empty!", E_USER_ERROR);
            return false;
        }
        list($key, $val) = each($in);
        $this->db->where_in($key, $val);
        return $this->db->delete($table, $conditions);
    }

    /**
     * Delete data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $conditions<p>
     * where
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbDeleteNotIn($table, $conditions, $in) {
        if (empty($conditions)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        if (!$conditions && !$in) {
            trigger_error("dbDeleteNotIn Conditions And In Cannot empty!", E_USER_ERROR);
            return false;
        }
        list($key, $val) = each($in);
        $this->db->where_not_in($key, $val);
        return $this->db->delete($table, $conditions);
    }

    /**
     * Delete data
     * @link 
     * @param String $table<p>
     * 数据库表名称
     * </p>
     * @param Array $data<p>
     * 数据对象
     * $data = array(
      'title' => 'My title',
      'name' => 'My Name',
      'date' => 'My date'
      );
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbReplace($table, $data) {
        if (empty($data)) {
            trigger_error("data Cannot empty!", E_USER_ERROR);
            return false;
        }
        return $this->db->replace($table, $data);
    }

    /**
     * Description
     * @link URL description
     * @param String $sql<p>
     * SQl-Statement
     * </p>
     *
     * @return  Array <b>select result</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbSelect($sql) {
        if (!$sql || strtolower(substr(trim($sql), 0, 6)) !== 'select') {
            return false;
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /**
     * Return full table name by $tablename
     * @link URL description
     * @param String $tablename<p>
     * Table Name
     * </p>
     *
     * @return  String <b>Table Name</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbprefix($tablename) {
        return $this->db->dbprefix($tablename);
    }

    /**
     * Get Primary of table
     * @link URL description
     * @param String $tablename<p>
     * table name
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbPrimary($tablename) {
        return $this->db->primary($tablename);
    }

    /**
     * Get Database name
     * @link URL description
     * @param NULL<p>
     * table name
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function dbDatabase() {
        return $this->db->database;
    }

    /**
     * show tables
     * @link URL description
     * @param NULL<p>
     * 
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    public function show_tables() {
        //select table_name from information_schema.tables where table_schema='baima_kefu'
        //show tables
        $tables = $this->db->list_tables();
        $strlen = strlen($this->db->dbprefix);
        foreach ($tables as &$item) {
            $item = substr($item, $strlen);
        }
        return $tables;
    }

    /**
     * check field is exists in a table
     * @link URL description
     * @param String $fieldname<p>
     * field name
     * </p>
     * @param String $tablename<p>
     * table name
     * </p>
     *
     * @return  bool.TRUE if that ﬁeld exists in that table, FALSE if not.
     * @author Parker <date>
     */
    public function field_exists($fieldname, $tablename) {
        return $this->db->field_exists($fieldname, $tablename);
    }

    /**
     * check table is exists in a table
     * @link URL description
     * @param String $tablename<p>
     * table name
     * </p>
     *
     * @return  bool.TRUE if that ﬁeld exists in that table, FALSE if not.
     * @author Parker <date>
     */
    public function table_exists($tablename) {
        return $this->db->table_exists($tablename);
    }

    /**
     * check table is exists in a table
     * @link URL description
     * @param String $tablename<p>
     * table name
     * </p>
     *
     * @return  bool.TRUE if that ﬁeld exists in that table, FALSE if not.
     * @author Parker <date>
     */
    public function get_compiled_select($table = '', $reset = TRUE) {
        return $this->db->get_compiled_select($table, $reset);
    }

    public function getData($params) {
        $params = $this->_get_conditions($params);
        extract($params); //抛出键值对
        if (!$fields || !$table) {
            trigger_error('table没指定');
            exit;
        }

        $distinct && $this->db->distinct();
        if ($compiled) {
            $this->db->from($table);
        } else {
            $this->db->select($fields, ($escape === false ? false : true))->from($table);
        }

        if ($join) {
            $len = count($join);
            if ($len % 2 != 0) {
                trigger_error('连接查询的参数不正确');
                exit;
            }
            //连接符
            $concat = $concat ? $concat : 'left';

            for ($i = 0; $i < $len; $i++) {
                $this->db->join($join[$i], $join[++$i], $concat);
            }
        }
        $where && $this->db->where($where);
        $conditions && $this->db->where($conditions);

        if ($_conditions) {
            foreach ($_conditions as $item) {
                $this->db->where($item);
            }
        }
        if ($_order) {
            if (is_array($_order)) {
                foreach ($_order as $item) {
                    list($key, $value) = each($item);
                    $this->db->order_by($key, $value);
                }
            } else {
                $this->db->order_by($_order);
            }
        }
        $in && $this->db->where_in($in[0], $in[1]);
        if ($ins) {
            foreach ($ins as $item) {
                list($key, $value) = each($item);
                if (empty($value)) {
                    trigger_error("Where IN Value Cannot empty!", E_USER_ERROR);
                    return false;
                }
                $this->db->where_in($key, $value);
            }
        }
        if ($not_ins) {
            foreach ($not_ins as $item) {
                list($key, $value) = each($item);
                if (empty($value)) {
                    trigger_error("Where NOT IN Value Cannot empty!", E_USER_ERROR);
                    return false;
                }
                $this->db->where_not_in($key, $value);
            }
        }
        if ($having) {
            foreach ($having as $item) {
                list($key, $value) = each($item);
                $this->db->having($key, $value, ($having_bool ? $having_bool : false));
            }
        }
        if ($likes) {
            foreach ($likes as $item) {
                list($key, $value) = each($item);
                $this->db->like($key, $value);
            }
        }
        if ($or_likes) {
            foreach ($or_likes as $item) {
                list($key, $value) = each($item);
                $this->db->or_like($key, $value);
            }
        }
        $or && $this->db->or_where($or[0], $or[1]);
        $order && $this->db->order_by($order[0], $order[1]);
        $limit && $this->db->limit($limit[0], $limit[1]);
        $group && $this->db->group_by($group);
        $like && $this->db->like($like[0], $like[1]);
        if ($orders) {
            $keys = explode(',', $orders[0]);
            $values = explode(',', $orders[1]);
            $arr = array_combine($keys, $values);
            foreach ($arr as $key => $item) {
                $this->db->order_by($key, $item);
            }
        }
        if ($row) {
            return $this->db->get()->row_array();
        }
        if ($count) {
            return $this->db->count_all_results();
        }
        return $this->db->get()->result_array();
    }

    /**
     * 
     * @func
     * @param fields 字段，table 表名,conditions 条件限制,limit 指定行数(array($offset,$start)),order 排序(array($field,$order))
     * @return 组合的数组
     * 作者：Fzhao
     * 时间：2012-11-8
     */
    protected function _get_conditions($params) {
        //count设置为true,只返回指定的总行数
        $arr = array(
            'fields' => '*', //要查询的字段
            'table' => '', //要选择的第一个表
            'conditions' => '', //查询要满足的条件
            '_conditions' => '',
            'limit' => '', //指定的行数 array($limit,$offset)
            'order' => '', //排序array($fields,$order)
            '_order' => '',
            'join' => '', //联合查询的表 和条件array('table','a.id=b.id')
            'count' => '', //只查询数目
            'concat' => '', //联合查询的连接符
            'in' => '', //查询in语句 array('index',array(1,2,3))
            'ins' => '',
            'not_ins' => '',
            'or' => '', //查询or语句 
            'row' => '', //只返回一行数据
            'orders' => '', //多行排序
            'group' => '', //分组查询
            'like' => '', //like查询
            'likes' => '', //like查询
            'or_likes' => '', //like查询
            'where' => '', //like查询
            'distinct' => '',
            'having' => '',
            'having_bool' => '',
            'escape' => '',
            'assoc' => '',
            'compiled' => '',
        );
        return array_merge($arr, $params);
    }

    /**
     * getTermByTaxonomy
     * 简介：读取分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2014-1-24
     */
    public function getTermByTaxonomy($taxonomy) {
        static $terms;
        if ($taxonomy && is_string($taxonomy) && !empty($terms[$taxonomy])) {
            return $terms[$taxonomy];
        }
        
        $_terms = $this->getData(array(
            'fields' => 'id,name,parent,slug,taxonomy,count',
            'table' => 'term',
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_)),
            '_order' => array(array('parent' => 'asc'), array('sort' => 'desc')),
        ));
        $terms = $this->_get_term_trees($_terms, 0, 0);

        $newTerms = array();
        if($taxonomy && is_array($taxonomy)){
            foreach($taxonomy as $item){
                if(empty($terms[$item])){
                    continue;
                }
                $newTerms[$item] = $terms[$item];
            }
            return $newTerms;
        }
        return !empty($terms[$taxonomy])?$terms[$taxonomy]:array();
    }
    
    private function _get_term_trees($data, $pId, $deep) {
        $tree = array();
        foreach ($data as $v) {
            if ($v['parent'] != $pId) {
                continue;
            }
            //父亲找到儿子
            $v['deep'] = $deep + 1;
            $v['childs'] = $this->_get_term_trees($data, $v['id'], $deep + 1); //, $class, $method, $directory, $_p_
            $index = $v['parent']==0?$v['taxonomy']:$v['id'];
            $tree[$index] = $v;
        }
        return $tree;
    }

    /**
     * getTermById
     * 简介：根据ID读取信息
     * 参数：$id int
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function getTermById($id,$field = '') {
        if (!$id) {
            return null;
        }
        $ids = $id;
        if(!is_array($id)){
            $ids = array($ids);
        }
        $params = array(
            'fields' => '*',
            'table' => 'term',
            '_conditions' => array(array('isHidden' => '0')), //, 'lang' => _LANGUAGE_
            'ins' => array(array('id' => $ids))
        );
        if(!is_array($id)){
            $params['row'] = true;
        }
        $result = $this->getData($params);
        if($field){
            if(!is_array($id) && isset($result[$field])){
                return $result[$field];
            }
            if(is_array($id) && $result){
                return array_column($field, 'id');
            }
        }
        return $result;
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
            'conditions' => array($this->primary_key => $id), //, 'lang' => _LANGUAGE_
            'row' => true
        ));
        return $result;
    }

    /**
     * getRowsByIds
     * 简介：根据IDs读取信息
     * 参数：$id int
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function getRowsByIds($ids, $fields = 'id', $conditions = array()) {
        if (!$ids || !is_array($ids)) {
            return null;
        }
        $result = $this->getData(array(
            'fields' => $fields,
            'table' => $this->table,
            'ins' => array(array($this->primary_key => $ids)),
            '_conditions' => $conditions,
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
        return $this->dbUpdate($this->table, $data, array($this->primary_key => $id)); //, 'lang' => _LANGUAGE_
    }

    /**
     * del
     * 简介：删除(放入回收站)
     * 参数：$id mixed
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function del($id, $termIdName = '') {
        if (!$id) {
            return false;
        }
        $data = array(
            'isHidden' => '1'
        );
        if (!is_array($id)) {
            $id = array($id);
        }
        $trems = array();
        $fields = 'id' . ($termIdName ? ',' . $termIdName : '');
        $rows = $this->getRowsByIds($id, $fields);
        if (!$rows) {
            return false;
        }
        $ids = array_column($rows, 'id');
        if ($termIdName) {
            $trems = array_filter(array_unique(array_column($rows, $termIdName)));
        }

        $this->trans_start();
        $this->dbUpdateIn($this->table, $data, array($this->primary_key . '!=' => ''), array($this->primary_key => $ids));
        if ($trems) {
            foreach ($trems as $term_id) {
                $this->dbSet('term', array('count' => 'count-1'), array('id' => $term_id));
            }
        }
        $this->trans_complete();
        if ($this->trans_status() === FALSE) {
            return false;
        }
        return true;
    }

    /**
     * recover
     * 简介：还原
     * 参数：$id mixed
     * 返回：Boole
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recover($id, $termIdName = '') {
        if (!$id) {
            return false;
        }
        $data = array(
            'isHidden' => '0'
        );
        if (!is_array($id)) {
            $id = array($id);
        }
        $trems = array();
        $fields = 'id' . ($termIdName ? ',' . $termIdName : '');
        $rows = $this->getRowsByIds($id, $fields);
        if (!$rows) {
            return false;
        }
        $ids = array_column($rows, 'id');
        if ($termIdName) {
            $trems = array_filter(array_unique(array_column($rows, $termIdName)));
        }

        $this->trans_start();
        $this->dbUpdateIn($this->table, $data, array($this->primary_key . '!=' => ''), array($this->primary_key => $ids));
        if ($trems) {
            foreach ($trems as $term_id) {
                $this->dbSet('term', array('count' => 'count+1'), array('id' => $term_id));
            }
        }
        $this->trans_complete();
        if ($this->trans_status() === FALSE) {
            return false;
        }
        return true;
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
        $rows = $this->getRowsByIds($id);
        if (!$rows) {
            return false;
        }
        $ids = array_column($rows, 'id');
        return $this->dbDeleteIn($this->table, array($this->primary_key . '!=' => ''), array($this->primary_key => $ids));
    }

}
