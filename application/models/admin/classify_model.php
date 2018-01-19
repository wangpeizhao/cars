<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * News_model
 * 简介：登录后台管理数据库模型
 * 返回：Boole
 * 作者：Parker
 * 时间：2018-01-13
 * 
 */
class Classify_model extends Fzhao_Model {

    private $menuTreesClass;
    private $total;

    public function __construct() {
        parent::__construct();
        $this->table = 'term';
        $this->primary_key = $this->dbPrimary($this->table);
        $this->menuTreesClass = array('mFirst', 'mSecond', 'mThird', 'mFourth', 'mFifth', 'mSixth', 'mSeventh', 'mEighth', 'mNinth', 'mTenth');
    }

    public function _get_menu_tree_html($data, $pId = 0, $deep = 0) {
        $html = '';
        $deepClass = isset($this->menuTreesClass[$deep]) ? $this->menuTreesClass[$deep] : '';
        foreach ($data as $v) {
            if ($v['pid'] == $pId) {//父亲找到儿子
                $html .= '<tr' . ($this->total % 2 == 0 ? ' class="even"' : '') . ' id="_' . $v['id'] . '" _parent="' . $v['parent'] . '" _pid="' . $v['pid'] . '" _name="' . $v['name'] . '" _desc="' . $v['description'] . '" _slug="' . $v['slug'] . '" _sort="' . $v['sort'] . '" _isHidden="' . $v['isHidden'] . '">';
                $html .= '  <td class="' . $deepClass . '" valign="left"><font>' . $v['title'] . '</font></td>';
                $html .= '  <td>' . $v['slug'] . '</td>';
                $html .= '  <td>' . $v['sort'] . '</td>';
                $html .= '  <td>' . $v['description'] . '</td>';
                $html .= '  <td>' . $v['count'] . '</td>';
                $html .= '  <td>' . ($v['isHidden'] == '0' ? '<span class="yes">是</span>' : '<span class="no">否</span>') . '</td>';
                $html .= '  <td>' . $v['create_time'] . '</td>';
                $html .= '  <td class="_action" id="' . $v['id'] . '">'
                        . '<a href="javascript:;" class="_add" act="add" title="添加子分类" _id="' . $v['id'] . '"></a>'
                        . '<a href="javascript:;" class="_edit" act="edit" title="编辑分类" _id="' . $v['id'] . '"></a>'
                        . '<a href="javascript:;" class="_del" act="del" title="删除分类" _id="' . $v['id'] . '"></a>'
                        . '</td>';
                $html .= '</tr>';

                ++$this->total;
                $html .= $this->_get_menu_tree_html($data, $v['id'], $deep + 1);
            }
        }
        return $html;
    }

}
