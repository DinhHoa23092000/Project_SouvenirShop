<?php
require_once "Product.php";
class SanhSu extends Product {
	function getType(){
  		return "Sản Phẩm Sành Sứ";
  	}
	
	function getImagePath(){
  		return $this->image;
  	}
	  function getPrice(){
		return $this->price;
	}

}
?>