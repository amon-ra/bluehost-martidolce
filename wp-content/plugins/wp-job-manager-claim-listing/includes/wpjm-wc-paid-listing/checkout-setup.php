<?php
/**
 * Extend WooCommerce + Paid Listing
 * This handle all Product Data Setup.
 * @since 3.0.0
 */
namespace wpjmcl\wpjm_wc_paid_listing;
if ( ! defined( 'WPINC' ) ) { die; }


/* Load Class */
Checkout_Setup::get_instance();

/**
 * Setup Class
 */
final class Checkout_Setup{

	/**
	 * Construct
	 */
	public function __construct(){

		/* Get data from session on page load */
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 11, 2 );

		/* Add order item meta */
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 11, 2 );

		/* Display item meta */
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 11, 2 );

	}

	/**
	 * Returns the instance.
	 */
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ) $instance = new self;
		return $instance;
	}

	/**
	 * Restore the data from the session on page load.
	 * "job_id" data will be handled by WC Paid Listing
	 */
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( ! empty( $values['claim_id'] ) ) {
			$cart_item['claim_id'] = $values['claim_id'];
		}
		return $cart_item;
	}

	/**
	 * order_item_meta function for storing the meta in the order line items
	 * "job_id" data will be handled by WC Paid Listing
	 */
	public function order_item_meta( $item_id, $values ) {
		// Add the fields
		if ( isset( $values['claim_id'] ) ) {
			$claim_obj = get_post( absint( $values['claim_id'] ) );
			$claimer_obj = get_userdata( $claim_obj->post_author );

			woocommerce_add_order_item_meta( $item_id, __( 'Claim By', 'wp-job-manager-claim-listing' ), $claimer_obj->data->display_name . " ({$claimer_obj->data->user_login})" );
			woocommerce_add_order_item_meta( $item_id, '_claim_id', $values['claim_id'] );
			woocommerce_add_order_item_meta( $item_id, '_claimer_id', $claimer_obj->ID );
		}
	}


	/**
	 * Display meta in cart
	 * "job_id" data will be handled by WC Paid Listing
	 */
	public function get_item_data( $data, $cart_item ) {
		if ( isset( $cart_item['claim_id'] ) ) {
			$claim_obj = get_post( absint( $cart_item['claim_id'] ) );
			$claimer_obj = get_userdata( $claim_obj->post_author );

			$data[] = array(
				'name'   => __( 'Claim By', 'wp-job-manager-claim-listing' ),
				'value'  => $claimer_obj->data->display_name . " ({$claimer_obj->data->user_login})",
			);
		}
		return $data;
	}


} // end class

