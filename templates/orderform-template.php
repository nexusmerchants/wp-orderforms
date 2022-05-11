<?php
/**
 * The template for displaying the order form
 *
 * @package OrderForms
 */

$option = get_option( 'orderforms' );

$url = get_post_meta( $post->ID, 'orderforms_id', true );
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'orderform' );
} );
?>
<!DOCTYPE html>
<html>
<head>
	<?php wp_head(); ?>
</head>
<body>
<iframe class="orderform" src="<?php esc_attr_e($url)  ?>" height="100%" width="100%"></iframe>
<?php wp_footer(); ?>
</body>
</html>
