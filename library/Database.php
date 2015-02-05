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
 * 数据库中间层实现方法
 */

class DataBase{

	static private $instance = array();
	static private $_instance = NULL;

	private function __construct(){}

	/**
	 * 单态获取数据对象
	 * @access public
	 * @param  config 配置信息
	 */

	static public function getInstance($config){
		$key = md5(implode($config,':'));
		if(!isset(self::$instance[$key])){
			//$config = self::parseConfig($config);
			$class  = "Db_".ucfirst($config['type']);
			new $class($config);
		}
	}

	/**
	 * 解析配置文件
	 * @access private
	 * @param  config 配置文件
	 */

	static private  function parseConfig($config){
		dump($config);
	}
}