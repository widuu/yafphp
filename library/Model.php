<?php


/**
 * Model模型类
 * 实现了ORM
 */

class Model{

	protected $tableName = NULL;

	public function __construct($config='default'){
		
		//获取数据表的名称
		if($this->tableName == NULL){
			$this->tableName = substr(get_class($this), 0,-5);
		}
		//数据表前缀
		$prefix = Yaf\Registry::get('config')->database->$config->prefix;
		$prefix = isset($prefix) ? $prefix : '';
		$this->tableName = $prefix.strtolower(ltrim($this->tableName,$prefix));
		//继承初始化方法
		$this->_init();
	}

}