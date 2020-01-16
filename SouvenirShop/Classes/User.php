<?php
class User{
	public $id;
	public $fullName;
	public $account;
	public $password;
	public $role;

	public function __construct($id, $fullName, $account, $password, $role) {
		$this->id = $id;
    	$this->fullName = $fullName;
        $this->account = $account;
        $this->password = $password;
        $this->role = $role;
	  }
	  

}
?>
