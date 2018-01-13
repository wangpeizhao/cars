<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cases extends Fzhao_Controller {

    protected $_type_ = 'cases';
    protected $_title_ = '成功案例';

    function __construct() {
        parent::__construct();
//        $this->checkLP(); //检测登录状态和权限
//        $this->load->library('uri');
        $this->load->model('admin/cases_model');
        $this->model = $this->cases_model;
    }

    /**
     * index
     * 简介：活动列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-19
     */
    function index() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->model->get_list($data);
            $this->doJson($result);
        } else {
            $data = array();
            $data['terms'] = $this->model->getTermByTaxonomy($this->_type_);
            $data['_title_'] = $this->_title_;
            $data['_type_'] = $this->_type_;
            $this->view('admin/cases', $data);
        }
    }

    /**
     * add
     * 简介：添加活动
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-19
     */
    function add() {
        $data = array();
        if (IS_POST) {
            $post = $this->input->post();
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = intval($this->input->post('is_commend', true));
            $data['is_issue'] = intval($this->input->post('is_issue', true));
            $data['sort'] = intval($this->input->post('sort', true));
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            $data['summary'] = trim($post['summary']) ? $this->input->post('summary', true) : '';
            $data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['owner'] = $this->userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['lang'] = _LANGUAGE_;
            
            $imagePath = '';
            if($_FILES['thumbPic']['tmp_name']){
                $imagePath = $this->uploadPic($_FILES['thumbPic'], 'c', 'uploads/cases/images/images');
                $this->zoomImage($imagePath, 'cases/images');
                $data['thumbPic'] = $imagePath;
            }
            $tid = $data['term_id'];
            $result = $this->model->add($data);
            if ($result) {
                $this->model->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count+1', 'id' => $tid));
            }
            $this->doIframe($result);
        } else {
            $data['terms'] = $this->model->getTermByTaxonomy($this->_type_);
            $data['_title_'] = $this->_title_;
            $data['_type_'] = $this->_type_;
            $this->view('admin/casesAdd', $data);
        }
    }

    /**
     * edit
     * 简介：编辑活动
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-19
     */
    function edit($id = null) {
        $data = array();
        if (IS_POST) {
            $id = intval($this->input->get_post('id', true));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                $this->doIframe('找不到数据', 0);
            }
            $post = $this->input->post();
            $path = addslashes($this->input->post('path', true));
            $id = intval($post['id']) ? intval($this->input->post('id', true)) : 0;
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = intval($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = intval($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            $data['sort'] = intval($this->input->post('sort', true));
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            $data['summary'] = trim($post['summary']) ? $this->input->post('summary', true) : '';
            $data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['update_time'] = date('Y-m-d H:i:s');
            
            $imagePath = '';
            if($_FILES['thumbPic']['tmp_name']){
                $imagePath = $this->uploadPic($_FILES['thumbPic'], 'c', 'uploads/cases/images/images');
                $this->zoomImage($imagePath, 'cases/images');
                $data['thumbPic'] = $imagePath;
            }

            $result = $this->model->dbUpdate('cases', $data, array('id' => $id));
            $this->doIframe($result);
        } else {
            $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
            $id = intval($this->uri->segment($n));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                show_404();
            }
            $data['terms'] = $this->model->getTermByTaxonomy($this->_type_);
            $data['data'] = $service;
            $data['_title_'] = $this->_title_;
            $data['_type_'] = $this->_type_;
            $this->view('admin/casesEdit', $data);
        }
    }

    /**
     * del
     * 简介：删除(放入回收站)活动
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-21
     */
    function del() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            if (is_numeric($id) || is_array($id)) {
                $result = $this->model->del($id);
            } else {
                $result = false;
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recycle
     * 简介：活动回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-22
     */
    function recycle() {
        $data = array();
        if (IS_POST) {
            $data['currPage'] = intval($this->input->post('currPage', true));
            $data['rows'] = intval($this->input->post('rows', true));
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->model->get_recycle_list($data);
            $this->doJson($result);
        } else {
            $data['terms'] = $this->model->getTermByTaxonomy($this->_type_);
            $data['_title_'] = $this->_title_;
            $data['_type_'] = $this->_type_;
            $this->view('admin/casesRecycle', $data);
        }
    }

    /**
     * recover
     * 简介：还原活动信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-22
     */
    function recover() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->model->recover($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dump
     * 简介：彻底删除活动信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-22
     */
    function dump() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->model->dump($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    function uploadTfile($fileData) {
        if (!empty($fileData)) {
            $targetFolder = 'uploads/files/activities';
            $this->createFolder($targetFolder);
            $tempFile = $fileData['tmp_name'];
            $targetPath = $targetFolder;
            $fileParts = pathinfo($fileData['name']);
            $fName = date("YmdHis") . '_' . rand(100, 999);
            $post['fileext'] = '.' . strtolower($fileParts['extension']);
            $post['fileType'] = $fileData['type'];
            $post['file_name'] = $fName . $post['fileext'];
            $targetFile = $post['file_path'] = rtrim($targetPath, '/') . '/' . $fName . '.' . strtolower($fileParts['extension']);

            $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'txt', 'rar', 'zip', 'pdf', 'csv', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'chm'); // File extensions
            $filesize = abs(filesize($tempFile)); //图片大小 
            $post['filesize'] = $filesize;
            if ($filesize >= 5120000) {
                clearstatcache();
                echo '<script type="text/javascript">window.top.window.fileResult("' . kc_iconv("上传文档太大,请重新选择,\\n支持文档大小为5M！") . '");</script>';
                exit();
            }
            if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
            } else {
                clearstatcache();
                echo '<script type="text/javascript">window.top.window.fileResult("' . kc_iconv("上传文档格式不正确,请重新选择,\\n支持文档格式：\'jpg\',\'jpeg\',\'gif\',\'png\',\'txt\',\'rar\',\'zip\',\'pdf\',\'csv\',\'doc\',\'docx\',\'xls\',\'xlsx\',\'ppt\',\'pptx\',\'chm\'！") . '");</script>';
                exit();
            }
        }
        return $post;
    }

    /**
     * uploadPic
     * 简介：上传图片
     * 参数：Array
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-22
     */
    function uploadPic($FILES, $realmName, $targetFolder = 'uploads/membervip', $filePath = '') {
        $this->createFolder($targetFolder);
        $tempFile = $FILES['tmp_name'];
        $targetPath = $targetFolder;
        $fileParts = pathinfo($FILES['name']);
        $targetFile = rtrim($targetPath, '/') . '/' . $realmName . '_' . $this->random() . '.' . strtolower($fileParts['extension']);

        $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
        $filesize = abs(filesize($tempFile)); //图片大小 
        if ($filesize > 358400) {
            clearstatcache();
            echo '<script type="text/javascript">window.top.window.iResult("' . kc_iconv("上传图片太大,请重新选择,\\n支持图片大小为小于300K！") . '");</script>';
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            exit();
        }
        if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
            move_uploaded_file($tempFile, $targetFile);
        } else {
            clearstatcache();
            echo '<script type="text/javascript">window.top.window.iResult("' . kc_iconv("上传图片格式不正确,请重新选择,\\n支持图片格式：jpg、jpeg、gif和png！") . '");</script>';
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            exit();
        }
        return $targetFile;
    }

    function random() {
        $seed = current(explode(" ", microtime())) * 10000;
        $random = rand(10000, intval($seed));
        return date("YmdHis", time()) . $random;
    }

}
