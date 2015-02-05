<?php

// +----------------------------------------------------------------------
// | YafPHP Develop
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.widuu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: widuu 
// +----------------------------------------------------------------------
// | Time  : 2015/2/4
// +----------------------------------------------------------------------

/**
 * Model模型类
 * 实现了ORM
 */

class Model{

	//当前数据表名称
	protected $tableName = NULL;
	// 当前数据库操作对象
    protected $db        = NULL;
    // 数据库对象池
	private   $_db		 =	array();

	public function __construct($tableName='',$config=''){
		//获取数据库配置
		if(is_string($config) || empty($config)) {
			//如果是字符串从配置文件读取
			if(empty($config)) $config = 'default';
			$config = Yaf\Registry::get('config')->database->$config->toArray();
		}
		//获取表前缀
		$prefix = isset($config['prefix']) ? $config['prefix'] : '';
		//获取数据表名称
		if(!empty($tableName)) $this->tableName = $tableName;
		//new Model的时候使用
		if($this->tableName == NULL) $this->tableName = substr(get_class($this), 0,-5);
		$this->tableName = $prefix.strtolower(ltrim($this->tableName,$prefix));
		//初始化方法
		$this->_init();
		//切换数据库连接
		$this->link(0,empty($this->config)?$config:$this->config,true);
	}

	/**
     * 切换数据库连接
     * @access public
     * @param  integer $linkNum  连接序号
     * @param  mixed   $config   数据库连接信息
     * @param  boolean $force    强制重新连接
     * @return Model
     */

	public function link($linkNum='',$config='',$force=false){
		//判断是否需要重新连接
		if(!isset($this->_db[$linkNum]) || $force){
			$this->_db[$linkNum] = Database::getInstance($config);
		}
		//取得当前的数据库对象
		$this->db = $this->_db[$linkNum];
	}

}