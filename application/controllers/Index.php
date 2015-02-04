<?php

class IndexController extends Yaf\Controller_Abstract{
	//默认首页
	public function indexAction(){
		$this->_view->title = "YAF测试首页";
	}
}