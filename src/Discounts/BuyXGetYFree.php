<?php

namespace App\Discounts;

use App\Discounts\Interfaces\Discount;
use App\Billing\Products;

class BuyXGetYFree implements Discount
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
		
		if ($y > $x) {
			throw new \Exception('y must be smaller than x');
		}
	
		$this->identifier = $identifier;
		$this->x = $x;
		$this->y = $y;
	}
	
	public function calculate($quantity)
	{
		$normal_price = $quantity * self::$products[$this->identifier];
		
		return $normal_price - self::$products[$this->identifier] * ($this->y * floor($quantity/$this->x));
	}
	
	public function getIdentifier()
	{
		return $this->identifier;
	}
}