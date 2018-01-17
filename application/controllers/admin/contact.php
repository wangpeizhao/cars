<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Contact extends Fzhao_Controller {
    
    private $title = '';
    
    function __construct() {
        parent::__construct();
        $this->title = '联系我们';
    }

    /**
     * editCompanyProfile
     * 简介：读取公司简介源文件并修改源文件
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018/01/17
     */
    function index() {
        if (!IS_POST) {
            $data['terms'] = $this->common->getTermByTaxonomy('contact');
            $data['id'] = post_get('id');
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $this->view('admin/contact', $data);
            return true;
        }

        $post = $this->input->post(null, true);
        $directory = 'application/views/default/dynamic/';
        $file = $directory . _LANGUAGE_ . '_' . $post['term'] . '_htm_html.php';
        if (isset($post['act']) && $post['act'] == 'get') {
            if (file_exists($file)) {
                $result = str_replace('LWWEB_LWWEB_DEFAULT_URL', site_url(''), html_entity_decode(file_get_contents($file)));
            } else {
                $result = 'Welcome,write something here.';
            }
            !trim($result) && $result = 'Welcome,write something here.';
            $this->doJson($result);
            exit();
        }
        if(!is_really_writable($directory)){
            $this->_doIframe('application/views/default/dynamic 目录不可写!',0);
        }
        $data = array();
        $content = str_replace(_URL_, 'LWWEB_LWWEB_DEFAULT_URL', trim($post['content_' . $post['index']]) ? htmlspecialchars($this->input->post('content_' . $post['index'])) : '');
        writeFile($content, $file);
        $this->_doIframe('提交成功');
    }

}
