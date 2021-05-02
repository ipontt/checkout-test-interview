<?php

namespace App\Discounts;

use App\Discounts\Interfaces\Discount;
use App\Billing\Products;

class BuyXGetYPercentOff implements Discount
{
	use Products;

	private $identifier;
	private $x;
	private $y;
	
	public function __construct($identifier, $x, $y)
	{
		if (!in_array($identifier, array_keys(self::$products))) {
			throw new \Exception('No product with the identifier '.$identifier.' exists in the database.');
		}
		
		if ($y >= 100) {
			throw new \Exception('y must be smaller than 100');
		}
	
		$this->identifier = $identifier;
		$this->x = $x;
		$this->y = $y;
	}
	
	public function calculate($quantity)
	{
		$normal_price = $quantity * self::$products[$this->identifier];
		
		return $normal_price * ($quantity < $this->x ? 1 : (1- $this->y/100));
	}
	
	public function getIdentifier()
	{
		return $this->identifier;
	}
}