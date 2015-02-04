<?php

// +----------------------------------------------------------------------
// | Yaf 引导程序
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.widuu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: widuu 
// +----------------------------------------------------------------------
// | Time  : 2015/2/4
// +----------------------------------------------------------------------


class Bootstrap extends Yaf\Bootstrap_Abstract{

	/**
     * include Initialization common function
     * @author widuu <admin@widuu.com>
	 */

	public function _initCommon(){
		$commonFile = APP_PATH."/common/function.php";
		if(file_exists($commonFile)){
			require_once $commonFile;
		}
	}

	/**
     * Initialization the configure
     * @author widuu <admin@widuu.com>
	 */

	public function _initConfig(){
		 $config = Yaf\Application::app()->getConfig();
         Yaf\Registry::set("config", $config);
	}

	/**
     * Initialization the local class
     * @author widuu <admin@widuu.com>
	 */

    public function _initLoader(){
    	Yaf\Loader::getInstance()->registerLocalNamespace(array("Db"));
    }
}