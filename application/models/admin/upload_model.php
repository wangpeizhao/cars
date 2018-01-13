<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Upload_model
 * 简介：后台管理数据库模型
 * 返回：Boole
 * 作者：Fzhao
 * 时间：2012-11-8
 */
class Upload_model extends Fzhao_Model {

  public function __construct() {
    parent::__construct();
  }

  /**
   * getImagesByPath
   * 简介：读取文件夹里的所有原图片
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  function getAllFilesByPath($path) {
    $files = array();
    $handle = opendir($path);
    while ($file = readdir($handle)) {
      if ($file != '.' && $file != '..') {
        $files['file'][] = $path . '/' . $file;
        $files['doDate'][] = filemtime($path . '/' . $file);
      }
    }
    $newFiles = array();
    if (!empty($files['doDate'])) {
      $newFiles['doDate'] = $files['doDate'];
      arsort($newFiles['doDate']);
      foreach ($newFiles['doDate'] as $key => $item) {
        $newFiles['file'][] = $files['file'][$key];
      }
    }
    return $newFiles;
  }

  /**
   * getAllVideoFilesByPath
   * 简介：读取文件夹里的所有视频
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  function getAllVideoFilesByPath($path) {
    $files = array();
    $handle = opendir($path);
    while ($file = readdir($handle)) {
      if ($file != '.' && $file != '..') {
        if (file_exists($path . '/' . $file)) {
          $files[] = $path . '/' . $file;
        }
      }
    }
    arsort($files);
    return $files;
  }

  /**
   * getTablesInfo
   * 简介：获取数据库表信息
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012-12-20
   */
  function getTablesInfo() {
    return $this->db->query("SHOW TABLE STATUS FROM " . $this->db->database . " LIKE '" . $this->db->dbprefix . "%'")->result_array();
  }

  /**
   * optimizeTable
   * 简介：优化数据库(清理碎片)
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2013/1/24
   */
  function optimizeTable($tables) {
    $result = array();
    /*
      $tableStatus = $this->getTablesInfo();
      if(!empty($tableStatus)){
      foreach($tableStatus as $item){
      if((isset($item['Engine']) && $item['Engine']=='InnoDB') || (isset($item['Type']) && $item['Type']=='InnoDB')){
      $result['result'] = false;
      $result['msg'] = '该优化不支持类型为innodb的数据表！ \n但可以修改数据表类型再优化，强烈建议在修改数据表类型前务必先备份好数据! \n选择【确定】将自动修改数据表类型并优化数据表；选择【取消】将停留于该页面或前去备份';
      break;
      }
      }
      }
      if(!empty($result)){
      return $result;
      }
     */
    $tables = $tables['tables'];
    if (!empty($tables)) {
      foreach ($tables as $item) {
        $status = $this->db->query("SHOW TABLE STATUS FROM " . $this->db->database . " LIKE '" . $item . "'")->result_array();
        if ((isset($status[0]['Engine']) && $status[0]['Engine'] == 'InnoDB') || (isset($status[0]['Type']) && $status[0]['Type'] == 'InnoDB')) {
          $result = $this->db->query("ALTER TABLE " . $item . " ENGINE = InnoDB;");
        } else {
          $result = $this->db->query("OPTIMIZE TABLE " . $item)->result_array();
        }
      }
    }
    return true;
  }

  /**
   * backup
   * 简介：备份数据库(表)
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012-12-20
   */
  function backup($table) {
    $this->load->dbutil(); // 加载数据库工具类
    $fileName = 'uploads/backup/' . $this->db->database . '_' . date("Ymd") . '_' . date("His") . '_' . rand(10, 99) . '.zip';
    $prefs = array(
      'tables' => $table, // 包含了需备份的表名的数组.
      'ignore' => array(), // 备份时需要被忽略的表
      'format' => 'zip', // gzip, zip, txt
      'filename' => $fileName, // 文件名 - 如果选择了ZIP压缩,此项就是必需的
      'add_drop' => TRUE, // 是否要在备份文件中添加 DROP TABLE 语句
      'add_insert' => TRUE, // 是否要在备份文件中添加 INSERT 语句
      'newline' => "\n"               // 备份文件中的换行符
    );
    
    $backup = $this->dbutil->backup($prefs);
    $this->load->helper('file');
    write_file($fileName, $backup);
    return $fileName;
//    if (writeFile($this->dbutil->backup($prefs), $fileName)) {
//      return $fileName;
//    } else {
//      return false;
//    }

    /*
      $this->load->dbutil();// 加载数据库工具类
      $postfix = '.sql';
      $fileName = $this->db->database.'_'.date("Ymd").'_'.date("His").'_'.rand(10,99);
      $fileFull = 'uploads/backup/'.$this->db->database.'_'.date("Ymd").'_'.date("His").'_'.rand(10,99);
      $prefs = array(
      'tables'      => $table,  // 包含了需备份的表名的数组.
      'ignore'      => array(),           // 备份时需要被忽略的表
      'format'      => 'txt',             // gzip, zip, txt
      'filename'    => $fileFull.$postfix,    // 文件名 - 如果选择了ZIP压缩,此项就是必需的
      'add_drop'    => TRUE,              // 是否要在备份文件中添加 DROP TABLE 语句
      'add_insert'  => TRUE,              // 是否要在备份文件中添加 INSERT 语句
      'newline'     => "\n"               // 备份文件中的换行符
      );
      $data = $this->dbutil->backup($prefs);
      $this->load->library('zip');
      $this->zip->add_data($fileName.$postfix,$data);
      if($this->zip->archive($fileFull.'.zip')){
      //if(writeFile($this->dbutil->backup($prefs),$fileName)){
      return $fileFull.'.zip';
      }else{
      return false;
      }
     */
  }

  /**
   * getBackup
   * 简介：读取备份文件
   * 参数：null
   * 返回：Boole
   * 作者：Fzhao
   * 时间：2012-12-9
   */
  function getBackup($path) {
    $files = $newFiles = array();
    $handle = opendir($path);
    while ($file = readdir($handle)) {
      if ($file != '.' && $file != '..' && $file && $file != '') {
        $files['filename'] = $file;
        $files['filesize'] = bytes(filesize($path . '/' . $file));
        $files['filetime'] = date("Y-m-d H:i:s", filemtime($path . '/' . $file));
        $newFiles[] = $files;
      }
    }
    return array_sort($newFiles, 'filetime', 'desc');
  }

  //执行恢复
  function runRes($sqlList) {
    foreach ($sqlList as $val) {
      $this->parseSQL($val);
    }
    return true;
  }

  //解析备份文件中的SQL
  function parseSQL($fileName) {
    $fhandle = fopen($fileName, 'r');
    while (!feof($fhandle)) {
      $lstr = fgets($fhandle); //获取指针所在的一行数据
      //判断当前行存在字符
      if (isset($lstr[0]) && $lstr[0] != '#') {
        $prefix = substr($lstr, 0, 2);  //截取前2字符判断SQL类型

        switch ($prefix) {
          case '--' :
          case '//' : {
              continue;
            }
          case '/*': {
              if (substr($lstr, -5) == "*/;\r\n" || substr($lstr, -4) == "*/\r\n")
                continue;
              else {
                $this->skipComment($fhandle);
                continue;
              }
            }

          default : {
              $sqlArray[] = trim($lstr);
              if (substr(trim($lstr), -1) == ";") {
                $sqlStr = join($sqlArray);
                $sqlArray = array();
                $this->query($sqlStr);
                //回调函数
                $this->actionCallBack($fileName);
              }
            }
        }
      }
    }
  }

  //略过注释
  function skipComment($fhandle) {
    $lstr = fgets($fhandle, 4096);
    if (substr($lstr, -5) == "*/;\r\n" || substr($lstr, -4) == "*/\r\n")
      return true;
    else
      $this->skipComment($fhandle);
  }

  //执行SQL
  function query($sql) {
    //创建数据库对象
    $this->db->query($sql);
  }

  //动作执行回调函数
  function actionCallBack($mess) {
    //防止超时
    set_time_limit(60);
  }

}