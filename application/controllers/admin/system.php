<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * 后台管理控制器
 * @author  Fzhao
 * @date    2012-11-8
 * @return  
 */
class System extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/system_model');
    }

    function index() {
        $data = array();
        $data['data']['products'] = $this->system_model->getData(array(
            'table' => 'products',
            'conditions' => array('is_valid' => 1),
            'count' => true,
        ));
        $data['data']['news'] = $this->system_model->getData(array(
            'table' => 'news',
            'conditions' => array('is_valid' => 1),
            'count' => true,
        ));
        $data['data']['services'] = $this->system_model->getData(array(
            'table' => 'services',
            'conditions' => array('is_valid' => 1),
            'count' => true,
        ));
        $data['data']['admin'] = $this->system_model->getData(array(
            'table' => 'admin',
            'conditions' => array('is_valid' => 1),
            'count' => true,
        ));
        //$data['link'] = $this->system_model->getLinkCount();
        //$data['comment'] = $this->system_model->getCommentCount();
        $this->view('admin/setting', $data);
    }

    /**
     * options
     * 简介：网站管理-网站设置
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/12
     */
    function options() {
        if (!IS_POST) {
            $path = 'themes/common/images';
            $data['logo'] = '';
            foreach (explode('|', 'gif|jpg|jpeg|png') as $type) {
                if (file_exists($path . '/logo.' . $type)) {
                    $data['logo'] = $path . '/logo.' . $type;
                }
            }
            $data['options'] = $this->system_model->getOptions('admin');
            //$data['indexB'] = $this->system_model->getTermByTaxonomy('newsInfo');
            //$data['indexM'] = $this->system_model->getTermByTaxonomy('company');
            $this->view('admin/options', $data);
            return true;
        }
        $post = $this->input->post(NULL, TRUE);
        $data = $company = $option = $options = array();
        if (!empty($post)) {
            $company['companyName'] = trim($post['companyName']);
            $company['companyPhone'] = trim($post['companyPhone']);
            $company['companyFax'] = trim($post['companyFax']);
            $company['companyAddress'] = trim($post['companyAddress']);
            $company['companyZipCode'] = trim($post['companyZipCode']);
            $company['companyEmail'] = trim($post['companyEmail']);
            $company['companyHotline'] = trim($post['companyHotline']);
            $company['companyQQ'] = trim($post['companyQQ']);
            $company['companyWeiXin'] = trim($post['companyWeiXin']);
            $company['companySkype'] = trim($post['companySkype']);
            $company['companyLinkman'] = trim($post['companyLinkman']);
            $company['companyMobile'] = trim($post['companyMobile']);
            $data['company'] = serialize($company);
            $data['sitesName'] = serialize(trim($post['sitesName']));
            $data['IndexKeywords'] = serialize(trim($post['IndexKeywords']));
            $data['IndexDescription'] = serialize(trim($post['IndexDescription']));
            $data['sitesName'] = serialize(trim($post['sitesName']));
            $data['closeSites'] = serialize(intval($post['closeSites']));
            $data['closeReason'] = serialize(intval($post['closeSites']) > 0 ? trim($post['closeReason']) : '');
            $data['Announcement'] = serialize(trim($post['Announcement']));
            $data['VideoUrl'] = serialize(trim($post['VideoUrl']));
            $i = 1;
            foreach ($data as $key => $item) {
                $option['option_id'] = _LANGUAGE_ . '_' . $i;
                $option['option_name'] = $key;
                $option['option_value'] = $item;
                $option['lang'] = _LANGUAGE_;
                $options[] = $option;
                $i++;
            }
        }

        $result = $this->system_model->editOptions($options);
        if (!$result) {
            $this->_doIframe('提交失败', 0);
        }
        if (!empty($_FILES['logo']['tmp_name'])) {
            $this->load->library('upload');
            $config['upload_path'] = $path = 'themes/common/images';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = '512';
            $config['file_name'] = 'logo';
            $config['overwrite'] = true; //是否覆盖
            $fileTypes = explode('|', $config['allowed_types']);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('logo')) {
                $error = $this->upload->display_errors();
                $this->_doIframe($error, 0);
            } else {
                $result = $this->upload->data();
                unset($fileTypes[array_search(ltrim($result['file_ext'], '.'), $fileTypes)]);
                foreach ($fileTypes as $type) {
                    file_exists($path . '/logo.' . $type) && unlink($path . '/logo.' . $type);
                }
            }
        }
        $this->_doIframe('提交成功');
    }

    /**
     * prohibitIp
     * 简介：禁止IP访问
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-1-5
     */
    function prohibitIp() {
        if (!IS_POST) {
            
        }
        if ($this->input->post('act', true) == 'get') {
            $this->doJson(array('IPs' => $this->getOptionValue('prohibitIPs'), 'isOpen' => $this->getOptionValue('isOpen')));
        }
        $post = $this->input->post(NULL, TRUE);
        //$maxId = $this->system_model->selectMax('option_id','options');
        $option['option_id'] = 1;
        $option['option_name'] = 'isOpen';
        $isOpen = intval($post['isOpen']);
        $option['option_value'] = serialize($isOpen);
        $options[] = $option;
        if ($isOpen == 1) {
            $option['option_id'] = 2;
            $option['option_name'] = 'prohibitIPs';
            $option['option_value'] = serialize($this->input->post('IPs', true));
            $options[] = $option;
        }
        $result = $this->system_model->editOptions($options);
        $this->doIframe($result);
    }

    /**
     * cache
     * 简介：设置缓存时间
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-1-30
     */
    function cacheTime() {
        if (!IS_POST) {
            show_404();
        }
        if ($this->input->post('act', true) == 'checkLP') {
            $this->json_success('success');
            exit();
        }
        if ($this->input->post('act', true) == 'get') {
            $this->doJson(array('cacheTime' => $this->getOptionValue('cacheTime')));
        }
        $cacheTime = $this->input->post('cache', true);
        if ($cacheTime == 0) {
            $path = 'application/cache/en/';
            $files = array();
            $handle = opendir($path);
            while ($file = readdir($handle)) {
                if ($file != '.' && $file != '..') {
                    if (file_exists($path . '/' . $file)) {
                        if ($file != 'index.html' && $file != '.htaccess') {
                            unlink($path . '/' . $file);
                        }
                    }
                }
            }

            $path = 'application/cache/cn/';
            $files = array();
            $handle = opendir($path);
            while ($file = readdir($handle)) {
                if ($file != '.' && $file != '..') {
                    if (file_exists($path . '/' . $file)) {
                        if ($file != 'index.html' && $file != '.htaccess') {
                            unlink($path . '/' . $file);
                        }
                    }
                }
            }
        }
        $option['option_id'] = 8;
        $option['option_name'] = 'cacheTime';
        $option['option_value'] = serialize($cacheTime);
        $options[] = $option;
        $result = $this->system_model->editOptions($options);
        $this->doIframe($result);
    }

    /**
     * delLinkImage
     * 简介：删除友情链接图片
     * 参数：
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-22
     */
    function delLinkImage() {
        if (IS_POST) {
            $imgPath = $this->input->post('imgPath', true);
            $link_id = $this->input->post('link_id', true);
            if ($imgPath && file_exists($imgPath)) {
                unlink($imgPath);
            }
            $result = $this->system_model->dbUpdate('links', array('link_image' => '', 'lang' => _LANGUAGE_), array('link_id' => $link_id));
//            $result = $this->system_model->updateLinkImage($link_id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * ipChart
     * 简介：读取、下载访问IP
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/10
     */
    function ipChart() {
        if (!IS_POST) {
            show_404();
        }
        $act = $this->input->post('act', true);
        if ($act && $act == 'get' || $act == 'down') {
            $time = intval($this->input->post('time', true));
            $val = trim($this->input->post('val', true));
            $downTtype = intval($this->input->post('downTtype', true));
            if ($downTtype) {
                $type = $this->input->post('type', true);
                $time = array($downTtype, $type);
            }
            $order = 'acs';
            if ($act == 'down') {
                $order = 'desc';
            }
            $result = $this->system_model->getIpList_S_D($time, $val, $order);
            if ($act == 'down') {
                $newResult = array();
                if (!empty($result)) {
                    foreach ($result as $item) {
                        $item['create_time'] = $item['create_time'] . chr(1);
                        $newResult[] = $item;
                    }
                }
                $this->downLoad(date("YmdHis") . '.csv', array_merge(array(array('IP', 'Visit Time')), $newResult));
            } else {
                $this->doJson($result);
            }
        } else {
            $time = $this->input->post('time', true);
            $type = $this->input->post('type', true);
            if (strtotime(date("Y-m-d H:i:s", $time)) != $time) {
                $this->doJson('参数错误:时间格式不正确!');
            }
            $result = $this->system_model->getIpChart($time, $type);
            $this->doJson($result);
        }
    }

    /**
     * market
     * 简介：营销服务标记
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013/3/11
     */

    /**
     * sendemail
     * 简介：发邮件
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2014-3-2
     */
    function sendemail() {
        if (!IS_POST) {
            show_404();
        }
        $type = intval($this->input->post('email_type', true));
        $email_address = $this->input->post('email_address', true);
        $email_subject = $this->input->post('email_subject', true);
        $email_content = $this->input->post('email_content', true);
        $email_content = str_replace("\n", '<br>', $email_content);
        $email_content = str_replace("\r\n", '<br>', $email_content);
        $subject = $email_subject . '-来自王培照';
        $content = $email_content;
        $this->config->load('custom_config');
        $config = $this->config->item('email_config');
        $from = array('email' => $this->config->item('from_mail'), 'title' => $this->config->item('from_title'));
        $emails = array();
        $lists = array();
        switch ($type) {
            case 1:
                $lists = explode(',', $email_address);
                break;
            case 2:
                $member = $this->system_model->get_email_list_by_type('inquires');
                if ($member) {
                    foreach ($member as $item) {
                        $lists[] = $item['email'];
                    }
                }
                $result = true;
                break;
        }
        if ($lists) {
            foreach ($lists as $item) {
                if (is_email($item)) {
                    $emails[] = $item;
                }
            }
        }
        $emails && $result = $this->myMail($config, $emails, $subject, $content, $from);
        if ($result) {
            $this->doIframe('e');
        }
        $this->doIframe($result);
    }

    public function adFlexSlider() {
        if (!IS_POST) {
            show_404();
        }
        $data['currPage'] = $this->input->post('currPage', true);
        $data['rows'] = $this->input->post('rows', true);
        $data['link_type'] = $this->input->post('link_type', true);
        $result = $this->system_model->getLinksList($data);
        $this->doJson($result);
    }

    public function addAdFlexSlider() {
        if (!IS_POST) {
            show_404();
        }
        $this->addLink();
    }

    public function editAdFlexSlider() {
        if (!IS_POST) {
            show_404();
        }
        $act = trim($this->input->post('act', true));
        if ($act == 'delImage') {
            $this->delLinkImage();
        }
        $this->editLink();
    }

    public function delAdFlexSlider() {
        if (!IS_POST) {
            show_404();
        }
        $this->delLink();
    }

}
