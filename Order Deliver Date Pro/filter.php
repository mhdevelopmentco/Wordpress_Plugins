<?php 
/**
 * Order Delivery Date Pro for WooCommerce
 *
 * Handles the display and filtering of delivery details in WooCommerce->Orders
 *
 * @author      Tyche Softwares
 * @package     Order-Delivery-Date-Pro-for-WooCommerce/Filter
 * @since       2.7
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

include_once( dirname( __FILE__ ) . '/orddd-common.php' );

/**
 * orddd_filter Class
 *
 * @class orddd_filter
 */
class orddd_filter {

	/**
	 * Default Constructor
	 *
	 * @since 8.1
	 */
	public function __construct() {

		//Delivery Date & Time on WooCommerce Edit Order page in Admin
		if ( get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'billing_section' || get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'after_your_order_table' || get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'custom' ) {
		    add_action( 'woocommerce_admin_order_data_after_billing_address',  array( &$this, 'orddd_display_delivery_date_admin_order_meta') , 10, 1 );
		    add_action( 'woocommerce_admin_order_data_after_billing_address',  array( &$this, 'orddd_display_time_slot_admin_order_meta' ), 10, 1 );
		} else if ( get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'shipping_section'|| get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'before_order_notes' || get_option( 'orddd_delivery_date_fields_on_checkout_page' ) == 'after_order_notes' ) {
		    add_action( 'woocommerce_admin_order_data_after_shipping_address', array( &$this, 'orddd_display_delivery_date_admin_order_meta') , 10, 1 );
		    add_action( 'woocommerce_admin_order_data_after_shipping_address', array( &$this, 'orddd_display_time_slot_admin_order_meta' ), 10, 1 );   
		}
			
		// Delivery date & Time in list of orders on WooCommerce Edit Order page in Admin
		if ( get_option( 'orddd_show_column_on_orders_page_check' ) == 'on' ) {
		    add_filter( 'manage_edit-shop_order_columns',          array( &$this, 'orddd_woocommerce_order_delivery_date_column' ), 20, 1 );
		    add_action( 'manage_shop_order_posts_custom_column',   array( &$this, 'orddd_woocommerce_custom_column_value' ), 20, 1 );
		    add_filter( 'manage_edit-shop_order_sortable_columns', array( &$this, 'orddd_woocommerce_custom_column_value_sort' ) );
		    add_filter( 'request',                                 array( &$this, 'orddd_woocommerce_delivery_date_orderby' ) );			     
		}
		
		//Filter to sort orders based on Delivery dates 
		if ( get_option( 'orddd_show_filter_on_orders_page_check' ) == 'on' ) {
		    add_action( 'restrict_manage_posts',                array( &$this, 'orddd_restrict_orders' ), 15 );
		    add_filter( 'request',                              array( &$this, 'orddd_add_filterable_field' ) );
		    add_filter( 'woocommerce_shop_order_search_fields', array( &$this, 'orddd_add_search_fields' ) );
		}
	}

	/**
	 * This function is used to add the custom plugin column 
	 * Delivery Date on WooCommerce->Orders page.
	 * 
	 * @param array $columns - The Existing columns for the WooCommerce->Orders table.
	 * @return array $new_columns - Updated list of column names.
	 * 
	 * @hook manage_edit-shop_order_columns
	 * @since 2.7
	 */
	public static function orddd_woocommerce_order_delivery_date_column( $columns ) {
		// get all columns up to and excluding the 'order_actions' column
		$new_columns = array();
		foreach ( $columns as $name => $value ) {
			if ( $name == 'wc_actions' ) {
				prev( $columns );
				break;
			}
			$new_columns[ $name ] = $value;
		}
		// inject our columns
		$new_columns[ 'order_delivery_date' ] = get_option( 'orddd_delivery_date_field_label' );
		// add the 'order_actions' column, and any others
		foreach ( $columns as $name => $value ) {
			$new_columns[ $name ] = $value;
		}
		return $new_columns;
	}

	/**
	 * This function echoes the delivery details to the 
	 * 'Delivery Date' column on WooCommerce->Orders for each order.
	 * 
	 * @param string $column - Column Name
	 * 
	 * @hook manage_shop_order_posts_custom_column
	 * @since 2.7
     */
	public static function orddd_woocommerce_custom_column_value( $column ) {
		global $post, $orddd_date_formats;
		if ( $column == 'order_delivery_date' ) {
			$delivery_date_formatted = orddd_common::orddd_get_order_delivery_date( $post->ID  );
    		echo $delivery_date_formatted;
    		
    		$time_slot = orddd_common::orddd_get_order_timeslot( $post->ID );
            echo '<p>' . $time_slot . '</p>';
		}	
		do_action( 'orddd_add_value_to_woocommerce_custom_column', $column, $post->ID );
	}

	/**
	 * Adds the Delivery Date column in WooCommerce->Orders
     * as a sortable column. Mentions the meta key present in
     * post meta table that can be used for sorting.
     * 
     * @param array $columns - List of sortable columns
     * @return array - Sortable columns with the plugin column included.array
     * 
     * @hook manage_edit-shop_order_sortable_columns
     * @since 2.7
	 */
	public static function orddd_woocommerce_custom_column_value_sort( $columns ) {
		$columns[ 'order_delivery_date' ] = '_orddd_timestamp';
		return $columns;
	}

	/**
	 * Delivery date column orderby. 
	 * 
	 * Helps WooCommerce understand using the value based on which a column should be sorted.
	 * The delivery date is stored as a timestamp in the _orddd_timestamp variable in wp_postmeta
	 * 
	 * @param array $vars - Query variables
	 * @return array $vars - Updated Query variables.
	 * 
	 * @hook request
	 * @since 2.7
	 */
	public static function orddd_woocommerce_delivery_date_orderby( $vars ) {
		global $typenow;
		if( get_option( "orddd_show_column_on_orders_page_check" ) == 'on' ) {
            $delivery_field_label = '_orddd_timestamp';
            if ( isset( $vars[ 'orderby' ] ) ) {
                if ( $delivery_field_label == $vars[ 'orderby' ] ) {
                    $sorting_vars = array( 'orderby'  => array( 'meta_value_num' => $vars[ 'order' ], 'date' => 'ASC' ) );
                    if ( !isset( $_GET[ 'order_delivery_date_filter' ] ) || $_GET['order_delivery_date_filter'] == '') {
                        $sorting_vars[ 'meta_query' ] = array(  'relation' => 'OR', 
                            array (
								'key'	  => $delivery_field_label, 
								'value'	  => '', 
								'compare' => 'NOT EXISTS'
							),
							array (
									'key'	  => $delivery_field_label,
									'compare' => 'EXISTS'
								)
							);
                    }
                    $vars = array_merge( $vars, $sorting_vars );
                }
            } elseif( get_option( "orddd_enable_default_sorting_of_column" ) == 'on' ) {
                if ( 'shop_order' != $typenow ) {
                    return $vars;
                }
                $sorting_vars = array(
                    'orderby'  => array( 'meta_value_num' => 'DESC', 'date' => 'ASC' ),
                    'order'	   => 'DESC' );
                if ( !isset( $_GET[ 'order_delivery_date_filter' ] ) || $_GET[ 'order_delivery_date_filter'     ] == '' ) {
                    $sorting_vars[ 'meta_query' ] = array( 'relation' => 'OR', 
    						array (
    								'key'	  => $delivery_field_label, 
    								'value'	  => '', 
    								'compare' => 'NOT EXISTS'
    							),
    						array (
    								'key'	  => $delivery_field_label,
    								'compare' => 'EXISTS'
    							)
                        );
                }
                $vars = array_merge( $vars, $sorting_vars );
            }
		}
		return $vars;
	}
	
	/**
	 * Prints a dropdown to filter the orders based on Delivery Dates
	 * in WooCommerce->Orders.
	 * 
	 * @hook restrict_manage_posts
	 * @since 2.7
	 */
	public static function orddd_restrict_orders() {
		global $typenow, $wpdb, $wp_locale;

		if ( 'shop_order' != $typenow ) {
			return;
		}

		$gmt = false;
		if( has_filter( 'orddd_gmt_calculations' ) ) {
			$gmt = apply_filters( 'orddd_gmt_calculations', '' );
		}
		$current_time = current_time( 'timestamp', $gmt );

		$javascript = '';
		$filter_field_name = 'order_delivery_date_filter';
		$db_field_name = '_orddd_timestamp';
	
		$months = $wpdb->get_results( $wpdb->prepare( "
		SELECT YEAR( FROM_UNIXTIME( meta_value ) ) as year, MONTH( FROM_UNIXTIME( meta_value ) ) as month, CAST( meta_value AS UNSIGNED ) AS meta_value_num
		FROM " . $wpdb->postmeta . "
		WHERE meta_key = %s
		GROUP BY year, month
		ORDER BY meta_value_num DESC", $db_field_name ) );
		$month_count = 0;
		if( is_array( $months ) ) {
			$month_count = count( $months );			
		}

		if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return;
		}

		if ( isset( $_GET[ $filter_field_name ] ) && $_GET[ $filter_field_name ] == 'today' ) {
			$m = $_GET[ $filter_field_name ];
		} else if ( isset( $_GET[ $filter_field_name ] ) && $_GET[ $filter_field_name ] == 'tomorrow' ) {
			$m = $_GET[ $filter_field_name ];
		} else {
			$m = isset( $_GET[ $filter_field_name ] ) ? (int) $_GET[ $filter_field_name ] : 0;
		}
	    
		$today_option = array( 'year' => date( 'Y', $current_time ), 'month' => 'today', 'meta_value_num' => $current_time );

		$tomorrow_date = date( 'Y-m-d', strtotime( '+1 day', $current_time ) );
		$tomorrow_time = strtotime( $tomorrow_date );
		$tomorrow_option = array( 'year' => date( 'Y', $tomorrow_time ), 'month' => 'tomorrow', 'meta_value_num' => $tomorrow_time );
		array_unshift( $months, (object)$today_option, (object)$tomorrow_option );
		?>
		<select name="order_delivery_date_filter" id="order_delivery_date_filter" class="orddd_filter">
			<option value=""><?php _e( "Show all Delivery Dates", "order-delivery-date" ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( $arc_row->month != 'today' && $arc_row->month != 'tomorrow' ) {
					if ( 0 == $arc_row->year || '1969' == $arc_row->year ) {
						continue;
					}
					$month = zeroise( $arc_row->month, 2 );
					$year = $arc_row->year;
					printf( '<option %s value="%s">%s</option>',
						selected( $m, $year . $month, false ),
						esc_attr( $arc_row->year . $month ),
						/* translators: 1: month name, 2: 4-digit year */
						sprintf( __( '%1$s %2$d', 'order-delivery-date' ), $wp_locale->get_month( $month ), $year )
					);
				} else {
					$arc_row->year = $year = '';
					$month = $arc_row->month;
					printf( '<option %s value="%s">%s</option>',
						selected( $m, $arc_row->month, false ),
						$arc_row->month,
						ucfirst( $arc_row->month )
					);
				}
			}
		?></select><?php

		$javascript .= "jQuery( 'select#order_delivery_date_filter' ).select2();";
		wc_enqueue_js( $javascript );
	}
	
	/**
	 * Filter the orders displayed in WooCommerce->Orders
	 * based on the Delivery Dates filter dropdown.
	 *
	 * @param array $vars - Query Variables
	 * @return array $vars - Updated Query Variables
	 * 
	 * @hook request
	 * @since 2.7
	 */
	public static function orddd_add_filterable_field( $vars ) {
		global $typenow;
		if ( 'shop_order' != $typenow ) {
			return $vars;
		}

		$gmt = false;
		if( has_filter( 'orddd_gmt_calculations' ) ) {
			$gmt = apply_filters( 'orddd_gmt_calculations', '' );
		}
		$current_time = current_time( 'timestamp', $gmt );

		$meta_queries = array( 'relation' => 'AND' );

		// if the field is filterable and selected by the user
		if ( isset( $_GET[ 'order_delivery_date_filter' ] ) && $_GET[ 'order_delivery_date_filter' ] ) {
			$date = $_GET[ 'order_delivery_date_filter' ];
			if ( $date == 'today' ) {
				// from the start to the end of the month
			    $current_date = date( 'Y-m-d', $current_time );
			     
			    $from_date = date( 'Y-m-d H:i:s', strtotime( $current_date . '00:00:00' ) );
			    $to_date = date( 'Y-m-d H:i:s', strtotime( $current_date . '23:59:59' ) );
			    
				$meta_queries[] = array(
					'key'     => '_orddd_timestamp',
					'value'   => array( strtotime( $from_date ), strtotime( $to_date ) ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN'
				);
			} else if ( $date == 'tomorrow' ) {				
				$current_date = date( 'Y-m-d', strtotime('+1 day', $current_time ) );
			     
			    $from_date = date( 'Y-m-d H:i:s', strtotime( $current_date . '00:00:00' ) );
			    $to_date = date( 'Y-m-d H:i:s', strtotime( $current_date . '23:59:59' ) );
			    
				$meta_queries[] = array(
					'key'     => '_orddd_timestamp',
					'value'   => array( strtotime( $from_date ), strtotime( $to_date ) ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN'
				);
			} else {
				// from the start to the end of the month
				$from_date = substr( $date, 0, 4 ) . '-' . substr( $date, 4, 2 ) . '-01';
				$to_date   = substr( $date, 0, 4 ) . '-' . substr( $date, 4, 2 ) . '-' . date( 't', strtotime( $from_date ) );
				$meta_queries[] = array(
					'key'     => '_orddd_timestamp',
					'value'   => array( strtotime( $from_date.' 00:00:00' ), strtotime( $to_date .' 23:59:59' ) ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN'
				);
			}
		}
		// update the query vars with our meta filter queries, if needed
		if ( is_array( $meta_queries ) && count( $meta_queries ) > 1 ) {
			$vars = array_merge(
				$vars,
				array( 'meta_query' => $meta_queries )
			);
		}
		return $vars;
	}

	/** 
	 * Adds the Delivery Date field to the set of searchable fields so that
	 * the orders can be searched based on Delivery details.
	 *
	 * @param array $search_fields - Array of post meta fields to search by 
	 * @return array $search_fields - Updated array of post meta fields to search by 
	 *  
	 * @hook woocommerce_shop_order_search_fields
	 * @since 2.7 
	 */
	public static function orddd_add_search_fields( $search_fields ) {
		$results = orddd_common::orddd_get_shipping_settings();
		foreach ( $results as $key => $value ) {
			$shipping_settings     = get_option( $value->option_name );
			$orddd_date_field_label   = orddd_common::orddd_get_shipping_date_field_label( $shipping_settings );
			array_push( $search_fields, $orddd_date_field_label );
		}
		
		array_push( $search_fields, get_option( 'orddd_delivery_date_field_label' ) );
		return $search_fields;
	}

	/**
	 * Echoes the Delivery date on WooCommerce->Orders->Edit Order page.
	 * 
	 * @param WC_Order $order - Order object
	 * 
	 * @hook woocommerce_admin_order_data_after_billing_address
	 *       woocommerce_admin_order_data_after_shipping_address
	 * @since 2.7      
	 */
	public static function orddd_display_delivery_date_admin_order_meta( $order ) {
		global $orddd_date_formats;
		
		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
            $order_id = $order->get_id();
        } else {
            $order_id = $order->id;
        }
		
		$delivery_date_formatted = orddd_common::orddd_get_order_delivery_date( $order_id );

		$location = orddd_common::orddd_get_order_location( $order_id );
        $shipping_method = orddd_common::orddd_get_order_shipping_method( $order_id );
        $product_category = orddd_common::orddd_get_order_product_category( $order_id );
        $shipping_class = orddd_common::orddd_get_product_shipping_class( $order_id );

         if( '' != $location ) {
            $locations_label = get_option( 'orddd_location_field_label' );
            $address = get_post_meta( $order_id, $locations_label, true );
            echo '<p><strong>' . __( $locations_label, 'order-delivery-date' ) . ': </strong>' . $address;
        }
        
        $field_date_label = orddd_common::orddd_get_delivery_date_field_label( $shipping_method, $product_category, $shipping_class, $location ); 
		
		if( $delivery_date_formatted != '' ) {
			echo '<p><strong>' . __( $field_date_label, 'order-delivery-date' ) . ': </strong>' . $delivery_date_formatted;
		}
	}
	
	/**
	 * Echoes the Delivery time on WooCommerce->Orders->Edit Order page.
	 * 
	 * @param WC_Order $order - Order object
	 * 
	 * @hook woocommerce_admin_order_data_after_billing_address
	 *       woocommerce_admin_order_data_after_shipping_address
	 * @since 2.7      
	 */
	public static function orddd_display_time_slot_admin_order_meta( $order ) {
		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
            $order_id = $order->get_id();
        } else {
            $order_id = $order->id;
        }
        
		$time_slot = orddd_common::orddd_get_order_timeslot( $order_id );

		$location = orddd_common::orddd_get_order_location( $order_id );
        $shipping_method = orddd_common::orddd_get_order_shipping_method( $order_id );
        $product_category = orddd_common::orddd_get_order_product_category( $order_id );
        $shipping_class = orddd_common::orddd_get_product_shipping_class( $order_id );

		$time_field_label = orddd_common::orddd_get_delivery_time_field_label( $shipping_method, $product_category, $shipping_class, $location ); 

		if ( $time_slot != '' && $time_slot != '' ) {
			echo '<p><strong>' . __( $time_field_label, 'order-delivery-date' ) . ': </strong>' . $time_slot . '</p>';
		}
	}
}
$orddd_filter = new orddd_filter();
?>