<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Search extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        $this->checkLP(); //检测登录状态和权限
        $this->load->library('uri');
        $this->load->model('admin/search_model');
        $this->model = $this->search_model;
        $this->title = '防伪信息';
        $this->chipTitle = '芯片信息';
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
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->title;
            $this->load->view('admin/search', $data);
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
        if (IS_POST) {
            $post = $this->input->post();
            $data = array();
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            
            $searchData = $this->model->getData(array(
                'fields' => '*',
                'table' => 'search',
                'conditions' => array('title' => trim($data['title']),'is_valid' => '1','type' => 'antifake', 'lang' => _LANGUAGE_),
                'row' => true
            ));
            if($searchData){
                $this->doIframe('防伪码已存在,请重新输入,防伪码必须保持唯一',0);
            }
            if(trim($post['content']) == 'Write something here.'){
                $this->doIframe('请填写内容',0);
            }

            $data['type'] = 'antifake';
            //$data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['owner'] = $this->userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['lang'] = _LANGUAGE_;
            $data['is_issue'] = intval($this->input->post('is_issue', true));
            $data['sort'] = intval($this->input->post('sort', true));

            $tid = $data['term_id'];
            $result = $this->model->add($data);
            if ($result) {
                $this->model->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count+1', 'id' => $tid));
            }
            $this->doIframe($result);
        } else {
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->title;
            $this->load->view('admin/searchAdd', $data);
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
        if (IS_POST) {
            $id = intval($this->input->get_post('id', true));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                $this->doIframe('找不到数据', 0);
            }
            $post = $this->input->post(null,true);
            $data = array();
            //$path = addslashes($this->input->post('path', true));
            $id = intval($post['id']) ? intval($this->input->post('id', true)) : 0;
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            //$data['is_commend'] = intval($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = intval($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            //$data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['sort'] = intval($this->input->post('sort', true));
            $data['update_time'] = date('Y-m-d H:i:s');

            $result = $this->model->dbUpdate('search', $data, array('id' => $id));
            $this->doIframe($result);
        } else {
            $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
            $id = intval($this->uri->segment($n));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                show_404();
            }
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['data'] = $service;
            $data['title'] = $this->title;
            $this->load->view('admin/searchEdit', $data);
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
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->title;
            $this->load->view('admin/searchRecycle', $data);
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
     * index
     * 简介：活动列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-1-19
     */
    function chip() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->model->get_list($data,1,'chip');
            $this->doJson($result);
        } else {
            $data = array();
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->chipTitle;
            $this->load->view('admin/searchChip', $data);
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
    function addChip() {
        if (IS_POST) {
            $post = $this->input->post();
            $data = array();
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            
            $searchData = $this->model->getData(array(
                'fields' => '*',
                'table' => 'search',
                'conditions' => array('title' => trim($data['title']),'is_valid' => '1','type' => 'chip', 'lang' => _LANGUAGE_),
                'row' => true
            ));
            if($searchData){
                $this->doIframe('防伪码已存在,请重新输入,防伪码必须保持唯一',0);
            }
            if(trim($post['content']) == 'Write something here.'){
                $this->doIframe('请填写内容',0);
            }

            $data['type'] = 'chip';
            //$data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['owner'] = $this->userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['lang'] = _LANGUAGE_;
            $data['is_issue'] = intval($this->input->post('is_issue', true));

            $tid = $data['term_id'];
            $result = $this->model->add($data);
            if ($result) {
                $this->model->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count+1', 'id' => $tid));
            }
            $this->doIframe($result);
        } else {
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->chipTitle;
            $this->load->view('admin/searchChipAdd', $data);
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
    function editChip($id = null) {
        if (IS_POST) {
            $id = intval($this->input->get_post('id', true));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                $this->doIframe('找不到数据', 0);
            }
            $post = $this->input->post(null,true);
            $data = array();
            //$path = addslashes($this->input->post('path', true));
            $id = intval($post['id']) ? intval($this->input->post('id', true)) : 0;
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            //$data['is_commend'] = intval($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = intval($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            //$data['ft_title'] = wordSegment($data['title']);
            $data['content'] = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content']) ? htmlspecialchars($this->input->post('content')) : '');
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['update_time'] = date('Y-m-d H:i:s');

            $result = $this->model->dbUpdate('search', $data, array('id' => $id));
            $this->doIframe($result);
        } else {
            $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
            $id = intval($this->uri->segment($n));
            $service = $this->model->get_info_byI_id($id);
            if (!$service) {
                show_404();
            }
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['data'] = $service;
            $data['title'] = $this->chipTitle;
            $this->load->view('admin/searchChipEdit', $data);
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
    function delChip() {
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
    function recycleChip() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->model->get_recycle_list($data,'chip');
            $this->doJson($result);
        } else {
            $data = array();
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->chipTitle;
            $this->load->view('admin/searchChipRecycle', $data);
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
    function recoverChip() {
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
    function dumpChip() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->model->dump($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }
    
    public function import(){
        if(!IS_POST){
            $data['terms'] = $this->model->getTermByTaxonomy('search');
            $data['title'] = $this->title;
            $this->load->view('admin/searchImport', $data);
            return true;
        }
        $term_id = intval($this->input->post('term_id',true));
        $content = trim($this->input->post('content',true));
        $type = trim($this->input->post('type',true));
        if(!$term_id){
            $this->_doIframe('请先选择分类',0);
        }
        if(!$content){
            $this->_doIframe('请填写模板内容',0);
        }
        $replace = strpos($content,'@KEYWORD@')!==false;
        $importData = array();
        if($type == 'file'){
            $this->load->library('Abaophpexcel', $_FILES, 'phpexcel'); //$_FILES 为参数，phpexcel为别名
            $r_validate = $this->phpexcel->ValidateUploadFile();
            if (!is_bool($r_validate)) {
              $this->_doIframe($r_validate,0);
            }
            $r_upload = $this->phpexcel->DoUploadFile();
            !is_bool($r_upload) && $this->_showProgress($r_upload);
            !is_bool($r_upload) && $this->phpexcel->doIframe($r_upload);
            $sheetdata = $this->phpexcel->DuilderReader('getSheet', '0');
            if(!$sheetdata){
                $this->_doIframe('数据获取错误,请检查工作簿名称',0);
            }
            foreach($sheetdata as $item){
                $importData[] = trim(current($item));
            }
        }else{
            $plain = trim($this->input->post('plain',true));
            if(!$plain){
                $this->_doIframe('请先填写内容,多个请换行即回车分隔',0);
            }
            $importData = explode(PHP_EOL,$plain);
        }
        $this->_showProgress('正在写入数据服务器');
        $insertData = array(
            'term_id' => $term_id,
            'type' => 'antifake',
            'is_valid' => 1,
            'views' => 0,
            'is_issue'=>1,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'lang' => _LANGUAGE_,
            'owner' => $this->userId
        );
        $insertDatas = array();
        foreach($importData as $item){
            $insertData['title'] = $item;
            $insertData['content'] = $replace?str_replace('@KEYWORD@',$item,$content):$content;
            $insertDatas[] = $insertData;
        }
        $insertDatas && $this->model->dbInsertBatch('search',$insertDatas);
        $this->_showProgress('导入成功');
        $this->_doIframe('导入成功');
    }

  /**
   * show Progress
   * @link /
   * @param String $msg<p>
   * Index of Message.
   * </p>
   * 
   * @return <b>Object</b> if <i>program</i> is successful.
   */
  private function _showProgress($msg) {
    ob_get_level() > 0 && ob_end_flush();
    //$msg = mb_convert_encoding($msg, "UTF-8", "auto");
    echo '<script type="text/javascript">window.top.window.showProgress("' . $msg . str_pad(" ", 949) . '");</script>';
    ob_implicit_flush(true);
  }

}
