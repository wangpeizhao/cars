<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Client_Controller {

    private $title = '';

    public function __construct() {
        parent::__construct();
        $this->load->model('default/news_model', 'admin');
        $this->load->model('default/tag_model', 'tag');
        $this->title = '7×24h 快讯';
    }

    public function newsflash() {
        $this->checkCache();
        $data = array();
        $data['title'] = $this->title;
        //快讯
        $data['newsflash'] = $this->admin->get_newsflash(15);
        //热门标签
        $data['hotTags'] = $this->tag->get_hot_tags(10);
        //热门文章
        $data['hotNews'] = $this->admin->getHotNews(10);
        $this->view('newsflash', $data);
    }

    public function detail() {
        $this->checkCache();
        $id = post_get('id', 2);
        $this->verify($id);
        $data = $this->admin->getRowById($id);
        $this->verify($data);
        $data['term_name'] = $this->admin->getTermById(intval($data['term_id']), 'name');
//        $data['praises'] = 0;
        //相关文章
        $data['related'] = $this->admin->getRelatedNews($data);
        //感兴趣文章
        $data['interested'] = $this->admin->getInterestedNews($data);
        //当前文章标签
        $data['tags'] = $this->tag->get_tags($data['tags']);
        //上一篇
        $prev = $this->admin->getPrev($data);
        if ($prev && $prev['tags']) {
            $prev['tags'] = $this->tag->get_tags($prev['tags']);
        }
        $data['prev'] = $prev;
        //下一篇
        $next = $this->admin->getNext($data);
        if ($next && $next['tags']) {
            $next['tags'] = $this->tag->get_tags($next['tags']);
        }
        $data['next'] = $next;
        $other = array();
        if (!$prev || !$next) {
            //热门标签
            $hotTags = $this->tag->get_hot_tags(10);
            $index = array_rand($hotTags);
            $other = $hotTags[$index];
        }
        $data['other'] = $other;

        //记录浏览量
        $this->_record_view($data);
        
        $this->view('detail', $data);
    }

    private function _record_view($data) {
        if (empty($data['id'])) {
            return false;
        }
        //同一ip每分钟内只记录一次
        $sign = md5(real_ip() . intval($data['id']));
        $viewRecordStatus = $this->session->$sign;
        if (!$viewRecordStatus || _TIME_ - $viewRecordStatus > VIEW_NEWS_RECORD_INTERVAL) {
            $this->admin->trans_start();
            $this->admin->dbSet('news', array('views' => 'views + 1'), array('id' => $data['id'], 'isHidden' => '0'));
            $this->admin->dbSet('term', array('views' => 'views + 1'), array('id' => $data['term_id'], 'isHidden' => '0'));
            $record = array(
                'type' => 'views',
                'oid' => $data['id'],
                'ip' => real_ip(),
                'create_time' => _DATETIME_
            );
            $this->admin->dbInsert('records',$record);
            $this->admin->trans_complete();
        }
        $this->session->$sign = _TIME_;
        return true;
    }
    
    public function doPraises(){
        if(!IS_POST || !IS_AJAX){
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $data = $this->admin->getRowById($id);
        $this->verify($data);
        $checkRecord = $this->admin->checkRecord('praises',$data['id']);
        if($checkRecord){
            $time = $checkRecord['timeLater'];
            errorOutput('你今天已赞啦(^-^),期待你'.$time.'后再赞.');
        }
        $this->admin->trans_start();
        $this->admin->dbSet('news', array('praises' => 'praises + 1'), array('id' => $data['id'], 'isHidden' => '0'));
        $record = array(
            'type' => 'praises',
            'oid' => $data['id'],
            'ip' => real_ip(),
            'create_time' => _DATETIME_
        );
        $this->admin->dbInsert('records',$record);
        $this->admin->trans_complete();
        successOutput(array(),'success.(^-^).');
    }

}
