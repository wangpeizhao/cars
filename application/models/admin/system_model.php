<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * System_Model
 * 简介：后台管理数据库模型
 * 返回：Boole
 * 作者：Fzhao
 * 时间：2012-11-8
 */
class System_Model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->menuTreesClass = array('mFirst', 'mSecond', 'mThird', 'mFourth', 'mFifth', 'mSixth', 'mSeventh', 'mEighth', 'mNinth', 'mTenth');
    }

    /**
     * editOptions
     * 简介：修改网站配置信息
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/12
     */
    function editOptions($data) {
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                $this->db->replace('options', $item);
                //wwww($this->db->last_query());
            }
        }
        return true;
    }

    /**
     * selectMax
     * 简介：修改网站配置信息
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/12
     */
    function selectMax($col, $table) {
        if (!$col || !$table) {
            return false;
        }
        $row = $this->getData(array(
            'fields' => 'MAX(' . $col . ') as ID',
            'table' => $table,
            'row' => true
        ));

        return $row['ID'];
    }

    /**
     * getOptions
     * 简介：根据option_type读取网站配置信息
     * 参数：$type 
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/12
     */
    function getOptions($type) {
        $options = $this->getData(array(
            'fields' => '*',
            'table' => 'options',
            'conditions' => array('option_type' => $type, 'lang' => _LANGUAGE_)
        ));
        $optionsData = $companyData = array();
        if (!empty($options)) {
            foreach ($options as $key => $item) {
                if ($item['option_name'] == 'company') {
                    $companyData = unserialize($item['option_value']);
                } else {
                    $optionsData[$item['option_name']] = unserialize($item['option_value']);
                }
            }
        }
        return array_merge($companyData, $optionsData);
    }

    /**
     * addProducts
     * 简介：上传添加产品
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-6
     */
    function addProducts($data) {
        return $this->db->insert('products', $data);
    }

    /**
     * getProductsList
     * 简介：读取产品列表
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-23
     */
    function getProductsList($data, $is_valid = 1) {
        $cond = isset($data['condition']) ? $data['condition'] : array();
        $conditions = array('p.is_valid' => $is_valid);
        $like = array();
        if (isset($cond['type']) && $cond['type']) {
            switch ($cond['type']) {
                case 'title':
                    $like = array('like' => array('title', $cond['keywords']));
                    break;
                case 'summary':
                    $like = array('like' => array('summary', $cond['keywords']));
                    break;
                case 'content':
                    $like = array('like' => array('content', $cond['keywords']));
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
        $taxonomy = isset($cond['taxonomy']) ? $cond['taxonomy'] : 'products';
        $conditions = array_merge($conditions, array('t.taxonomy' => $taxonomy, 'p.lang' => _LANGUAGE_));
        $data = $this->getData(array_merge(array(
            'fields' => 'pt.`name` t_name1,t.name term_name,t.slug,p.id,p.term_id,p.title,p.summary,p.is_valid,p.owner,p.views,p.is_commend,p.is_issue,p.create_time,a.username',
            'table' => 'products p',
            'join' => array('term t', 't.id=p.term_id', 'term pt', 'pt.id=t.parent', 'admin a', 'a.id=p.owner'),
            'conditions' => $conditions,
            'order' => array('p.update_time', 'desc'),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
                        ), $like));

        $count = $this->getData(array_merge(array(
            'table' => 'products p',
            'join' => array('term t', 't.id=p.term_id', 'term pt', 'pt.id=t.parent', 'admin a', 'a.id=p.owner'),
            'conditions' => $conditions,
            'count' => true,
                        ), $like));
        return array('data' => $data, 'count' => $count);
    }

    /**
     * getProductById
     * 简介：根据ID读取产品信息
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function getProductById($id) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'products',
                    'conditions' => array('id' => $id, 'lang' => _LANGUAGE_),
                    'row' => true
        ));
    }

    /**
     * editProducts
     * 简介：修改产品信息
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function editProducts($data, $id) {
        return $this->db->update('products', $data, array('id' => $id));
    }

    /**
     * getProductsRecycleList
     * 简介：读取产品列表(回收站)
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getProductsRecycleList($data) {
        return $this->getProductsList($data, 0);
    }

    /**
     * delProduct
     * 简介：删除(放入回收站)产品
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function delProduct($id) {
        $data = array(
            'is_valid' => 0
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('products', $data);
        } else {
            return $this->db->update('products', $data, array('id' => $id));
        }
    }

    /**
     * dumpProducts
     * 简介：删除(彻底清除)产品信息
     * 参数：$id int 
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-30
     */
    function dumpProducts($id) {
        $products = $this->getData(array(
            'fields' => 'term_id,thumbPic',
            'table' => 'products',
            'in' => array('id', $id)
        ));
        foreach ($products as $product) {
            if ($product['thumbPic'] && file_exists($product['thumbPic'])) {
                //unlink($product['thumbPic']);
            }
        }
        $tid = $product['term_id'];
        $this->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count-1', 'id' => $tid));
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->delete('products');
        } else {
            return $this->db->delete('products', array('id' => $id));
        }
    }

    /**
     * recoverProducts
     * 简介：还原产品信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function recoverProducts($id) {
        $data = array(
            'is_valid' => 1
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('products', $data);
        } else {
            return $this->db->update('products', $data, array('id' => $id));
        }
    }

    /**
     * getAdminCount
     * 简介：读取管理员人员数量
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-27
     */
    function getAdminCount() {
        return $this->getData(array(
                    'table' => 'admin',
                    'count' => true,
        ));
    }

    /**
     * getLinkCount
     * 简介：读link数量
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-27
     */
    function getLinkCount() {
        $data = $this->getData(array(
            'fields' => 'count(link_id) num,link_type',
            'table' => 'links',
            'group' => 'link_type',
        ));
        $link = array();
        if (!empty($data)) {
            foreach ($data as $item) {
                $link[$item['link_type']] = $item['num'];
            }
        }
        return $link;
    }

    /**
     * getCommentCount
     * 简介：读comments数量
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-27
     */
    function getCommentCount() {
        $data = $this->getData(array(
            'fields' => 'count(id) num,type',
            'table' => 'comments',
            'group' => 'type',
        ));
        $link = array();
        if (!empty($data)) {
            foreach ($data as $item) {
                $link[$item['type']] = $item['num'];
            }
        }
        return $link;
    }

    /**
     * getIpChart
     * 简介：IP统计
     * 参数：$type->h:按小时显示;$type->d:按日显示;
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2013/1/21
     */
    function getIpChart($time, $type) {
        $data = $categories = $val = array();
        $conditions = array('create_time >=' => date("Y-m-d H:i:s", $time + 3600 * 24));
        if ($type == 'h') {
            $conditions = array("DATE_FORMAT(create_time,'%Y-%m-%d')" => date("Y-m-d", $time));
        }
        $ips = $this->getIpList($conditions);
        if ($type == 'h') {
            for ($i = 0; $i < 24; $i++) {
                $categories[$i] = $i . '时';
                $val[$i] = 0;
            }
            $data['categories'] = $categories;
            if (!empty($ips)) {
                foreach ($ips as $item) {
                    $h = intval(next(explode(' ', current(explode(':', $item['create_time'])))));
                    $val[$h] = $val[$h] + 1;
                }
            }
            $data['data'] = $val;
        }
        if ($type == 'd') {
            $days = (strtotime(date("Y-m-d", time())) - $time) / 3600 / 24;
            if ($days == 0)
                $days = 1;
            $categories = explode(',', $this->setDays($days));
            foreach ($categories as $item) {
                $val["'" . $item . "'"] = 0;
            }
            $data['categories'] = $categories;
            if (!empty($ips)) {
                foreach ($ips as $item) {
                    $h = explode('-', current(explode(' ', $item['create_time'])));
                    $index = "'" . $h[1] . '-' . $h[2] . "'";
                    $val[$index] = $val[$index] + 1;
                }
            }
            $data['data'] = array_values($val);
        }

        $data['count'] = $this->getData(array(
            'table' => 'visit_ip',
            'conditions' => $conditions,
            'count' => true
        ));
        return $data;
    }

    /**
     * getIpList_S_D
     * 简介：读取查看、下载IP列表
     * 参数：
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2013/1/23
     */
    function getIpList_S_D($time, $val, $order) {
        $val = str_replace('时', '', $val);
        $type = '';
        if (is_array($time)) {
            $type = $time[1];
            $time = $time[0];
        }
        if (is_numeric($val)) {
            if ($val < 10) {
                $val = '0' . $val;
            }
            $conditions = array("DATE_FORMAT(create_time,'%Y-%m-%d-%H')" => date("Y-m-d-", $time) . $val); //今天、昨天、某一天
        } else if ($val) {
            $conditions = array("DATE_FORMAT(create_time,'%Y-%m-%d')" => date("Y-") . $val); //最近7天、最近30天、本月
        } else {
            $conditions = array('create_time >=' => date("Y-m-d H:i:s", $time + 3600 * 24));
            if ($type == 'h') {
                $conditions = array("DATE_FORMAT(create_time,'%Y-%m-%d')" => date("Y-m-d", $time));
            }
        }
        return $this->getIpList($conditions, $order);
    }

    /**
     * getIpList
     * 简介：读取IP列表
     * 参数：
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2013/1/22
     */
    function getIpList($conditions, $order = 'desc') {
        $data = $this->getData(array(
            'fields' => 'ip,create_time',
            'table' => 'visit_ip',
            'conditions' => $conditions,
            'order' => array('create_time', $order)
        ));
        //setlog($this->db->last_query());
        return $data;
    }

    /**
     * 根据天数返回日期列表
     * 参数：int  $days   天数
     * 返回：string   天数字符串
     * 作者：Fzhao
     * 时间：2013/1/21
     */
    function setDays($days) {
        $str = '';
        for ($i = $days; $i > 0; $i--) {
            $str .=date('m-d', time() - 3600 * 24 * ($i - 1)) . ',';
        }
        return substr($str, 0, -1);
    }

    /**
     * get_email_list_by_type
     * 简介：读取Email列表
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function get_email_list_by_type($table = 'member', $where = array()) {
        $data = $this->getData(array(
            'fields' => 'email',
            'table' => $table,
            'conditions' => array_merge(array('is_active' => 1, 'email !=' => ''), $where)
        ));
        return $data;
    }

}
