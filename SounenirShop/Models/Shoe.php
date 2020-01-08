<?php
require_once "IShoe.php";
abstract class Shoe implements IShoe{
	public $id;
	public $name;
	protected $price;
	public $color;
	protected $image;
	public function __construct($id, $name, $price, $color, $image) {
		$this->id = $id;
    	$this->name = $name;
    	$this->price = $price;
    	$this->color = $color;
    	$this->image = $image;
  	}
}
?>