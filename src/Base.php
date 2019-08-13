<?php 
namespace OrderForms;

class Base extends Admin {
	public static $instance = null;
	private $path, $url;

	public function __construct( $path, $url ) {
		$this->path = $path;
		$this->url = $url;
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'admin_menu', [ $this, 'admin_settings_nav' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_meta' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_filter( 'enter_title_here', [ $this, 'change_title_placeholder' ] );
		add_filter( 'single_template', [ $this, 'load_custom_template' ] );
	}

	public static function instance( $path, $url ) {
		if( !self::$instance ) 
			self::$instance = new self( $path, $url );
		return self::$instance;
	}

	public function enqueue() {
		wp_register_style( 'orderform', $this->url . '/css/index.css' );
	}

	public function load_custom_template( $template ) {
		global $post;
		
		if( $post->post_type == self::POST_TYPE ) {
			return $this->path . 'templates/orderform-template.php';
		}
		return $template;
	}

	public static function activation() {
		self::register_post_type();
		flush_rewrite_rules();
	}

	public static function deactivation() {
		flush_rewrite_rules();
	}
}