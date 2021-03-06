<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        defined('_URL_')  OR define('_URL_', site_url(''));
    }

    /**
     * getLogo
     * 简介：获取Logo路径 根据后缀名
     * 参数：null
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/13
     */
    function getLogo() {
        $path = 'themes/common/images';
        $logo = '';
        foreach (explode('|', 'gif|jpg|jpeg|png') as $type) {
            if (file_exists($path . '/logo.' . $type)) {
                $logo = site_url($path . '/logo.' . $type);
                break;
            }
        }
        return $logo;
    }

    /**
     * getOptionValue
     * 简介：读取配置值
     * 参数：null
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/13
     */
    function getOptionValue($option_name) {
        static $option;
        if (!isset($option)) {
            $option = $this->common->getOptionValue();
        }
        return isset($option[$option_name]) ? $option[$option_name] : null;
    }

    /**
     * checkClose
     * 简介：检测是否关闭网站
     * 参数：null
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/19
     */
    function checkClose() {
        if (_ADMIN) {
            return true;
        }
        if ($this->getOptionValue('closeSites') == 1) {
            header('Content-Type: text/html; charset=utf-8');
            exit($this->getOptionValue('closeReason'));
        }
    }

    /**
     * prohibitIPs
     * 简介：检测是否禁止IP访问
     * 参数：null
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013-1-5
     */
    function prohibitIPs() {
        if ($this->getOptionValue('isOpen') != 1) {
            return true;
        }
        $ips = $this->getOptionValue('prohibitIPs');
        if ($ips) {
            if (in_array(real_ip(), explode(';', $ips))) {
                header('Content-Type: text/html; charset=utf-8');
                exit('警告！您已被本站通缉，强烈禁止您访问...');
            }
        }
        return true;
    }

    /**
     * visitIp
     * 简介：记录访问IP
     * 参数：null
     * 返回：Array
     * 作者：Fzhao
     * 时间：2012/12/19
     */
    function visitIp() {
        if (_ADMIN) {
            return true;
        }
        $this->load->library('session');
        if (!$this->session->userdata('visitIp')) {
            $this->common->visit_ip(real_ip());
            $this->session->set_userdata('visitIp', real_ip());
        }
    }

    /**
     * array_iconv
     * 简介：数组转码
     * 参数：$out_charset $arr
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013/1/22
     */
    function array_iconv($out_charset, $arr) {
        $encode = mb_detect_encoding(var_export($arr, true), array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
        return eval('return ' . iconv($encode, $out_charset, var_export($arr, true) . ';'));
    }

    function checkCache() {
        $cacheTime = $this->getOptionValue('cacheTime');
        if ($cacheTime > 0) {
            $this->output->cache($cacheTime);
        }
    }

    /**
     * Echo result to browser
     * @link URL 
     * @param mixed $result<p>
     * The string or array being echo to browser.
     * Array will be json_encode.
     * </p>
     * @param Int $status<p>
     * status for this action,will push to brower only when $result is string
     * 2 Warning;1 Success;0 Error
     * </p>
     * @param Bool $lang<p>
     * True will get from Language 
     * </p>
     * @param String $callback<p>
     * A function name that will be callback to browser.
     * </p>
     * @param String $exit<p>
     * if exit?
     * </p>
     *
     * @return  String if <i>program</i> is successful.
     * @author Parker <2016-01-18>
     */
    public function _doIframe($result, $status = 1, $callback = '', $exit = true) {
        if ($result) {
            if (is_array($result)) {
                echo '<script type="text/javascript">var data = \'' . str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($result))) . '\';window.top.window.iResult(data,"' . $callback . '");</script>';
            } else {
                echo '<script type="text/javascript">window.top.window.iResultAlter(\'' . strip_tags(addslashes($result)) . '\',' . (is_string($status)?"'".$status."'":$status) . ');</script>';
            }
            $exit && exit();
        } else {
            echo '<script type="text/javascript">window.top.window.iResultAlter(\'Unknown error.\');</script>';
            exit();
        }
    }

    //传送结果
    public function doIframe($result) {
        if ($result) {
            echo '<script type="text/javascript">window.top.window.iResult("' . kc_iconv($result) . '");</script>';
            exit();
        } else {
            echo '<script type="text/javascript">window.top.window.iResult("提交失败，请刷新重试");</script>';
            exit();
        }
    }

    //传送结果
    function doJson($result) {
        if ($result) {
            echo json_encode(array('done' => true, 'data' => $result));
            exit();
        } else {
            echo json_encode(array('done' => false));
            exit();
        }
    }

    public function verify($data, $message = "Data Can Not Be Null.") {
        if ($data) {
            return true;
        }
        IS_AJAX && errorOutput($message);
        IS_POST && $this->_doIframe($message, 0);
        $this->load->view('errors/html/error_general', array('heading' => '错误提示', 'message' => $message));
        $this->output->_display();
        exit();
    }

}

class Fzhao_Controller extends My_Controller {

    protected $hasPower = false; //是否有权限进入后台
    protected $isLogin = false;
    public $userId = 0;
    protected $grade = 0;
    protected $currMenuId = 0;
    protected $supervisor = false;

    public function __construct() {
        parent::__construct();
//        session_start();
        $this->load->library('session');
        $this->authentical();

        define('LOGO', $this->getLogo()); //logo 地址
        define('SITESNAME', $this->getOptionValue('sitesName')); //网站名称
//        $this->checkClose(); //是否关闭网站
//        $this->prohibitIPs(); //是否禁止IP访问
//        $this->visitIp(); //记录访问IP
        $this->userId = 0;

        //记录操作日志
        $this->_loging();
    }

    //Don't need to login
    protected function _rules() {
        return array(
            'login' => array('index', 'logout', 'vCode', 'checkVCode'),
//            'index' => array('index'),
            'system' => array('index'),
            'denied' => array('index'),
        );
    }

    /**
     * [rules 用于排除需要验证的方法,但需要是登录状态]
     * @return [type] [description]
     */
    public function rules() {
        return array(
            'array' => array(//增加Controller验证,但为了兼容已重写的rules,就保留了原有的格式
                'upload' => array('index','del','uploading'),
            )
        );
    }

    private function authentical() {
        //获取当前的action
        $curMethod = $this->router->method;
        $curClass = $this->router->class;
        $_rules = $this->_rules(); //Don't need to login in
        $adminLoginInfo = $this->session->userdata('adminLoginInfo');
        $this->userId = $adminLoginInfo ? intval($adminLoginInfo['id']) : 0;
        if (isset($_rules[$curClass]) && in_array($curMethod, $_rules[$curClass])) {
            //not need to do anything.
        } else {
            $rules = $this->rules(); //Only need to login in
            $array = array();
            if (isset($rules['array'])) {
                $array = $rules['array'];
                unset($rules['array']);
            }
            if ($this->userId && is_array($rules) && in_array($curMethod, $rules)) {
                //not need to do anything.
            } else if ($this->userId && is_array($array) && isset($array[$curClass]) && in_array($curMethod, $array[$curClass])) {
                //not need to do anything.
            } else {
                $this->_authentical();
            }
        }
        $this->grade = intval($adminLoginInfo['role_id']);
        $this->isLogin = true;
        defined('ADMIN_ID') or define('ADMIN_ID', $this->userId);
        defined('ADMIN_USERNAME') or define('ADMIN_USERNAME', $adminLoginInfo['nickname']);
    }

    /**
     * [_authentical 验证是否登地的辅助方法]
     * @return [type] [description]
     */
    private function _authentical() {
        //判断当前菜单是否存在[start]
        //if(!$this->isPremier){
        //获取当前控制器
        $curController = strtolower($this->router->class);
        //获取当前的action
        $curMethod = $this->router->method;
        $directory = rtrim($this->router->directory, '/');
        $this->load->model('admin/menu_model', 'menu');
        $curMenu = $this->menu->getData(array(
            'fields' => 'id,parameter',
            'table' => 'menus',
            '_conditions' => array(array('m' => $directory), array('c' => $curController), array('a' => $curMethod ? $curMethod : 'index'), array('pid >' => 0), array('status' => '1')),
                //'row' => true
        ));
        if (!$curMenu) {
            is_ajax() && errorOutput('你没有权限访问');
            IS_POST && $this->_doIframe('Access Denied:You have no permission to access.', 0);
            if (!isset($_SERVER['HTTP_REFERER']) || !$_SERVER['HTTP_REFERER'] || "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] == $_SERVER['HTTP_REFERER']) {
                $this->error('没权限访问,请联系系统管理员', '/admin/login/index');
            } else {
                $this->error('没权限访问,请联系系统管理员');
            }
        }
        $_p_ = trim($this->input->get('_p_', true));
        if (count($curMenu) == 1) {
            $this->currMenuId = $curMenu[0]['id'];
        } else if ($_p_) {
            foreach ($curMenu as $item) {
                if ($item['parameter'] == '_p_=' . $_p_) {
                    $this->currMenuId = $item['id'];
                    break;
                }
            }
        }
        if (!$this->currMenuId && $curMenu) {
            $this->currMenuId = $curMenu[0]['id'];
        }
        //}
        //判断当前菜单是否存在[end]
        $adminLoginInfo = $this->session->userdata('adminLoginInfo');
        if (!$adminLoginInfo) {
            is_ajax() && errorOutput('登录超时，请重新登录');
            IS_POST && $this->_doIframe('Access Denied:Login timeout,please login again.', 0);
            $http_url = "?url=" . urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            $this->error('登录超时，请重新登录', '/admin/login/index' . $http_url);
        }

        //总的超级管理员
        if ($this->supervisor) {
            return true;
        }

        //检查是否有权限[start]
        //当前URL
        $curr_url = '/' . $this->router->directory . $this->router->class . '/' . ($this->router->method ? $this->router->method : 'index');
        //当前用户所拥有的权限
        $authorized = !empty($_SESSION['authorized']) ? $_SESSION['authorized'] : array(); //$adminLoginInfo['authorized'];
        //无权限
//        www($curr_url);ww($authorized);
        if (!$authorized || !in_array($curr_url, $authorized)) {
            is_ajax() && errorOutput('你没有权限访问');
            IS_POST && $this->_doIframe('Access Denied:You have no permission to access.', 0);
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            if ("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] == $referer || strpos($referer, $_SERVER['REQUEST_URI']) !== false) {
                $referer = '/admin/login/logout';
            }
            $this->error('没权限访问,请联系系统管理员.', $referer ? $referer : '/admin/login/logout');
        }
        //检查是否有权限[end]
    }

    //后台权限控制
    function check_power() {
        $userInfo = $this->session->userdata('adminLoginInfo');
        if ($userInfo && intval($userInfo['id']) > 0) {
            $this->userId = intval($userInfo['id']);
            $this->grade = intval($userInfo['grade']);
            $this->isLogin = true;
            $this->config->load('freePower');
            $freePowers = $this->config->item('adminFreePower');
            $curRequest = _CLASS . (_METHOD ? '-' . _METHOD : '');
            $userPowers = explode('|', $userInfo['regulars']);
            if ($this->grade == 2) {//2为超级管理员 
                $this->hasPower = true;
            } else if (in_array($curRequest, $freePowers) || in_array($curRequest, $userPowers)) {
                $this->hasPower = true;
            }
        } else {//需登录
            if (!IS_POST) {
                $http_url = str_replace('index.php/', '', "?url=http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? "?" . $_SERVER['QUERY_STRING'] : ''));
                redirect(WEB_DOMAIN . '/admin/login' . $http_url, 'refresh');
            }
        }
        if (!$this->hasPower) {//没权限
            if (!IS_POST) {
                $this->error('没权限访问,请联系系统管理员.');
            }
        }
    }

    /**
     * downLoad
     * 简介：下载
     * 参数：$fileName $data
     * 返回：Array
     * 作者：Fzhao
     * 时间：2013/1/22
     */
    function downLoad($fileName, $data, $type = 'text/csv') {
        $fp = fopen($fileName, 'w');
        //fwrite($fp,chr(0xEF).chr(0xBB).chr(0xBF));
        $data = $this->array_iconv('UTF-8', $data);
        foreach ($data as $line) {
            fputcsv($fp, $line);
        }
        if (fclose($fp)) {
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Component: must-revalidate, post-check=0, pre-check=0");
            header("Content-type:" . $type);
            header("Content-Length:" . filesize($fileName));
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Transfer-Encoding: binary");
            (readfile($fileName) && file_exists($fileName)) && unlink($fileName);
        }
    }

    public function view($path, $data = array(), $isLayout = 1) {
        if ($isLayout) {
            $this->load->model('admin/menu_model', 'menu');
            $this->menu->get_left_nav_bar($data, $this->supervisor, $this->grade, $this->currMenuId);
        }
        $this->load->view($path, $data);
    }

    public function error($message, $url = '') {
        if(!$url){
            $url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
            if("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']==$url){
                $url = '/admin/login/logout';
            }
        }
        $data = array('heading' => '异常提示', 'message' => $message,'url' => $url);
        $this->load->view('errors/html/error_access_denied', $data);
        $this->output->_display();
        exit();
    }

    /**
     * 记录操作日志
     * @link All URLs
     * @param NULL<p>
     * 
     * </p>
     *
     * @return  bool <b>TRUE</b> if <i>program</i> is successful.
     * @author Parker <date>
     */
    private function _loging() {
        $ignore = $this->input->post_get('_ignore_', TRUE);
        if (!empty($ignore)) {
            return true;
        }
        $HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $method = IS_POST?'post':'get';
        $log_info = get_curr_url() . ' --- from[' . $method . '] ----- ' . $HTTP_REFERER;
        $data = format_data($this->input->POST(NULL, TRUE));
        $log = array(
            'uid' => ADMIN_ID,
            'log_info' => $log_info,
            'log_post' => $data,
            'ip' => real_ip(),
            'log_time' => _DATETIME_,
        );
        $this->common->dbInsert('admin_log', $log);
    }
    
    //获取操作人真实姓名
    protected function set_administrator_real_name(&$lists,$assoc = 'uid',$assocName='administrator'){
        if(!$lists){
            return false;
        }
        $_uids = array_column($lists, $assoc);
        if($_uids){
            $_uids = array_filter(array_unique($_uids));
        }
        if(!$_uids){
            foreach($lists as &$item){
                $item[$assocName] = '系统管理员';
            }
            return false;
        }
        $conditions = array();
        $conditions[] = array('isHidden' => '0');

        $_users = $this->common->getData(array(
            'fields' => 'id,nickname',
            'table' => 'admin',
            '_conditions' => $conditions,
            'ins' => array(array('id'=>$_uids)),
        ));
        $users = array_column($_users, 'nickname','id');
        foreach($lists as &$item){
            $item[$assocName] = isset($users[$item[$assoc]])?$users[$item[$assoc]]:'系统管理员';
        }
    }

}

class Client_Controller extends My_Controller {

    public function __construct() {
        parent::__construct();
        //初始化语言
//        $this->load->helper('language');
//        $this->_initLanguage();
//
        define('LOGO', $this->getLogo()); //logo 地址
        define('SITESNAME', $this->getOptionValue('sitesName')); //网站名称
        define('SITESNAMESHORT', $this->getOptionValue('sitesShortName')); //网站简称
        $this->checkClose(); //是否关闭网站
        $this->prohibitIPs(); //是否禁止IP访问
        $this->visitIp(); //记录访问IP
    }

    //初始化语言
    private function _initLanguage() {
        $lang_support = config_item('support_language');
        $lang_url = $this->uri->segment(1);
        $lang_default = config_item('language');
        $lang_current = ( FALSE !== in_array($lang_url, $lang_support) ) ? $lang_url : $lang_default;

        $this->config->set_item('language', $lang_current);
        $this->lang->load('MrParker', $lang_current);

        return;
    }

    public function view($path, $data = array()) {
        $this->load->model('default/home_model');
        $data['options'] = $this->common->getOptionsByOption_name('company');
        $data['links'] = $this->home_model->getLinks('link');
        $data['foot_mappings'] = array(
            'contactUs' => 'contact',
            'products' => 'products',
            'sheji' => 'sheji',
            'cases' => 'cases',
            'company' => 'company'
        );
        $data['foot_terms'] = $this->home_model->getTermByTaxonomy(array('contactUs', 'products', 'sheji', 'cases', 'company')); //ww($data['foot_terms']);
        
        $this->config->load('custom_config');
        $data['uninterested'] = $this->config->item('uninterested');
        $data['feedback'] = $this->config->item('feedback');
        
        $this->load->view('default/'.$path, $data);
    }

}

// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/Controller.php */