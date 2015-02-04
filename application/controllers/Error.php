<?php

class ErrorController extends Yaf\Controller_Abstract{

	public function errorAction($exception){
		 
		 $exception = $this->getRequest()->getException();
		 
		 try {
		    	throw $exception;
		  } catch (Yaf_Exception_LoadFailed $e) {
		  	var_dump($e);
		    	echo "加载失败".$e->getMessage();
		  } catch (Yaf_Exception $e) {
		    	echo "异常".$e->getMessage();
		  }
	}
}