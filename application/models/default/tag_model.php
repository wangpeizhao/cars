<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tag_model extends Fzhao_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'term';
        $this->primary_key = $this->dbPrimary($this->table);
    }
    
    public function getTermRowByTaxonomy($taxonomy){
        if(!$taxonomy){
            return false;
        }
        $term = $this->getData(array(
            'fields' => 'id,name,parent,slug,taxonomy',
            'table' => $this->table,
            '_conditions' => array(array('isHidden' => '0'), array('lang' => _LANGUAGE_),array('taxonomy' => $taxonomy)),
            'row' => true
        ));
        return $term;
    }

}
