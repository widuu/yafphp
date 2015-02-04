<?php

class UserModel extends Model{

	//Model的初始化方法
	public function _init(){
		dump($this->tableName);
	}
}