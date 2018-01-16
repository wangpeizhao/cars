<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * News_model
 * 简介：登录后台管理数据库模型
 * 返回：Boole
 * 作者：Parker
 * 时间：2018-01-13
 * 
 */
class Login_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'admin';
        $this->primary_key = $this->dbPrimary($this->table);
    }

    /**
     * checkAdmin
     * 简介：验证管理员登录信息并返回查询结果
     * 参数：$data array 登录信息
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function checkAdmin($data) {
        $this->db->select('*');
        $this->db->from('admin');
        //$this->db->join('group', 'admin.grade = group.groupid');
        $array = array('username' => $data['username'], 'isHidden' => '0'); //, 'password' => encryption($data['password'])
        $this->db->where($array);
        $query = $this->db->get();
        $userInfo = $query->row_array();
        if (empty($userInfo)) {
            return false;
        }
        if ($userInfo['password'] != encryption($data['password'].$userInfo['salt'])) {
            return false;
        }

        $group = $this->getData(array(
            'fields' => '*',
            'table' => 'group',
            '_conditions' => array(array('groupid' => intval($userInfo['role_id'])), array('isHidden' => '0')),
            'row' => true
        ));
        if (!$group) {
            return false;
        }
        $userInfo['supervisor'] = $group['supervisor'];
        $userInfo['grouptitle'] = $group['grouptitle'];

        $privs = $this->getData(array(
            'fields' => '*',
            'table' => 'priv',
            'conditions' => array('role_id' => intval($userInfo['role_id']))
        ));
        $authorized = array();
        if ($privs) {
            foreach ($privs as $item) {
                if (!$item['m']) {
                    continue;
                }
                $authorized[] = '/' . $item['m'] . '/' . $item['c'] . '/' . $item['a'] . ($item['p'] ? '|' . $item['p'] : '');
            }
        }
        $userInfo['authorized'] = array_values(array_filter(array_unique($authorized)));
        $updateData = array(
            'last_login_time' => _DATETIME_,
            'last_login_ip' => real_ip()
        );
        $this->db->update('admin', $updateData, array('id' => $userInfo['id']));
        return $userInfo;
    }

}
