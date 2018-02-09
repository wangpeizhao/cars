<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Collect extends Fzhao_Controller {
    
    private $title = '';
    
    function __construct() {
        parent::__construct();
        $this->load->model('admin/collect_model','collect');
        $this->title = '采集数据';
    }

}
