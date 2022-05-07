<?php

namespace OrderForms;

class Admin {
	private $wpdb, $options;
	const POST_TYPE = 'orderforms';

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	public static function register_post_type() {
		$labels  = [
			'name'               => _x( 'Forms', 'post type general name', self::POST_TYPE ),
			'singular_name'      => _x( 'Form', 'post type singular name', self::POST_TYPE ),
			'menu_name'          => _x( 'OrderForms', 'admin menu', self::POST_TYPE ),
			'name_admin_bar'     => _x( 'OrderForms', 'add new on admin bar', self::POST_TYPE ),
			'add_new'            => _x( 'Add New', 'book', self::POST_TYPE ),
			'add_new_item'       => __( 'Add New Form', self::POST_TYPE ),
			'new_item'           => __( 'New Form', self::POST_TYPE ),
			'edit_item'          => __( 'Edit Form', self::POST_TYPE ),
			'view_item'          => __( 'View Form', self::POST_TYPE ),
			'all_items'          => __( 'Forms', self::POST_TYPE ),
			'search_items'       => __( 'Search Forms', self::POST_TYPE ),
			'parent_item_colon'  => __( 'Parent Forms:', self::POST_TYPE ),
			'not_found'          => __( 'No Forms found.', self::POST_TYPE ),
			'not_found_in_trash' => __( 'No Forms found in Trash.', self::POST_TYPE )

		];
		$options = get_option( 'orderforms' );
		register_post_type( self::POST_TYPE, [
			'labels'              => $labels,
			'public'              => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'has_archive'         => self::POST_TYPE,
			'rewrite'             => [
				'slug'       => $options['slug'],
				'with_front' => true,
				'feeds'      => false,
				'pages'      => false,
			],
			'show_ui'             => true,
			'supports'            => [ 'title' ]
		] );
	}

	public function change_title_placeholder( $title ) {
		global $post_type;
		if ( $post_type == self::POST_TYPE ) {
			return __( 'Form Name', self::POST_TYPE );
		}

		return $title;
	}

	public function save_meta( $post_id ) {
		global $post_type;

		if ( $post_type == self::POST_TYPE ) {
			if ( isset( $_POST['orderforms_id'] ) ) {
				if ( ! add_post_meta( $post_id, 'orderforms_id', $_POST['orderforms_id'], true ) ) {
					update_post_meta( $post_id, 'orderforms_id', $_POST['orderforms_id'] );
				}
			}
		}
	}

	public function add_meta_boxes() {
		global $post_type;
		if ( $post_type == self::POST_TYPE ) {
			add_meta_box( 'orderform_field', __( 'Embed Form', self::POST_TYPE ), [
				$this,
				'add_metabox'
			], self::POST_TYPE, 'normal' );
		}
	}

	public function add_metabox( $post ) {
		$data  = get_option( 'orderforms' );
		$value = get_post_meta( $post->ID, 'orderforms_id', true );
		?>
        <div>
            <label><strong>Form URL:</strong></label><br/>
            <input style='width:100%' type="text" value="<?php echo $value ?>" name="orderforms_id" size="70" placeholder="https://SUBDOMAIN.orderforms.com/order/form/FORMID" pattern="https://+[A-Za-z0-9]+.orderforms.com/order/form/.+[A-Za-z0-9]"/
            required>
        </div>
        <div style='font-size:12px;margin-top:8px;'>Enter the full URL to the order form</div><?php
	}

	public function admin_settings_nav() {
		add_submenu_page( 'edit.php?post_type=' . self::POST_TYPE, 'OrderForms Settings', __( 'Settings' ), 'manage_options', 'settings', [
			$this,
			'settings_page'
		] );
	}

	public function settings_page() {
		$options = get_option( 'orderforms' );
		if ( ! empty( $_POST['_orderforms_wpnonce'] ) && wp_verify_nonce( $_POST['_orderforms_wpnonce'] ) ) {
			update_option( 'orderforms', $_POST['orderforms'] );
			$options = $_POST['orderforms'];
			$updated = true;
		}
		?>
        <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>

		<?php if ( ! empty( $updated ) ): ?>
            <div class="notice notice-success">
                <p><?php _e( 'Settings Saved!' ); ?></p>
            </div>
		<?php endif; ?>

        <form action="" method="post" accept-charset="utf-8">
			<?php wp_nonce_field( - 1, '_orderforms_wpnonce' ); ?>
            <div>
                <label>Enter Slug Name:</label><br/>
				<?php echo get_site_url(); ?>
                /<input type="text" name="orderforms[slug]" value="<?php echo $options['slug'] ?? '' ?>">/formname
            </div>
            <div style='font-size:12px;margin-top:8px;'><strong>Note:</strong> After changing the slug please <a href="/wp-admin/options-permalink.php">update the "Permalink Settings" by clicking on the "Save Changes" button</a>.</div>

			<?php submit_button(); ?>
        </form>
        </div><?php
	}
}
