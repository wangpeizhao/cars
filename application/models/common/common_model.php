<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * getOptionValue
     * 简介：根据option_value
     * 参数：$type 
     * 返回：Array
     * 作者：Parker
     * 时间：2018/01/13
     */
    function getOptionValue($name = '') {
        $options = $this->getData(array(
            'fields' => '*',
            'table' => 'options',
            'conditions' => $name ? array('option_name' => $name, 'lang' => _LANGUAGE_) : array('lang' => _LANGUAGE_)
        ));
        $optionsData = $companyData = array();
        if (!empty($options)) {
            foreach ($options as $key => $item) {
                if ($item['option_name'] == 'company') {
                    $companyData = unserialize($item['option_value']);
                } else {
                    $optionsData[$item['option_name']] = unserialize($item['option_value']);
                }
            }
        }
        return array_merge($companyData, $optionsData);
    }

    /**
     * editOptions
     * 简介：记录UV
     * 参数：新数据
     * 返回：Boole
     * 作者：Parker
     * 时间：2018/01/13
     */
    function visit_ip($ip) {
        if ($ip) {
            $data = $this->getData(array(
                'table' => 'visit_ip',
                'conditions' => array('create_time >=' => date("Y-m-d"), 'ip' => $ip, 'lang' => _LANGUAGE_),
                'count' => true
            ));
            if (empty($data)) {//记录UV
                return $this->db->insert('visit_ip', array('ip' => $ip));
            }
        }
        return false;
    }

}
