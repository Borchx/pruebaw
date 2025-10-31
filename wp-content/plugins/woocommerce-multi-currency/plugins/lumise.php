<?php

/**
 * Class WOOMULTI_CURRENCY_Plugin_Lumise
 * Plugin: Lumise
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_Plugin_Lumise {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( $this->settings->get_enable() && is_plugin_active( 'lumise/lumise.php' ) ) {
			add_filter( 'lumise_product_base_price', array( $this, 'lumise_product_base_price' ), 20 );
			add_filter( 'woocommerce_widget_cart_item_quantity', array(
				$this,
				'woocommerce_widget_cart_item_quantity'
			), 1000, 3 );
		}
	}

	public function lumise_product_base_price( $price ) {
		if ( $this->settings->get_default_currency() !== $this->settings->get_current_currency() ) {
			$price = wmc_revert_price( $price );
		}

		return $price;
	}

	public function woocommerce_widget_cart_item_quantity( $html, $cart_item, $cart_item_key ) {
		if ( isset( $cart_item['lumise_data'] ) ) {
			foreach ( $cart_item['lumise_data']['attributes'] as $id => $attr ) {
				if ( $attr->type == 'quantity' && isset( $cart_item['lumise_data']['options']->{$id} ) ) {
					$total = $cart_item['lumise_data']['price']['total'];
					$total = wmc_get_price( $total );
					$qty   = @json_decode( $cart_item['lumise_data']['options']->{$id}, true );
					if ( json_last_error() === 0 && is_array( $qty ) ) {
						$qty = array_sum( $qty );
					} else {
						$qty = (Int) $cart_item['lumise_data']['options']->{$id};
					}
					$html = '<span class="quantity">' . sprintf( '%s &times; %s', $qty, wc_price( $total / $qty ) ) . '</span>';
				}
			}

		}

		return $html;
	}
}