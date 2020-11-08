<?php
class Item{
	function __construct($batch, $dept, $vendor, $description, $ordercode, $upc, $size, $cost, $allowance, $base, $retail, $fs, $newretail, $newfs){
		$this->batch = $batch;
		$this->dept = $dept;
		$this->vendor = $vendor;
		$this->description = $description;
		$this->ordercode = $ordercode;
		$this->upc = $upc;
		$this->size = $size;
		$this->cost = $cost;
		$this->allowance = $allowance;
		$this->base = $base;
		$this->retail = $retail;
		$this->fs = $fs;
		$this->newretail = $newretail;
		$this->newfs = $newfs;
	}
}
