<?php
require_once "Product.php";
class DoMyNghe extends Product {
	function getType(){
  		return "Đồ Mỹ Nghệ";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}

	  function getPrice(){
		return $this->price;
	}
}
?>