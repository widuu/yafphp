<?php


class IndexController extends Yaf\Controller_Abstract{

	public function indexAction(){
		$uid = $this->getRequest()->getParam('uid',0);
		Yaf\Dispatcher::getInstance()->disableView();
	}

}