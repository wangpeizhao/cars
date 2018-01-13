<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Contact extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
//        $this->checkLP(); //检测登录状态和权限
        $this->load->model('admin/system_model');
    }

    /**
     * editCompanyProfile
     * 简介：读取公司简介源文件并修改源文件
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/10
     */
    function index() {
        if (!IS_POST) {
            $data['terms'] = $this->system_model->getTermByTaxonomy('contactUs');
            $this->view('admin/contact', $data);
            return true;
        }

        $post = $this->input->post(null, true);
        $file = 'application/views/default/' . _LANGUAGE_ . '_' . $post['term'] . '_htm_html.php';
        if (isset($post['act']) && $post['act'] == 'get') {
            if (file_exists($file)) {
                $result = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
                if (!trim($result)) {
                    $result = 'Welcome,write something here.';
                }
            } else {
                $result = 'Welcome,write something here.';
            }
            $this->doJson($result);
            exit();
        }
        $data = array();
        $content = str_replace(site_url(''), 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content_' . $post['index']]) ? htmlspecialchars($this->input->post('content_' . $post['index'])) : '');
        $result = writeFile($content, $file);
        $this->doIframe($result);
    }

}
