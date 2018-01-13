<?php	if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * diseases_model
 * 简介：技术支持与服务后台管理数据库模型
 * 返回：Boole
 * 作者：Fzhao
 * 时间：2016/4/16
 * www($this->db->last_query());
 */

class Diseases_model extends Fzhao_Model{
	
	private $table;
    public function __construct(){
      parent::__construct();
			$this->table = 'diseases';
    }

	/**
	 * add_courses
	 * 简介：添加活动
	 * 参数：新数据
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2014-1-21
	 */
	function add($data){
		$this->db->insert($this->table,$data); 
		return $this->db->insert_id();
	}

	/**
	 * getCoursesList
	 * 简介：读取活动列表
	 * 参数：
	 * 返回：Array
	 * 作者：Fzhao
	 * 时间：2014-1-21
	 */
	function get_list($data,$is_valid=1){
		$cond = isset($data['condition'])?$data['condition']:array();
		$conditions = array('p.is_valid'=>$is_valid,'p.lang'=>_LANGUAGE_);
		$like = array();
		if(isset($cond['type']) && $cond['type']){
			switch($cond['type']){
				case 'title':
					$like = array('like'=>array('title',$cond['keywords']));
					break;
				case 'content':
					$like = array('like'=>array('content',$cond['keywords']));
					break;
				case 'id':
					$conditions = array_merge($conditions,array('p.id'=>$cond['keywords']));
					break;
			}
		}else{
			if(isset($cond['term_id']) && $cond['term_id']!=''){
				$conditions = array_merge($conditions,array('p.term_id'=>$cond['term_id']));
			}
			if(isset($cond['is_commend']) && $cond['is_commend']!=''){
				$conditions = array_merge($conditions,array('p.is_commend'=>$cond['is_commend']));
			}
			if(isset($cond['is_issue']) && $cond['is_issue']!=''){
				$conditions = array_merge($conditions,array('p.is_issue'=>$cond['is_issue']));
			}
			if(isset($cond['startTime']) && $cond['startTime']!=''){
				$conditions = array_merge($conditions,array('p.create_time >='=>$cond['startTime']));
			}
			if(isset($cond['endTime']) && $cond['endTime']!=''){
				$conditions = array_merge($conditions,array('p.create_time <='=>$cond['endTime']));
			}
		}

		$data = $this->getData(array_merge(array(
			'fields' 	 => 'p.id,p.term_id,p.title,p.is_valid,p.owner,p.views,p.is_commend,p.is_issue,p.create_time,t.name term_name,t.slug,a.username',
			'table'  	 => $this->table.' p',
			'join'		 => array('term t','t.id=p.term_id','admin a','a.id=p.owner'),
			'conditions' => $conditions,
			'order'		 => array('p.create_time','desc'),
			'limit'		 => array($data['rows'],$data['rows']*($data['currPage']-1)),
		),$like));
		$count = $this->getData(array_merge(array(
			'table'  	 => $this->table.' p',
			'join'		 => array('term t','t.id=p.term_id','admin a','a.id=p.owner'),
			'conditions' => $conditions,
			'count'		 => true,
		),$like));
		return array('data'=>$data,'count'=>$count);
	}

	/**
	 * get_info_byI_id
	 * 简介：根据ID读取活动信息
	 * 参数：null
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2014-1-21
	 */
	function get_info_byI_id($id){
		return $this->getData(array(
			'fields' 	 => '*',
			'table'  	 => $this->table,
			'conditions' => array('id'=>$id,'lang'=>_LANGUAGE_),
			'row'		 => true
		));
	}

	/**
	 * getProductsRecycleList
	 * 简介：读取产品列表(回收站)
	 * 参数：
	 * 返回：Array
	 * 作者：Fzhao
	 * 时间：2012-11-11
	 */
	function get_recycle_list($data){
		return $this->get_list($data,0);
	}

	/**
	 * del
	 * 简介：删除(放入回收站)活动
	 * 参数：null
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2012/12/6
	 */
	function del($id){
		$data = array(
		   'is_valid' => 0
		);
		if(is_array($id)){
			$this->db->where_in('id',$id);
			return $this->db->update($this->table, $data);
		}else{
			return $this->db->update($this->table,$data,array('id'=>$id)); 
		}
	}

	/**
	 * recover
	 * 简介：还原活动信息
	 * 参数：$id int 新数据
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2012/12/6
	 */
	function recover($id){
		$data = array(
		   'is_valid' => 1
		);
		if(is_array($id)){
			$this->db->where_in('id',$id);
			return $this->db->update($this->table, $data);
		}else{
			return $this->db->update($this->table,$data,array('id'=>$id)); 
		}
	}
	

	/**
	 * dump
	 * 简介：删除(彻底清除)活动信息
	 * 参数：$id int 
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2014-1-22
	 */
	function dump($id){
		$info = $this->getData(array(
			'fields' 	 => 'term_id',
			'table'  	 => $this->table,
			'in'		 => array('id',$id)
		));
		if($info){
			foreach($info as $item){
				$tid = $item['term_id'];
				$this->iUpdate(array('table'=>'term','field'=>'count','val'=>'count-1','id'=>$tid));
			}
		}
		if(is_array($id)){
			$this->db->where_in('id',$id);
			return $this->db->delete($this->table);
		}else{
			return $this->db->delete($this->table,array('id'=>$id)); 
		}
	}

	/**
	 * iUpdate
	 * 简介：更改表信息
	 * 参数：null
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2014-1-22
	 */
	function iUpdate($data){
		$this->db->set($data['field'], $data['val'],false);
		$this->db->where('id', $data['id']);
		return $this->db->update($data['table']); 
	}

	/**
	 * get_file_by_id
	 * 简介：读取文件信息
	 * 参数：null
	 * 返回：Boole
	 * 作者：Fzhao
	 * 时间：2014-1-23
	 */
	function get_file_by_id($id=0,$type=''){
		return $this->getData(array(
			'fields' 	 => 'id,file_path,title,fileext',
			'table'  	 => 'tfiles',
			'conditions' => array('tid'=>$id,'type'=>$type),
			'row'		 => true
		));
	}
}
?>