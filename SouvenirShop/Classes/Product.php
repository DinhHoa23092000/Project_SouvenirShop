<?php
require_once "IProduct.php";
abstract class Product implements IProduct{
	public $id;
	public $name;
    protected $image;
    protected $price;
    public $detail;
	public function __construct($id, $name, $image, $price, $detail) {
		$this->id = $id;
    	$this->name = $name;
        $this->image = $image;
        $this->price = $price;
        $this->detail = $detail;
  	}
}
?>