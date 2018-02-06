<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Blogroll extends Fzhao_Controller {

    private $title = '';

    function __construct() {
        parent::__construct();
        $this->load->model('admin/blogroll_model', 'admin');
        $this->title = '友情链接';
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
            $data['terms'] = $this->admin->getTermByTaxonomy('links');
            $this->view('admin/blogroll', $data);
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
            $this->set_administrator_real_name($result['data']);
            $this->doJson($result);
        } else {
            $data['terms'] = $this->admin->getTermByTaxonomy('links');
            $data['title'] = $this->title . '回收站';
            $data['_title_'] = $this->title;
            $this->view('admin/blogroll_recycles', $data);
        }
    }

    private function _verifyForm($info = array()) {
        $data = array();

//        $data['link_type'] = trim($this->input->post('link_type', true));
        $data['link_term'] = intval($this->input->post('link_term', true));
        if (!$data['link_term']) {
            $this->_doIframe('所属分类不能为空', 0);
        }
        $term = $this->admin->getTermById($data['link_term']);
        if(!$term){
            $this->_doIframe('分类ID不存在', 0);
        }
        $data['link_type'] = $term['slug'];
        $data['link_name'] = trim($this->input->post('link_name', true));
        if (!$data['link_name']) {
            $this->_doIframe('链接名称不能为空', 0);
        }
        $data['link_url'] = trim($this->input->post('link_url', true));
        if (!$data['link_url']) {
            $this->_doIframe('链接url不能为空', 0);
        }
        $result = null;
        if($data['link_type'] == 'link'){
            if ($info) {
                $result = $this->admin->getRowByTitle(array(array('link_url' => $data['link_url']), array('id!=' => $info['id'])));
            } else {
                $result = $this->admin->getRowByTitle(array(array('link_url' => $data['link_url'])));
            }
        }
        if ($result) {
            $this->_doIframe('url已存在', 0);
        }
//        if (!empty($_FILES['link_image']['tmp_name'])) {
//            $link_image = $this->_uploadPic();
//            $data['link_image'] = $link_image;
//            if($link_image!=$info['link_image'] && $info['link_image'] && file_exists($info['link_image'])){
//                unlink($info['link_image']);
//            }
//        }
        $link_image = trim($this->input->post('thumb', true));
        $data['link_image'] = $link_image? str_replace(site_url('/'), '',$link_image):'';
        $data['link_target'] = trim($this->input->post('link_target', true));
        $data['isHidden'] = (string) intval($this->input->post('isHidden', true));
        $data['link_sort'] = intval($this->input->post('link_sort', true));
        $data['link_description'] = trim($this->input->post('link_description', true));

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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $info = $this->admin->getRowById($id);
        $this->verify($info);

        $act = trim($this->input->post('act', true));
        if ($act == 'get') {
            $info['link_image_thumb'] = changeImagePath($info['link_image']);
            if(!file_exists($info['link_image_thumb'])){
                $info['link_image_thumb'] = $info['link_image'];
            }
            successOutput($info);
        }
        $fields = $this->_verifyForm($info);
        $fields['update_time'] = _DATETIME_;

        $this->admin->edit($fields, $id);
        $this->_doIframe('修改成功','async');
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
        if (!IS_POST) {
            show_404();
        }
        $fields = $this->_verifyForm();
        $fields['update_time'] = _DATETIME_;
        $fields['create_time'] = _DATETIME_;
        $fields['uid'] = ADMIN_ID;
        $fields['lang'] = _LANGUAGE_;
//        $fields['link_type'] ='link';

        $this->admin->add($fields);
        $this->_doIframe('添加成功','async');
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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $result = $this->admin->del($id, 'link_term');
        $this->doJson($result);
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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $result = $this->admin->dump($id);
        $this->doJson($result);
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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $result = $this->admin->recover($id, 'link_term');
        $this->doJson($result);
    }

    /**
     * uploadPic
     * 简介：上传图片
     * 参数：Array
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-22
     */
    private function _uploadPic($name = 'link_image') {
        $config['upload_path'] = './uploads/links';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1024;
        $config['encrypt_name'] = true;
        $config['file_ext_tolower'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($name)) {
            $err = $this->upload->display_errors();
            $this->_doIframe($err,'0');
        }
        return 'uploads/links/'.$this->upload->data('file_name');
    }

}
