<?php
require_once "Product.php";
class QuaDeBan extends Product {
	function getType(){
  		return "Quà Để Bàn";
  	}
	
	function getImagePath(){
  		return $this->image;
	  }
	
	  function getPrice(){
		return $this->price;
	}



}
?>