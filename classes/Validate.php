<?php
#Validate according to given inputs
class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	#constructer
	public function __construct(){
		$this->_db = DB::getInstance();#singleton
	}
	#check if validate
	public function check($source, $items = array()){
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				$value = trim($source[$item]);
				$item = escape($item);

				if($rule === 'required' && empty($value)){
					$this->addError("{$item} is required");
				} else if(!empty($value)) {
					switch ($rule) {
						case 'min':
								if(strlen($value) < $rule_value){
									$this->addError("{$item} must be a minimum of {$rule_value} characters");
								}
							break;
						case 'max':
							if(strlen($value) > $rule_value){
									$this->addError("{$item} must be a maximum of {$rule_value} characters");
								}
							break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$rule_value} must match {$item}");
							}
							break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()){
								$this->addError("{$item} already exist");
							}
							break;
						case 'email_validate':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
						      $this->addError("{$value} must be e-mail address");
						    }
							break;
					}
				}
			}
		}

		if(empty($this->_errors)){
			$this->_passed = true;
		}

		return $this;
	}
	#get last check errors
	public function errors(){
		return $this->_errors;
	}
	#get last check is passed
	public function passed(){
		return $this->_passed;
	}
	#add given error to _errors fields
	private function addError($error){
		$this->_errors[] = $error;
	}
}

?>