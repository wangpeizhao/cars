<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class News extends Fzhao_Controller {
    
    private $title = '';
    function __construct() {
        parent::__construct();
        $this->load->model('admin/news_model', 'admin');
        $this->title = '新闻资讯';
    }

    /**
     * news
     * 简介：新闻资讯
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function index() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->lists($data);
            $this->set_administrator_real_name($result['data']);
            $this->doJson($result);
        } else {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $data['terms'] = $this->admin->getTermByTaxonomy('newsInfo');
            $this->view('admin/news', $data);
        }
    }

    private function _verifyForm($info = array()) {
        $data = array();

        $data['term_id'] = intval($this->input->post('term_id', true));
        if(!$data['term_id']){
            $this->_doIframe('所属分类不能为空',0);
        }
        $data['is_commend'] = (string)intval($this->input->post('is_commend', true));
        $data['is_issue'] = (string)intval($this->input->post('is_issue', true));
        $data['views'] = intval($this->input->post('views', true));
        $data['from'] = trim($this->input->post('from', true));
        $data['author'] = trim($this->input->post('author', true));
        $data['title'] = trim($this->input->post('title', true));
        if(!$data['title']){
            $this->_doIframe('标题不能为空',0);
        }
        if($info){
            $result = $this->admin->getRowByTitle($data['title'],array(array('id!='=>$info['id'])));
        }else{
            $result = $this->admin->getRowByTitle($data['title']);
        }
        if($result){
            $this->_doIframe('标题已存在',0);
        }
        $this->load->helper('char');
        $data['ft_title'] = wordSegment($data['title']);
        $data['summary'] = htmlspecialchars($this->input->post('summary', true));
        $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', htmlspecialchars($this->input->post('content')));
        if(!$data['content']){
            $this->_doIframe('详细内容不能为空',0);
        }
        $data['SEOKeywords'] = htmlspecialchars($this->input->post('SEOKeywords', true));
        $data['SEODescription'] = htmlspecialchars($this->input->post('SEODescription', true));
        $data['sort'] = intval($this->input->post('sort', true));
        
        $data['thumb'] = str_replace(site_url(''), '',trim($this->input->post('thumb', true)));

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
    function edit() {
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
        $info['thumb_tiny'] = changeImagePath($info['thumb'],'tiny');
        $data['terms'] = $this->admin->getTermByTaxonomy('newsInfo');
        $data['data'] = $info;
        $data['title'] = '编辑'.$this->title.' - '.$info['title'];
        $data['_title_'] = $this->title;
        $this->view('admin/news_edit', $data);
    }

    /**
     * add
     * 简介：添加
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function add() {
        $data = array();
        if (IS_POST) {
            $fields = $this->_verifyForm();
            $fields['update_time'] = _DATETIME_;
            $fields['create_time'] = _DATETIME_;
            $fields['uid'] = ADMIN_ID;
            $fields['lang'] = _LANGUAGE_;
            
            $this->admin->trans_start();
            $result = $this->admin->add($fields);
            if ($result) {
                $this->admin->dbSet('term',array('count' => 'count+1', array('id' => $fields['term_id'])));
            }
            $this->admin->trans_complete();
            
            $this->doIframe($result);
        }
        $data['terms'] = $this->admin->getTermByTaxonomy('newsInfo');
        $data['title'] = '添加'.$this->title;
        $data['_title_'] = $this->title;
        $this->view('admin/news_add', $data);
    }

    /**
     * recycles
     * 简介：回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recycles() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->recycles($data);
            $this->set_administrator_real_name($result['data']);
            $this->doJson($result);
        } else {
            $data['terms'] = $this->admin->getTermByTaxonomy('newsInfo');
            $data['title'] = $this->title.'回收站';
            $data['_title_'] = $this->title;
            $this->view('admin/news_recycles', $data);
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
    function del() {
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
    function dump() {
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
    function recover() {
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
     * batch
     * 简介：批量操作
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-15
     */
    function batch() {
        if (IS_POST) {
            $type = post_get('val');
            $this->verify($type,'操作类型不能为空');
            $ids = $this->input->post('ids', true);
            $this->verify($ids,'请至少选择一项');
            $conditions = array('lang'=>_LANGUAGE_);
            $data = array();
            switch ($type){
                case '1';//批量标记推荐
                    $data = array('is_commend' => '1');
                    break;
                case '2';//批量取消推荐
                    $data = array('is_commend' => '0');
                    break;
                case '3';//批量标记发布
                    $data = array('is_issue' => '1');
                    break;
                case '4';//批量取消发布
                    $data = array('is_issue' => '0');
                    break;
            }
            $result = $this->admin->dbUpdateIn('news',$data,$conditions,array('id'=>$ids));
            $this->doJson($result);
        } else {
            show_404();
        }
    }

}
