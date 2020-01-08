<?php
require_once "Shoe.php";
class SportShoe extends Shoe {
	function getType(){
		return "Sport Shoe";
	}
	function getImagePath(){
  		return $this->image;
  }

  function getDisplayPrice(){
     if($this->color == "black"){
        return $this->price." VND";
     }
     return "";
  }
}
?>