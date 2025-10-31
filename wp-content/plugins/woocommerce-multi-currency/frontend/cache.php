<?php

/**
 * Class WOOMULTI_CURRENCY_Frontend_Update
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_Frontend_Cache {
	protected static $settings;
	protected $price_args;

	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( self::$settings->get_enable() ) {
//			add_action( 'init', array( $this, 'clear_browser_cache' ) );
			add_action( 'wp_ajax_wmc_get_products_price', array( $this, 'get_products_price' ) );
			add_action( 'wp_ajax_nopriv_wmc_get_products_price', array( $this, 'get_products_price' ) );
			if ( self::$settings->get_param( 'cache_compatible' ) || ( self::$settings->enable_switch_currency_by_js() && self::$settings->get_param( 'do_not_reload_page' ) ) ) {
				add_filter( 'woocommerce_get_price_html', array( $this, 'compatible_cache_plugin' ), 20, 2 );
			}
		}
	}

	/**
	 * @param $price
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	public function compatible_cache_plugin( $price, $product ) {
		return "<span class='wmc-cache-pid' data-wmc_product_id='{$product->get_id()}'>" . $price . '</span>';
	}

	/**
	 * Clear cache browser
	 */
	public function clear_browser_cache() {
		if ( isset( $_GET['wmc-currency'] ) ) {
			header( "Cache-Control: no-cache, must-revalidate" );
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Content-Type: application/xml; charset=utf-8" );
		}
	}

	/**
	 *
	 */
	public function get_products_price() {
		do_action( 'wmc_get_products_price_ajax_handle_before' );
		$pids             = ! empty( $_POST['pids'] ) ? wc_clean( $_POST['pids'] ) : [];
		$shortcodes       = ! empty( $_POST['shortcodes'] ) ? wc_clean( $_POST['shortcodes'] ) : array();
		$result           = array(
			'shortcodes' => array()
		);
		$current_currency = self::$settings->get_current_currency();
		$list_currencies  = self::$settings->get_list_currencies();

		$data   = $list_currencies[ $current_currency ];
		$format = WOOMULTI_CURRENCY_Data::get_price_format( $data['pos'] );
		$args   = array(
			'currency'     => $current_currency,
			'price_format' => $format
		);
		if ( isset( $data['decimals'] ) ) {
			$args['decimals'] = absint( $data['decimals'] );
		}

		if ( ! empty( $pids ) ) {
			$this->price_args = $args;
			add_filter( 'wc_price_args', array(
				$this,
				'change_price_format_by_specific_currency'
			), PHP_INT_MAX );
			foreach ( $pids as $pid ) {
				$product = wc_get_product( $pid );
				if ( $product ) {
					$result['prices'][ $pid ] = $product->get_price_html();
				}
			}
			remove_filter( 'wc_price_args', array(
				$this,
				'change_price_format_by_specific_currency'
			), PHP_INT_MAX );
			$this->price_args = array();
		}

		$result['current_currency'] = $current_currency;
		$result['current_country']  = strtolower( self::$settings->get_country_data( $current_currency )['code'] );

		if ( count( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcode ) {
				$flag_size              = isset( $shortcode['flag_size'] ) ? $shortcode['flag_size'] : '';
				$dropdown_icon          = isset( $shortcode['dropdown_icon'] ) ? $shortcode['dropdown_icon'] : '';
				$custom_format          = isset( $shortcode['custom_format'] ) ? $shortcode['custom_format'] : '';
				$result['shortcodes'][] = do_shortcode( "[woo_multi_currency_{$shortcode['layout']} flag_size='{$flag_size}' dropdown_icon='{$dropdown_icon}' custom_format='{$custom_format}']" );
			}
		}

		if ( ! empty( $_POST['exchange'] ) ) {
			$exchange_sc  = [];
			$exchange_arr = wc_clean( $_POST['exchange'] );
			foreach ( $exchange_arr as $ex ) {
				$exchange_sc[] = array_merge( $ex, [ 'shortcode' => do_shortcode( "[woo_multi_currency_exchange product_id='{$ex['product_id']}' keep_format='{$ex['keep_format']}' price='{$ex['price']}' original_price='{$ex['original_price']}' currency='{$ex['currency']}']" ) ] );
			}
			$result['exchange'] = $exchange_sc;
		}
		do_action( 'wmc_get_products_price_ajax_handle_after' );
		wp_send_json_success( apply_filters( 'wmc_get_products_price_ajax_handle_response', $result ) );
	}

	public function change_price_format_by_specific_currency( $args ) {
		if ( count( $this->price_args ) ) {
			$args = wp_parse_args( $this->price_args, $args );
		}

		return $args;
	}
}