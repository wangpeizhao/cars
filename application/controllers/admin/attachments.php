<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attachments extends Fzhao_Controller {

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/attachments_model', 'admin');
        $this->title = '附件';
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
            $this->set_administrator_real_name($result['data']);
            $this->doJson($result);
        } else {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $data['terms'] = $this->admin->getTermByTaxonomy('attachments');
            $this->view('admin/attachments', $data);
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
    public function recycles() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->recycles($data);
            $this->set_administrator_real_name($result['data']);
            $this->doJson($result);
        } else {
            $data['terms'] = $this->admin->getTermByTaxonomy('attachments');
            $data['title'] = $this->title . '回收站';
            $data['_title_'] = $this->title;
            $this->view('admin/attachments_recycles', $data);
        }
    }

    private function _verifyForm($info = array()) {
        $data = array();

        $data['link_term'] = intval($this->input->post('link_term', true));
        if (!$data['link_term']) {
            $this->_doIframe('所属分类不能为空', 0);
        }
        $data['link_name'] = trim($this->input->post('link_name', true));
        if (!$data['link_name']) {
            $this->_doIframe('链接名称不能为空', 0);
        }
        $data['link_url'] = trim($this->input->post('link_url', true));
        if (!$data['link_url']) {
            $this->_doIframe('链接url不能为空', 0);
        }
        if ($info) {
            $result = $this->admin->getRowByTitle(array(array('link_url' => $data['link_url']), array('id!=' => $info['id'])));
        } else {
            $result = $this->admin->getRowByTitle(array(array('link_url' => $data['link_url'])));
        }
        if ($result) {
            $this->_doIframe('链接url已存在', 0);
        }
        if (!empty($_FILES['link_image']['tmp_name'])) {
            $link_image = $this->_uploadPic();
            $data['link_image'] = $link_image;
            if ($link_image != $info['link_image'] && $info['link_image'] && file_exists($info['link_image'])) {
                unlink($info['link_image']);
            }
        }
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
    public function edit() {
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $info = $this->admin->getRowById($id);
        $this->verify($info);

        $this->verify($_FILES['attachments']);
        $FILES = $_FILES['attachments'];
        $tid = post_get('tid');
        $attachments = $this->_uploading($FILES, $tid, true);
        if ($attachments) {
            $this->admin->dbUpdate('attachments', $attachments[0], array('id' => $id));
        }
        $file_path = $info['file_path'];
        if ($attachments && $file_path && file_exists($file_path)) {
            unlink($file_path);
            $small = changeImagePath($file_path, 'small');
            if (file_exists($small)) {
                unlink($small);
            }
            $tiny = changeImagePath($file_path, 'tiny');
            if (file_exists($tiny)) {
                unlink($tiny);
            }
        }
        $this->_doIframe('修改成功');
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
        if (!IS_POST) {
            show_404();
        }
        $this->verify($_FILES['attachments']);
        $FILES = $_FILES['attachments'];
        $tid = post_get('tid');
        $attachments = $this->_uploading($FILES, $tid);

        if ($attachments) {
            $this->admin->dbInsertBatch('attachments', $attachments);
        }
        $this->_doIframe('添加成功');
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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $result = $this->admin->del($id, 'tid');
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
    public function dump() {
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
    public function recover() {
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $result = $this->admin->recover($id, 'tid');
        $this->doJson($result);
    }

    /**
     * uploading
     * 简介：上传图片
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-15
     */
    private function _uploading($FILES, $tid, $edit = false) {
        if (!IS_POST) {
            show_404();
        }
        $directory = implode("/", array(date('Y'), date('m'), date('d')));
        $config['upload_path'] = 'uploads/' . $directory . '/images';
        //上传图片
        createFolder('uploads/' . $directory . '/images');
        createFolder('uploads/' . $directory . '/small');
        createFolder('uploads/' . $directory . '/tiny');
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '1024';
        $config['encrypt_name'] = true; //是否重命名文件。如果该参数为TRUE，上传的文件将被重命名为随机的加密字符串。
        $this->load->library('upload');
        $this->upload->initialize($config);
        $_files = array();
        foreach ($FILES as $key => $items) {
            foreach ($items as $k => $item) {
                $_files['_' . $k][$key] = $item;
            }
        }
        $_FILES = $_files;
        $attachments = array();
        $attachment = array('update_time' => _DATETIME_, 'uid' => ADMIN_ID, 'tid' => $tid);
        if (!$edit) {
            $attachment['create_time'] = _DATETIME_;
        }
        foreach ($_files as $key => $item) {
            if (!$this->upload->do_upload($key)) {
                $error = $this->upload->display_errors();
                $this->_doIframe($error, 0);
            } else {
                $success = $this->upload->data();
                $_path = $config['upload_path'] . '/' . $success['file_name'];
                $this->_zoomImage($_path, $directory);
                $attachment['file_orig'] = $success['orig_name'];
                $attachment['file_name'] = $success['file_name'];
                $attachment['file_ext'] = $success['file_ext'];
                $attachment['file_type'] = $success['file_type'];
                $attachment['file_size'] = $success['file_size'];
                $attachment['file_path'] = $_path;
                $attachment['is_image'] = '1';
                $attachment['image_width'] = $success['image_width'];
                $attachment['image_height'] = $success['image_height'];
                $attachment['image_type'] = $success['image_type'];
                $attachments[] = $attachment;
            }
        }
        return $attachments;
    }

    private function _zoomImage($path, $folder = 'dishes') {
        $imageInfo = getimagesize($path);
        $pPath = pathinfo($path);
        //生成small缩略图
        $this->load->library('image_lib');
        $img_config['create_thumb'] = TRUE;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'height';
        $img_config['source_image'] = $path;
        $img_config['new_image'] = 'uploads/' . $folder . '/small/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['height'] = 200;
        $img_config['width'] = 200 * $imageInfo[0] / $imageInfo[1];
//        $this->createFolder('uploads/' . $folder . '/small');
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe(-4);
        }
        $this->image_lib->clear();

        //生成tiny缩略图
        $img_config['create_thumb'] = TRUE;
        $img_config['source_image'] = $path;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'auto';
        $img_config['new_image'] = 'uploads/' . $folder . '/tiny/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['width'] = 125;
        $img_config['height'] = 125 * $imageInfo[1] / $imageInfo[0];
//        $this->createFolder('uploads/' . $folder . '/tiny');
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe(-5);
        }
        $this->image_lib->clear();
    }

}
