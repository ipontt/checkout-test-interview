<?php

namespace App\Billing;

trait Products
{
	// Let's imagine this was obtained from the database
	static $products = [
		'FR1' => 311,
		'SR1' => 500,
		'CF1' => 1123
	];
}