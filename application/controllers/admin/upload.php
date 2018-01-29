<?php

if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Upload extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
//    $this->checkLP(); //检测登录状态和权限
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->model('admin/upload_model');
    }

    /**
     * index
     * 简介：图片列表
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-15
     */
    function index() {
        if (!IS_POST) {
            $result = array();
            $result['terms'] = $this->upload_model->getTermByTaxonomy('attachments');
            $this->view('admin/uploadImage',$result);
            return false;
        }
        $currPage = getPages('currPage');
        $rows = getPageSize(8);
//        $post['currPage'] = intval($this->input->post('currPage', true));
//        $post['rows'] = intval($this->input->post('rows', true));
        $type = intval($this->input->post('type', true));
        $_conditions = array(array('is_image' => '1'), array('isHidden' => '0'));
        if($type){
            $_conditions[] = array('tid' => $type);
        }
        $attachments = $this->upload_model->getData(array(
            'fields' => 'id,file_path,file_orig',
            'table' => 'attachments',
            '_conditions' => $_conditions,
            'limit' => array($rows, $rows * ($currPage - 1)),
            '_order' => array(array('id' => 'desc'))
        ));
        if ($attachments) {
            foreach ($attachments as &$item) {
                $item['thumb'] = changeImagePath($item['file_path']);
            }
        }
        $total = $this->upload_model->getData(array(
            'table' => 'attachments',
            '_conditions' => $_conditions,
            'count' => true,
        ));
        $result['files'] = $attachments;
        $result['count'] = $total;
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
    function uploading() {
        if (!IS_POST) {
            show_404();
        }
        $act = post_get('act',0,'trim');
        $uri = post_get('imageUrl',0,'trim');
        $directory = implode("/", array(date('Y'), date('m'), date('d')));
        $config['upload_path'] = 'uploads/' . $directory . '/images';
        createFolder('uploads/' . $directory . '/images');
        createFolder('uploads/' . $directory . '/small');
        createFolder('uploads/' . $directory . '/tiny');
        $attachment = array('create_time' => _DATETIME_, 'update_time' => _DATETIME_, 'uid' => ADMIN_ID);
        if($act == 'network'){
            $success = $this->_download_image_remote($uri,$config['upload_path']);
            if(!$success){
                $this->_doIframe('上传失败',0);
            }
            $this->_fill_attachment($attachment,$success,$act);
            $attachment && $this->upload_model->dbInsert('attachments', $attachment);
            $this->zoomImage($success['file_path'], $directory);
            $this->_doIframe(_URL_ . $success['file_path']);
        }
        //上传图片
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '1024';
        $config['encrypt_name'] = true; //是否重命名文件。如果该参数为TRUE，上传的文件将被重命名为随机的加密字符串。
        $this->upload->initialize($config);
        $_files = array();
        foreach ($_FILES['file'] as $key => $items) {
            foreach ($items as $k => $item) {
                $_files['_' . $k][$key] = $item;
            }
        }
        $_FILES = $_files;
        $attachments = array();
        
        foreach ($_files as $key => $item) {
            if (!$this->upload->do_upload($key)) {
                $error = $this->upload->display_errors();
                $this->_doIframe($error, 0);
            } else {
                $success = $this->upload->data();
                $_path = $config['upload_path'] . '/' . $success['file_name'];
                $success['file_path'] = $_path;
                $this->zoomImage($_path, $directory);
                $this->_fill_attachment($attachment,$success,$act);
                $attachments[] = $attachment;
            }
        }
        $attachments && $this->upload_model->dbInsertBatch('attachments', $attachments);
        $this->_doIframe(_URL_ . $config['upload_path'] . '/' . $success['file_name']);
    }
    
    private function _fill_attachment(&$attachment,$item,$act){
        if($act == 'location'){
            $attachment['tid'] = intval($this->input->post('_type_2',true));
        }else if($act == 'network'){
            $attachment['tid'] = intval($this->input->post('_type_3',true));
        }
        $attachment['file_orig'] = $item['orig_name'];
        $attachment['file_name'] = $item['file_name'];
        $attachment['file_ext'] = $item['file_ext'];
        $attachment['file_type'] = $item['file_type'];
        $attachment['file_size'] = $item['file_size'];
        $attachment['file_path'] = $item['file_path'];
        $attachment['is_image'] = '1';
        $attachment['image_width'] = $item['image_width'];
        $attachment['image_height'] = $item['image_height'];
        $attachment['image_type'] = $item['image_type'];
    }

    private function _download_image_remote($uri = '', $path = '') {
        if (!$uri || !$path) {
            return false;
        }
        $pathinfo = pathinfo($uri);//ww($pathinfo);
        if(!empty($pathinfo['extension'])){
            $ext = $pathinfo['extension'];
            if (!$ext) {
                return false;
            }
            $_ext = array();
            preg_match('/^jpg|gif|png|jpeg/', strtolower($ext), $_ext);
            if (!$_ext) {
                return false;
            }
            $_ext_ = current($_ext);
        }else{
            $_ext_ = 'jpg';
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $filename = date('YmdHis').rand(1000,9999).'.'. $_ext_;
        $_path = $path.'/'.$filename;
        $_file = fopen($_path, 'w');
        fwrite($_file, $file_content);
        fclose($_file);

        $image_info = getimagesize($_path);
        $image_size = filesize($_path);
        clearstatcache();
        $fileInfo = array(
            'orig_name' => $pathinfo['basename'],
            'file_name' => $filename,
            'file_ext' => '.'. $_ext_,
            'file_type' => $image_info['mime'],
            'file_size' => sprintf("%.2f",($image_size/1024)),
            'file_path' => $_path,
            'image_width' => $image_info[0],
            'image_height' => $image_info[1],
            'image_type' => $_ext_
        );
        return $fileInfo;
    }

    private function zoomImage($path, $folder = 'dishes') {
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

    /**
     * zoomImage
     * 简介：缩放图片(生成缩略图)
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    private function _zoomImage($path) {
        $this->load->library('image_lib');
        $config['image_library'] = 'GD';
        $config['source_image'] = $path;
        $config['width'] = 150;
        $config['height'] = 150;
        $config['create_thumb'] = TRUE; //是否生成缩略图
        //$config['new_image'] = '';//新生图片路径及名称
        $this->image_lib->initialize($config);
        return $this->image_lib->resize();
    }

    /**
     * del
     * 简介：删除图片
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function del() {
        if (IS_POST) {
            $id = intval($this->input->post('id', true));
            $this->verify($id, '图片ID不能为空');
            $result = $this->upload_model->dbUpdate('attachments', array('isHidden' => '1'), array('is_image' => '1', 'id' => $id));
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * uploadVideo
     * 简介：上传视频
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function uploadVideo() {
        $config['upload_path'] = 'uploads/products/videos';
        if (IS_POST) {
            if (trim($this->input->post('act', true)) == 'get') {//读取图片
                $data = array();
                $post['currPage'] = $this->input->post('currPage', true);
                $post['rows'] = $this->input->post('rows', true);
                $files = $this->upload_model->getAllVideoFilesByPath($config['upload_path']);
                $start = ($post['currPage'] - 1) * $post['rows'];
                $end = $post['currPage'] * $post['rows'] - 1;
                foreach ($files as $key => $file) {
                    if ($key >= $start && $key <= $end) {
                        $f = pathinfo($file);
                        if (file_exists($file)) {
                            $data['fullName'][] = $file;
                            $data['fileName'][] = $f['basename'];
                        }
                        if (count($data['fullName']) == $post['rows']) {
                            break;
                        }
                    }
                }
                $result['files'] = $data;
                $result['count'] = count($files);
                $this->doJson($result);
            } else {//上传视频
                $config['allowed_types'] = 'mp4|swf|flv|f4v';
                $config['max_size'] = current(explode('M', ini_get("upload_max_filesize"))) * 1024;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($this->input->post('inputDOM', true))) {
                    $error = array('error' => $this->upload->display_errors());
                    echo '<script type="text/javascript">window.top.window.fileResult("' . dumpHtml($error['error']) . '","");</script>';
                    exit();
                } else {
                    $success = $this->upload->data();
                    echo '<script type="text/javascript">window.top.window.fileResult(1,"' . site_url() . $config['upload_path'] . '/' . $success['file_name'] . '");</script>';
                    exit();
                }
            }
        } else {
            $this->view('admin/uploadVideo');
        }
    }

    /**
     * dumpUploadVideo
     * 简介：上传图片
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function dumpUploadVideo() {
        if (IS_POST) {
            $path = str_replace(site_url(''), '', $this->input->post('path', true));
            if ($path) {
                if (file_exists($path)) {
                    $result = unlink($path);
                }
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * backup
     * 简介：备份数据库
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/20
     */
    function backup() {
        if (IS_POST) {
            $tables = $this->input->post('tables', true);
            if (!empty($tables)) {
                $result = $this->upload_model->backup($tables);
            } else {
                $result = false;
            }
            $this->doJson($result);
        } else {
            $data['tableInfo'] = $this->upload_model->getTablesInfo();
            $data['title'] = '数据库管理-备份数据库';
            $this->view('admin/database_backup', $data);
        }
    }

    /**
     * restore
     * 简介：还原数据库
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/20
     */
    function restore() {
        if (IS_POST) {
            $fileArr = $this->input->post('fileName', true);
            if (!empty($fileArr)) {
                $fullFile = array();
                foreach ($fileArr as $file) {
                    $filePath = 'uploads/backup/' . $file;
                    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    if ($ext != 'sql') {
                        $this->json_error('只支持SQL文件,请上传SQL格式文件.');
                    }
                    if (file_exists($filePath)) {
                        $fullFile[] = $filePath;
                    }
                }
                $result = $this->upload_model->runRes($fullFile);
            }
            $this->doJson($result);
        } else {
            $data['files'] = $this->upload_model->getBackup('uploads/backup');
            $data['title'] = '数据库管理-还原数据库';
            $this->view('admin/database_restore', $data);
        }
    }

    /**
     * backup
     * 简介：备份数据库
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/20
     */
    function databases() {
        $data['tableInfo'] = $this->upload_model->getTablesInfo();
        $this->view('admin/database', $data);
    }

    /**
     * downLoad
     * 简介：下载备份文件
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-20
     */
    function downloadsql() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $filePath = $this->input->post('filePath', true);
            $file = pathinfo($filePath);
            if (!file_exists($filePath)) {
                echo '<script type="text/javascript">window.top.window.fileResult("对不起,备份文件已丢失，下载失败");</script>';
                exit();
            }
            if ($file['extension'] != 'zip' || intval($this->input->post('zip', true)) == 1) {
                $this->load->library('zip');
                $this->zip->read_file($filePath, true);
                $this->zip->download($file['filename']);
                exit();
            }
            $this->load->helper('download');
            force_download($filePath, NULL);
//      
//      header("Pragma: public");
//      header("Expires: 0");
//      header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
//      header("Content-type:text/plain");
//      header("Content-Length:" . filesize($filePath));
//      header("Content-Disposition: attachment; filename=" . $file['basename']);
//      header("Content-Transfer-Encoding: binary");
//      $result = readfile($filePath);
//      if (!$result) {
//        echo '<script type="text/javascript">window.top.window.fileResult("提交失败，请刷新重试");</script>';
//        exit();
//      }
        } else {
            show_404();
        }
    }

    /**
     * delBackup
     * 简介：删除备份数据库文件
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/21
     */
    function delBackup() {
        if (IS_POST) {
            $fileArr = $this->input->post('fileName', true);
            if (!empty($fileArr)) {
                foreach ($fileArr as $file) {
                    $filePath = 'uploads/backup/' . $file;
                    if (file_exists($filePath)) {
                        $result = unlink($filePath);
                    } else {
                        $result = false;
                        break;
                    }
                }
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * import
     * 简介：本地导入
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-22
     */
    function import() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $post = $this->input->post();
            if (empty($post)) {
                echo '<script type="text/javascript">window.top.window.fileResult("上传文档太大,请重新选择！");</script>';
                exit();
            }
            if (!empty($_FILES) && $_FILES['file']['tmp_name']) {
                $config['upload_path'] = 'uploads/backup';
                $config['allowed_types'] = 'sql|txt';
                $config['max_size'] = current(explode('M', ini_get("upload_max_filesize"))) * 1024;
                $this->upload->initialize($config);
                $this->upload->set_allowed_types('sql|txt');
                if (!$this->upload->do_upload($this->input->post('inputDOM', true))) {
                    $error = array('error' => $this->upload->display_errors());
                    echo '<script type="text/javascript">window.top.window.fileResult("' . dumpHtml($error['error']) . '","");</script>';
                    exit();
                } else {
                    $success = $this->upload->data();
                    echo '<script type="text/javascript">window.top.window.fileResult(2);</script>';
                    exit();
                }
            } else {
                echo '<script type="text/javascript">window.top.window.fileResult("提交失败，选择文件重试(请检查文件是否过大)");</script>';
                exit();
            }
        } else {
            show_404();
        }
    }

    /**
     * optimizeTable
     * 简介：优化数据库
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013/1/24
     */
    function optimize() {
        if (IS_POST) {
            $tables = $this->input->post();
            $result = $this->upload_model->optimizeTable($tables);
            $this->doJson($result);
        } else {
            $data['tableInfo'] = $this->upload_model->getTablesInfo();
            $data['title'] = '数据库管理-优化数据库';
            $this->view('admin/database_optimizetable', $data);
        }
    }

}
