<?php
require_once "Product.php";
class GauBong extends Product {
	function getType(){
  		return "Gấu Bông";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}
	  function getPrice(){
		return $this->price;
	}

}
?>