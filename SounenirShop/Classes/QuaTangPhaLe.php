<?php
require_once "Product.php";
class QuaTangPhaLe extends Product {
	function getType(){
  		return "Quà Tặng Pha Lê";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}

	  function getPrice(){
		return $this->price;
	}
}
?>