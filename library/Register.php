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
 * 注册模式，注册对象
 */

class Register{

	private static $model = array();

	public static function _set($alisa,$object){
		if(isset(self::$model[$alisa])){
			self::_unset($model[$alisa]);
		}
		self::$model[$alisa] = $object;
	}

	public static function _get($alisa){
		if(!isset(self::$model[$alisa])){
			return null;
		}
		return self::$model[$alisa];
	}

	public static function _unset($alisa){
		unset(self::$mode[$alisa]);
	}
}