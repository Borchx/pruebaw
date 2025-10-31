<?php

class WOOMULTI_CURRENCY_Plugin_Affiliate_WP {

	public function __construct() {

		add_filter( "affwp_pre_insert_sale_data", [ $this, 'convert_order_total' ] );

		add_filter( 'affwp_woocommerce_get_products_line', [ $this, 'convert_line_product_price' ] );

		add_filter( "affwp_calc_referral_amount", [ $this, 'calc_referral_amount' ], 100, 6 );

		add_filter( "affwp_insert_pending_referral", [ $this, 'convert_order_total' ] );

//		Stop complete checkout processing to check commision value
//		add_filter( 'affwp_woocommerce_add_pending_referral_amount', function ( $a ) {
//			echo '<pre>' . print_r( $a, true ) . '</pre>';
//			die;
//			return $a;
//		} );
	}

	public function wmc_affwp_convert_amount( $amount ) {

		$affwp_currency  = affwp_get_currency();
		$data            = WOOMULTI_CURRENCY_Data::get_ins();
		$currencies_list = $data->get_list_currencies();

		if ( empty( $currencies_list[ $affwp_currency ]['rate'] ) ) {
			return $amount;
		}

		$default_currency = $data->get_default_currency();
		$amount           = wmc_revert_price( $amount );

		return $affwp_currency == $default_currency ? $amount : wmc_get_price( $amount, $affwp_currency );
	}

	public function convert_order_total( $data ) {
		$order_total         = $data['order_total'] ?? 0;
		$data['order_total'] = $this->wmc_affwp_convert_amount( floatval( $order_total ) );

		return $data;
	}

	public function convert_line_product_price( $data ) {
		$price         = $data['price'] ?? 0;
		$data['price'] = $this->wmc_affwp_convert_amount( $price );

		return $data;
	}

	public function calc_referral_amount( $referral_amount, $affiliate_id, $amount, $reference, $product_id, $context ) {

		if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
			return $referral_amount;
		}

		if ( $context ) {

			$rates = $this->get_rates( $affiliate_id );

			$rates = isset( $rates[ $context ] ) ? $rates[ $context ] : '';
			if ( $rates ) {
				foreach ( $rates as $rate ) {
					// product matches.
					$products_rate = $rate['products'] ?? [];
					if ( in_array( $product_id, $products_rate ) ) {
						if ( 'percentage' == $rate['type'] ) {
							return $this->wmc_affwp_convert_amount( $amount * $rate['rate'] / 100 );
						} else {
							return $rate['rate'];
						}
					}
				}
			}

			$affiliate_rate = affiliate_wp()->affiliates->get_column( 'rate', $affiliate_id );
			$affiliate_rate = affwp_abs_number_round( $affiliate_rate );
			$aff_type       = affwp_get_affiliate_rate_type( $affiliate_id );

			if ( null !== $affiliate_rate ) {
				return 'percentage' == $aff_type ? $this->wmc_affwp_convert_amount( $referral_amount ) : $referral_amount;
			}

			$product_type = get_post_meta( $product_id, '_affwp_' . $context . '_product_rate_type', true );

			$type = ! $product_type ? affwp_get_affiliate_rate_type( $affiliate_id ) : $product_type;

			$referral_amount = ( 'percentage' === $type ) ? $this->wmc_affwp_convert_amount( $referral_amount ) : $referral_amount;
		}

		return $referral_amount;
	}

	public function get_rates( $affiliate_id = 0 ) {
		$rates = get_user_meta( affwp_get_affiliate_user_id( $affiliate_id ), 'affwp_product_rates', true );

		return $rates;
	}
}