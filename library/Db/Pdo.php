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
 * PDO数据库操作
 */


class Db_Pdo extends Driver{

	//PDO的参数
	private $options = array(
			PDO::ATTR_CASE              =>  PDO::CASE_LOWER,
	        PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_EXCEPTION,
	        PDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,
	        PDO::ATTR_STRINGIFY_FETCHES =>  false,
		);

	public function __construct($config){
		$this->config = $config;
	}

	/**
     * 数据库连接方法
     * @access public
     */

    public function connect($linkNum=0){
    	if(!isset($this->linkID[$linkNum])){
    		try{
    			//当PHP5.3.6时候禁止模拟预处理
    			if(version_compare(PHP_VERSION,'5.3.6','<=')){ 
                    $this->options[PDO::ATTR_EMULATE_PREPARES]  =   false;
                }
                //判断DSN是否存在
                if(empty($this->config['dsn'])) $this->config['dsn'] = parseDsn();
    			//实例化PDO
    			$this->linkID[$linkNum] = new PDO( $this->config['dsn'], $this->config['username'], $this->config['password'],$this->options);
    		}catch(PDOException $e){
    			Error($e->getMessage());
    		}
    	}
    	return $this->linkID[$linkNum];
    }

	/**
     * 数据库初始化方法
     * @access public
     */

	public function init($master=true){
		 //主从分离多服务器连接暂时不做
		 //判断数据库资源是否存在，不存在连接
		 if ( !$this->_linkID ) $this->_linkID = $this->connect();
	}

    /**
     * 释放查询结果
     * @access public
     */

    public function free(){
    	$this->PDOStatement = null;
    }

    /**
     * 启动事务
     * @access public
     * @return void
     */

    public function startTrans(){

    }

    /**
     * 事务回滚
     * @access public
     * @return boolean
     */

    public function rollback(){

    }

    /**
     * 数据库初始化方法
     * @access public
     */

	public function parseDsn(){
		$dsn  =   'mysql:dbname='.$this->config['database'].';host='.$this->config['hostname'];
		if(!empty($this->config['hostport'])) {
            $dsn  .= ';port='.$this->config['hostport'];
        }elseif(!empty($this->config['socket'])){
            $dsn  .= ';unix_socket='.$this->config['socket'];
        }
        if(!empty($this->config['charset'])){
        	// PHP5.3.6以下不支持charset设置
            if(version_compare(PHP_VERSION,'5.3.6','<')){ 
                $this->options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES '.$this->config['charset'];
            }else{
                $dsn  .= ';charset='.$this->config['charset'];
            }
        }
        return $dsn;
	}

	/**
	 * 查询数据表信息
	 * @access public
	 */

	public function getFields($tableName){
		$sql    = 'SHOW COLUMNS FROM `'.$tableName.'`';
		$result = $this->query($sql);
		$info   =   array();
		foreach ($result as $key => $val) {
			$info[$val['field']] = array(
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => (bool) ($val['null'] === ''), 
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment'),
                );
		}
		return $info;
	}

	/**
     * 查找记录
     * @access public
     * @param array $options 表达式
     * @return mixed
     */
    
    public function select($options=array()) {
    	$sql = $this->buildSql($options);
    	echo $sql;
    	dump($this->query($sql));
    }

    public function buildSql($options){
    	 $sql   = str_replace(
            			array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%LOCK%','%COMMENT%','%FORCE%'),
            			array(
            				$this->parseTable($options['table']),
            				$this->parseDistinct(isset($options['distinct']) ? true : false),
            				$this->parseField($options['field']),
            				$this->parseJoin(!empty($options['join'])?$options['join']:''),
            		     	$this->parseWhere(!empty($options['where']) ? $options['where'] : ''),
            		     	$this->parseGroup(!empty($options['group'])?$options['group']:''),
			                $this->parseHaving(!empty($options['having'])?$options['having']:''),
			                $this->parseOrder(!empty($options['order'])?$options['order']:''),
			                $this->parseLimit(!empty($options['limit'])?$options['limit']:''),
			                $this->parseUnion(!empty($options['union'])?$options['union']:''),
			                $this->parseLock(isset($options['lock'])?$options['lock']:false),
			                $this->parseComment(!empty($options['comment'])?$options['comment']:''),
			                $this->parseForce(!empty($options['force'])?$options['force']:'')
            				),
            			$this->selectSql);
        return $sql;
    }

    /**
     * 解析数据表
     * @access public
     * @param string $table  表名称
     */

    private function parseTable($table){
    	if(empty($table)) Error(Lang('_NO_DB_DATABASE_'),1006);
    	return $table;
    }

    /**
     * 解析数据表
     * @access public
     * @param string $table  表名称
     */

    private function parseDistinct($distinct){
    	return !$distinct ? ' DISTINCT ': '';
    }

    private function parseField($fields){
    	if('' == $fields) {
            $fields = '*';
        }
        return $fields;
    }

    private function parseLimit($limit) {
        return !empty($limit)?   ' LIMIT '.$limit.' ':'';
    }

    private function parseUnion($union){

    }

    private function parseJoin($join){

    }

    private function parseWhere($where){
    	if(!empty($where) && is_string($where)){
   			$where = ' WHERE '.$where;
    	}
    	return $where;
    }

    /**
     * 设置锁机制
     * @access protected
     * @return string
     */

    protected function parseLock($lock=false) {
        return $lock?   ' FOR UPDATE '  :   '';
    }

    /**
     * index分析，可在操作链中指定需要强制使用的索引
     * @access protected
     * @param mixed $index
     * @return string
     */

    protected function parseForce($index) {
        if(empty($index)) return '';
        if(is_array($index)) $index = join(",", $index);
        return sprintf(" FORCE INDEX ( %s ) ", $index);
    }

    /**
     * group分析
     * @access protected
     * @param mixed $group
     * @return string
     */
    
    protected function parseGroup($group) {
        return !empty($group)? ' GROUP BY '.$group:'';
    }
    
    /**
     * having分析
     * @access protected
     * @param string $having
     * @return string
     */
    
    protected function parseHaving($having) {
        return  !empty($having)?   ' HAVING '.$having:'';
    }
    
    /**
     * comment分析
     * @access protected
     * @param string $comment
     * @return string
     */
   
    protected function parseComment($comment) {
        return  !empty($comment)?   ' /* '.$comment.' */':'';
    }

    /**
     * Order分析
     * @access protected
     * @param string $comment
     * @return string
     */

    protected function parseOrder($order) {
        return  !empty($order)?   ' order by '.$order.'':'';
    }



	/**
     * 执行查询 返回数据集
     * @access public
     * @param string $str  sql指令
     * @param boolean $fetchSql  不执行只是获取SQL
     * @return mixed
     */

	public function query($sql,$bind=array()){
		//初始化连接
		$this->init();
		//如果连接失败
		if(!$this->_linkID) return false;
		//记录sql指令
		$this->queryStr = $sql;
		//释放前次的查询结果
        if ( !empty($this->PDOStatement) ) $this->free();
        //PDO预处理
        $this->PDOStatement = $this->_linkID->prepare($sql);
        //参数绑定
        if(!empty($bind)) $this->bindSql($bind,$sql); 
        //执行预处理语句
        $this->PDOStatement->execute();
        //返回结果集
        $result =   $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        $this->numRows = count( $result );
        return $result;
	}

	/**
     * 解析参数绑定
     * @access public
     * @param  array  $bind      参数
     */

	public function bindSql($bind = array()){
		foreach($bind as $key =>$val){
			if(is_numeric($key)){
				$this->PDOStatement->bindValue($key+1, $val);
			}else{
				$this->PDOStatement->bindValue($key, $val);
			}
		}
	}	

	/**
     * 数据库错误信息
     * 并显示当前的SQL语句
     * @access public
     * @return string
     */

	public function error(){
		//捕获错误信息
		if($this->PDOStatement){
			$errorInfo = $this->PDOStatement->errorInfo();
			$this->error = $errorInfo[1].':'.$errorInfo[2];
			$errorCode = $this->PDOStatement->errorCode();
		}else{
			$errorInfo = '';
			$errorCode = 1000;
		}
		//如果sql语句不为空
		if(!empty($this->queryStr)){
			$this->error .= "\n[SQL语句]：".$this->queryStr;
		}
		Error($this->error,$errorCode);
	}

    /**
     * 关闭数据库
     * @access public
     */

    public function close() {
        $this->_linkID = null;
    }

    /**
     * SQL指令安全过滤
     * @access public
     * @param string $str  SQL字符串
     * @return string
     */

    public function escapeString($str) {
        return addslashes($str);
    }

    /**
     * 析构方法
     * @access public
     */

    public function __destruct(){
    	//释放资源
    	if($this->_linkID){
    		$this->_linkID = null;
    	}
    	//释放结果集
    	$this->PDOStatement = null;
    }

}