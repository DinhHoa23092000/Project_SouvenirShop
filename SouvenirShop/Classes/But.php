<?php
require_once "Product.php";
class But extends Product {
	function getType(){
  		return "Bút";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}

	  function getPrice(){
		return $this->price;
	}
}
?>