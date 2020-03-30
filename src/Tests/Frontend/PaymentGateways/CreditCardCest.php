<?php

namespace SkyVerge\Lumiere\Tests\Frontend\PaymentGateways;

use Codeception\Actor;
use Codeception\Module\WPWebDriver;
use SkyVerge\Lumiere\Page\Frontend\Product;
use SkyVerge\Lumiere\Page\Frontend\Checkout;
use SkyVerge\Lumiere\Tests\AcceptanceBase;

abstract class CreditCardCest extends AcceptanceBase {


	/** @var \WC_Product_Simple a shippable product */
	protected $shippable_product;


	/**
	 * Runs before each test.
	 *
	 * @param WPWebDriver|Actor $I tester instance
	 */
	public function _before( $I ) {

		parent::_before( $I );

		// TODO: consider creating these products as a run-once-per-suite action or using WP-CLI in wp-bootstrap.php {WV 2020-03-29}
		$this->shippable_product = $this->tester->haveSimpleProductInDatabase( [ 'name' => 'Shippable 1' ] );
	}


    public function try_custom_name_is_shown( Product $single_product_page, Checkout $checkout_page ) {

		$this->tester->havePaymentGatewaySettingsInDatabase( $this->get_payment_gateway_id(), [ 'title' => 'My Credit Card' ] );

		$this->tester->amOnPage( Product::route( $this->shippable_product ) );

		$single_product_page->addSimpleProductToCart( $this->shippable_product );

		$this->tester->amOnPage( Checkout::route() );

		$checkout_page->seePaymentMethodTitle( $this->get_payment_gateway_id(), 'My Credit Card' );
	}


	public function try_successful_transaction_for_shippable_product( Product $single_product_page, Checkout $checkout_page ) {

		$this->tester->amOnPage( Product::route( $this->shippable_product ) );

		$single_product_page->addSimpleProductToCart( $this->shippable_product );

		$this->tester->amOnPage( Checkout::route() );

		$checkout_page->fillBillingDetails();

		$this->place_order( $checkout_page );

		$this->tester->waitForElementVisible( '.woocommerce-order-details' );
		$this->tester->see( 'Order received', '.entry-title' );
	}


	/**
	 * Gets the ID of the payment gateway being tested.
	 *
	 * @return string
	 */
	protected abstract function get_payment_gateway_id();


	/**
	 * Performs the necessary steps to place a new order from the Checkout page.
	 *
	 * Normally clicking the Place Order button is the only necessary step.
	 * Payment geteways may overwrite this method to perform extra steps, like entering a particular credit card number or test amount.
	 *
	 * @param Checkout $checkout_page Checkout page object
	 */
	protected function place_order( Checkout $checkout_page ) {

		$this->tester->click( Checkout::BUTTON_PLACE_ORDER );
	}


}
