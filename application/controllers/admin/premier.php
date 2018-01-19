<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Premier extends Fzhao_Controller {

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/premier_model', 'admin');
        $this->title = '管理员';
    }

    /**
     * news
     * 简介：新闻资讯
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function index() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->lists($data);
            $this->doJson($result);
        } else {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $data['roles'] = $this->admin->getRoles();
            $this->view('admin/' . strtolower(__CLASS__), $data);
        }
    }

    private function _verifyForm($info = array()) {
        $data = array();

        $data['role_id'] = intval($this->input->post('role_id', true));
        if (!$data['role_id']) {
            $this->_doIframe('所属角色不能为空', 0);
        }
        $data['username'] = trim($this->input->post('username', true));
        if (!$data['username']) {
            $this->_doIframe('登录名不能为空', 0);
        }
        if ($info) {
            $result = $this->admin->getRowByTitle($data['username'], array(array('id!=' => $info['id'])));
        } else {
            $result = $this->admin->getRowByTitle($data['username']);
        }
        if ($result) {
            $this->_doIframe('登录名已存在', 0);
        }
        $data['nickname'] = trim($this->input->post('nickname', true));
        if (!$data['nickname']) {
            $this->_doIframe('真实姓名不能为空', 0);
        }
        $data['password'] = trim($this->input->post('password', true));
        if (!$data['password']) {
            $this->_doIframe('密码不能为空', 0);
        }
        $data['repassword'] = trim($this->input->post('repassword', true));
        (!$info && !$data['repassword']) && $this->_doIframe('确认密码不能为空！', 0);

        if ($data['password'] != $data['repassword']) {
            $this->_doIframe('两次密码不相同！', 0);
        }
        unset($data['repassword']);
        if ($data['password'] && $data['password'] != '******') {
            $this->load->helper('string');
            $random_str = random_string('alnum', 6);
            $data['salt'] = $random_str;
            $data['password'] = encryption($data['password'] . $random_str);
        } else {
            unset($data['password']);
        }
        $data['branch'] = trim($this->input->post('branch', true));
        $data['email'] = trim($this->input->post('email', true));
        $data['phone'] = trim($this->input->post('phone', true));
        $data['mobile'] = trim($this->input->post('mobile', true));
        $data['qq'] = trim($this->input->post('qq', true));
        $data['weixin'] = trim($this->input->post('weixin', true));
        $data['describe'] = htmlspecialchars(trim($this->input->post('describe', true)));
        $data['sort'] = intval($this->input->post('sort', true));

        return $data;
    }

    /**
     * edit
     * 简介：修改新闻资讯
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function edit() {
        $data = array();
        $id = post_get('id');
        $this->verify($id);
        $info = $this->admin->getRowById($id);
        $this->verify($info);
        if (IS_POST) {
            $fields = $this->_verifyForm($info);
            $fields['update_time'] = _DATETIME_;

            $result = $this->admin->edit($fields, $id);
            $this->doIframe($result);
        }

        $data['data'] = $info;
        $data['title'] = $this->title . '编辑 - ' . $info['nickname'];
        $data['_title_'] = $this->title;
        $data['roles'] = $this->admin->getRoles();
        $this->view('admin/' . strtolower(__CLASS__) . '_edit', $data);
    }

    /**
     * add
     * 简介：添加
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function add() {
        $data = array();
        if (IS_POST) {
            $fields = $this->_verifyForm();
            $fields['update_time'] = _DATETIME_;
            $fields['create_time'] = _DATETIME_;
            $fields['uid'] = ADMIN_ID;
            $fields['lang'] = _LANGUAGE_;

            $result = $this->admin->add($fields);

            $this->doIframe($result);
        }
        $data['title'] = $this->title . '添加';
        $data['_title_'] = $this->title;
        $data['roles'] = $this->admin->getRoles();
        $this->view('admin/' . strtolower(__CLASS__) . '_add', $data);
    }

    /**
     * recycles
     * 简介：回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function recycles() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->recycles($data);
            $this->doJson($result);
        } else {
            $data['title'] = $this->title . '回收站';
            $data['_title_'] = $this->title;
            $data['roles'] = $this->admin->getRoles();
            $this->view('admin/' . strtolower(__CLASS__) . '_recycles', $data);
        }
    }

    /**
     * del
     * 简介：删除(放入回收站)
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function del() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->del($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dump
     * 简介：彻底删除信息
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function dump() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->dump($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recover
     * 简介：还原信息
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function recover() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->recover($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * regular
     * 简介：管理员角色列表
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    public function regular() {
        if (!IS_POST) {
            $roleId = intval($this->input->get('roleId', true));
            $this->load->model('admin/permission_model', 'permission');
            $data = $this->permission->edit_role($roleId, array());

            $data['roles'] = $this->admin->getData(array(
                'fields' => '*',
                'table' => 'admin_role',
                '_conditions' => array(array('isHidden' => '0')),
            ));
            $data['roleId'] = $roleId;

            $data['title'] = '权限分配';
            $data['_title_'] = '权限分配';
            $this->view('admin/regular', $data);
            return true;
        }
        $roleId = intval($this->input->post('roleId', true));
        if (!$roleId) {
            errorOutput('参数错误');
        }
        $role = array();
        $role_name = trim($this->input->post('role_name', true));
        if($roleId == '-1' && !$role_name){
            errorOutput('新建角色时角色名称不能为空');
        }
        if($roleId != '-1'){
            $role = $this->admin->getData(array(
                'fields' => '*',
                'table' => 'admin_role',
                '_conditions' => array(array('isHidden' => '0'), array('id' => $roleId)),
                'row' => true
            ));
            if (!$role) {
                errorOutput('角色不存在');
            }
        }
        if($role){
            $this->admin->dbUpdate('admin_role',array('role_name'=>$role_name),array('id'=>$roleId));
        }else{
            $roleData = array(
                'role_name'=>$role_name,
                'create_time' => _DATETIME_,
                'update_time' => _DATETIME_,
                'uid' => ADMIN_ID,
                'lang' => _LANGUAGE_
            );
            $roleId = $this->admin->dbInsert('admin_role',$roleData,true);
        }
        $regulars = $this->input->post('regular', true);
        $menus = array();
        if ($regulars) {
            $roleIds = array_values($regulars);
            $menus = $this->admin->getData(array(
                'fields' => 'id,m,c,a',
                'table' => 'menus',
                '_conditions' => array(array('status' => '1')),
                'ins' => array(array('id' => $roleIds)),
            ));
        }
        $privs = array();
        if ($menus) {
            foreach ($menus as $item) {
                $privs[] = array(
                    'role_id' => $roleId,
                    'm' => $item['m'],
                    'c' => $item['c'],
                    'a' => $item['a'],
                    'menu_id' => $item['id']
                );
            }
        }
        $this->admin->trans_start();
        $this->admin->dbDelete('priv', array('role_id' => $roleId));
        $privs && $this->admin->dbInsertBatch('priv', $privs);
        $this->admin->trans_complete();
        successOutput(array(),$roleId);
    }

}
