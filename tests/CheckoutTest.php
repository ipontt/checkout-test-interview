<?php
use App\Billing\Checkout;
use App\Discounts\BuyXGetYFree;
use App\Discounts\BuyXGetYPercentOff;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
	/**
	 * @dataProvider checkoutTestDataProvider
	 */
	public function testCheckoutGivesCorrectPrice($pricing_rules, $basket, $total_price_expected)
	{
		$co = new Checkout($pricing_rules);
		foreach ($basket as $item) {
			$co->scan($item);
		}
		$price = $co->total;
		$this->assertEquals($price, $total_price_expected);
	}

	public function checkoutTestDataProvider()
	{
		return [
			"FR1,SR1,FR1,FR1,CF1" => [
				[new BuyXGetYFree('FR1', 2, 1), new BuyXGetYPercentOff('SR1', 3, 10)],
				['FR1', 'SR1', 'FR1', 'FR1', 'CF1'],
				'£22.45'
			],
			"FR1,FR1" => [
				[new BuyXGetYFree('FR1', 2, 1), new BuyXGetYPercentOff('SR1', 3, 10)],
				['FR1', 'FR1'],
				'£3.11'
			],
			"SR1,SR1,FR1,SR1" => [
				[new BuyXGetYFree('FR1', 2, 1), new BuyXGetYPercentOff('SR1', 3, 10)],
				['SR1', 'SR1', 'FR1', 'SR1'],
				'£16.61'
			]
		];
	}
}
