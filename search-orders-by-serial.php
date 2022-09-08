<?php

/*
 * Hook into WooCommerce Shop Order Search Results
 */

add_filter( 'woocommerce_shop_order_search_results', 'search_woo_orders_by_serial', 10, 3 );

function search_woo_orders_by_serial( $order_ids, $term, $search_fields ) {

	$sn_regex = '/^\d[a-zA-Z]\d{3}[lowercaseAnduppercaseFeatureCodes]{0,7}$/';

	if ( ! empty( $term && preg_match( $sn_regex, $term ) ) ) {

		$sn_regex = '/^\d[a-zA-Z]\d{3}[lowercaseAnduppercaseFeatureCodes]{0,7}$/';

		if ( preg_match( $sn_regex, $term ) ) {
			// Check both WooCommerce and imported Opencart tables
			global $wpdb;

			// Just get the first 5 capitalize
			$sn = strtoupper( substr( $term, 0, 5 ) );

			// Check WooCommerce
			$wc_table_to_use = $wpdb->prefix . 'tablename';

			$wc_order_id_by_serial_check = $wpdb->get_results( $wpdb->prepare( "SELECT order_id AS oid, CONCAT(serial_number, '', serial_feature_code) AS sn FROM $wc_table_to_use WHERE serial_number LIKE '%s'", $sn . '%' ) );

      if ( ! empty( $wc_order_id_by_serial_check ) ) {
				return array_merge( $search_fields, [ $wc_order_id_by_serial_check[0]->oid ] );
			}

			$oc_table_to_use = 'oc_tablename';

			$wp_user_id_by_serial_check = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM wp_users INNER JOIN wp_usermeta ON ID = user_id WHERE meta_key = 'open_cart_id' AND meta_value = (SELECT customer_id FROM oc_tablename WHERE order_id = (SELECT oid FROM oc_tablename WHERE `key` LIKE '%s' ORDER BY oid DESC LIMIT 1))", $sn ) );

			if ( ! empty( $wp_user_id_by_serial_check ) ) {
				$target_user = get_user_by( 'id', $wp_user_id_by_serial_check[0]->ID );

				if ( method_exists( 'user_switching', 'maybe_switch_url' ) ) {
					$url = user_switching::maybe_switch_url( $target_user );
					$url = html_entity_decode( $url );

					if ( $url ) {
						echo "<script>if ( confirm('This is a historical Opencart serial number. Click OK to jump to his customer') ) document.location='$url';</script>";
					}
				}
			}
		}
	}

	return $order_ids;
}
