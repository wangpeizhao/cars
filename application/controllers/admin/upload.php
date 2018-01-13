<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Upload extends Fzhao_Controller {

  function __construct() {
    parent::__construct();
//    $this->checkLP(); //检测登录状态和权限
    $this->load->helper(array('form', 'url'));
    $this->load->library('upload');
    $this->load->model('admin/upload_model');
  }

  /**
   * uploadImage
   * 简介：上传图片
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  function uploadImage() {
    $config['upload_path'] = 'uploads/products/pictures/images';
    if (IS_POST) {
      if (trim($this->input->post('act', true)) == 'get') {//读取图片
        $data = array();
        $post['currPage'] = $this->input->post('currPage', true);
        $post['rows'] = $this->input->post('rows', true);
        $files = $this->upload_model->getAllFilesByPath('uploads/products/pictures/tiny');
        if (!empty($files)) {
          $files = $files['file'];
          $start = ($post['currPage'] - 1) * $post['rows'];
          $end = $post['currPage'] * $post['rows'];
          foreach ($files as $key => $item) {
            if ($key >= $start && $key <= $end) {
              $f = pathinfo($item);
              $file = dirname($f['dirname']) . '/images/' . rtrim($f['filename'], '_thumb') . '.' . $f['extension'];
              if (file_exists($file)) {
                $data['fullName'][] = $file;
                $data['thumbName'][] = $item;
                $data['fileName'][] = $f['basename'];
              }
              if (!empty($data['fullName']) && count($data['fullName']) == $post['rows']) {
                break;
              }
            }
          }
        }
        $result['files'] = $data;
        $result['count'] = count($files);
        $this->doJson($result);
      } else {//上传图片
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
        foreach ($_files as $key => $item) {
          if (!$this->upload->do_upload($key)) {
            $error = array('error' => $this->upload->display_errors());
            echo '<script type="text/javascript">window.top.window.fileResult("' . dumpHtml($error['error']) . '","");</script>';
            exit();
          } else {
            $success = $this->upload->data();
            $this->zoomImage($config['upload_path'] . '/' . $success['file_name'], 'products/pictures');
          }
        }
        echo '<script type="text/javascript">window.top.window.fileResult(1,"' . site_url() . $config['upload_path'] . '/' . $success['file_name'] . '");</script>';
        exit();
      }
    } else {
      $this->view('admin/uploadImage');
    }
  }

  /**
   * zoomImage
   * 简介：缩放图片(生成缩略图)
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  public function _zoomImage($path) {
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
   * dumpUploadImage
   * 简介：上传图片
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  function dumpUploadImage() {
    if (IS_POST) {
      $path_thumb = str_replace(site_url(''), '', $this->input->post('path_thumb', true));
      $path = str_replace(site_url(''), '', $this->input->post('path', true));
      if ($path_thumb) {
        if (file_exists($path_thumb)) {
          $result = unlink($path_thumb);
        }
      }
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
      $this->view('admin/backup', $data);
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
          $ext = strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
          if($ext != 'sql'){
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
      $this->view('admin/restore', $data);
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
      if ($file['extension']!='zip' || intval($this->input->post('zip', true)) == 1) {
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
      $this->view('admin/optimizetable', $data);
    }
  }

}