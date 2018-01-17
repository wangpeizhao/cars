<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Menu_model
 * 简介：
 * 返回：Boole
 * 作者：Fzhao
 * 时间：2017-07-25
 */
class Menu_model extends Fzhao_Model {
    
    protected $currMenuId = 0;
    protected $currMenuIdArr = array();
    private $menuTreesClass;

    public function __construct() {
        parent::__construct();
        $this->table = 'menus';
        $this->primary_key = $this->dbPrimary($this->table);
        $this->menuTreesClass = array('mFirst', 'mSecond', 'mThird', 'mFourth', 'mFifth', 'mSixth', 'mSeventh', 'mEighth', 'mNinth', 'mTenth');
    }

    //左侧导航
    public function get_left_nav_bar(&$data,$supervisor,$role_id,$currMenuId) {
        $this->currMenuId = $currMenuId;
        $priv_menus_params = array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(array('status' => '1'), array('plat' => 'admin')),
            '_order' => array(array('pid' => 'asc'), array('sort' => 'asc'), array('id' => 'asc')),
        );
        //如果不是总控的超级管理员,需要验证过虑所有可视的菜单
        if (!$supervisor) {
            $admin_priv_params = array(
                'fields' => 'menu_id',
                'table' => 'priv',
                '_conditions' => array(array('role_id' => $role_id)),
            );
            $_privs = $this->getData($admin_priv_params);
            $mids = array_column($_privs, 'menu_id');
            if (!$mids) {
                redirect(WEB_DOMAIN . ($role_id==0?'/admin/login':'/admin/noPower'), 'refresh');
            }
            $priv_menus_params['ins'] = array(array('id' => $mids));
        }
        //获取所有可视菜单集合
        $all_priv_menus = $this->getData($priv_menus_params);

        if (!$all_priv_menus) {
            redirect(WEB_DOMAIN . '/admin/noPower', 'refresh');
        }

        //全部可视菜单树
        $menuTrees = array();
        if ($all_priv_menus) {
            $menuTrees = $this->_get_menu_trees($all_priv_menus, 0, 0); //, $class, $method, $directory, $_p_
        }

        //生成当前菜单的html菜单树
        $leftNavBar = '';
        if ($menuTrees) {
            $leftNavBar = $this->_get_menu_trees_html($menuTrees);
        }

        $data['leftNavBar'] = $leftNavBar;
    }

    private function _get_menu_trees_html($menus, $deep = 0) {
        $html = '';
        $deepClass = isset($this->menuTreesClass[$deep]) ? $this->menuTreesClass[$deep] : '';
        foreach ($menus as $v) {
            if (!$v['show']) {
                continue;
            }
            $cur = in_array($v['id'], $this->currMenuIdArr) && $v['pid']>0 ? ' selected' : '';
            $href = $v['ext_link'] ? $v['ext_link'] : ($v['link'] ? $v['link'] . ($v['parameter'] ? '?' . ltrim($v['parameter'], '?') : '') : 'javascript:;');
            $html .= "<li class=\"" . $deepClass . $cur . "\">";
            //顶级菜单不需要在这显示
            if ($deep == 0) {
                $html .= "<span>" . $v['title'] . "</span>";
            } else {
                $html .= "<a class=\"" . $deepClass . "\" href=\"" . $href . "\">" . $v['title'] . "</a>";
            }
            $v['childs'] && $html .= $this->_get_menu_trees_html($v['childs'], $v['deep']);
            $html .= "</li>";
        }
        return $html ? '<ul class="' . ($deep == 0 ? 'submenu ' : 'menu_item ') . $deepClass . '">' . $html . '</ul>' : $html;
    }

    private function _get_menu_trees($data, $pId, $deep) {//, $class, $method, $directory, $_p_
        static $currMenuId;
        $tree = array();
        foreach ($data as $v) {
            if ($v['pid'] != $pId) {
                continue;
            }
            //父亲找到儿子
            !isset($currMenuId) && $this->currMenuIdArr[$deep] = $v['id'];
            if($this->currMenuId == $v['id'] && $v['pid']>0){
                $currMenuId = $v['id'];
                array_splice($this->currMenuIdArr, $deep + 1);
            }
            $v['deep'] = $deep + 1;
            $v['childs'] = $this->_get_menu_trees($data, $v['id'], $deep + 1); //, $class, $method, $directory, $_p_
            $tree[$v['id']] = $v;
        }
        return $tree;
    }
    
    public function get_manager_records_menus($_url = '/admin/manager/records.html',$plat = 'admin'){
        $recordChilds = array();
        if(checkPower($_url)){
            list($m,$c,$a) = explode("/",$_url);
            //经营户->记录的菜单的ID
            $recordMid = $this->getData(array(
                'fields' => 'id',
                'table' => 'menus',
                '_conditions' => array(array('status'=>'1'),array('m'=>$m),array('c'=>$c),array('a'=>$a),array('plat'=>$plat)),
                'row' => true
            ));
            if($recordMid){
                $recordChilds = $this->getData(array(
                    'fields' => '*',
                    'table' => 'menu',
                    '_conditions' => array(array('status'=>'1'),array('pid'=>$recordMid['id'])),
                    '_order' => array(array('sort'=>'asc'))
                ));
                
                $_menus_name = $this->getData(array(
                    'fields' => 'mid,title',
                    'table' => 'admin_menu_name',
                    '_conditions' => array(array('cId' => COMPANY_ID)),
                    //'assoc' => array('mid'=>'title')
                ));
                $menus_name = array_column($_menus_name,'title','mid');
                foreach($recordChilds as &$_item){
                    isset($menus_name[$_item['id']]) && $_item['title'] = $menus_name[$_item['id']];
                }

            }
            if(!$recordChilds){
                return false;
            }
            foreach($recordChilds as $k=>$item){
                if(!$item['link'] || !checkPower($item['link'])){
                    unset($recordChilds[$k]);
                }
            }
            unset($item);
        }
        return $recordChilds;
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
