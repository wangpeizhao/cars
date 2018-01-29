<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classify extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/classify_model','admin');
    }
    
    public function index(){
        if(IS_POST){
            $this->_post();
            return true;
        }
        $data = $this->_get();
        $data['title'] = '系统分类';
        $this->view('admin/classifies',$data);
    }
    
    private function _get(){
        $pid = intval($this->input->post('pid',true));
        $terms = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'term',
//            '_conditions' => array(array('isHidden' => '0')),
            '_order' => array(array('parent' => 'ASC'), array('sort' => 'desc')),
        ));

        foreach ($terms as &$item) {
            $item['pid'] = $item['parent'];
            $item['title'] = $item['name'];
            if ($pid == $item['id']) {
                $item['selected'] = 'selected';
            } else {
                $item['selected'] = '';
            }
        }
        //menus select option
        $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
        $this->load->library('tree');
        $tree = new Tree();
        $tree->init($terms);
        $data['select_menus'] = $tree->get_tree(0, $str);
        
        //menus lists
        $menus_lists = $this->admin->_get_menu_tree_html($terms);
        $data['menus_lists'] = $menus_lists;
//        successOutput($data);
        return $data;
    }
    
    public function add(){
        if(!IS_POST){
            show_404();
        }
        $parms = $this->input->post(null,true);
        $data = $this->_validation($parms,false);
        $termId = $this->admin->dbInsert('term',$data,true);
        if(!empty($parms['act']) && $parms['act']=='addNewsTag'){
            $this->_doIframe($termId,3);
        }
        $this->_doIframe('添加成功');
    }
    
    public function edit(){
        if(!IS_POST){
            show_404();
        }
        $id = intval($this->input->post('id',true));
        $this->verify($id);
        $term = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'term',
            '_conditions' => array(array('id' => $id)),
            'row' => true
        ));
        if(!$term){
            $this->_doIframe('分类不存在',0);
        }
        $parms = $this->input->post(null,true);
        $data = $this->_validation($parms,$term);
        $this->admin->dbUpdate('term',$data,array('id' => $id));
        $this->_doIframe('编辑成功');
    }
    
    public function del(){
        if(!IS_POST){
            show_404();
        }
        $id = intval($this->input->post('id',true));
        $this->verify($id);
        $term = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'term',
            '_conditions' => array(array('id' => $id)),
            'row' => true
        ));
        if(!$term){
            errorOutput('分类不存在');
        }
        //子分类
        $sTerm = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'term',
            '_conditions' => array(array('parent' => $id)),
        ));
        if($sTerm){
            errorOutput('该分类下存在子分类,不允许删除');
        }
        $this->admin->dbUpdate('term',array('isHidden' => '1'),array('id' => $id));
        successOutput('删除成功');
    }
    
    private function _validation($parms,$term = array()){
        if(!$parms){
            $this->_doIframe('参数为空',0);
        }
        $field = array();
        $field['parent'] = intval($parms['pid']);

        if ($field['parent'] === '') {
            $this->_doIframe('请选择父级分类',0);
        }
        $pTerm = array();
        if($field['parent']){
            $pTerm = $this->admin->getData(array(
                'fields' => '*',
                'table' => 'term',
                '_conditions' => array(array('id' => $field['parent'])),
                'row' => true
            ));
            if(!$pTerm){
                $this->_doIframe('父分类不存在',0);
            }
        }
        
        $field['name'] = trim($parms['name']);
        if (!$field['name']) {
            $this->_doIframe('分类名称不能为空',0);
        }
        
        $field['slug'] = strtolower(trim($parms['slug']));
        if (!$field['slug']) {
            $this->_doIframe('URL别名不能为空',0);
        }
        $_conditions = array(array('slug'=>$field['slug']),array('parent'=>$field['parent']));
        if($term){
            $_conditions[] = array('id!=' =>$term['id']);
        }
        $cpTerm = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'term',
            '_conditions' => $_conditions,
            'row' => true
        ));
        if($cpTerm){
            $this->_doIframe('URL别名已存在该父分类下',0);
        }
        
        $field['description'] = trim($parms['description']);
        
        $field['update_time'] = _DATETIME_;
        if(!$term){
            $field['create_time'] = _DATETIME_;
            $field['uid'] = ADMIN_ID;
            $field['lang'] = _LANGUAGE_;
        }
        $field['sort'] = intval($parms['sort']);
        $field['isHidden'] = trim($parms['isHidden']);
        $field['taxonomy'] = $pTerm?$pTerm['slug']:$field['slug'];
        
        return $field;
    }

}
