<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Index extends Fzhao_Controller {

    function __construct() {
        parent::__construct();
        //$this->checkLP();
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
            'table' => 'cases',
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

    function server() {
        $this->load->view('admin/server');
    }

    function phpinfo() {
        $this->load->view('admin/phpinfo');
    }

}
