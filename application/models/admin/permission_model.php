<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class permission_model extends Fzhao_Model {

    private $TreesClass;
    private $companyComponents; //已分配给公司的字段
    private $specifiedPermissions; //已分配的权限
    private $specifiedComponents; //已分配给角色的字段

    public function __construct() {
        parent::__construct();
        //Class
        $this->TreesClass = array('first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth');
        $this->specifiedPermissions = array();
    }

    public function add_role($company_id, $companyInfo) {
        $data = array();
        if(defined('ADMINISTRATOR_PERMISSION')){
            $menus = $this->getData(array(
                'fields' => 'id,pid,title,permissions,model',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => 'premier')),
                //'ins' => array(array('id'=>$menu_ids)),
                'order' => array(array('pid' => 'ASC'), array('sort' => 'ASC'), array('id' => 'ASC')),
            ));
        }else if(defined('COMPANY_TEMPLATE')){
            $where = '';
            if(defined('BASIC_DATA_SOURCE_ID')){
                $where .= '(id IN(';
                $conditions = array(array('role_id' => '0'), array('ctId' => BASIC_DATA_SOURCE_ID));
                $companyInfo && $conditions[] = array('oid' => $company_id);
                $where .= $this->getData(array(
                    'fields' => 'menu_id',
                    'table' => $companyInfo?'admin_priv_template':'admin_priv',
                    'conditions' => $conditions,
                    'compiled' => true
                ));
                $where .= '))';
            }
            $menus = $this->getData(array(
                'fields' => 'id,pid,title,permissions,model',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => 'admin')),
                //'ins' => array(array('id'=>$menu_ids)),
                'where' => $where,
                'order' => array(array('pid' => 'ASC'), array('sort' => 'ASC'), array('id' => 'ASC')),
            ));
            //model
            if(defined('BASIC_DATA_SOURCE_ID')){
                $this->_check_bind_model($menus, BASIC_DATA_SOURCE_ID);
                $this->set_menu_title($menus, BASIC_DATA_SOURCE_ID);
            }else{
                $this->_check_bind_model($menus, 1);
            }
        }else{
            $data['companyInfo'] = $companyInfo;

            //已分配的权限
            $menu_ids = $this->getData(array(
                'fields' => 'menu_id',
                'table' => 'admin_priv',
                'conditions' => array(array('role_id' => 0), array('ctId' => $company_id)),
                'assoc' => array('menu_id' => 'menu_id')
            )); //ww($this->last_query());
            
            if(!$menu_ids){
                return array('error' => '请先分配权限给该公司');
            }

            $menus = $this->getData(array(
                'fields' => 'id,pid,title,permissions,model',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => 'admin')),
                'ins' => array(array('id' => $menu_ids)),
                'order' => array(array('pid' => 'ASC'), array('sort' => 'ASC'), array('id' => 'ASC')),
            ));
            $this->set_menu_title($menus, $company_id);

            //model
            $this->_check_bind_model($menus, $company_id);
            //ww($menus);
            //Class
        }

        $specifiedComponents = array();
        $specifiedComponents['add'] = array();
        $specifiedComponents['edit'] = array();
        $specifiedComponents['view'] = array();
        $specifiedComponents['export'] = array();
        $specifiedComponents['all'] = array();
        $this->specifiedComponents = $specifiedComponents;

        $permissions_lists = $this->_get_permissions_tree_html($menus);
        $data['permissions_lists'] = $permissions_lists;
        if(!defined('ADMINISTRATOR_PERMISSION') && !defined('COMPANY_TEMPLATE')){
            $floors = $this->getData(array(
                'fields' => 'id,name',
                'table' => 'store_region',
                'conditions' => array(array('isHidden' => '0'), array('cId' => $company_id), array('type' => 'floor')), //,array('floor !=' => '2')
                'order' => array(array('sort' => 'asc'), array('id' => 'asc')),
            ));
//            if (!$floors) {
//                $FLOOR = $this->config->item('floor');
//                $_floors = $FLOOR ? explode(",", $FLOOR) : array(0);
//                foreach ($_floors as $item) {
//                    $floors[] = array('id' => $item, 'name' => $item . '楼');
//                }
//            }
            $data['floors'] = $floors;
        }
        return $data;
    }

    public function edit_role( $role_id, $roleInfo) {
        $data = array();

        $data['roleInfo'] = $roleInfo;

        $menus = $this->getData(array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(array('status' => '1'), array('plat' => 'admin')),
            '_order' => array(array('pid' => 'ASC'), array('sort' => 'ASC'), array('id' => 'ASC')),
        ));

        //已分配的权限
        $specifiedPermissions = $this->getData(array(
            'fields' => 'menu_id',
            'table' => 'priv',
            '_conditions' => array(array('role_id' => $role_id)),
        ));
        $this->specifiedPermissions = array_column($specifiedPermissions,'menu_id');

        $permissions_lists = $this->_get_permissions_tree_html($menus);
        $data['permissions_lists'] = $permissions_lists;

        return $data;
    }

    private function _get_permissions_tree_html($data, $pId = 0, $deep = 0) {
        $html = '';
        $deepClass = isset($this->TreesClass[$deep]) ? $this->TreesClass[$deep] : '';
        foreach ($data as $v) {
            if ($v['pid'] == $pId) {//父亲找到儿子
                $checked = in_array($v['id'], $this->specifiedPermissions) ? ' checked' : '';
                $dn = in_array($v['id'], $this->specifiedPermissions) ? '' : ' dn';

                $html .= '<div class="' . $deepClass . '_level clearfix">';
                $html .= '  <div class="' . $deepClass . '_left">';
                $html .= '      <div class="selected">'
                        . '<input type="checkbox" name="permissions[' . $v['pid'] . '][' . $v['id'] . ']" value="' . $v['id'] . '" cid="' . $v['id'] . '" pid="' . $v['pid'] . '" '
                        . 'class="dn"' . $checked . ' param="' . (isset($v['parameter']) ? $v['parameter'] : '') . '">'
                        . '<button type="button" class="btn_perm" title="' . $v['title'] . '">' . $v['title'] . '</button>'
                        . '<i class="iconfont-add' . $dn . '">✓</i>'
                        . '</div>';
                $html .= '          <a class="js-toggle toggle addImg"> </a>';
                $html .= '      </div>';
                $html .= '  <div class="' . $deepClass . '_right js-content dn">';
                $html .= $this->_get_permissions_tree_html($data, $v['id'], $deep + 1);
                $html .= '  </div>';
                $html .= '</div>';
            }
        }
        return $html;
    }

    private function _get_components_tree_html($childs, $type) {
        if (!$childs) {
            return false;
        }
        $html = '<ul class="clearfix components">';
        foreach ($childs as $item) {
            if (!defined('COMPANY_PERMISSION') && (!in_array($item['cid'], $this->companyComponents[$type]))) {
                continue;
            }
            $checked = (in_array($item['cid'], $this->specifiedComponents[$type]) ? ' checked' : '');
            $dn = (in_array($item['cid'], $this->specifiedComponents[$type]) ? '' : ' dn');
            $html .= '<li>';
            $html .= '    <div><div class="selected">';
            $html .= '    <input type="checkbox" name="components[' . $item['model'] . '][' . $item['pid'] . '][' . $type . '][' . $item['cid'] . ']" value="' . $item['cid'] . '" '
                    . 'cid="' . $item['cid'] . '" pid="' . $item['pid'] . '" class="dn"' . $checked . '>';
            $html .= '    <button type="button" class="btn_perm">' . $item['field_label'] . '</button>';
            $html .= '    <i class="iconfont-add' . $dn . '">&#xe65e;</i>';
            $html .= '    </div></div>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
    
    private function _validate_template(&$fields,$edit = false){
        $fields['name'] = trim($this->input->post('name', true));
        $fields['describe'] = trim($this->input->post('describe', true));
        if (!$fields['name']) {
            $this->_doIframe('模板名称不能为空', 0);
        }
        $conditions = array();
        $conditions[] = array('isHidden' => '0');
        $conditions[] = array('name' => $fields['name']);
        if ($edit) {
            $conditions[] = array('name !=' => $edit['name']);
        }
        $temp = $this->getData(array(
            'fields' => 'id',
            'table' => 'company_template',
            'conditions' => $conditions,
            'row' => true,
        ));
        if ($temp) {
            $this->_doIframe('模板名称已存在,请确认', 0);
        }
        if(!$edit){
            $minTid = $this->getData(array(
                'fields' => 'min(tid) tid',
                'table' => 'company_template',
                'conditions' => array(array('isHidden' => '0')),
                'row' => true,
            ));
            $fields['tid'] = $minTid?$minTid['tid']:0;
        }
    }
    
    public function _set_permissions_template($edit = null){
        $postData = $this->input->post(null, true);
        $permissions = isset($postData['permissions']) ? $postData['permissions'] : array();
        $components = isset($postData['components']) ? $postData['components'] : array();
        if (!$permissions) {
            $this->_doIframe('您未选择任何权限，请选择后提交！', 0);
        }
        $fields = array();
        $this->_validate_template($fields,$edit);
        
        $id = 0;
        $cId = defined('BASIC_DATA_SOURCE_ID')?BASIC_DATA_SOURCE_ID:1;
        $company_id = 0;
        //Enable transaction
        $this->trans_start();
        if(!$edit){
            $company_id = $fields['tid']-1;
            $insertData = array(
                'name' => $fields['name'],
                'tid' => $company_id,
                'oid' => $cId,
                'describe' => $fields['describe'],
                'uid' => ADMIN_ID, 
                'create' => SYSTEM_TIME, 
                'update' => SYSTEM_TIME
            );
            $id = $this->dbInsert('company_template', $insertData, true);
        }else{
            $id = intval($edit['id']);
            $company_id = intval($edit['tid']);
            $updateData = array(
                'name' => $fields['name'], 
                'describe' => $fields['describe'],
                'update' => SYSTEM_TIME
            );
            $this->dbUpdate('company_template', $updateData, array('id' => $id));
        }
        //同步相关数据
        if(!$edit){
            $this->load->model('companys_model', 'companys'); //加载Model
            $this->companys->_copy_fields_from_baima($company_id,$cId,false);
        }
        
        $company_ids = $this->_get_company_ids($company_id,$edit);
        sort($company_ids);
        //操作权限
        $menus = array();
        if ($permissions) {
            $mids = array(0);
            foreach ($permissions as $items) {
                if (!$items) {
                    continue;
                }
                foreach ($items as $item) {
                    $mids[] = $item;
                }
            }
            
            //菜单信息
            $where = '';
            if(defined('BASIC_DATA_SOURCE_ID')){
                $where .= '(id IN(';
                $conditions = array(array('role_id' => '0'), array('ctId' => $cId));
                $edit && $conditions[] = array('oid' => $company_id);
                $where .= $this->getData(array(
                    'fields' => 'menu_id',
                    'table' => $edit?'admin_priv_template':'admin_priv',
                    'conditions' => $conditions,
                    'compiled' => true
                ));
                $where .= '))';
            }
            $allMenus = $this->getData(array(
                'fields' => 'id,pid,m,c,a,power_parameter p,link,show,sort',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => 'admin')),
                //'ins' => array(array('id' => $mids)),
                'where' => $where,
                'order' => array(array('pid' => 'asc'), array('sort' => 'asc'))
            ));
            //wwww($this->last_query());
            if ($allMenus) {
                foreach ($allMenus as $item) {
                    in_array($item['id'], $mids) && $menus[$item['id']] = $item;
                }
            }
            
            //菜单更名
            if(!$edit){
                $admin_menu_name = $this->getData(array(
                    'fields' => '*',
                    'table' => 'admin_menu_name',
                    'conditions' => array(array('cId' => $cId)),
                ));
                if($admin_menu_name){
                    foreach($admin_menu_name as &$_item){
                        $_item['cId'] = $company_id;
                    }
                    $this->dbInsertBatch('admin_menu_name', $admin_menu_name);
                }
            }
            
            //复制模板(因为模板是基于某个现有的公司新建的,为了方便日后模板增加权限,特别的复制出来)
            if(!$edit){
                $admin_priv = $this->getData(array(
                    'fields' => '*',
                    'table' => 'admin_priv',
                    'conditions' => array(array('role_id' => '0'), array('ctId' => $cId)),
                ));
                $admin_privs = array();
                foreach($admin_priv as $item){
                    //unset($item['priv_id']);
                    $item['oid'] = $company_id;
                    $admin_privs[] = $item;
                }
                $admin_privs && $this->dbInsertBatch('admin_priv_template', $admin_privs);
            }
            
            foreach($company_ids as $_company_id){
                //当前菜单
                $cur_menus = array();
                if ($edit) {
                    $cur_menus = $this->getData(array(
                        'fields' => 'menu_id',
                        'table' => 'admin_priv',
                        'conditions' => array(array('role_id' => 0), array('ctId' => $_company_id)),
                    ));
                    //$curr_mids = array_column($_curr_mids,'menu_id');
                }

                //特殊处理菜单链接重复的数据:要么同时分配,要么同时不分配,由后端处理,前端不处理
                special_handling_repeated_menus($allMenus, $menus, $cur_menus);

                //先删除该公司的所有已分配的权限
                $edit && $this->dbDelete('admin_priv', array('role_id' => 0, 'ctId' => $_company_id));
                //插入新权限数据
                if ($menus) {
                    $newPrivs = array();
                    foreach ($menus as $item) {
                        $newPriv = array(
                            'role_id' => 0,
                            'm' => $item['m'],
                            'c' => $item['c'],
                            'a' => $item['a'],
                            'p' => $item['p'],
                            'menu_id' => $item['id'],
                            'ctId' => $_company_id,
                        );
                        $newPrivs[] = $newPriv;
                    }
                    $newPrivs && $this->dbInsertBatch('admin_priv', $newPrivs);
                }
                ($mids && $edit) && $this->dbDeleteNotIn('admin_priv', array('role_id >' => 0, 'm' => 'admin', 'ctId' => $_company_id), array('menu_id' => $mids));
            }
        }
        
        //字段权限
        if ($components) {
            
            //复制模板(因为模板是基于某个现有的公司新建的,为了方便日后模板增加权限,特别的复制出来)
            if(!$edit){
                $modules_permission = $this->getData(array(
                    'fields' => '*',
                    'table' => 'modules_permissions',
                    'conditions' => array(array('rid' => '0'), array('cid' => $cId)),
                ));
                $modules_permissions = array();
                foreach($modules_permission as $item){
                    $item['oid'] = $company_id;
                    $modules_permissions[] = $item;
                }
                $modules_permissions && $this->dbInsertBatch('modules_permissions_template', $modules_permissions);
            }
            foreach($company_ids as $_company_id){
                $nodes = $this->_get_model_nid($_company_id);
                $conditions = array();
                $conditions[] = array('company_id' => $edit?$_company_id:$cId);
                $conditions[] = array('is_delete' => 0);
                $all_components = $this->getData(array(
                    'fields' => 'cid,field_key_code',
                    'table' => 'active_fields_components',
                    'conditions' => $conditions,
                    'assoc' => array('cid'=>'field_key_code')
                ));
                $edit && $this->dbDelete('modules_permissions', array('rid' => 0, 'cid' => $_company_id));
                foreach ($components as $model => $items_) {//model
                    $this->_do_components_template($all_components,$nodes,$model,$items_,$_company_id,$edit);
                }
            }
        }
        //Commit transaction
        $this->trans_complete();
        return $this->trans_status();
        
    }
    
    private function _get_company_ids($company_id,$edit){
        if(!$edit){
            return array($company_id);
        }
        $_cId = $this->getData(array(
            'fields' => 'cId',
            'table' => 'center',
            'conditions' => array(array('tId' => $company_id)),
        ));
        if(!$_cId){
            return array($company_id);
        }
        $company_ids = array_column($_cId,'cId');
        $company_ids[] = $company_id;
        return $company_ids;
    }
    
    private function _do_components_template($all_components,$nodes,$model,$items_,$company_id,$edit){
        $nid = isset($nodes[$model]) ? intval($nodes[$model]) : 0;
        if (!$items_ || !$nid) {
            return false;
        }

        $cids = array_keys($items_);
        $items = $this->_set_components_type_add_edit_view($items_);
        $_cids = $cids;
        foreach($items as $_item){
            $_cids = array_unique(array_merge($_cids,$_item));
        }
        $new_mp = $this->_set_modules_permissions_values(0, $nid, $company_id, $cids,$_cids,$edit?true:false);

        //$this->_format_items($items,$company_id,$_cids);

        foreach ($items as $type => $_items) {//type
            if (!$_items) {
                continue;
            }
            foreach ($_items as $item) {//cid
                if (!isset($all_components[$item]) || !isset($new_mp[$all_components[$item]])) {
                    continue;
                }
                $_fieldKey = $all_components[$item];
                $new_mp[$_fieldKey][$type] = '1';
                !in_array($item, $cids) && $cids[] = $item;
            }
        }
        if ($new_mp) {
            $this->dbInsertBatch('modules_permissions', array_values($new_mp));
            //$edit && $this->dbDeleteNotIn('modules_permissions', array('cid' => $company_id, 'nid' => $nid), array('fid' => $cids));
        }
    }
    
    private function _do_components($all_components,$nodes,$model,$items_,$company_id,$add,$role_id){
        $nid = isset($nodes[$model]) ? intval($nodes[$model]) : 0;
        if (!$items_ || !$nid) {
            return false;
        }

        $cids = array_keys($items_);

        $items = $this->_set_components_type_add_edit_view($items_);
        $_cids = $cids;
        foreach($items as $_item){
            $_cids = array_unique(array_merge($_cids,$_item));
        }
        $new_mp = $this->_set_modules_permissions_values($role_id, $nid, $company_id, $cids,$_cids,$add?false:true);
        
        foreach ($items as $type => $_items) {//type
            if (!$_items) {
                continue;
            }
            foreach ($_items as $item) {//cid
                if (!isset($all_components[$item]) || (defined('COMPANY_PERMISSION') && !isset($new_mp[$all_components[$item]]))) {
                    continue;
                }
                $_fieldKey = $all_components[$item];
                $new_mp[$_fieldKey][$type] = '1';
                !in_array($item, $cids) && $cids[] = $item;
            }
        }

        if ($new_mp) {
            $this->dbInsertBatch('modules_permissions', array_values($new_mp));
            defined('COMPANY_PERMISSION') && $this->dbDeleteNotIn('modules_permissions', array('cid' => $company_id, 'nid' => $nid), array('fid' => $cids));
        }
    }

    public function _set_permissions($role_id, $company_id, $add = false, $curr_role = array()) {
        if (!defined('ADMINISTRATOR_PERMISSION') && ((!$add && !$role_id && !defined('COMPANY_PERMISSION')) || !$company_id)) {
            return false;
        }
        $postData = $this->input->post(null, true);
        $permissions = isset($postData['permissions']) ? $postData['permissions'] : array();
        $components = isset($postData['components']) ? $postData['components'] : array();
        if (!$permissions) {
            $this->_doIframe('您未选择任何权限，请选择后提交！', 0);
        }

        //Enable transaction
        $this->trans_start();
        
        if(!defined('COMPANY_PERMISSION')){
            $role_name = trim($this->input->post('role_name', true));
            $sort = intval($this->input->post('sort', true));
            $purchaserABlock = trim($this->input->post('purchaserABlock', true));
            $managerABlock = trim($this->input->post('managerABlock', true));
            $tenantsABlock = trim($this->input->post('tenantsABlock', true));

            $floors = $this->input->post('floor', true);
            if ($floors) {
                $floors = implode(",", $floors);
            } else {
                $floors = '';
            }

            $conditions = array();
            $conditions[] = array('ctId' => $company_id);
            $conditions[] = array('role_name' => $role_name);
            if (!$add && $curr_role && $curr_role['role_name']) {
                $conditions[] = array('role_name !=' => $curr_role['role_name']);
            }

            $user_role = $this->getData(array(
                'fields' => 'role_id',
                'table' => 'user_role',
                'conditions' => $conditions,
                'row' => true,
            ));
            if ($user_role) {
                $this->_doIframe('角色名称已存在,请确认', 0);
            }
            if ($add) {
                //$floor = $this->config->item('floor');
                $insertData = array(
                    'role_name' => $role_name, 
                    'sort' => $sort, 
                    'ctId' => $company_id, 
                    'floor' => $floors, 
                    'purchaserABlock' => $purchaserABlock, 
                    'managerABlock' => $managerABlock, 
                    'tenantsABlock' => $tenantsABlock, 
                    'uid' => ADMIN_ID, 
                    'create' => SYSTEM_TIME, 
                    'update' => SYSTEM_TIME
                );
                $role_id = $this->dbInsert('user_role', $insertData, true);
            } else {
                $updateData = array(
                    'role_name' => $role_name, 
                    'sort' => $sort, 
                    'floor' => $floors, 
                    'purchaserABlock' => $purchaserABlock, 
                    'managerABlock' => $managerABlock, 
                    'tenantsABlock' => $tenantsABlock, 
                    'update' => SYSTEM_TIME
                );
                $this->dbUpdate('user_role', $updateData, array('role_id' => $role_id, 'ctId' => $company_id));
            }
        }else{
            $this->dbUpdate('center', array('update' => SYSTEM_TIME), array('isDelete' => '0', 'cId' => $company_id));
        }

        //操作权限
        $menus = array();
        if ($permissions) {
            $mids = array(0);
            foreach ($permissions as $items) {
                if (!$items) {
                    continue;
                }
                foreach ($items as $item) {
                    $mids[] = $item;
                }
            }
            
            //菜单信息
            $allMenus = $this->getData(array(
                'fields' => 'id,pid,m,c,a,power_parameter p,link,show,sort',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => defined('ADMINISTRATOR_PERMISSION')?'premier':'admin')),
                //'ins' => array(array('id' => $mids)),
                'order' => array(array('pid' => 'asc'), array('sort' => 'asc'))
            ));
            //wwww($this->last_query());
            if ($allMenus) {
                foreach ($allMenus as $item) {
                    if (in_array($item['id'], $mids)) {
                        $menus[$item['id']] = $item;
                    }
                }
            }
            
            if(defined('COMPANY_PERMISSION')){
                //为该公司创建一个超级管理员的用户组，并赋予该公司的全部权限
                //如果该公司的用户组为空，则为之创建一个超级管理员的用户组，并赋予该公司的全部权限
                $_role = $this->getData(array(
                    'fields' => 'role_id',
                    'table' => 'user_role',
                    'conditions' => array(array('ctId' => $company_id)),
                ));
                //$role_id = 0;
                if (!$_role) {
                    $FLOOR = $this->config->item('floor');
                    $insertData = array(
                        'role_name' => '系统管理员', 
                        'ctId' => $company_id, 
                        'floor' => $FLOOR?$FLOOR:'1,2,3,4,5,6,7,8,9',
                        'supervisor'=>'1',
                        'create' => time(),
                        'update' => time()
                    );
                    $role_id = $this->dbInsert('user_role', $insertData, true);
                }

                //先删除该公司的所有已分配的权限
                $this->dbDelete('admin_priv', array('role_id' => 0, 'ctId' => $company_id));
            }else{
                //当前菜单
                $cur_menus = array();
                if (!$add) {
                    $cur_menus = $this->getData(array(
                        'fields' => 'menu_id',
                        'table' => 'admin_priv',
                        'conditions' => array(array('role_id' => $role_id), array('ctId' => $company_id)),
                    ));
                    //$curr_mids = array_column($_curr_mids,'menu_id');
                }

                //特殊处理菜单链接重复的数据:要么同时分配,要么同时不分配,由后端处理,前端不处理
                special_handling_repeated_menus($allMenus, $menus, $cur_menus);

                //先删除该公司的所有已分配的权限
                !$add && $this->dbDelete('admin_priv', array('role_id' => $role_id, 'ctId' => $company_id));
            }

            //插入新权限数据
            if ($menus) {
                $newPrivs = array();
                foreach ($menus as $item) {
                    $newPriv = array(
                        'role_id' => defined('COMPANY_PERMISSION')?0:$role_id,
                        'm' => $item['m'],
                        'c' => $item['c'],
                        'a' => $item['a'],
                        'p' => $item['p'],
                        'menu_id' => $item['id'],
                        'ctId' => $company_id,
                    );
                    $newPrivs[] = $newPriv;
                }
                $newPrivs && $this->dbInsertBatch('admin_priv', $newPrivs);
            }
            if(defined('COMPANY_PERMISSION') && $mids){
                $this->dbDeleteNotIn('admin_priv', array('role_id >' => 0, 'm' => 'admin', 'ctId' => $company_id), array('menu_id' => $mids));
            }
        }

        //设置顶级菜单链接
        $this->_set_the_top_menu_hyperlinks($role_id, $menus, $company_id);

        !defined('ADMINISTRATOR_PERMISSION') && $this->dbDelete('modules_permissions', array('rid' => $role_id, 'cid' => $company_id));
        //字段权限
        if ($components) {

            $nodes = $this->_get_model_nid($company_id);
            $conditions = array();
            $conditions[] = array('company_id' => !$add?$company_id:1);
            $conditions[] = array('is_delete' => 0);
            $all_components = $this->getData(array(
                'fields' => 'cid,field_key_code',
                'table' => 'active_fields_components',
                'conditions' => $conditions,
                'assoc' => array('cid'=>'field_key_code')
            ));
            foreach ($components as $model => $items_) {//model
                $this->_do_components($all_components,$nodes,$model,$items_,$company_id,$add,$role_id);
            }
        }
        //Commit transaction
        $this->trans_complete();
        return $this->trans_status();
    }

    public function _set_the_top_menu_hyperlinks($role_id, $menus, $company_id) {
        if(!$menus){
            //菜单信息
            $get_menu_ids_sql = $this->getData(array(
                'fields' => 'menu_id',
                'table' => 'admin_priv',
                'conditions' => array(array('role_id' => 0), array('ctId' => $company_id)),
                //'ins'=>array(array('m'=>array('admin','premier'))),
                'compiled' => true
            ));
            $allMenus = $this->getData(array(
                'fields' => 'id,pid,m,c,a,power_parameter p,link,show,sort',
                'table' => 'admin_menu_new',
                'conditions' => array(array('status' => '1'), array('plat' => defined('ADMINISTRATOR_PERMISSION')?'premier':'admin')),
                'where' => $get_menu_ids_sql?('(id IN('.$get_menu_ids_sql.'))'):'',
                'order' => array(array('pid' => 'asc'), array('sort' => 'asc'))
            ));
            if (!$allMenus) {
                return false;
            }
            foreach ($allMenus as $item) {
                $menus[$item['id']] = $item;
            }
        }
        if ((!defined('COMPANY_PERMISSION') && !$role_id)) {
            return false;
        }
        $links = array();
        $this->_get_links_by_menus_tree($menus, 0, 0, $links);
        if ($links) {
            $conditions = array();
            $conditions['ctId'] = $company_id;
            if(defined('COMPANY_PERMISSION')){
                $conditions['supervisor'] = '1';
            }else{
                $conditions['role_id'] = $role_id;
            }
            $this->dbUpdate('user_role', array('hyperlinks' => serialize($links)), $conditions);
        }
        return true;
    }

    private function _get_links_by_menus_tree($data, $pId = 0, $deep = 0, &$links, $_pid = 0) {
        $tree = '';
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pId) {//父亲找到儿子
                if ($pId == 0) {
                    $_pid = $v['id'];
                    $links[$_pid] = '';
                }
                if ($deep == 2 && $v['show'] == 1 && empty($links[$_pid])) {
                    $links[$_pid] = $v['link'];
                }
                $v['deep'] = $deep + 1;
                $v['childs'] = $this->_get_links_by_menus_tree($data, $v['id'], $deep + 1, $links, $_pid);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    private function _set_components_type_add_edit_view($items) {
        if (!$items) {
            return false;
        }
        $newData = array();
        foreach ($items as $item) {
            foreach ($item as $k => $_item) {
                $newData[$k] = isset($newData[$k]) ? array_merge($newData[$k], $_item) : array_merge(array(), $_item);
            }
        }
        return $newData;
    }
    
    private function _format_items(&$items,$company_id,$_cids){
        $cur_field_key_codes = $this->_get_field_key_code_by_cids($_cids,$company_id);
        $_new_field_key_codes = $this->_get_field_key_code_by_cids($_cids,$company_id,$cur_field_key_codes);
        $new_field_key_codes = array_flip($_new_field_key_codes);

        foreach ($items as $type=>$item){
            foreach($item as $k=>$_item){
                if(empty($new_field_key_codes[$cur_field_key_codes[$_item]])){
                    unset($items[$type][$k]);
                    continue;
                }
                $items[$type][$k] = $new_field_key_codes[$cur_field_key_codes[$_item]];
            }
        }
    }
    
    private function _get_field_key_code_by_cids($_cids,$company_id,$reset = null){
        static $field_key_codes = array();
        $_cids_str = md5(implode(',',$_cids));
        if(!$reset && !empty($field_key_codes[$_cids_str]) && $company_id>0){
            return $field_key_codes[$_cids_str];
        }
        $ins = array();
        if(!$reset){
            $ins[] = array('cid' => $_cids);
        }else{
            $ins[] = array('field_key_code' => array_values($reset));
        }
        $components = $this->getData(array(
            'fields' => 'cid,field_key_code',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => 0),array('company_id'=>$company_id)),
            'ins' => $ins,
        ));
        if($reset){
            return array_column($components,'field_key_code','cid');
        }
        $field_key_codes[$_cids_str] = array_column($components,'field_key_code','cid');
        return $field_key_codes[$_cids_str];
    }
    
    private function _get_all_parent_components(&$all_components,&$pComponents,$nid,$company_id,&$pid,$_cids,$edit){
        $conditions = array();
        $conditions['nid'] = array('nid' => $nid);
        $conditions['company_id'] = array('company_id' => $company_id);
        !defined('COMPANY_PERMISSION') && $conditions[] = array('is_delete' => 0);
        $ins = array();
        //$edit && $ins[] = array('pid'=>$pid);
        $field_key_codes = $this->_get_field_key_code_by_cids($_cids,$company_id);
        ($edit && $field_key_codes) && $ins[] = array('field_key_code'=>array_values($field_key_codes));
        $all_components = $this->getData(array(
            'fields' => 'cid,field_key_code',
            'table' => 'active_fields_components',
            'conditions' => $conditions,
            'ins' => $ins,
            'assoc' => array('cid'=>'field_key_code')
        ));//ww($this->last_query());
        if(!$edit){
            unset($conditions['company_id'],$conditions['nid']);
            $conditions[] = array('pid' => 0);
            $conditions[] = array('company_id' => defined('BASIC_DATA_SOURCE_ID')?BASIC_DATA_SOURCE_ID:1);
            $_pComponents = $this->getData(array(
                'fields' => 'field_key_code',
                'table' => 'active_fields_components',
                'conditions' => $conditions,
            ));
            $pComponents = array_column($_pComponents,'field_key_code');
        }
        
        $cur_pid_keys = $this->_get_field_key_code_by_cids($pid,$company_id);
        $cur_pid = $this->_get_field_key_code_by_cids($pid,$company_id,$cur_pid_keys);
        if($cur_pid){
            $pid = array_keys($cur_pid);
        }
    }

    private function _set_modules_permissions_values($role_id, $nid, $company_id, &$pid,$_cids,$edit = true) {
        if (!$nid || !$company_id) {
            return false;
        }
        $all_components = array();
        $pComponents = array();
        //读取已建给该公司的该模块的所有字段
        $this->_get_all_parent_components($all_components,$pComponents,$nid,$company_id,$pid,$_cids,$edit);
        
        $new_mp = array();
        foreach ($all_components as $k=>$item) {
            $val = '0';
            if(in_array($item,$pComponents)){
                $val = '1';
            }
            $new_mp[$item] = array(
                'cid' => $company_id,
                'nid' => $nid,
                'rid' => $role_id,
                'fid' => $k,
                'add' => $val,
                'edit' => $val,
                'view' => $val,
                'export' => $val,
            );
        }
        if ($pid && $edit) {
            foreach ($pid as $item) {
                if(empty($all_components[$item])){
                    continue;
                }
                $new_mp[$all_components[$item]] = array(
                    'cid' => $company_id,
                    'nid' => $nid,
                    'rid' => $role_id,
                    'fid' => $item,
                    'add' => '1',
                    'edit' => '1',
                    'view' => '1',
                    'export' => '1',
                );
            }
        }
        return $new_mp;
    }

    private function set_menu_title(&$menus, $cid = COMPANY_ID) {
        if (!$menus) {
            return false;
        }
        $this->load->model('admin_menu_model', 'admin_menu');
        if (!empty($menus[0]) && is_array($menus[0])) {
            $menus_name = $this->admin_menu->getData(array(
                'fields' => 'mid,title',
                'table' => 'admin_menu_name',
                'conditions' => array(array('cId' => $cid)),
                'assoc' => array('mid' => 'title')
            ));
            foreach ($menus as &$item) {
                isset($menus_name[$item['id']]) && $item['title'] = $menus_name[$item['id']];
            }
        } else {
            $menu_name = $this->admin_menu->getData(array(
                'fields' => 'mid,title',
                'table' => 'admin_menu_name',
                'conditions' => array(array('cId' => $cid), array('mid' => $cid)),
                'row' => true
            ));
            isset($menu_name[$menus['id']]) && $menus['title'] = $menu_name[$menus['id']];
        }
    }

    public function _doIframe($result, $status = 1, $lang = false, $callback = '', $exit = true) {
        if ($result) {
            if (is_array($result)) {
                echo '<script type="text/javascript">var data = \'' . str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($result))) . '\';window.top.window.iResult(data,"' . $callback . '");</script>';
            } else {
                if ($lang) {
                    $CI = & get_instance();
                    $result = $CI->lang->line($result);
                }
                echo '<script type="text/javascript">window.top.window.iResultAlter(\'' . $result . '\',' . $status . ');</script>';
            }
            $exit && exit();
        } else {
            echo '<script type="text/javascript">window.top.window.iResultAlter(\'Unknown error.\');</script>';
            exit();
        }
    }
    
    public function _edit_company_template_components($components,$tid, $oid) {
        $objNodes = $this->_get_model_nid($oid);
        $objComponents = array();
        $_objComponents = $this->getData(array(
            'fields' => 'cid,nid,field_key_code',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => 0), array('company_id' => $oid)),
        )); //ww($this->last_query());
        if(!$_objComponents){
            return false;
        }
        foreach($_objComponents as $item){
            $objComponents[$item['nid']][$item['cid']] = $item['field_key_code'];
        }
        $curNodes = $this->_get_model_nid($tid);
        $curComponents = array();
        $_curComponents = $this->getData(array(
            'fields' => 'cid,nid,field_key_code',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => 0), array('company_id' => $tid)),
        )); //ww($this->last_query());
        if(!$_curComponents){
            return false;
        }
        foreach($_curComponents as $item){
            $curComponents[$item['nid']][$item['field_key_code']] = $item['cid'];
        }
        
//        $where = $this->getData(array(
//            'fields' => 'fid',
//            'table' => 'modules_permissions_template',
//            'conditions' => array(array('oid' => $tid)),
//            'compiled' => true
//        ));
        
//        $selectedComponents = array();
//        $_selectedComponents = $this->getData(array(
//            'fields' => 'cid,nid,field_key_code',
//            'table' => 'active_fields_components',
//            'conditions' => array(array('is_delete' => 0)),
//            'where' => 'cid IN ('.$where.')'
//        ));
//        
//        $_nodes = array();
//        $nids = array_column($_selectedComponents,'nid');
//        if($nids){
//            $nids = array_filter(array_unique($nids));
//            $_nodes = $this->getData(array(
//                'fields' => 'nid,type',
//                'table' => 'active_fields_node',
//                'ins' => array(array('nid' => $nids)),
//                'assoc' => array('nid' => 'type'),
//            ));
//        }
//        if($_selectedComponents){
//            foreach($_selectedComponents as $item){
//                $selectedComponents[$_nodes[$item['nid']]][$item['field_key_code']] = $item['cid'];
//            }
//        }
        
        //ww($components);
        $missComponents = array();
        //$addComponents = array();
        foreach ($components as $model => $items) {//model
            $objNid = !empty($objNodes[$model])?$objNodes[$model]:0;
            if(empty($objComponents[$objNid])){
                //www($model.$objNid);
                continue;
            }
            $objComponent = $objComponents[$objNid];
            $_items = array();
            array_walk_recursive($items, function($value) use (&$_items) {
                array_push($_items, $value);
                $_items = array_filter(array_unique($_items));
            });
            if($_items){
                $_items = array_merge($_items,array_keys($items));
            }
//            if($model == 'manager'){
//                ww($_items);
//            }
            $_items_ = array_flip($_items);
            $_objComponent = array_intersect_key($objComponent,$_items_);

            $curNid = !empty($curNodes[$model])?$curNodes[$model]:0;
            $curComponent = !empty($curComponents[$curNid])?$curComponents[$curNid]:array();
            $_curComponent = $curComponent?array_flip($curComponent):array();
            //www($_objComponent);www($_curComponent);

            $_missComponents = array_diff($_objComponent,$_curComponent);
            $missComponents[$objNid] = $_missComponents;
            //ww($missComponents[$objNid]);
            
//            $selectedComponent = !empty($selectedComponents[$model])?$selectedComponents[$model]:array();
//            $_selectedComponent = $selectedComponent?array_flip($selectedComponent):array();
//            $_addComponents = array_diff($_curComponent,$_selectedComponent);//ww($_addComponents);
//            $newComponents = $curComponent && $_addComponents?array_diff($curComponent,array_flip($_addComponents)):array();
//            $addComponents[$objNid] = $newComponents;
        }
//        www($missComponents);ww($addComponents);
        if(!array_filter($missComponents)){
            return false;
        }else{
            $missComponents = array_filter($missComponents);
        }
        $_objNodes = array_flip($objNodes);
        foreach ($missComponents as $k=>$item){
            $nid = !empty($curNodes[$_objNodes[$k]])?$curNodes[$_objNodes[$k]]:0;
            if(!$nid){
                $nid = $this->_add_active_fields_node($k,$tid);
            }
            if(!$nid || !$item){
                continue;
            }
            $this->_add_active_fields_components($nid,$k,$item,$tid,$oid);
        }
    }
    
    private function _add_active_fields_node($nid,$tid){
        $node = $this->getData(array(
            'fields' => '*',
            'table' => 'active_fields_node',
            'conditions' => array(array('nid' => $nid)),
        ));
        if(!$node){
            return 0;
        }
        unset($node['nid']);
        $node['company_id'] = $tid;
        return $this->dbInsert('active_fields_node',$node,true);
    }
    
    private function _add_active_fields_components($nid,$k,$items,$tid,$oid){
        if(!$items){
            return false;
        }
        $components = $this->getData(array(
            'fields' => '*',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => '0')),
            'ins' => array(array('cid' => array_keys($items))),
            'order' => array(array('pid' => 'asc'), array('cid' => 'asc')),
        ));
        if(!$components){
            return false;
        }
        $new_pcid = array();
        $new_scid = array();
        $modules_permissions_templates = array();
        $modules_permissions_template = array(
            'oid' => $tid,
            'cid' => $oid,
            'nid' => $k,
            'rid' => 0,
            'add' => 1,
            'edit' => 1,
            'view' => 1,
            'export' => 1,
        );
        $_items = array_flip($items);
        foreach ($components as $item) {
            $modules_permissions_template['fid'] = $_items[$item['field_key_code']];
            $item['company_id'] = $tid;
            $item['nid'] = $nid;
            $cid = $item['cid'];
            //$item['oid'] = $cid;
            unset($item['cid']);
            if ($item['pid'] == 0) {
                $newcid = $this->dbInsert('active_fields_components', $item, true);
                $new_pcid[$cid] = $newcid;
                $modules_permissions_templates[] = $modules_permissions_template;
                continue;
            }
            if(empty($new_pcid[$item['pid']])){
                $new_pcid[$item['pid']] = $this->_get_active_fields_components($nid,$item);
            }
            if(empty($new_pcid[$item['pid']])){
                continue;
            }
            $item['pid'] = $new_pcid[$item['pid']];
            $newcid = $this->dbInsert('active_fields_components', $item, true);
            $new_scid[$cid] = $newcid;
            $modules_permissions_templates[] = $modules_permissions_template;
        }
        $modules_permissions_templates && $this->dbInsertBatch('modules_permissions_template', $modules_permissions_templates);
        return true;
    }
    
    private function _get_active_fields_components($nid,$item){
        $_components = $this->getData(array(
            'fields' => 'field_key_code',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => '0'),array('cid' => $item['pid'])),
            'row' => true
        ));
        if(!$_components){
            return false;
        }
        $components = $this->getData(array(
            'fields' => 'cid',
            'table' => 'active_fields_components',
            'conditions' => array(array('is_delete' => '0'),array('nid' => $nid),array('field_key_code' => $_components['field_key_code'])),
            'row' => true
        ));
        //ww($this->last_query());
        return $components?$components['cid']:null;
    }
    
    

}
