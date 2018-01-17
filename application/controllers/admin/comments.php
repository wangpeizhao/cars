<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Comments extends Fzhao_Controller {
    
    private $title = '';
    function __construct() {
        parent::__construct();
        $this->load->model('admin/comments_model', 'admin');
        $this->title = '留言';
    }

    /**
     * news
     * 简介：新闻资讯
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function index() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->lists($data);
            $this->doJson($result);
        } else {
            $data['title'] = $this->title;
            $data['_title_'] = $this->title;
            $data['terms'] = $this->admin->getTermByTaxonomy('comments');
            $this->view('admin/comments', $data);
        }
    }

    /**
     * recycles
     * 简介：回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recycles() {
        $data = array();
        if (IS_POST) {
            $data = $this->input->post(null, true);
            $data['currPage'] = getPages();
            $data['rows'] = getPageSize();
            $result = $this->admin->recycles($data);
            $this->doJson($result);
        } else {
            $data['terms'] = $this->admin->getTermByTaxonomy('comments');
            $data['title'] = $this->title.'回收站';
            $data['_title_'] = $this->title;
            $this->view('admin/comments_recycles', $data);
        }
    }

    /**
     * del
     * 简介：删除(放入回收站)
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function del() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->del($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dump
     * 简介：彻底删除信息
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function dump() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->dump($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recover
     * 简介：还原信息
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function recover() {
        if (IS_POST) {
            $id = post_get('id');
            $this->verify($id);
            $result = $this->admin->recover($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }
    
    /**
     * batch
     * 简介：批量操作
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-15
     */
    function batch() {
        if (IS_POST) {
            $type = post_get('val');
            $this->verify($type,'操作类型不能为空');
            $ids = $this->input->post('ids', true);
            $this->verify($ids,'请至少选择一项');
            $conditions = array('lang'=>_LANGUAGE_,'isHidden'=>'0');
            $data = array();
            switch ($type){
                case '1';//批量标记公开
                    $data = array('is_public' => '1');
                    break;
                case '2';//批量取消公开
                    $data = array('is_public' => '0');
                    break;
                case '3';//批量标记屏蔽
                    $data = array('is_shield' => '1');
                    break;
                case '4';//批量取消屏蔽
                    $data = array('is_shield' => '0');
                    break;
            }
            $result = $this->admin->dbUpdateIn('comments',$data,$conditions,array('id'=>$ids));
            $this->doJson($result);
        } else {
            show_404();
        }
    }
    
    public function reply(){
        if(!IS_POST){
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $comment = $this->admin->getRowById($id);
        $this->verify($comment);
        $act = trim($this->input->post('act',true));
        if($act == 'get'){
            successOutput($comment);
            return true;
        }else if($act == 'reply'){
            $updateData = array();
            $updateData['is_shield'] = (string)intval($this->input->post('is_shield',true));
            $updateData['replyContent'] = trim($this->input->post('replyContent',true));
            $this->admin->dbUpdate('comments',$updateData,array('id' => $id));
            successOutput('success');
        }
    }
    
    private function _send_email($result,$replyContent){
        $config = $this->config->item('email_config');
        $from = array('email' => $this->config->item('from_mail'), 'title' => $this->config->item('from_title'));
        $list = array($result['email']);
        $subject = '回复留言-来自国际人才圈';
        $str = '<p><a href="http://www.italentedu.com/" target="_blank"><img title="国际人才圈" border="0" src="http://italentedu.com/themes/common/images/logo.gif"></a></p>';
        if ($result['username']) {
            $str .= '<p style="margin:5px 0;">尊敬的国际人才圈用户 <strong>：' . $result['username'] . '</strong></p>';
        } else {
            $str .= '<p style="margin:5px 0;">尊敬的国际人才圈用户 <strong>：<a href="mailto:' . $result['email'] . '">' . $result['email'] . '</a></strong></p>';
        }
        $str .= '<p style="margin:5px 0;text-indent:2em;">您好！</p>';
        $str .= '<p style="margin:5px 0;text-indent:2em;">' . $replyContent . '</p>';
        $str .= '<p style="margin:15px 0;text-indent:2em;">----------回复在线留言----------</p>';
        $str .= '<p style="margin:5px 0;text-indent:2em;">留言时间：<font color="#666666">' . $result['create_time'] . '</font></p>';
        $str .= '<p style="margin:5px 0;text-indent:2em;">留言内容：<font color="#666666">' . $result['declare'] . '</font></p>';
        $str .= '<p style="margin:15px 0;">此为系统消息，请勿回复。</p>';
        $content = $str;
        return $this->myMail($config, $list, $subject, $content, $from);
    }

}
