<?php

namespace App\Billing;

use App\Discounts\Interfaces\Discount;
use App\Billing\Products;

class Checkout
{
	use Products;

	public $total = 0;
	private $basket = [];
	private $pricing_rules = [];
		
	public function __construct($pricing_rules = [])
	{
		foreach ($pricing_rules as $rule) {
			if (!$rule instanceof Discount) {
				throw new \Exception('Invalid Argument');
			}
			
			array_push($this->pricing_rules, $rule);
		}
	}
	
	public function scan(string $identifier): void
	{
		if (!in_array($identifier, array_keys(self::$products))) {
			throw new \Exception('No product with the identifier '.$identifier.' exists in the database.');
		}
		
		array_push($this->basket, $identifier);
		
		$cents = $this->calculateTotal();
		
		$this->total = $this->formatTotal($cents);
	}
	
	private function calculateTotal(): int
	{
		$grouped_basket = array_count_values($this->basket);
		
		return array_sum(array_map(
			function ($quantity, $identifier) {
				$discount = array_values(array_filter($this->pricing_rules, function (Discount $discount) use ($identifier) {
					return $discount->getIdentifier() === $identifier;
				}));

				if (count($discount) > 0) {
					return $discount[0]->calculate($quantity);
				}
				
				return self::$products[$identifier] * $quantity;
			},
			$grouped_basket, array_keys($grouped_basket)
		));
	}
	
	private function formatTotal(int $cents): string
	{
		return '£' . (string) number_format($cents/100, 2, '.', '');
	}
}
