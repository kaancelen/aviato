<?php
#DB operations class, singleton used
class DB{
	#DB instance
	private static $_instance = null;
	private $_pdo, 				#php database object
			$_query, 			#sql query
			$_error = false, 	#error message
			$_results, 			#query result
			$_count = 0;		#

	#Constructer, its private Because we never call new DB() only use getInstance outside
	private function __construct(){
		try{
			$this-> _pdo = new PDO(
				'mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),
				Config::get('mysql/username'),
				Config::get('mysql/password'));
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}
	#return instance of DB object
	public static function getInstance(){
		if(!isset(self::$_instance)){ #for singleton feature
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	#execute given query
	#query("SELECT * FROM users WHERE username = ? AND groups = ?", array('kaan', 2))
	public function query($sql, $params = array()){
		$this->_error = false;#maybe previous error stay
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count =$this->_query->rowCount();
			}else{
				$this->_error = true;
			}
		}
		return $this;
	}
	#another query execute method
	#action('SELECT *','users', array('username', '=', 'alex')
	public function action($action, $table, $where = array()){
		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator, $operators)){ #like contains
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql, array($value))->error()){ #replace ?
					return $this;
				}
			}
		}
		return false;
	}
	#another query execute method
	#get('users', array('username', '=', 'alex'))
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	#another query execute method
	#delete('users', array('username', '=', 'alex'))
	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}
	#insert object to DB
	#insert('users', array('username' => 'Kaan'....))
	public function insert($table, $fields = array()){
		$keys = array_keys($fields);
		$values = '';
		$x = 1;

		foreach ($fields as $field) {
			$values .= '?';
			if($x < count($fields)){
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`".implode('`,`', $keys)."`) VALUES ($values)";
		
		if(!$this->query($sql, $fields)->error()){
			return true;
		}

		return false;
	}
	#update record
	#insert('users', 3, array('username' => 'Kaan'....))
	public function update($table, $id, $fields){
		$set = '';
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)){
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if(!$this->query($sql, $fields)->error()){
			return true;
		}

		return false;
	}
	#result of last query
	public function results(){
		return $this->_results;
	}
	#first result of last query
	public function first(){
		return $this->results()[0];
	}
	#all result of last query
	public function all(){
		return $this->results();
	}
	#return last error
	public function error(){
		return $this->_error;
	}
	#count of last query result set element number
	public function count(){
		return $this->_count;
	}
}
?>