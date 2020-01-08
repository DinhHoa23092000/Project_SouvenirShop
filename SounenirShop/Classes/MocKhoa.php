<?php
require_once "Product.php";
class MocKhoa extends Product {
	function getType(){
  		return "Móc Khóa";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}
	  function getPrice(){
		return $this->price;
	}

}
?>