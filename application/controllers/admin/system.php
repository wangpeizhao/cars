<?php

if (!defined('BASEPATH')){
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
        //$this->checkLP(); //检测登录状态和权限
//        $this->load->library('uri');
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
        if (is_ajax()) {
            $this->_ajax_options();
            return true;
        }
        if (IS_POST) {
            $post = $this->input->post(NULL, TRUE);
            $data = $company = $option = $options = array();
            $result = 0;
            if (!empty($post)) {
                $company['companyName'] = isset($post['companyName']) && trim($post['companyName']) ? $this->input->post('companyName', true) : '';
                $company['companyPhone'] = isset($post['companyPhone']) && trim($post['companyPhone']) ? $this->input->post('companyPhone', true) : '';
                $company['companyFax'] = isset($post['companyFax']) && trim($post['companyFax']) ? $this->input->post('companyFax', true) : '';
                $company['companyAddress'] = isset($post['companyAddress']) && trim($post['companyAddress']) ? $this->input->post('companyAddress', true) : '';
                $company['companyZipCode'] = isset($post['companyZipCode']) && trim($post['companyZipCode']) ? $this->input->post('companyZipCode', true) : '';
                $company['companyEmail'] = isset($post['companyEmail']) && trim($post['companyEmail']) ? $this->input->post('companyEmail', true) : '';
                $company['companyHotline'] = isset($post['companyHotline']) && trim($post['companyHotline']) ? $this->input->post('companyHotline', true) : '';
                $company['companyQQ'] = isset($post['companyQQ']) && trim($post['companyQQ']) ? $this->input->post('companyQQ', true) : '';
                $company['companyWeiXin'] = isset($post['companyWeiXin']) && trim($post['companyWeiXin']) ? $this->input->post('companyWeiXin', true) : '';
                $company['companySkype'] = isset($post['companySkype']) && trim($post['companySkype']) ? $this->input->post('companySkype', true) : '';
                $company['companyLinkman'] = isset($post['companyLinkman']) && trim($post['companyLinkman']) ? $this->input->post('companyLinkman', true) : '';
                $company['companyMobile'] = isset($post['companyMobile']) && trim($post['companyMobile']) ? $this->input->post('companyMobile', true) : '';
                $data['company'] = serialize($company);
                $data['sitesName'] = serialize(isset($post['sitesName']) && trim($post['sitesName']) ? $this->input->post('sitesName', true) : '');
                $data['IndexKeywords'] = serialize(isset($post['IndexKeywords']) && trim($post['IndexKeywords']) ? $this->input->post('IndexKeywords', true) : '');
                $data['IndexDescription'] = serialize(isset($post['IndexDescription']) && trim($post['IndexDescription']) ? $this->input->post('IndexDescription', true) : '');
                $data['sitesName'] = serialize(isset($post['sitesName']) && trim($post['sitesName']) ? $this->input->post('sitesName', true) : '');
                $data['closeSites'] = serialize(isset($post['closeSites']) && intval($post['closeSites']) ? $this->input->post('closeSites', true) : '');
                if (intval($this->input->post('closeSites', true)) > 0) {
                    $data['closeReason'] = serialize(isset($post['closeReason']) && trim($post['closeReason']) ? $this->input->post('closeReason', true) : '');
                } else {
                    $data['closeReason'] = serialize('');
                }
                //$data['Announcement']	= serialize(isset($post['Announcement']) && trim($post['Announcement'])?$this->input->post('Announcement',true):'');
                $data['VideoUrl'] = serialize(isset($post['VideoUrl']) && trim($post['VideoUrl']) ? $this->input->post('VideoUrl', true) : '');
            }
            if (!empty($data)) {
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
            if ($result = $this->system_model->editOptions($options)) {
                if (!empty($_FILES) && $_FILES['logo']['tmp_name']) {
                    $this->load->library('upload');
                    $config['upload_path'] = $path = 'themes/common/images';
                    //$config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['allowed_types'] = 'gif|jpg|jpeg|png';
                    $config['max_size'] = '512';
                    $config['file_name'] = 'logo';
                    $config['overwrite'] = true; //是否覆盖
                    $fileTypes = explode('|', $config['allowed_types']);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('logo')) {
                        $error = array('error' => $this->upload->display_errors());
                        echo '<script type="text/javascript">window.top.window.iResult("' . dumpHtml($error['error']) . '");</script>';
                        exit();
                    } else {
                        $result = $this->upload->data();
                        unset($fileTypes[array_search(ltrim($result['file_ext'], '.'), $fileTypes)]);
                        foreach ($fileTypes as $type) {
                            if (file_exists($path . '/logo.' . $type)) {
                                unlink($path . '/logo.' . $type);
                            }
                        }
                    }
                }
            }
            $this->doIframe($result);
        } else {
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
        }
    }
    
    
    private function _ajax_options(){
        $act = strtolower(trim($this->input->post('act',true)));
        switch($act){
            case 'get':
                $this->_ajax_options_menu_get();
                break;
            case 'post':
                $this->_ajax_options_menu_post();
                break;
        }
    }
    
    private function _ajax_options_menu_get(){
        $plat = trim($this->input->post('plat',true));
        $pid = intval($this->input->post('pid',true));
        $menus = $this->system_model->getData(array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(array('status' => '1'), array('plat' => $plat?$plat:'admin')),
            '_order' => array(array('pid' => 'ASC'), array('sort' => 'desc')),
        ));

        foreach ($menus as &$item) {
            if ($pid == $item['id']) {
                $item['selected'] = 'selected';
            } else {
                $item['selected'] = '';
            }
        }
        //menus select option
        $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
        $this->load->library('tree');
        $tree = new Tree();
        $tree->init($menus);
        $data['select_menus'] = $tree->get_tree(0, $str);
        
        //menus lists
        $menus_lists = $this->system_model->_get_menu_tree_html($menus);
        $data['menus_lists'] = $menus_lists;
        successOutput($data);
    }
    
    private function _ajax_options_menu_post(){
        $parms = $this->input->post('parms',true);
        $edit = false;
        if($parms['act'] == 'edit' && intval($parms['menu_id'])){
            $edit = intval($parms['menu_id']);
        }
        $data = $this->_menus_validation($parms,$edit);
        if($edit){
            $this->system_model->dbUpdate('menus',$data,array('id' => $edit));
            $this->system_model->dbUpdate('priv',array('m' => $data['m'],'c' => $data['c'],'a' => $data['a']),array('menu_id' => $edit));
        }else{
            $this->system_model->dbInsert('menus',$data);
        }
        if($data['plat'] == 'client'){
            $this->navWriteInFile();
        }
        successOutput(array(),'提交成功');
    }
    
    private function _menus_validation($parms,$edit = false){
        if(!$parms){
            errorOutput('参数为空');
        }
        $field = array();
        $field['pid'] = intval($parms['pid']);

        if ($field['pid'] === '') {
            errorOutput('请选择父级菜单');
        }

        $field['m'] = null;
        $field['c'] = null;
        $field['a'] = null;
        $p = null;

        $field['link'] = trim($parms['link']);
        $field['parameter'] = trim($parms['parameter']);
        if ($field['link']) {
//            $match = array();
//            preg_match('/^\/(\w+)\/(\w+)\/(\w+)/', $field['link'], $match);
//            if (!$match) {
//                errorOutput('菜单链接格式不正确');
//            }
            $matchAll = array();
            preg_match_all('/^\/(\w+)\/(\w+)\/(\w+)/', $field['link'], $matchAll);
            $field['m'] = !empty($matchAll[1][0])?$matchAll[1][0]:'';
            $field['c'] = !empty($matchAll[2][0])?$matchAll[2][0]:'';
            $field['a'] = !empty($matchAll[3][0])?$matchAll[3][0]:'';
            $p = !empty($matchAll[4][0])?$matchAll[4][0]:'';
        }
        if($field['link'] && $field['link']!='#'){
            $conditions = array(array('pid >' => 0), array('status' => '1'), array('link' => $field['link']), array('parameter' => $field['parameter']));
            if($edit){
                $conditions[] = array('id !=' => $edit);
            }
            $result = $this->system_model->getData(array(
                'fields' => '*',
                'table' => 'menus',
                '_conditions' => $conditions,
                'row' => true
            ));
            if ($result) {
                errorOutput('该菜单+参数已存在,请确认;或者添加键值为"_p_"的附加参数.');
            }
        }
        $field['title'] = trim($parms['title']);
        $field['link'] = trim($parms['link']);
        $field['link_target'] = trim($parms['link_target']);
        $field['sort'] = intval($parms['sort']);
        $field['show'] = trim($parms['show']);
        $field['plat'] = trim($parms['plat']);
        $field['uid'] = defined('ADMIN_ID')?ADMIN_ID:1;
        !$edit && $field['create'] = time();
        $field['update'] = time();
        return $field;
    }

    /**
     * indexNav
     * 简介：导航列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function indexNav() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['link_type'] = 'indexNav';
            $result = $this->system_model->getLinksList($data);
            $this->doJson($result);
        } else {
            $this->view('admin/links');
        }
    }

    /**
     * menuAdd
     * 简介：添加导航
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function menuAdd() {
        if (is_ajax() && IS_POST) {
            $this->_ajax_options();
            return true;
        }else{
            show_404();
        }
        if (IS_POST) {
            $data = array();
            $post = $this->input->post(NULL, TRUE);
            if (!empty($post['data'][0])) {
                foreach ($post['data'][0] as $key => $item) {
                    $data[$key] = addslashes($item);
                }
            }
            $data['link_url'] = next(explode(WEB_DOMAIN, $data['link_url']));
            $data['link_type'] = 'indexNav';
            $data['link_owner'] = $this->userId;
            $data['link_updated'] = date('Y-m-d H:i:s');
            $data['lang'] = _LANGUAGE_;
            $result = $this->system_model->addLink($data);
            if($data['link_type']=='indexNav'){
                $this->navWriteInFile();
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * menuEdit
     * 简介：修改导航信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function menuEdit() {
        if (is_ajax()) {
            $this->_ajax_options();
            return true;
        }else{
            show_404();
        }
        if (IS_POST) {
            $id = intval($this->input->post('id', true));
            $act = trim($this->input->post('act', true));
            if(!$id){
                $this->doJson(false);
            }
            if ($act == 'delImage') {
                $this->delLinkImage();
                return true;
            }
            $result = $this->system_model->getLink($id);
            if ($act == 'get') {
                $this->doJson($result);
            }
            
            $data = array();
            $post = $this->input->post(NULL, TRUE);
            if (!empty($post['data'][0])) {
                foreach ($post['data'][0] as $key => $item) {
                    $data[$key] = addslashes($item);
                }
            }
            $data['link_url'] = (strpos($data['link_url'], WEB_DOMAIN) !== false) ? next(explode(WEB_DOMAIN, $data['link_url'])) : $data['link_url'];
            $_result = $this->system_model->editIndexNav($data, $id);
            if($result['link_type']=='indexNav'){
                $this->navWriteInFile();
            }
            $this->doJson($_result);
        } else {
            show_404();
        }
    }

    /**
     * menuDel
     * 简介：删除导航
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function menuDel() {
        if (IS_POST) {
            $id = intval($this->input->post('id', true));
            $result = $this->system_model->getData(array(
                'fields' => '*',
                'table' => 'menus',
                'conditions' => array('id' => $id, 'status' => '1'),
                'row' => true
            ));
            if ($result) {
                $result = $this->system_model->dbUpdate('menus',array('status' => '0','update'=>time()),array('id'=>$id));
            } else {
                $this->doJson(false);
            }
            if($result['link_type']=='indexNav'){
                $this->navWriteInFile();
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * navWriteInFile
     * 简介：把导航信息写入文件
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/18
     */
    function navWriteInFile() {
//        $parm['link_type'] = 'indexNav';
//        $parm['currPage'] = 1;
//        $parm['rows'] = 1000;
//        $indexNav = $this->system_model->getLinksList($parm);
        $str = '';
        $menus = $this->system_model->getData(array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(
                array('status' => '1'),
                array('show' => '1'),
                array('plat' => 'client'),
            ),
            '_order' => array(array('pid'=>'asc'),array('sort'=>'desc'))
        ));
        $indexNav = array();
        if($menus){
            foreach($menus as $item){
                if($item['pid']==0){
                    $indexNav[$item['id']] = $item;
                    continue;
                }
                if(empty($indexNav[$item['pid']])){
                    continue;
                }
                $indexNav[$item['pid']]['son_link'][] = $item;
            }
        }
        /*
          if(!empty($nav)){
          foreach($nav as $key=>$item){
          if((strpos($item['link_url'],'http://')!==false || strpos($item['link_url'],'https://')!==false)){
          $str.= '<li><a href="'.$item['link_url'].'" target="'.$item['link_target'].'">'.$item['link_name'].'</a></li>';
          }else{
          $str.= '<li><a<?=(_CLASS==\''.ltrim(($item['link_url']==''?'index':$item['link_url']),'/').'\'?\' class="on"\':\'\')?> href="<?=WEB_DOMAIN?>'.$item['link_url'].'" target="'.$item['link_target'].'">'.$item['link_name'].'</a></li>';
          }
          }
          } */
        if ($indexNav) {
            foreach ($indexNav as $item) {
                $class = 'index';
                $uri = $item['link'];
                if (trim(strlen($uri)) > 3) {
                    if (in_array(substr($uri, 0, 4), array('/en/', '/cn/'))) {
                        $_uri = substr($uri, 4);
                    } else {
                        $_uri = substr($uri, 1);
                    }
                    if ($_uri) {
                        $_uri_arr = explode("/", $_uri);
                        $class = current($_uri_arr);
                    }
                }
                //$class = _CLASS == ltrim(($uri == '' ? 'index' : $uri), '/') ? 'class="on"' : '';
                //hover active
                $str .= '<li' . (!empty($item['son_link']) ? ' class="parent"' : '') . '>';
                if ((strpos($uri, 'http://') !== false || strpos($uri, 'https://') !== false)) {
                    $str.= '<a href="' . $uri . '" target="' . $item['link_target'] . '">' . $item['title'] . '</a>';
                } else {
                    $str.= '<a class="nav_' . $class . '" href="' . WEB_DOMAIN . $uri . '" target="' . $item['link_target'] . '">' . $item['title'] . '</a>';
                }
                if (!empty($item['son_link'])) {
                    $str.= '<dl>';
                    foreach ($item['son_link'] as $son_link) {
                        $str.= '<dd><a href="' . WEB_DOMAIN . $son_link['link'] . '" target="' . $son_link['link_target'] . '">' . $son_link['title'] . '</a></dd>';
                    }
                    $str.= '</dl>';
                }
                $str.= '</li>' . "\n";
            }
            $str.= <<<EOM
<script>
  $(function(){
    var _class = '<?=_CLASS?>';
    $('a.nav_'+_class).parent().addClass('hover active');
  });
</script>
EOM;
        }
        return writeFile($str, 'application/ch/views/default/index_nav_' . _LANGUAGE_ . '.php');
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
        if (IS_POST) {
            if ($this->input->post('act', true) == 'get') {
                $this->doJson(array('IPs' => $this->getOptionValue('prohibitIPs'), 'isOpen' => $this->getOptionValue('isOpen')));
            }
            $result = false;
            $post = $this->input->post(NULL, TRUE);
            //$maxId = $this->system_model->selectMax('option_id','options');
            $option['option_id'] = 1;
            $option['option_name'] = 'isOpen';
            $isOpen = $this->input->post('isOpen', true) ? 1 : 0;
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
        } else {
            show_404();
        }
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
     * links
     * 简介：友情链接
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-20
     */
    function links() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['link_type'] = $this->input->post('link_type', true);
            $result = $this->system_model->getLinksList($data);
            $this->doJson($result);
        } else {
            $data['link_term'] = $this->system_model->getTermByTaxonomy('partners');
            $this->view('admin/links', $data);
        }
    }

    /**
     * addLink
     * 简介：添加友情链接
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-20
     */
    function addLink() {
        if (IS_POST) {
            $post = $this->input->post(NULL, TRUE);
            if (empty($post)) {
                echo '<script type="text/javascript">window.top.window.fileResult("上传文档太大,请重新选择！");</script>';
                exit();
            }
            $post['link_owner'] = $this->userId;
            if ($_FILES['link_image']['tmp_name']) {
                $pathinfo = pathinfo($post['link_url']);
                $post['link_image'] = $this->uploadPic($_FILES['link_image'], 'link');
            }
            $post['lang'] = _LANGUAGE_;
            $result = $this->system_model->addLink($post);
            if ($result) {
                echo '<script type="text/javascript">window.top.window.fileResult(1);</script>';
                exit();
            } else {
                echo '<script type="text/javascript">window.top.window.fileResult("提交失败，请刷新重试");</script>';
                exit();
            }
        } else {
            show_404();
        }
    }

    /**
     * uploadPic
     * 简介：上传图片
     * 参数：Array
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-22
     */
    function uploadPic($FILES, $realmName, $targetFolder = 'uploads/links', $filePath = '') {
        $this->createFolder($targetFolder);
        $tempFile = $FILES['tmp_name'];
        $targetPath = $targetFolder;
        $fileParts = pathinfo($FILES['name']);
        $targetFile = rtrim($targetPath, '/') . '/' . $realmName . '_' . $this->random() . '.' . strtolower($fileParts['extension']);

        $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // File extensions
        $filesize = abs(filesize($tempFile)); //图片大小 
        if ($filesize > 2 * 1024000) {
            clearstatcache();
            echo '<script type="text/javascript">window.top.window.iResult("上传图片太大,请重新选择,\n支持图片大小为小于2MK！");</script>';
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            exit();
        }
        if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
            move_uploaded_file($tempFile, $targetFile);
        } else {
            clearstatcache();
            echo '<script type="text/javascript">window.top.window.iResult("上传图片格式不正确,请重新选择,\n支持图片格式：jpg、jpeg、gif和png！");</script>';
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            exit();
        }
        return $targetFile;
    }

    function random() {
        $seed = current(explode(" ", microtime())) * 10000;
        $random = rand(10000, intval($seed));
        return date("YmdHis", time()) . $random;
    }

    /**
     * delLink
     * 简介：删除友情链接
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-21
     */
    function delLink() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->delLink($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * editLink
     * 简介：修改友情链接
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-21
     */
    function editLink() {
        if (IS_POST) {
            $id = intval($this->input->post('id', true));
            $act = trim($this->input->post('act', true));
            if ($act == 'delLinkImage') {
                $this->delLinkImage();
                exit();
            }
            if (isset($act) && $act == 'get') {
                $result = $this->system_model->getLink($id);
                if ($result['link_image'] && !file_exists($result['link_image'])) {
                    $result['link_image'] = '';
                }
            } else {
                $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
                if (intval($this->uri->segment($n)) && is_numeric($this->uri->segment($n))) {
                    $post = $this->input->post(NULL, TRUE);
                    $post['link_image'] = '';
                    if ($_FILES['link_image']['tmp_name']) {
                        $pathinfo = pathinfo($post['link_url']);
                        $post['link_image'] = $this->uploadPic($_FILES['link_image'], 'link');
                    }
                    if ($post['link_image'] == '') {
                        unset($post['link_image']);
                    }
                    $result = $this->system_model->editLink($post, $this->uri->segment($n));
                    if ($result) {
                        echo '<script type="text/javascript">window.top.window.fileResult(2);</script>';
                        exit();
                    } else {
                        echo '<script type="text/javascript">window.top.window.fileResult("提交失败，请刷新重试");</script>';
                        exit();
                    }
                }
            }
            $this->doJson($result);
        } else {
            show_404();
        }
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
     * comment
     * 简介：留言管理
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/23
     */
    function comment() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getCommentList($data);
            $this->doJson($result);
        } else {
            $this->view('admin/comments');
        }
    }

    /**
     * commentRecycleList
     * 简介：留言回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/8
     */
    function commentRecycleList() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getCommentRecycleList($data);
            $this->doJson($result);
        } else {
            $this->view('admin/commentsRecycleList');
        }
    }

    /**
     * replyComment
     * 简介：回复留言
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/23
     */
    function replyComment() {
        if (IS_POST) {
            $id = intval($this->input->post('id', true));
            $act = trim($this->input->post('act', true));
            $result = $this->system_model->getComment($id);
            if ($act == 'reply') {
                $is_shield = $this->input->post('is_shield', true);
                $replyContent = trim($this->input->post('replyContent', true));
                $this->system_model->replyComment($id, $is_shield, $replyContent);
                if (isset($result['email']) && $result['email']) {
                    $this->config->load('custom_config');
//                    $config = $this->config->item('email_config');
//                    $from = array('email' => $this->config->item('from_mail'), 'title' => $this->config->item('from_title'));
//                    $list = array($result['email']);
//                    $subject = '回复留言-来自国际人才圈';
//                    $str = '<p><a href="http://www.italentedu.com/" target="_blank"><img title="国际人才圈" border="0" src="http://italentedu.com/themes/common/images/logo.gif"></a></p>';
//                    if ($result['username']) {
//                        $str .= '<p style="margin:5px 0;">尊敬的国际人才圈用户 <strong>：' . $result['username'] . '</strong></p>';
//                    } else {
//                        $str .= '<p style="margin:5px 0;">尊敬的国际人才圈用户 <strong>：<a href="mailto:' . $result['email'] . '">' . $result['email'] . '</a></strong></p>';
//                    }
//                    $str .= '<p style="margin:5px 0;text-indent:2em;">您好！</p>';
//                    $str .= '<p style="margin:5px 0;text-indent:2em;">' . $replyContent . '</p>';
//                    $str .= '<p style="margin:15px 0;text-indent:2em;">----------回复在线留言----------</p>';
//                    $str .= '<p style="margin:5px 0;text-indent:2em;">留言时间：<font color="#666666">' . $result['create_time'] . '</font></p>';
//                    $str .= '<p style="margin:5px 0;text-indent:2em;">留言内容：<font color="#666666">' . $result['declare'] . '</font></p>';
//                    $str .= '<p style="margin:15px 0;">此为系统消息，请勿回复。</p>';
//                    $content = $str;
//                    $result = $this->myMail($config, $list, $subject, $content, $from);
                }
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * delProduct
     * 简介：删除(放入回收站)留言
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/8
     */
    function delComment() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            if (is_numeric($id) || is_array($id)) {
                $result = $this->system_model->delComment($id);
            } else {
                $result = false;
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dumpComment
     * 简介：彻底删除留言信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/8
     */
    function dumpComment() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->dumpComment($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recoverComment
     * 简介：还原留言信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-11
     */
    function recoverComment() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->recoverComment($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * classify
     * 简介：分类管理
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-25
     */
    function classify() {
        //$this->config->load('custom_config');
        //$classify = $this->config->item('custom');
        $classify = array();
        if (IS_POST) {
            $result['data'] = $this->system_model->getClassifyList();
            //$result['subclass'] = $classify['products'];
            $this->doJson($result);
        } else {
            $classify['userInfo'] = $this->session->userdata('userInfo');
            $this->view('admin/classify', $classify);
        }
    }

    /**
     * getSunClassify
     * 简介：获取分类子类列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-26
     */
    function getSunClassify() {
        if (IS_POST) {
            $taxonomy = $this->input->post('currPage', true);
            $result = $this->system_model->getClassifyListById($taxonomy);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * addTerm
     * 简介：添加子分类
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/27
     */
    function addTerm() {
        if (IS_POST) {
            $post = $this->input->post(NULL, TRUE);
            $data = array();
            foreach ($post['data'] as $item) {
                $item['owner'] = $this->userId;
                $item['count'] = 0;
                $item['banner'] = '';
                $item['slug'] = trim($item['slug']);
                if (isset($item['subclass'])) {
                    $this->config->load('custom_config');
                    $subclass = $this->config->item('custom');
                    $item['name'] = $subclass['products'][$item['subclass']];
                }
                $item['subclass'] = 0;
                $item['lang'] = _LANGUAGE_;
                $data[] = $item;
            }
            $result = $this->system_model->addTerm($data);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * editTerm
     * 简介：修改分类子类列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-11-27
     */
    function editTerm() {
        if (IS_POST) {
            $data['term_id'] = $this->input->post('term_id', true);
            $data['act'] = $this->input->post('act', true);
            $data['val'] = trim($this->input->post('val', true)); //delSpace
            $data['pid'] = $this->input->post('pid', true);
            if ($data['act'] == 'subclass') {
                $this->config->load('custom_config');
                $subclass = $this->config->item('custom');
                $result = $this->system_model->dbUpdate('term', array($data['act'] => $data['val'], 'name' => $subclass['products'][$data['val']]), array('id' => $data['term_id']));
                $this->doJson($subclass['products'][$data['val']]);
            }
            $result = $this->system_model->editTerm($data);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * delTerm
     * 简介：彻底删除子分类
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/27
     */
    function delTerm() {
        if (IS_POST) {
            $term_id = $this->input->post('term_id', true);
            $p = $this->input->post('p', true);
            $result = $this->system_model->delTerm($term_id, $p);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * delTerm
     * 简介：上传分类Banner
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/28
     */
    function addBanner() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $post = $this->input->post(NULL, TRUE);
            if (empty($post)) {
                echo '<script type="text/javascript">window.top.window.fileResult("上传文档太大,请重新选择！");</script>';
                exit();
            }
            $id = intval($this->input->post('taxonomy', true));
            if ($_FILES['banner']['tmp_name']) {
                $banner = $this->uploadPic($_FILES['banner'], $id, 'uploads/banner');
            }
            $result = $this->system_model->addBanner($id, $banner);
            if ($result) {
                $path = addslashes($this->input->post('path', true));
                $path = str_replace(site_url('') . '/', '', $path);
                if (file_exists($path)) {
                    unlink($path);
                }
                echo '<script type="text/javascript">window.top.window.fileResult(1);</script>';
                exit();
            } else {
                echo '<script type="text/javascript">window.top.window.fileResult("提交失败，请刷新重试");</script>';
                exit();
            }
        } else {
            show_404();
        }
    }

    /**
     * delTerm
     * 简介：上传分类Banner
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/11/28
     */
    function delBanner() {
        if (IS_POST) {
            $term_id = intval($this->input->post('term_id', true));
            $src = addslashes($this->input->post('src', true));
            if ($term_id && $src) {
                $src = str_replace(site_url('') . '/', '', $src);
                if (file_exists($src)) {
                    unlink($src);
                }
                $result = $this->system_model->dbUpdate('term', array('banner' => ''), array('id' => $term_id));
                $this->doJson($result);
            }
        } else {
            show_404();
        }
    }

    /**
     * products
     * 简介：产品管理
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/4
     */
    function products() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getProductsList($data);
            $this->doJson($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/products', $data);
        }
    }

    /**
     * addProducts
     * 简介：添加产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/5
     */
    function addProducts() {
        if (IS_POST) {
            $post = $this->input->post(NULL, TRUE);
            $data = array();
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = isset($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = isset($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            if(!trim($data['title'])){
                $this->doIframe('产品标题不能为空',0);
            }
            $result = $this->system_model->getData(array(
                'fields' => '*',
                'table' => 'products',
                '_conditions' => array(array('is_valid'=>'1'),array('title'=>trim($data['title']))),
                'row' => true
            ));
            if($result){
                $this->doIframe('产品标题已存在',0);
            }
            $data['ft_title'] = wordSegment($data['title']);
            $data['summary'] = trim($post['summary']) ? htmlspecialchars($this->input->post('summary', true)) : '';
            //$data['content']		 = str_replace(site_url(''),'LWWEB_LWWEB_DEFAULT_URL',trim($post['content'])?htmlspecialchars($this->input->post('content')):'');
            $data['content'] = serialize($_POST['contents']);
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            //$data['video_url']		 = trim($post['video_url'])?$this->input->post('video_url',true):'';
            $data['sort'] = intval($this->input->post('sort', true));
            $data['owner'] = $this->userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['thumbPic'] = '';
            $tid = $data['term_id'];
            $data['lang'] = _LANGUAGE_;

            $upload = array();
            $_FILES['thumbPic1']['tmp_name'] && $upload['thumbPic1'] = $_FILES['thumbPic1'];
            $_FILES['thumbPic2']['tmp_name'] && $upload['thumbPic2'] = $_FILES['thumbPic2'];
            $_FILES['thumbPic3']['tmp_name'] && $upload['thumbPic3'] = $_FILES['thumbPic3'];
            $_FILES['thumbPic4']['tmp_name'] && $upload['thumbPic4'] = $_FILES['thumbPic4'];
            $_FILES['thumbPic5']['tmp_name'] && $upload['thumbPic5'] = $_FILES['thumbPic5'];

            if (!empty($upload)) {
                $thumbPic = '';
                for ($i = 1; $i <= 5; $i++) {
                    $imagePath = '';
                    if (isset($upload['thumbPic' . $i])) {
                        $imagePath = $this->uploadPic($upload['thumbPic' . $i], 'p', 'uploads/products/images/images');
                        $this->zoomImage($imagePath, 'products/images');
                    }
                    $thumbPic .= $imagePath . '+++' . (str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''))) . '###';
                }
                $data['thumbPic'] = $thumbPic;
            } else {
                $data['thumbPic'] = next(explode(site_url(''), pregpic($this->input->post('content'))));
                $data['thumbPic'] && $this->zoomImage($data['thumbPic'], 'products/images');
                $data['thumbPic'] .= '+++' . $data['title'] . '###+++###+++###+++###+++###';
            }
            $result = $this->system_model->addProducts($data);
            if ($result) {
                $this->system_model->iUpdate(array('table' => 'term', 'field' => 'count', 'val' => 'count+1', 'id' => $tid));
            }
            $this->doIframe($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/productsAdd', $data);
        }
    }

    function zoomImage_bak($path) {
        $imageInfo = getimagesize($path);
        $pPath = pathinfo($path);
        //生成small缩略图
        $this->load->library('image_lib');
        $img_config['create_thumb'] = TRUE;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'height';
        $img_config['source_image'] = $path;
        $img_config['new_image'] = 'uploads/products/images/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['height'] = 200;
        $img_config['width'] = 200 * $imageInfo[0] / $imageInfo[1];
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe("生成small缩略图失败" . $img_config['new_image']);
        }
        $this->image_lib->clear();

        //生成tiny缩略图
        $img_config['create_thumb'] = TRUE;
        $img_config['source_image'] = $path;
        $img_config['maintain_ratio'] = TRUE;
        $img_config['master_dim'] = 'auto';
        $img_config['new_image'] = 'uploads/products/thumbPic/' . $pPath["basename"]; //指定生成图片的路径
        $img_config['width'] = 50;
        $img_config['height'] = 50 * $imageInfo[1] / $imageInfo[0];
        $this->image_lib->initialize($img_config);
        if (!$this->image_lib->resize()) {
            $this->doIframe("生成tiny缩略图失败" . $img_config['new_image']);
        }
        $this->image_lib->clear();
    }

    /**
     * editProducts
     * 简介：修改产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function editProducts() {
        if (IS_POST) {
            if ($this->input->post('act', true) == 'checkLP') {
                $this->json_success('success');
                exit();
            }
            $post = $this->input->post(NULL, TRUE);
            $data = array();
            $id = intval($post['id']) ? intval($this->input->post('id', true)) : 0;
            $data['term_id'] = intval($post['term_id']) ? intval($this->input->post('term_id', true)) : 0;
            $data['is_commend'] = isset($post['is_commend']) ? intval($this->input->post('is_commend', true)) : 0;
            $data['is_issue'] = isset($post['is_issue']) ? intval($this->input->post('is_issue', true)) : 0;
            //$data['views']			 = intval($post['views'])?intval($this->input->post('views',true)):0;
            $data['title'] = trim($post['title']) ? $this->input->post('title', true) : '';
            $data['ft_title'] = wordSegment($data['title']);
            $data['summary'] = trim($post['summary']) ? htmlspecialchars($this->input->post('summary', true)) : '';
            //$data['content']		 = str_replace(site_url(''),'LWWEB_LWWEB_DEFAULT_URL',trim($post['content'])?htmlspecialchars($this->input->post('content')):'');
            $data['content'] = serialize($_POST['contents']);
            $data['SEOKeywords'] = trim($post['SEOKeywords']) ? htmlspecialchars($this->input->post('SEOKeywords', true)) : '';
            $data['SEODescription'] = trim($post['SEODescription']) ? htmlspecialchars($this->input->post('SEODescription', true)) : '';
            $data['sort'] = intval($this->input->post('sort', true));
            //$data['video_url']		 = trim($post['video_url'])?$this->input->post('video_url',true):'';
            $data['update_time'] = date('Y-m-d H:i:s');

            $upload = array();
            $_FILES['thumbPic1']['tmp_name'] && $upload['thumbPic1'] = $_FILES['thumbPic1'];
            $_FILES['thumbPic2']['tmp_name'] && $upload['thumbPic2'] = $_FILES['thumbPic2'];
            $_FILES['thumbPic3']['tmp_name'] && $upload['thumbPic3'] = $_FILES['thumbPic3'];
            $_FILES['thumbPic4']['tmp_name'] && $upload['thumbPic4'] = $_FILES['thumbPic4'];
            $_FILES['thumbPic5']['tmp_name'] && $upload['thumbPic5'] = $_FILES['thumbPic5'];

            if (!empty($upload)) {
                $thumbPic = explode('###', rtrim($post['thumbPic'], '###'));
                for ($i = 1; $i <= 5; $i++) {
                    $imagePath = '';
                    if (isset($upload['thumbPic' . $i])) {
                        $imagePath = $this->uploadPic($upload['thumbPic' . $i], 'p', 'uploads/products/images/images');
                        $imagePath && $this->zoomImage($imagePath, 'products/images');
                        if (file_exists(current(explode('+++', $thumbPic[$i - 1])))) {
                            $this->dropPic(current(explode('+++', $thumbPic[$i - 1])));
                        }
                        array_splice($thumbPic, $i - 1, 1, $imagePath . '+++' . (str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''))));
                    } else {
                        $thumbArr = explode('+++', $thumbPic[$i - 1]);
                        $thumbArr[1] = str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''));
                        array_splice($thumbPic, $i - 1, 1, implode('+++', $thumbArr));
                    }
                }
                $data['thumbPic'] = implode('###', $thumbPic);
            } else {
                if ($post['thumbPic'] == '+++###+++###+++###+++###+++###') {
                    $data['thumbPic'] = next(explode(site_url(''), pregpic($this->input->post('content'))));
                    $data['thumbPic'] && $this->zoomImage($data['thumbPic'], 'products/images');
                    $data['thumbPic'] .= '+++' . $data['title'] . '###+++###+++###+++###+++###';
                } else {
                    $thumbPic = explode('###', rtrim($post['thumbPic'], '###'));
                    for ($i = 1; $i <= 4; $i++) {
                        $thumbArr = explode('+++', $thumbPic[$i - 1]);
                        $thumbArr[1] = str_replace('###', '***', (trim($post['caption' . $i]) ? $this->input->post('caption' . $i, true) : ''));
                        array_splice($thumbPic, $i - 1, 1, implode('+++', $thumbArr));
                    }
                    $data['thumbPic'] = implode('###', $thumbPic);
                }
            }

            $result = $this->system_model->editProducts($data, $id);
            $this->doIframe($result);
        } else {
            $n = in_array($this->uri->segment(1), array('cn', 'en')) ? 5 : 4;
            $id = intval($this->uri->segment($n));
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $data['product_data'] = $this->system_model->getProductById($id);
            if (isset($data['product_data']['thumbPic']) && $data['product_data']['thumbPic'] && strpos($data['product_data']['thumbPic'], '###') !== FALSE) {
                foreach (explode('###', rtrim($data['product_data']['thumbPic'], '###')) as $key => $item) {
                    $thumbPic = explode('+++', $item);
                    $thumbPic[2] = $thumbPic[0];
                    $thumbPic[0] = $this->getPImageFormat($thumbPic[0]);
                    $data['product_data']['pics'][] = $thumbPic;
                }
            }//ww($data['product_data']['pics']);
            $this->view('admin/productsEdit', $data);
        }
    }

    /**
     * getPImageFormat
     * 简介：根据产品图片路径读取tiny缩略图、small缩略图路径
     * 参数：$path
     * 返回：String
     * 作者：Fzhao
     * 时间：2013/3/26
     */
    function getPImageFormat($path, $format = 'tiny') {
        if ($path && file_exists($path)) {
            $imagePath = explode('/', $path);
            $c = count($imagePath);
            $imagePath[$c - 2] = $format;
            $fileName = explode('.', end($imagePath));
            $imagePath[$c - 1] = $fileName[0] . '_thumb.' . $fileName[1];
            return implode('/', $imagePath);
        } else {
            return false;
        }
    }

    /**
     * 删除图片
     * Fzhao
     * 2013/3/26
     */
    function dropPic() {
        $result = true;
        $id = $this->input->post('id', true);
        $index = $this->input->post('index', true);
        $path = $this->input->post('src', true);
        if (!$path || !file_exists($path)) {
            $this->doJson(false);
        }
        $small = $this->getPImageFormat($path, 'small');
        if (file_exists($small)) {
            unlink($small);
        }
        $tiny = $this->getPImageFormat($path, 'tiny');
        if (file_exists($tiny)) {
            unlink($tiny);
        }
        if (file_exists($path)) {
            unlink($path);
        }
        $this->doJson($result);
    }

    /**
     * productsRecycleList
     * 简介：产品回收站
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function productsRecycleList() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $data['condition'] = $this->input->post('condition', true);
            if (empty($data['condition']) || !is_array($data['condition'])) {
                unset($data['condition']);
            } else {
                $data['condition'] = $data['condition'][0];
            }
            $result = $this->system_model->getProductsRecycleList($data);
            $this->doJson($result);
        } else {
            $data['product_term'] = $this->system_model->getTermByTaxonomy('products');
            $this->view('admin/productsRecycleList', $data);
        }
    }

    /**
     * delProduct
     * 简介：删除(放入回收站)产品
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/6
     */
    function delProduct() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            if (is_numeric($id) || is_array($id)) {
                $result = $this->system_model->delProduct($id);
            } else {
                $result = false;
            }
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * dumpProducts
     * 简介：彻底删除产品信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function dumpProducts() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->dumpProducts($id);
            $this->doJson($result);
        } else {
            show_404();
        }
    }

    /**
     * recoverProducts
     * 简介：还原产品信息
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012-12-9
     */
    function recoverProducts() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->recoverProducts($id);
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
            if (!is_time(date("Y-m-d H:i:s", $time))) {
                $this->doJson('参数错误:时间格式不正确!');
            }
            $result = $this->system_model->getIpChart($time, $type);
            $this->doJson($result);
        }
    }

    /**
     * job
     * 简介：读取招贤纳士列表
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function job() {
        if (IS_POST) {
            $data['currPage'] = $this->input->post('currPage', true);
            $data['rows'] = $this->input->post('rows', true);
            $result = $this->system_model->getJobList($data);
            $this->doJson($result);
        } else {
            $this->view('admin/jobList');
        }
    }

    /**
     * addJob
     * 简介：添加招聘岗位
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function addJob() {
        if (IS_POST) {
            $data['quarters'] = $this->input->post('quarters', true);
            $data['duty'] = $this->input->post('duty', true);
            $data['demand'] = $this->input->post('demand', true);
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['lang'] = _LANGUAGE_;
            $result = $this->system_model->dbInsert('job', $data);
            $this->doIframe($result);
        } else {
            $data['act'] = 'add';
            $this->view('admin/job', $data);
        }
    }

    /**
     * editJob
     * 简介：修改招聘岗位
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function editJob($id) {
        if (IS_POST) {
            $data['quarters'] = $this->input->post('quarters', true);
            $data['duty'] = $this->input->post('duty', true);
            $data['demand'] = $this->input->post('demand', true);
            $data['create_time'] = date('Y-m-d H:i:s');
            $result = $this->system_model->dbUpdate('job', $data, array('id' => $id));
            $this->doIframe($result);
        } else {
            $data['act'] = 'edit';
            $data['data'] = $this->system_model->getData(array('fields' => '*', 'table' => 'job', 'conditions' => array('id' => $id), 'row' => true));
            $this->view('admin/job', $data);
        }
    }

    /**
     * delJob
     * 简介：删除招聘岗位
     * 参数：NULL
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-3-6
     */
    function delJob() {
        if (IS_POST) {
            $id = $this->input->post('id', true);
            $result = $this->system_model->dbDelete('job',array('id'=>$id));
            $this->doJson($result);
        } else {
            show_404();
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
        if (IS_POST) {
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
        } else {
            show_404();
        }
    }
    
    public function adFlexSlider(){
        if(!IS_POST){
            show_404();
        }
        $data['currPage'] = $this->input->post('currPage', true);
        $data['rows'] = $this->input->post('rows', true);
        $data['link_type'] = $this->input->post('link_type', true);
        $result = $this->system_model->getLinksList($data);
        $this->doJson($result);
    }
    
    public function addAdFlexSlider(){
        if(!IS_POST){
            show_404();
        }
        $this->addLink();
    }
    
    public function editAdFlexSlider(){
        if(!IS_POST){
            show_404();
        }
        $act = trim($this->input->post('act',true));
        if($act == 'delImage'){
            $this->delLinkImage();
        }
        $this->editLink();
    }
    
    public function delAdFlexSlider(){
        if(!IS_POST){
            show_404();
        }
        $this->delLink();
    }

}
