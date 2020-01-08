<?php
require_once "Product.php";
class TienIch extends Product {
	function getType(){
  		return "Tiện Ích";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}

	  function getPrice(){
		return $this->price;
	}
}
?>