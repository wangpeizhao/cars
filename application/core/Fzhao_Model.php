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
        static $term;
        if (!empty($term)) {
            return $term;
        }
        if (!is_array($taxonomy)) {
            $taxonomy = array($taxonomy);
        }
        $data = $this->getData(array(
            'fields' => 'id,name,parent,slug,taxonomy,count,subclass',
            'table' => 'term',
            'conditions' => array('is_valid' => 1, 'lang' => _LANGUAGE_),
            'ins' => array(array('taxonomy' => $taxonomy)),
            'orders' => array('parent,sort', 'asc,desc'),
        ));

        $sunTerm = array();
        foreach ($data as $key => $item) {
            if ($item['parent'] == 0) {
                $term[$item['id']] = $item;
            } else {
                if (isset($term[$item['parent']])) {
                    $item['grandson'] = array();
                    $term[$item['parent']]['sunTerm'][] = $item;
                    $sunTerm[$item['id']] = array($item['parent'], count($term[$item['parent']]['sunTerm']) - 1);
                } else if (isset($sunTerm[$item['parent']])) {
                    $term[$sunTerm[$item['parent']][0]]['sunTerm'][$sunTerm[$item['parent']][1]]['grandson'][] = $item;
                    array_sort($term[$sunTerm[$item['parent']][0]]['sunTerm'][$sunTerm[$item['parent']][1]]['grandson'], 'subclass');
                }
            }
        }
        rsort($term);
        return $term;
    }

    /**
     * 生成页码
     * 简介：生成页码
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/15
     */
    function createPages($total, $row, $thepage, $url = '', $postfix = '', $select = true) {
        $pages = ceil($total / $row);
        $prev = $thepage - 1 > 0 ? $thepage - 1 : 1;
        $next = $thepage + 1 <= $pages ? $thepage + 1 : $pages;
        if ($thepage == 1) {
            $str = '<a class="page-previous page-previous-disabled"></a>';
        } else {
            $str = '<a href="' . $url . $prev . $postfix . '" class="page-previous" title="上一页"></a>';
        }
        if ($pages <= 6) {
            $minpage = $pages;
        } else {
            $minpage = 6;
        }
        if ($thepage <= 5) {
            for ($p = 1; $p <= $minpage; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } else {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        } elseif ($pages - $thepage <= 4) {
            $str .= '<a href="' . $url . '1' . $postfix . '" title="1">1</a><a>..</a>';
            for ($p = $pages - 5; $p <= $pages; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } else {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        } else {
            $str .= '<a href="' . $url . '1' . $postfix . '" title="1">1</a><a>..</a>';
            for ($p = $thepage - 2; $p <= $thepage + 2; $p++) {
                if ($p == $thepage) {
                    $str .= '<a class="on" title="' . $p . '">' . $p . '</a>';
                } elseif ($p <= $pages) {
                    $str .= '<a href="' . $url . $p . $postfix . '" title="' . $p . '">' . $p . '</a>';
                }
            }
        }
        if ($pages - $thepage > 2 && $pages > 6) {
            $str .= '<a>..</a><a href="' . $url . $pages . $postfix . '" title="' . $pages . '">' . $pages . '</a>';
        }
        if ($thepage == $pages) {
            $str .= '<a class="page-next page-next-disabled"></a>';
        } else {
            $str .= '<a href="' . $url . $next . $postfix . '" class="page-next" title="下一页"></a>';
        }
        if ($select) {
            $str .= '<span class="select"><select ';
            $str .= "onchange='window.location=\"$url\"+this.value'>";
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $thepage) {
                    $str .= '<option value="' . $i . '" selected>' . $i . '页</option>';
                } else {
                    $str .= '<option value="' . $i . '">' . $i . '页</option>';
                }
            }
            $str .= '</select></span>';
        }
        return $str;
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
