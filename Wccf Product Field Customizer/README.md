Wccf Product Field Customizer - Wordpress Plugin
===================================

Customization Woocommerce Product Field Customizer Plugin

Project Period
----------------------
- Start: 2018.7.29
- Finished: 2018.7.31

## Environment
- CMS: Wordpress
- Theme: XStore 3.1
- Plugin: [Wccf-Product-Field-Customizer](http://www.rightpress.net/woocommerce-custom-fields)

## Project History
Customized this plugin.
Added Prefix/Suffix to Product Prices

- PHP Snippet: Add Suffix to WooCommerce Prices
````
/**
 * @snippet       Adds suffix to WooCommerce prices
 * @compatible    WooCommerce 3.8
 */
   
add_filter( 'woocommerce_get_price_suffix', 'bbloomer_add_price_suffix', 99, 4 );
  
function bbloomer_add_price_suffix( $html, $product, $price, $qty ){
    $html .= ' suffix here';
    return $html;
}
````
- PHP Snippet: Add Prefix to WooCommerce Prices
````
/**
 * @snippet       Adds prefix to WooCommerce prices
 * @compatible    WooCommerce 3.8
 */
   
add_filter( 'woocommerce_get_price_html', 'bbloomer_add_price_prefix', 99, 2 );
  
function bbloomer_add_price_prefix( $price, $product ){
    $price = 'Prefix here ' . $price;
    return $price;
}
````
