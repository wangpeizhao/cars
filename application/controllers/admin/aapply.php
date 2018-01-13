<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Aapply extends Fzhao_Controller {

  function __construct() {
    parent::__construct();
    $this->checkLP(); //检测登录状态和权限
    $this->load->library('uri');
    $this->load->model('admin/aapply_model');
    $this->model = $this->aapply_model;
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
        $_SESSION['aapply_conditions'] = null;
      } else {
        $data['condition'] = $data['condition'][0];
        $_SESSION['aapply_conditions'] = $data['condition'];
      }
      $result = $this->model->get_list($data);
      $this->doJson($result);
    } else {
      $data = array();
      $data['terms'] = $this->model->getTermByTaxonomy('products');
      $this->load->view('admin/aapply', $data);
    }
  }

  /**
   * edit
   * 简介：编辑活动报名
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2014-2-26
   */
  function edit($id = null) {
    $id = intval($id);
    $aapply = $this->model->get_info_by_id($id);
    if (IS_POST) {
      if(!$aapply){
        $this->doIframe('找不到数据(数据不存在)');
      }
      $data = array();
      $data['first_name'] = substr(trim($this->input->post('first_name', true)), 0, 20);
      $data['last_name'] = substr(trim($this->input->post('last_name', true)), 0, 20);
      $data['email'] = substr(trim($this->input->post('email', true)), 0, 100);
      $data['address'] = substr(trim($this->input->post('address', true)), 0, 200);
      $data['city'] = substr(trim($this->input->post('city', true)), 0, 50);
      $data['state'] = substr(trim($this->input->post('state', true)), 0, 50);
      $data['country'] = substr(trim($this->input->post('country', true)), 0, 50);
      $data['postal_code'] = substr(trim($this->input->post('postal_code', true)), 0, 10);
      $data['phone'] = substr(trim($this->input->post('phone', true)), 0, 20);
      $data['fax'] = substr(trim($this->input->post('fax', true)), 0, 20);
      $data['subject'] = substr(trim($this->input->post('subject', true)), 0, 100);
      $data['message'] = substr(trim($this->input->post('message', true)), 0, 20);
      $data['update_time'] = date('Y-m-d H:i:s');
      $result = $this->model->dbUpdate('inquires', $data, array('id' => $id));
      $this->doIframe($result);
    } else {
      if(!$aapply){
        show_404();
      }
      $data['aapply'] = $aapply;
      $this->load->view('admin/aapply_edit', $data);
    }
  }

  /**
   * view
   * 简介：活动报名预览
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2014-1-26
   */
  function view($id = null) {
    if (IS_POST) {
      if ($this->input->post('act', true) == 'checkLP') {
        $this->json_success('success');
        exit();
      }
      $id = $this->input->post('id', true);
      if (is_numeric($id) && $id) {
        $result = true;
      } else {
        $result = false;
      }
      $this->doJson($result);
    } else {
      $data['apply'] = $this->model->get_info_by_id($id);
      $this->load->view('admin/aapply_view', $data);
    }
  }

  /**
   * print
   * 简介：活动报名打印
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2014-2-26
   */
  function printing($id = null) {
    $id = current(explode('.', $id));
    if (is_numeric($id)) {
      $condition['condition'] = array('type' => 'aid', 'keywords' => intval($id));
    } else {
      $condition['condition'] = isset($_SESSION['aapply_conditions']) && $_SESSION['aapply_conditions'] ? $_SESSION['aapply_conditions'] : array();
    }
    $data = array();
    $result = $this->model->get_list($condition);
    if ($result && $result['data']) {
      $data['print'] = $result['data'];
    }
    $this->load->view('admin/aapply_print', $data);
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
    if (IS_POST) {
      $data['currPage'] = $this->input->post('currPage', true);
      $data['rows'] = $this->input->post('rows', true);
      $data['condition'] = $this->input->post('condition', true);
      if (empty($data['condition']) || !is_array($data['condition'])) {
        unset($data['condition']);
      } else {
        $data['condition'] = $data['condition'][0];
      }
      $result = $this->model->get_recycle_list($data);
      $this->doJson($result);
    } else {
      $data = array();
      $data['terms'] = $this->model->getTermByTaxonomy('activities');
      $this->load->view('admin/aapply_recycle', $data);
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

  /**
   * download
   * 简介：下载
   * 参数：NULL
   * 返回：Array
   * 作者：Fzhao
   * 时间：2014-2-18
   */
  function down_load() {
    if (IS_POST) {
      $data['condition'] = isset($_SESSION['aapply_conditions']) && $_SESSION['aapply_conditions'] ? $_SESSION['aapply_conditions'] : array();
      $fileName = '产品询盘_' . date('YmdHis') . '.csv';
      $outputTitle = array(kc_iconv('产品分类,产品名称,FirstName,LastName,Email,详细地址,市,省(州),国家,邮编,电话,传真,留言标题,Message,创建时间'));
      $domainArr = array();
      $outputData = array();
      $data['currPage'] = 1;
      $data['rows'] = 1000000000;
      $result = $this->model->get_list($data);
      if ($result && $result['data']) {
        foreach ($result['data'] as $item) {
          unset($item['id'], $item['proId'], $item['is_active'], $item['slug'], $item['update_time']);
          $item['message'] = dumpHtml(html_entity_decode($item['message']));
          $item['create_time'] = "'" . $item['create_time'];
          $item['phone'] = "'" . $item['phone'];
          $outputData[] = implode(',', $item);
        }
      }
      $this->outputFile($fileName, array_merge($outputTitle, $outputData));
    } else {
      show_404();
    }
  }

  //导出文件$fileName:文件路径及名称,$data:所有数据,$type文件类型
  function outputFile($fileName, $data, $type = 'text/csv') {//www($data);
    $fp = fopen($fileName, 'w');
    //fwrite($fp,chr(0xEF).chr(0xBB).chr(0xBF));
    $data = $this->array_iconv('GBK', $data);
    foreach ($data as $line) {
      fputcsv($fp, explode(',', $line));
    }
    if (fclose($fp)) {
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
      header("Content-type:" . $type);
      header("Content-Length:" . filesize($fileName));
      header("Content-Disposition: attachment; filename=" . $fileName);
      header("Content-Transfer-Encoding: binary");
      if (readfile($fileName)) {
        if (file_exists($fileName)) {
          unlink($fileName);
        }
      }
    }
  }

}

?>