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
     * getAdminList
     * 简介：读取管理员列表
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getAdminList($data) {
        $data = $this->getData(array(
            'fields' => 'a.*,g.grouptitle',
            'table' => 'admin a',
            'join' => array('group g', 'a.grade=g.groupid'),
            'conditions' => array('is_valid' => 1),
            'orders' => array('sort', 'desc'),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
        ));
        $count = $this->getData(array(
            'table' => 'admin a',
            'join' => array('group g', 'a.grade=g.groupid'),
            'conditions' => array('is_valid' => 1),
            'count' => true,
        ));
        return array('data' => $data, 'count' => $count);
    }

    /**
     * getAdminRecycleList
     * 简介：读取管理员列表(回收站)
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getAdminRecycleList($data) {
        $data = $this->getData(array(
            'fields' => 'a.*,g.grouptitle',
            'table' => 'admin a',
            'join' => array('group g', 'a.grade=g.groupid'),
            'conditions' => array('is_valid' => 0),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
        ));
        $count = $this->getData(array(
            'table' => 'admin a',
            'join' => array('group g', 'a.grade=g.groupid'),
            'conditions' => array('is_valid' => 0),
            'count' => true,
        ));
        return array('data' => $data, 'count' => $count);
    }

    /**
     * getUserInfo
     * 简介：根据uid读取管理员信息
     * 参数：$uid int 管理员uid
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getUserInfo($uid) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'admin',
                    'conditions' => array('id' => $uid),
                    'row' => true
        ));
    }

    /**
     * checkUsername
     * 简介：根据Username检测管理员名称是否存在
     * 参数：$sername string 管理员名称
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function checkUsername($username) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'admin',
                    'conditions' => array('username' => $username),
                    'row' => true
        ));
    }

    /**
     * editUserInfo
     * 简介：修改管理员信息
     * 参数：$data array 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function editUserInfo($data, $uid = 0) {
        $info = $data['data'];
        $data = array(
            'username' => $info['username'],
            'is_active' => $info['is_active'],
            'email' => $info['email'],
            'phone' => $info['phone'],
            'grade' => $info['grade'],
            'nickname' => $info['nickname'],
            'branch' => $info['branch'],
            'mobile' => $info['mobile'],
            'describe' => $info['describe'],
            'sort' => $info['sort'],
            'qq' => $info['qq']
        );
        if (isset($info['password']) && trim($info['password'])) {
            $data = array_merge($data, array('password' => $info['password']));
        }
        return $this->db->update('admin', $data, array('id' => $uid));
    }

    /**
     * addUserInfo
     * 简介：添加管理员信息
     * 参数：$data array 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function addUserInfo($data) {
        $data['password'] = encryption($data['password']);
        return $this->db->insert('admin', $data);
    }

    /**
     * delUserInfo
     * 简介：删除管理员信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function delUserInfo($id) {
        $data = array(
            'is_valid' => 0
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('admin', $data);
        } else {
            return $this->db->update('admin', $data, array('id' => $id));
        }
    }

    /**
     * dumpUserInfo
     * 简介：删除(彻底清除)管理员信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-12
     */
    function dumpUserInfo($id) {
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->delete('admin');
        } else {
            return $this->db->delete('admin', array('id' => $id));
        }
    }

    /**
     * recoverUserInfo
     * 简介：还原管理员信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-12
     */
    function recoverUserInfo($id) {
        $data = array(
            'is_valid' => 1
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('admin', $data);
        } else {
            return $this->db->update('admin', $data, array('id' => $id));
        }
    }

    /**
     * getRoleList
     * 简介：读取管理员权限角色列表
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getRoleList() {
        return $this->getData(array(
                    'fields' => 'groupid,grouptitle',
                    'table' => 'group'
        ));
    }

    /**
     * addRole
     * 简介：添加管理员权限角色
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function addRole($data) {
        return $this->db->insert_batch('group', $data);
    }

    /**
     * delRole
     * 简介：删除管理员权限角色
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function delRole($gid) {
        return $this->db->delete('group', array('groupid' => $gid));
    }

    /**
     * editRole
     * 简介：修改管理员权限角色
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function editRole($gid, $newTitle) {
        return $this->db->update('group', array('grouptitle' => $newTitle), array('groupid' => $gid));
    }

    /**
     * adminRoleList
     * 简介：管理员角色列表
     * 参数：$thePage string 页码
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-14
     */
    function adminRoleList($data) {
        $data = $this->getData(array(
            'fields' => '*',
            'table' => 'group',
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
        ));
        $count = $this->getData(array(
            'table' => 'group',
            'count' => true,
        ));
        return array('data' => $data, 'count' => $count);
    }

    /**
     * editRegular
     * 简介：修改权限分配
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-12-25
     */
    function editRegular($data) {
        return $this->db->update('group', array('regulars' => $data['regular']), array('groupid' => $data['groupid']));
    }

    /**
     * getRegularByGroupid
     * 简介：根据Groupid读取regulars
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/26
     */
    function getRegularByGroupid($data) {
        return $this->getData(array(
                    'fields' => 'regulars',
                    'table' => 'group',
                    'conditions' => array('groupid' => $data['groupid']),
                    'row' => true
        ));
    }

    /**
     * addLink
     * 简介：添加友情链接/添加导航/首页切图
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-20
     */
    function addLink($data) {
        return $this->db->insert('links', $data);
    }

    /**
     * delIndexNav
     * 简介：彻底删除导航
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function delIndexNav($id) {
        return $this->db->delete('links', array('link_id' => $id));
    }

    /**
     * editIndexNav
     * 简介：修改导航
     * 参数：新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function editIndexNav($data, $id) {
        return $this->db->update('links', $data, array('link_id' => $id));
    }

    /**
     * getNavList
     * 简介：读取导航列表
     * 参数：link_type
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function getNavList($link_type = 'indexNav') {
        return $this->getData(array(
                    'fields' => 'link_url,link_name,link_target',
                    'table' => 'links',
                    'conditions' => array('link_type' => $link_type, 'lang' => _LANGUAGE_),
                    'orders' => array('link_rating,link_updated', 'desc,desc'),
        ));
    }

    /**
     * getLinksList
     * 简介：读取友情链接
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-21
     */
    function getLinksList($data) {
        //link
        $count = array();
        $linkData = $this->getData(array(
            'fields' => 'l.*,a.username,t.name link_term_name',
            'table' => 'links l',
            'join' => array('admin a', 'a.id=l.link_owner', 'term t', 't.id=l.link_term'),
            'conditions' => array('link_type' => $data['link_type'], 'l.lang' => _LANGUAGE_),
            'orders' => array('link_parent,link_rating,link_updated', 'asc,desc,desc'),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
        )); //wwww($this->db->last_query());
        if ($data['link_type'] != 'indexNav') {
            $count = $this->getData(array(
                'table' => 'links l',
                'join' => array('admin a', 'a.id=l.link_owner'),
                'conditions' => array('link_type' => $data['link_type'], 'l.lang' => _LANGUAGE_),
                'count' => true,
            ));
        }
        $link = array();
        if (!empty($linkData)) {
            foreach ($linkData as $key => $item) {
                if (!file_exists($item['link_image'])) {
                    $item['link_image'] = '';
                }
                if ($data['link_type'] == 'indexNav') {
                    if ($item['link_parent'] == 0) {
                        $item['son_link'] = array();
                        $link[$item['link_id']] = $item;
                    } else {
                        $link[$item['link_parent']]['son_link'][] = $item;
                    }
                } else {
                    $link[$key] = $item;
                }
            }
            if ($data['link_type'] == 'indexNav') {
                $link = array_values($link);
            }
        }
        return array('data' => $link, 'count' => $count);
    }

    /**
     * delLink
     * 简介：删除友情链接
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-21
     */
    function delLink($id) {
        $data = $this->getData(array(
            'fields' => 'link_image',
            'table' => 'links',
            'conditions' => array('link_id' => $id, 'lang' => _LANGUAGE_),
            'row' => true
        ));
        if ($data['link_image'] && file_exists($data['link_image'])) {
            unlink($data['link_image']);
        }
        return $this->db->delete('links', array('link_id' => $id));
    }

    /**
     * getLink
     * 简介：根据id读取友情链接全部信息
     * 参数：$id int id
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-21
     */
    function getLink($id) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'links',
                    'conditions' => array('link_id' => $id, 'lang' => _LANGUAGE_),
                    'row' => true
        ));
    }

    /**
     * editLink
     * 简介：修改友情链接角色
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function editLink($data, $id) {
        $link = $this->getData(array(
            'fields' => 'link_image',
            'table' => 'links',
            'conditions' => array('link_id' => $id, 'lang' => _LANGUAGE_),
            'row' => true
        ));
        if (isset($data['link_image']) && $data['link_image'] && file_exists($data['link_image'])) {
            unlink($link['link_image']);
        }
        return $this->db->update('links', $data, array('link_id' => $id));
    }

    /**
     * updateLinkImage
     * 简介：修改友情链接图片链接
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-22
     */
    function updateLinkImage($id) {
        return $this->db->update('links', array('link_image' => '', 'lang' => _LANGUAGE_), array('link_id' => $id));
    }

    /**
     * getCommentList
     * 简介：读取留言列表
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-23
     */
    function getCommentList($data, $is_valid = 1) {
        $cond = isset($data['condition']) ? $data['condition'] : array();
        $conditions = array('is_valid' => $is_valid);
        $like = array();
        if (isset($cond['type']) && $cond['type'] && isset($cond['keywords']) && $cond['keywords']) {
            $like = array('like' => array($cond['type'], $cond['keywords']));
        } else {
            if (isset($cond['type']) && $cond['type'] && $cond['type'] != '') {
                $conditions = array_merge($conditions, array('type' => $cond['type']));
            }
            if (isset($cond['is_public']) && $cond['is_public'] != '') {
                $conditions = array_merge($conditions, array('is_public' => $cond['is_public']));
            }
            if (isset($cond['is_shield']) && $cond['is_shield'] && $cond['is_shield'] != '') {
                $conditions = array_merge($conditions, array('is_shield' => $cond['is_shield']));
            }
            if (isset($cond['replyContent']) && $cond['replyContent'] && intval($cond['replyContent']) == 1) {
                $conditions = array_merge($conditions, array('replyContent !=' => ''));
            }
            if (isset($cond['replyContent']) && $cond['replyContent'] && intval($cond['replyContent']) == 0) {
                $conditions = array_merge($conditions, array('replyContent' => null));
            }
            if (isset($cond['startTime']) && $cond['startTime'] && $cond['startTime'] != '') {
                $conditions = array_merge($conditions, array('create_time >=' => $cond['startTime']));
            }
            if (isset($cond['endTime']) && $cond['endTime'] && $cond['endTime'] != '') {
                $conditions = array_merge($conditions, array('create_time <=' => $cond['endTime']));
            }
        }
        $lists = $this->getData(array_merge(array(
            'fields' => '*',
            'table' => 'comments',
            'conditions' => $conditions,
            'order' => array('iTime', 'desc'),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
                        ), $like));
        if($lists){
            foreach($lists as &$item){
                $item['create_time'] = date('Y-m-d H:i:s',$item['iTime']);
            }
        }
        $count = $this->getData(array_merge(array(
            'table' => 'comments',
            'conditions' => $conditions,
            'count' => true,
                        ), $like));
        return array('data' => $lists, 'count' => $count);
    }

    /**
     * getCommentRecycleList
     * 简介：读取留言列表(回收站)
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function getCommentRecycleList($data) {
        return $this->getCommentList($data, 0);
    }

    /**
     * getComment
     * 简介：根据id读取留言全部信息
     * 参数：$id int id
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/23
     */
    function getComment($id) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'comments',
                    'conditions' => array('id' => $id),
                    'row' => true
        ));
    }

    /**
     * replyComment
     * 简介：回复留言
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/11/23
     */
    function replyComment($id, $is_shield, $replyContent) {
        return $this->db->update('comments', array('is_shield' => $is_shield, 'replyContent' => $replyContent), array('id' => $id));
    }

    /**
     * delProduct
     * 简介：删除(放入回收站)留言
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/8
     */
    function delComment($id) {
        $data = array(
            'is_valid' => 0
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('comments', $data);
        } else {
            return $this->db->update('comments', $data, array('id' => $id));
        }
    }

    /**
     * dumpComment
     * 简介：删除(彻底清除)留言信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/8
     */
    function dumpComment($id) {
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->delete('comments');
        } else {
            return $this->db->delete('comments', array('id' => $id));
        }
    }

    /**
     * recoverComment
     * 简介：还原文档信息
     * 参数：$id int 新数据
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-12
     */
    function recoverComment($id) {
        $data = array(
            'is_valid' => 1
        );
        if (is_array($id)) {
            $this->db->where_in('id', $id);
            return $this->db->update('comments', $data);
        } else {
            return $this->db->update('comments', $data, array('id' => $id));
        }
    }

    /**
     * getClassifyList
     * 简介：读取技术文档
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-25
     */
    function getClassifyList() {
        $data = $this->getData(array(
            'fields' => 't.*',//,a.username
            'table' => 'term t',
//            'join' => array('admin a', 'a.id=t.owner'),
            'conditions' => array('t.subclass' => 0, 't.lang' => _LANGUAGE_),
            'orders' => array('t.parent,t.taxonomy,t.create_time', 'asc,asc,asc'),
        ));
        $term = array();
        foreach ($data as $key => $item) {
            if ($item['parent'] == 0) {
                $term[$item['id']] = $item;
            } else {
                if (isset($term[$item['parent']])) {
                    $gdata = $this->getData(array(
                        'fields' => 't.*',//,a.username
                        'table' => 'term t',
//                        'join' => array('admin a', 'a.id=t.owner'),
                        'conditions' => array('t.parent' => $item['id'], 't.lang' => _LANGUAGE_),
                        'orders' => array('t.create_time', 'asc'),
                    ));
                    if (!empty($gdata)) {
                        $item['grandson'] = $gdata;
                    }
                    $term[$item['parent']]['sunTerm'][] = $item;
                }
            }
        }
        rsort($term);
        return array('data' => $term, 'count' => 1);
    }

    /**
     * getTfilesTermByTaxonomy
     * 简介：根据$taxonomy读取第一级分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-25
     */
    function getTfilesTermByTaxonomy($taxonomy) {
        return $this->getData(array(
                    'fields' => 'id,name',
                    'table' => 'term',
                    'conditions' => array('is_valid' => 1, 'parent' => 0, 'taxonomy' => $taxonomy, 'lang' => _LANGUAGE_),
        ));
    }

    /**
     * getClassifyListById
     * 简介：根据ID读取文档分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-26
     */
    function getClassifyListById($id) {
        return $this->getData(array(
                    'fields' => '*',
                    'table' => 'term',
                    'conditions' => array('is_valid' => 1, 'parent' => $id, 'lang' => _LANGUAGE_),
        ));
    }

    /**
     * editTerm
     * 简介：修改分类信息
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-27
     */
    function editTerm($data) {
        $term = $this->getData(array(
            'fields' => 'slug,taxonomy',
            'table' => 'term',
            'conditions' => array('id' => $data['term_id'], 'lang' => _LANGUAGE_),
            'row' => true
        ));
        if ($term['slug'] != $data['val'] && ($term['taxonomy'] == 'company' || $term['taxonomy'] == 'market')) {
            $oldFile = 'application/' . APPLICATION . '/views/default/' . $term['slug'] . '.php';
            $newFile = 'application/' . APPLICATION . '/views/default/' . $data['val'] . '.php';
            if (file_exists($oldFile)) {
                if (!rename($oldFile, $newFile)) {
                    return false;
                }
            }
        }
        if (intval($data['pid']) == 0 && $data['act'] == 'is_valid') {
            $this->db->update('term', array($data['act'] => $data['val']), array('parent' => $data['term_id']));
        }
        return $this->db->update('term', array($data['act'] => $data['val']), array('id' => $data['term_id']));
    }

    /**
     * delTerm
     * 简介：根据id彻底删除子分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function delTerm($id, $p) {
        $files = $this->getData(array(
            'fields' => 'banner',
            'table' => 'term',
            'conditions' => array('id' => $id, 'lang' => _LANGUAGE_),
            'row' => true
        ));
        if ($files['banner'] && file_exists($files['banner'])) {
            unlink($files['banner']);
        }
        if ($p) {
            $files = $this->getData(array(
                'fields' => 'banner',
                'table' => 'term',
                'conditions' => array('parent' => $id, 'lang' => _LANGUAGE_)
            ));
            if (!empty($files)) {
                foreach ($files as $item) {
                    if ($item['banner'] && file_exists($item['banner'])) {
                        unlink($item['banner']);
                    }
                }
            }
        }
        $this->db->trans_start();
        $this->db->delete('term', array('id' => $id));
        $this->db->delete('term', array('parent' => $id));
        return $this->db->trans_complete();
    }

    /**
     * addTerm
     * 简介：添加子分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/11/27
     */
    function addTerm($data) {
        return $this->db->insert_batch('term', $data);
    }

    /**
     * iUpdate
     * 简介：更改表信息
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012/12/15
     */
    function iUpdate($data) {
        $this->db->set($data['field'], $data['val'], false);
        $this->db->where('id', $data['id']);
        return $this->db->update($data['table']);
    }

    /**
     * addBanner
     * 简介：修改Banner
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-25
     */
    function addBanner($id, $banner) {
        $term = $this->getData(array(
            'fields' => 'banner',
            'table' => 'term',
            'conditions' => array('id' => $id, 'lang' => _LANGUAGE_),
            'row' => true
        ));
        if ($term['banner'] && file_exists($term['banner'])) {
            unlink($term['banner']);
        }
        return $this->db->update('term', array('banner' => $banner), array('id' => $id));
    }

    /**
     * getTermByTaxonomy
     * 简介：读取分类
     * 参数：null
     * 返回：Boole
     * 作者：Fzhao
     * 时间：2012-11-25

      function getTermByTaxonomy($taxonomy){
      $data = $this->getData(array(
      'fields' 	 => 'id,name,parent,slug,count,subclass',
      'table'  	 => 'term',
      'conditions' => array('is_valid'=>1,'taxonomy'=>$taxonomy),
      'orders'		 => array('parent,id','asc,asc'),
      ));

      $term = $sunTerm = array();
      foreach($data as $key=>$item){
      if($item['parent']==0){
      $term[$item['id']] = $item;
      }else{
      if(isset($term[$item['parent']])){
      $item['grandson'] = array();
      $term[$item['parent']]['sunTerm'][] = $item;
      $sunTerm[$item['id']] = array($item['parent'],count($term[$item['parent']]['sunTerm'])-1);
      }else if(isset($sunTerm[$item['parent']])){
      $term[$sunTerm[$item['parent']][0]]['sunTerm'][$sunTerm[$item['parent']][1]]['grandson'][] = $item;
      array_sort($term[$sunTerm[$item['parent']][0]]['sunTerm'][$sunTerm[$item['parent']][1]]['grandson'],'subclass');
      }
      }
      }
      rsort($term);
      return $term;
      } */

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
     * getJobList
     * 简介：读取岗位列表
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function getJobList($data) {
        $data = $this->getData(array(
            'fields' => '*',
            'table' => 'job',
            'conditions' => array('is_valid' => 1, 'lang' => _LANGUAGE_),
            'limit' => array($data['rows'], $data['rows'] * ($data['currPage'] - 1)),
        ));
        $count = $this->getData(array(
            'table' => 'job',
            'conditions' => array('is_valid' => 1, 'lang' => _LANGUAGE_),
            'count' => true,
        ));
        return array('data' => $data, 'count' => $count);
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

    public function _get_menu_tree_html($data, $pId = 0, $deep = 0, $total = 0) {
        $html = '';
        $deepClass = isset($this->menuTreesClass[$deep]) ? $this->menuTreesClass[$deep] : '';
        foreach ($data as $v) {
            if ($v['pid'] == $pId) {//父亲找到儿子
                $link = (!$v['link'] || $v['link'] == '#') ? '#' : base_url($v['link']);
                $html .= '<tr' . ($total % 2 == 0 ? ' class="even"' : '') . ' _pid="' . $v['pid'] . '" _title="' . $v['title'] . '" _show="' . $v['show'] . '" _sort="' . $v['sort'] . '" _link="' . $v['link'] . '" _link_target="' . $v['link_target'] . '" _parameter="' . $v['parameter'] . '">';
                $html .= '  <td class="' . $deepClass . '" valign="left"><font>' . $v['title'] . '</font></td>';
                $html .= '  <td>' . ($v['show'] ? $v['sort'] : '0') . '</td>';
                $html .= '  <td>' . ($v['show'] ? '显示' : '隐藏') . '</td>';
                $html .= '  <td>' . ($v['link_target'] ? $v['link_target'] : '默认') . '</td>';
                $html .= '  <td><a href="' . $link . '" target="_blank" style="color:#339900;">' . $link . '</a></td>';
                $html .= '  <td>' . ($v['parameter'] ? $v['parameter'] : '-') . '</td>';
                $html .= '  <td class="menu_action" id="' . $v['id'] . '"><a href="javascript:;" class="menu_add" act="add" title="添加子菜单"></a><a href="javascript:;" class="menu_edit" act="edit" title="编辑菜单"></a><a href="javascript:;" class="menu_del" act="del" title="删除菜单"></a></td>';
                $html .= '</tr>';

                ++$total;
                $html .= $this->_get_menu_tree_html($data, $v['id'], $deep + 1, $total);
            }
        }
        return $html;
    }

}
