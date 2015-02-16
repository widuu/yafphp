<?php

// +----------------------------------------------------------------------
// | YafPHP Develop
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.widuu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: widuu 
// +----------------------------------------------------------------------
// | Time  : 2015/2/16
// +----------------------------------------------------------------------

/**
 * yac缓存
 */

class Cache_Yac extends Cache{

 

	public function __construct(){
		$this->handler = new Yac();
	}

	public function set($name,$args,$ttl = null){
		if(isset($ttl) && is_numeric($ttl)){
			$this->handler->set($name,$args,$ttl);
		}
		$this->handler->set($name,$args);
	}

	public function get($name){
		$data = $this->handler->get($name);
		if(!$data) $data = '';
		return $data;
	}

	public function delete($name,$delay =0){
		$this->handler->delete($name,$delay);
	}

	public function flush(){
		$this->handler->flush();
	}
}