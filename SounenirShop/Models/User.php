<?php
class User{
	public $password;
	public $role;
	public $fullName;
	function getShortName(){
		$spacePos = strpos($this->fullName, ' ');
		if($spacePos){
			return substr($this->fullName, 0, $spacePos);
		}
		return $this->fullName;
	}
	function canManageShoe(){
		return $this->role == "admin";
	}
	function canBuyShoe(){
		return $this->role == "user";
	}
	
}
?>
