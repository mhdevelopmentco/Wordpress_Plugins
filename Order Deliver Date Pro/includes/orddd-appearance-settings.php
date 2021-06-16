<?php
/**
 * Order Delivery Date Appearance Settings
 *
 * @author Tyche Softwares
 * @package Order-Delivery-Date-Pro-for-WooCommerce/Admin/Settings/General
 * @since 2.8.3
 * @category Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class orddd_appearance_settings {
	
    /**
     * Callback for adding Appearance tab settings
     */
	public static function orddd_appearance_admin_setting_callback() { }
	
	/**
	 * Callback for adding Field Appearance section settings.
	 */
	public static function orddd_field_appearance_admin_setting_callback() { }
	
	/**
	 * Callback for adding Calendar Language setting
	 * 
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	public static function orddd_appearance_calendar_language_callback( $args ) {
		global $orddd_languages;
		$language_selected = get_option( 'orddd_language_selected' );
		if ( $language_selected == "" ) {
			$language_selected = "en-GB";
		}
		
		echo '<select id="orddd_language_selected" name="orddd_language_selected">';
		
		foreach ( $orddd_languages as $key => $value ) {
			$sel = "";
			if ( $key == $language_selected ) {
				$sel = "selected";
			}
			echo "<option value='$key' $sel>$value</option>";
		}
		
		echo '</select>';
		
		$html = '<label for="orddd_language_selected"> ' . $args[ 0 ] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for adding Date formats setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	public static function orddd_appearance_date_formats_callback( $args ) {
		global $orddd_date_formats;
		
		echo '<select name="orddd_delivery_date_format" id="orddd_delivery_date_format" size="1">';
		
		foreach ( $orddd_date_formats as $k => $format ) {
			printf( "<option %s value='%s'>%s</option>\n",
                selected( $k, get_option( 'orddd_delivery_date_format' ), false ),
                esc_attr( $k ),
                date( $format )
			);
		}
		echo '</select>';
		
		$html = '<label for="orddd_delivery_date_format">' . $args[ 0 ] . '</label>';
		echo $html;
	}

	/**
	 * Callback for adding Time format for time sliders setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_time_format_callback( $args ) {
	    global $orddd_time_formats;
	    echo '<select name="orddd_delivery_time_format" id="orddd_delivery_time_format" size="1">';
	
	    foreach ( $orddd_time_formats as $k => $format ) {
	        printf( "<option %s value='%s'>%s</option>\n",
	        selected( $k, get_option( 'orddd_delivery_time_format' ), false ),
	        esc_attr( $k ),
	        $format
	        );
	    }
	
	    echo '</select>';
	
	    $html = '<label for="orddd_delivery_time_format"> ' . $args[0] . '</label>';
	    echo $html;
	}
	
	/**
	 * Callback for adding First day of week setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_appearance_first_day_of_week_callback( $args ) {
		global $orddd_days;
		$day_selected = get_option( 'start_of_week' );
		if( $day_selected == "" ) {
			$day_selected = 0;
		}
		
		echo '<select id="start_of_week" name="start_of_week">';
		
		foreach ( $orddd_days as $key => $value ) {
			$sel = "";
			if ( $key == $day_selected ) {
			    $sel = " selected ";
			}
			echo "<option value='$key' $sel>$value</option>";
		}
		echo '</select>';
		
		$html = '<label for="start_of_week"> ' . $args[ 0 ] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for adding Locations field label setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_location_field_label_callback( $args ) {
		echo '<input type="text" name="orddd_location_field_label" id="orddd_location_field_label" value="' . get_option( 'orddd_location_field_label' ) . '" maxlength="40"/>';
		
		$html = '<label for="orddd_location_field_label"> ' . $args[ 0 ] . '</label>';
		echo $html;
	}

	/**
	 * Callback for adding Delivery Date field label setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_delivery_date_field_label_callback( $args ) {
		echo '<input type="text" name="orddd_delivery_date_field_label" id="orddd_delivery_date_field_label" value="' . get_option( 'orddd_delivery_date_field_label' ) . '" maxlength="40"/>';
		
		$html = '<label for="orddd_delivery_date_field_label"> ' . $args[ 0 ] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for adding Time slot field label setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_delivery_timeslot_field_label_callback( $args ) {
	    echo '<input type="text" name="orddd_delivery_timeslot_field_label" id="orddd_delivery_timeslot_field_label" value="' . get_option( 'orddd_delivery_timeslot_field_label' ) . '" maxlength="40"/>';
	
	    $html = '<label for="orddd_delivery_timeslot_field_label"> ' . $args[0] . '</label>';
	    echo $html;
	}
	
	/**
	 * Callback for adding Delivery Date field placeholder setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_delivery_date_field_placeholder_callback( $args ) {
	    echo '<input type="text" name="orddd_delivery_date_field_placeholder" id="orddd_delivery_date_field_placeholder" value="' . get_option( 'orddd_delivery_date_field_placeholder' ) . '" maxlength="40"/>';
	
	    $html = '<label for="orddd_delivery_date_field_placeholder"> ' . $args[ 0 ] . '</label>';
	    echo $html;
	}
	
	/**
	 * Callback for adding Delivery Date field note text setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_delivery_date_field_note_text_callback( $args ) {		
        echo '<textarea rows="4" cols="70" name="orddd_delivery_date_field_note" id="orddd_delivery_date_field_note">' . stripslashes( get_option( 'orddd_delivery_date_field_note' ) ) . '</textarea>';
		
		$html = '<label for="orddd_delivery_date_field_note"> ' . $args[ 0 ] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for adding Number of months setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_appearance_number_of_months_callback( $args ) {
		global $orddd_number_of_months;
		echo '<select name="orddd_number_of_months" id="orddd_number_of_months" size="1">';
		
		foreach ( $orddd_number_of_months as $k => $v ) {
			printf( "<option %s value='%s'>%s</option>\n",
				selected( $k, get_option( 'orddd_number_of_months' ), false ),
				esc_attr( $k ),
				$v
			);
		}
		echo '</select>';
	    
		$html = '<label for="orddd_number_of_months">' . $args[ 0 ] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for adding Delivery Date fields in Shipping section setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */ 
	
	public static function orddd_delivery_date_in_shipping_section_callback( $args ) {
	    
		$orddd_date_in_billing = "checked";
		$orddd_date_in_shipping = '';
		$orddd_date_before_order_notes = '';
		$orddd_date_after_order_notes = '';
		$orddd_date_after_your_order_table = '';
		$orddd_custom_hook_for_fields_placement = '';
		if ( "billing_section" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
		    $orddd_date_in_billing = "checked";		    
		} else if ( "shipping_section" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
		    $orddd_date_in_shipping = "checked";		    
		} else if ( "before_order_notes" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
		    $orddd_date_before_order_notes = "checked";		    
		} else if ( "after_order_notes" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
		    $orddd_date_after_order_notes = "checked";		    
		} else if ( "after_your_order_table" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
			$orddd_date_after_your_order_table = "checked";			
		} else if ( "custom" == get_option( 'orddd_delivery_date_fields_on_checkout_page' ) ) {
			$orddd_custom_hook_for_fields_placement = "checked";			
		}
		
		echo '<input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="billing_section" ' . $orddd_date_in_billing . '>' . __( 'In Billing Section', 'order-delivery-date' ) . '<br>
			 <input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="shipping_section" ' . $orddd_date_in_shipping . '>' . __( 'In Shipping Section', 'order-delivery-date' ) . '<br>
		     <input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="before_order_notes" ' . $orddd_date_before_order_notes . '>' . __( 'Before Order Notes', 'order-delivery-date' ) . '<br>
		     <input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="after_order_notes" ' . $orddd_date_after_order_notes . '>' . __( 'After Order Notes', 'order-delivery-date' ) . '<br>
		     <input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="after_your_order_table" ' . $orddd_date_after_your_order_table . '>' . __( 'Between Your Order & Payment Section', 'order-delivery-date' ) . '&nbsp;&nbsp;<br>
		     <input type="radio" name="orddd_delivery_date_fields_on_checkout_page" id="orddd_delivery_date_fields_on_checkout_page" value="custom" ' . $orddd_custom_hook_for_fields_placement . '>' . __( 'Custom:', 'order-delivery-date' ) . '&nbsp;&nbsp;&nbsp;
		     <input type="text" name="orddd_custom_hook_for_fields_placement" id="orddd_custom_hook_for_fields_placement" value="' . get_option( 'orddd_custom_hook_for_fields_placement' ) . '" placeholder="Add a custom hook" style="width:400px;"/>';
		
		$html = '<label for="orddd_delivery_date_fields_on_checkout_page"><br>' . $args[0] . '</label>';
		echo $html;
	}
	
	/**
	 * Callback for hiding Delivery Date fields on the checkout page for Featured product setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_appearance_featured_product_callback( $args ) {	
	    $html = '<label for="orddd_no_fields_for_featured_product"> ' . $args[ 0 ] . '</label>';
	    echo $html;
	}
	
	/**
	 * Callback for adding Calendar theme setting
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	
	public static function orddd_appearance_calendar_theme_callback( $args ) {
	    global $orddd_calendar_themes;
		$language_selected = get_option( 'orddd_language_selected' );
		if ( $language_selected == "" ) {
			$language_selected = "en-GB";
		}

		$calendar_theme = get_option( 'orddd_calendar_theme' );
		if( '' == $calendar_theme ) {
			$calendar_theme = 'smoothness';
		} 

		$calendar_theme_name = get_option( 'orddd_calendar_theme_name' );
		if( '' == $calendar_theme_name ) {
			$calendar_theme_name = 'Smoothness';
		} 

		echo '<input type="hidden" name="orddd_calendar_theme" id="orddd_calendar_theme" value="' . $calendar_theme . '">
            <input type="hidden" name="orddd_calendar_theme_name" id="orddd_calendar_theme_name" value="' . $calendar_theme_name . '">';
		echo '<script>
			jQuery( document ).ready( function( ) {
               var calendar_themes = ' . json_encode( $orddd_calendar_themes ) .'
	   	       jQuery( "#switcher" ).themeswitcher( {
	   	       		imgpath: "'.plugins_url().'/order-delivery-date/images/",
					loadTheme: "' . $calendar_theme_name . '",
					cookieName: "orddd-jquery-ui-theme",
					onclose: function() {
						var cookie_name = this.cookiename;
						jQuery( "input#orddd_calendar_theme" ).val( jQuery.cookie( "orddd-jquery-ui-theme" ) );
                        jQuery.each( calendar_themes, function( key, value ) {
                            if(  jQuery.cookie( "orddd-jquery-ui-theme" ) == key ) {
                                jQuery( "input#orddd_calendar_theme_name" ).val( value );
                            }
                        });
                        jQuery( "<link/>", {
                                rel: "stylesheet",
                                type: "text/css",
                                href: "' . plugins_url() . '/order-delivery-date/css/datepicker.css"
                        }).appendTo( "head" );
		    		},
		    		
				});
			});
			jQuery( function() {
				jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "" ] );
				jQuery( "#datepicker" ).datepicker( jQuery.datepicker.regional[ "' . $language_selected . '" ] );
				jQuery( "#localisation_select" ).change(function() {
					jQuery( "#datepicker" ).datepicker( "option",
						jQuery.datepicker.regional[ jQuery( this ).val() ] );
					} );
			} );
		</script>
		<div id="switcher"></div>
		<br><strong>' . __( 'Preview theme:', 'order-delivery-date' ) . '</strong><br>
		<div id="datepicker" style="width:300px"></div>';
	   
		$html = '<label for="orddd_calendar_theme_name"> ' . $args[0] . '</label>';
		echo $html;
	}

	/**
	 * Callback for adding the setting to display Delivery Date on cart page
	 *
	 * @param array $args Extra arguments containing label & class for the field
	 * @since 2.8.3
	 */
	public static function orddd_delivery_date_on_cart_page_callback( $args ) {
		$delivery_date_on_cart_page = "";
		if ( get_option( ' orddd_delivery_date_on_cart_page' ) == 'on' ) {
			$delivery_date_on_cart_page = "checked";
		}
		
		echo '<input type="checkbox" name="orddd_delivery_date_on_cart_page" id="orddd_delivery_date_on_cart_page" class="day-checkbox" ' . $delivery_date_on_cart_page . '/>';

		$html = '<label for="orddd_delivery_date_on_cart_page"> ' . $args[0] . '</label>';
		echo $html;	
	}

	/**
	 * Callback for adding Color Picker Settings section
	 *
	 * @since 8.4
	 */
	public static function orddd_color_picker_admin_setting_callback() { }

	/**
	 * Callback for adding Holidays Color setting
	 *
	 * @since 8.4
	 */
	public static function orddd_holiday_color_callback( $args ) {
		$orddd_holiday_color = get_option( 'orddd_holiday_color' );
		echo '<input id="orddd_holiday_color"  name="orddd_holiday_color" class="cpa-color-picker" value="' . $orddd_holiday_color . '">';
		$html = '<label for="orddd_holiday_color"> ' . $args[0] . '</label>';
		echo $html;	
	}

	/**
	 * Callback for adding Booked Dates Color setting
	 *
	 * @since 8.4
	 */
	public static function orddd_booked_dates_color_callback( $args ) {
		$orddd_booked_dates_color = get_option( 'orddd_booked_dates_color' );
		echo '<input id="orddd_booked_dates_color"  name="orddd_booked_dates_color" class="cpa-color-picker" value="' . $orddd_booked_dates_color . '">';
		$html = '<label for="orddd_booked_dates_color"> ' . $args[0] . '</label>';
		echo $html;	
	}


	/**
	 * Callback for adding Cut-off Time over dates Color setting
	 *
	 * @since 8.4
	 */
	public static function orddd_cut_off_time_color_callback( $args ) {
		$orddd_cut_off_time_color = get_option( 'orddd_cut_off_time_color' );
		echo '<input type="text" id="orddd_cut_off_time_color"  name="orddd_cut_off_time_color" class="cpa-color-picker" value="' . $orddd_cut_off_time_color . '">';
		$html = '<label for="orddd_cut_off_time_color"> ' . $args[0] . '</label>';
		echo $html;	
	}

	/**
	 * Callback for adding available dates Color setting
	 *
	 * @since 8.4
	 */
	public static function orddd_available_dates_color_callback( $args ) {
		$orddd_available_dates_color = get_option( 'orddd_available_dates_color' );
		echo '<input type="text" id="orddd_available_dates_color"  name="orddd_available_dates_color" class="cpa-color-picker" value="' . $orddd_available_dates_color . '">';
		$html = '<label for="orddd_available_dates_color"> ' . $args[0] . '</label>';
		echo $html;	
	}
}