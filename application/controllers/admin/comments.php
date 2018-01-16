<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Comments extends Fzhao_Controller {
    
    private $title = '';
    function __construct() {
        parent::__construct();
        $this->load->model('admin/comments_model', 'admin');
        $this->title = '留言';
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
            $this->doJson($result);
        } else {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $data['terms'] = $this->admin->getTermByTaxonomy('comments');
            $this->view('admin/comments', $data);
        }
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
