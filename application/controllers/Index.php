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

class IndexController extends Yaf\Controller_Abstract{
	
	public function indexAction(){
		$this->_view->title = "YAF测试首页";
		$model = new UserModel();
		$map['username'] = 'xiaowei';
		$map['id'] = array('gt',1);
		$result = $model->field('username,password')->where($map)->order('id')->findOne();
		dump($result);
	}
}