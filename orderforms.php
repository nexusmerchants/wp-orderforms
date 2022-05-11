<?php
/**
Plugin Name: OrderForms
Plugin URI: https://www.orderforms.com
Description: OrderForms.com allows you to take payments online from your website or ads with professionally designed order forms that are connected to your Stripe merchant account.
Version: 1.5.7
Author: OrderForms.com
Author URI:  https://www.orderforms.com
Text Domain: orderforms
 */

require_once __DIR__ . '/vendor/autoload.php';

$orderforms = \OrderForms\Base::instance( __DIR__ . '/', plugins_url( '', __FILE__ ) );
register_activation_hook( __FILE__, [ $orderforms, 'activation' ] );
register_deactivation_hook( __FILE__, [ $orderforms, 'deactivation' ] );
