<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

$option = get_option( 'orderforms' );

$url = sprintf( get_post_meta( $post->ID, 'orderforms_id', true ) );
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'orderform' );
});
?>
<!DOCTYPE html>
<html>
<head>
	<?php wp_head();?>
</head>
<body>
	<iframe class="orderform" src="<?php echo $url?>" height="100%" width="100%"></iframe>
	<?php wp_footer();?>
</body>
</html>
