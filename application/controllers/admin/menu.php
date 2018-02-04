<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Menu extends Fzhao_Controller {

    private $title = '';

    function __construct() {
        parent::__construct();
        $this->load->model('admin/menu_model', 'admin');
        $this->title = '菜单';
    }

    /**
     * news
     * 简介：菜单
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-17
     */
    function index() {
        if(!IS_POST){
            show_404();
        }
        $plat = trim($this->input->post('plat', true));
        $pid = intval($this->input->post('pid', true));
        $menus = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(array('status' => '1'), array('plat' => $plat ? $plat : 'admin')),
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
        $menus_lists = $this->admin->_get_menu_tree_html($menus);
        $data['menus_lists'] = $menus_lists;
        successOutput($data);
    }

    /**
     * edit
     * 简介：修改新闻资讯
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function edit() {
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $info = $this->admin->getRowById($id);
        $this->verify($info);

        $data = $this->_verifyForm($id);
        $this->admin->edit($data, $id);
        $this->admin->dbUpdate('priv', array('m' => $data['m'], 'c' => $data['c'], 'a' => $data['a']), array('menu_id' => $id));
        if ($data['plat'] == 'client') {
            $this->navWriteInFile();
        }
        
        successOutput('修改成功');
    }

    /**
     * add
     * 简介：添加
     * 参数：NULL
     * 返回：Array
     * 作者：Parker
     * 时间：2018-01-13
     */
    function add() {
        if (!IS_POST) {
            show_404();
        }
        $data = $this->_verifyForm();

        $this->admin->add($data);
        if ($data['plat'] == 'client') {
            $this->navWriteInFile();
        }
        successOutput('添加成功');
    }

    private function _verifyForm($edit = false) {
        $parms = $this->input->post('parms', true);
        if (!$parms) {
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
            $matchAll = array();
            preg_match_all('/^\/(\w+)\/(\w+)\/(\w+)/', $field['link'], $matchAll);
            $field['m'] = !empty($matchAll[1][0]) ? $matchAll[1][0] : '';
            $field['c'] = !empty($matchAll[2][0]) ? $matchAll[2][0] : '';
            $field['a'] = !empty($matchAll[3][0]) ? $matchAll[3][0] : '';
            $p = !empty($matchAll[4][0]) ? $matchAll[4][0] : '';
        }
        if ($field['link'] && $field['link'] != '#') {
            $conditions = array(array('pid >' => 0), array('status' => '1'), array('link' => $field['link']), array('parameter' => $field['parameter']));
            if ($edit) {
                $conditions[] = array('id !=' => $edit);
            }
            $result = $this->admin->getData(array(
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
        $field['uid'] = defined('ADMIN_ID') ? ADMIN_ID : 1;
        !$edit && $field['create'] = _TIME_;
        $field['update'] = _TIME_;
        return $field;
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
        if (!IS_POST) {
            show_404();
        }
        $id = post_get('id');
        $this->verify($id);
        $info = $this->admin->getRowById($id);
        $this->verify($info);
        $pMenu = $this->admin->getData(array(
            'fields' => 'id',
            'table' => 'menus',
            '_conditions' => array(array('pid' => $id),array('status' => '1')),
        ));
        if ($pMenu) {
            errorOutput('该菜单存在子菜单,不允许删除!');
        }
        $result = $this->admin->edit(array('status' => '0', 'update' => _TIME_),$id);
        if(!$result){
            errorOutput('删除失败,请重试');
        }
        if ($info['plat'] == 'client') {
            $this->navWriteInFile();
        }
        successOutput('删除成功');
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
        $directory = 'application/views/default/dynamic/';
        if(!is_really_writable($directory)){
            errorOutput('application/views/default/dynamic 目录不可写!');
        }
        $str = '';
        $menus = $this->admin->getData(array(
            'fields' => '*',
            'table' => 'menus',
            '_conditions' => array(
                array('status' => '1'),
                array('show' => '1'),
                array('plat' => 'client'),
            ),
            '_order' => array(array('pid' => 'asc'), array('sort' => 'desc'))
        ));
        $indexNav = array();
        if ($menus) {
            foreach ($menus as $item) {
                if ($item['pid'] == 0) {
                    $indexNav[$item['id']] = $item;
                    continue;
                }
                if (empty($indexNav[$item['pid']])) {
                    continue;
                }
                $indexNav[$item['pid']]['son_link'][] = $item;
            }
        }
        if (!$indexNav) {
            return writeFile($str, $directory.'/index_nav_' . _LANGUAGE_ . '.php');
        }
        foreach ($indexNav as $item) {
            $uri = $item['link'];
            $class = get_style_class($uri);
//            if (trim(strlen($uri)) > 3) {
//                if (in_array(substr($uri, 0, 4), array('/en/', '/cn/'))) {
//                    $_uri = substr($uri, 4);
//                } else {
//                    $_uri = substr($uri, 1);
//                }
//                if ($_uri) {
//                    $_uri_arr = explode("/", $_uri);
//                    $class = current($_uri_arr);
//                }
//            }
            //hover active
            $str .= '<li' . (!empty($item['son_link']) ? ' class="parent"' : '') . '>';
            $target = $item['link_target']?' target="' . $item['link_target'] . '"':'';
            $em = !empty($item['son_link'])?'<em></em>':'';
            if ((strpos($uri, 'http://') !== false || strpos($uri, 'https://') !== false)) {
                $str.= '<a href="' . $uri . '" '.$target.'>' . $item['title'] . '</a>';
            } else {
                $href = $uri=='#'?'javascript:;':WEB_DOMAIN . $uri;
                $str.= '<a class="nav_' . $class . '" href="' . $href . '" '.$target.'>' . $item['title'] . '</a>';
            }
            $str.= $em;
            if (!empty($item['son_link'])) {
                $str.= '<dl>';
                foreach ($item['son_link'] as $son_link) {
                    $target = $son_link['link_target']?' target="' . $son_link['link_target'] . '"':'';
                    $str.= '<dd><a href="' . WEB_DOMAIN . $son_link['link'] . '"'.$target.'>' . $son_link['title'] . '</a></dd>';
                }
                $str.= '</dl>';
            }
            $str.= '</li>' . "\n";
        }
        $str.= <<<EOM
<script>
  $(function(){
    var _class = '<?=get_style_class()?>';
    $('a.nav_'+_class).parent().addClass('hover active');
  });
</script>
EOM;
        return writeFile($str, $directory.'/index_nav_' . _LANGUAGE_ . '.php');
    }
}
