<?php

namespace App\Discounts\Interfaces;

interface Discount
{
	public function calculate($quantity);
	public function getIdentifier();
}