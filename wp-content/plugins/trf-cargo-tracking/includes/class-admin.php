<?php
/**
 * Admin screens.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cargo tracking admin UI.
 */
class TRF_Cargo_Tracking_Admin {

	/**
	 * Register menus and handlers.
	 */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'admin_post_trf_track_save', array( __CLASS__, 'handle_save' ) );
		add_action( 'admin_post_trf_track_import', array( __CLASS__, 'handle_import' ) );
		add_action( 'admin_post_trf_track_settings', array( __CLASS__, 'handle_settings' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_list_actions' ) );
	}

	/**
	 * Admin menu.
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'ردیابی بار', 'trf-cargo-tracking' ),
			__( 'ردیابی بار', 'trf-cargo-tracking' ),
			'manage_options',
			'trf-cargo-tracking',
			array( __CLASS__, 'render_list' ),
			'dashicons-location-alt',
			26
		);

		add_submenu_page(
			'trf-cargo-tracking',
			__( 'همه محموله‌ها', 'trf-cargo-tracking' ),
			__( 'همه محموله‌ها', 'trf-cargo-tracking' ),
			'manage_options',
			'trf-cargo-tracking',
			array( __CLASS__, 'render_list' )
		);

		add_submenu_page(
			'trf-cargo-tracking',
			__( 'افزودن محموله', 'trf-cargo-tracking' ),
			__( 'افزودن محموله', 'trf-cargo-tracking' ),
			'manage_options',
			'trf-cargo-tracking-add',
			array( __CLASS__, 'render_add' )
		);

		add_submenu_page(
			'trf-cargo-tracking',
			__( 'درون‌ریزی CSV', 'trf-cargo-tracking' ),
			__( 'درون‌ریزی CSV', 'trf-cargo-tracking' ),
			'manage_options',
			'trf-cargo-tracking-import',
			array( __CLASS__, 'render_import' )
		);

		add_submenu_page(
			'trf-cargo-tracking',
			__( 'تنظیمات ردیابی', 'trf-cargo-tracking' ),
			__( 'تنظیمات', 'trf-cargo-tracking' ),
			'manage_options',
			'trf-cargo-tracking-settings',
			array( __CLASS__, 'render_settings' )
		);
	}

	/**
	 * Admin CSS/JS on our screens.
	 *
	 * @param string $hook Current hook.
	 */
	public static function enqueue( $hook ) {
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 0 !== strpos( $page, 'trf-cargo-tracking' ) ) {
			return;
		}

		wp_enqueue_style(
			'trf-cargo-tracking-admin',
			TRF_TRACK_URL . 'assets/css/admin.css',
			array(),
			TRF_TRACK_VERSION
		);

		if ( in_array( $page, array( 'trf-cargo-tracking', 'trf-cargo-tracking-add' ), true ) ) {
			wp_enqueue_media();
			wp_enqueue_script(
				'trf-cargo-tracking-admin',
				TRF_TRACK_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				TRF_TRACK_VERSION,
				true
			);
		}
	}

	/**
	 * List / edit / bulk delete.
	 */
	public static function handle_list_actions() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( 'trf-cargo-tracking' !== $page ) {
			return;
		}

		$action = '';
		if ( isset( $_REQUEST['action'] ) && '-1' !== $_REQUEST['action'] ) {
			$action = sanitize_key( wp_unslash( $_REQUEST['action'] ) );
		} elseif ( isset( $_REQUEST['action2'] ) && '-1' !== $_REQUEST['action2'] ) {
			$action = sanitize_key( wp_unslash( $_REQUEST['action2'] ) );
		}

		if ( 'delete' !== $action ) {
			return;
		}

		$raw = isset( $_REQUEST['shipment'] ) ? wp_unslash( $_REQUEST['shipment'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$ids = array_filter( array_map( 'absint', (array) $raw ) );
		if ( empty( $ids ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		$ok    = wp_verify_nonce( $nonce, 'bulk-trf_shipments' );
		if ( ! $ok && 1 === count( $ids ) ) {
			$ok = wp_verify_nonce( $nonce, 'trf_track_delete_' . $ids[0] );
		}
		if ( ! $ok ) {
			return;
		}

		$deleted = TRF_Cargo_Tracking_Repository::delete_many( $ids );
		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => 'trf-cargo-tracking',
					'deleted' => $deleted,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	/**
	 * Add/update form POST.
	 */
	public static function handle_save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی مجاز نیست.', 'trf-cargo-tracking' ) );
		}
		check_admin_referer( 'trf_track_save' );

		$id          = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$code        = isset( $_POST['validation'] ) ? sanitize_text_field( wp_unslash( $_POST['validation'] ) ) : '';
		$description = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';
		$urls        = TRF_Cargo_Tracking_Gallery::urls_from_request();

		if ( $id ) {
			$result = TRF_Cargo_Tracking_Repository::update( $id, $description, $urls );
			$target = add_query_arg(
				array(
					'page'   => 'trf-cargo-tracking',
					'action' => 'edit',
					'id'     => $id,
				),
				admin_url( 'admin.php' )
			);
		} else {
			$result = TRF_Cargo_Tracking_Repository::insert( $code, $description, $urls );
			$target = admin_url( 'admin.php?page=trf-cargo-tracking-add' );
		}

		if ( is_wp_error( $result ) ) {
			$target = add_query_arg(
				array(
					'trf_error' => rawurlencode( $result->get_error_message() ),
				),
				$target
			);
		} else {
			$target = add_query_arg( array( 'trf_saved' => 1 ), $target );
			if ( ! $id && is_int( $result ) ) {
				$target = add_query_arg(
					array(
						'page'      => 'trf-cargo-tracking',
						'action'    => 'edit',
						'id'        => $result,
						'trf_saved' => 1,
					),
					admin_url( 'admin.php' )
				);
			}
		}

		wp_safe_redirect( $target );
		exit;
	}

	/**
	 * CSV import POST.
	 */
	public static function handle_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی مجاز نیست.', 'trf-cargo-tracking' ) );
		}
		check_admin_referer( 'trf_track_import' );

		$target = admin_url( 'admin.php?page=trf-cargo-tracking-import' );

		if ( empty( $_FILES['csv_import']['tmp_name'] ) ) {
			wp_safe_redirect( add_query_arg( 'trf_error', rawurlencode( __( 'فایلی انتخاب نشد.', 'trf-cargo-tracking' ) ), $target ) );
			exit;
		}

		$name = isset( $_FILES['csv_import']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['csv_import']['name'] ) ) : '';
		$ext  = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );
		if ( 'csv' !== $ext ) {
			wp_safe_redirect( add_query_arg( 'trf_error', rawurlencode( __( 'فقط فایل CSV مجاز است.', 'trf-cargo-tracking' ) ), $target ) );
			exit;
		}

		$result = TRF_Cargo_Tracking_Import::from_file( $_FILES['csv_import']['tmp_name'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$args   = array(
			'inserted'   => (int) $result['inserted'],
			'duplicates' => (int) $result['duplicates'],
			'skipped'    => (int) $result['skipped'],
		);
		if ( $result['error'] ) {
			$args['trf_error'] = rawurlencode( $result['error'] );
		}

		wp_safe_redirect( add_query_arg( $args, $target ) );
		exit;
	}

	/**
	 * Settings POST.
	 */
	public static function handle_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی مجاز نیست.', 'trf-cargo-tracking' ) );
		}
		check_admin_referer( 'trf_track_settings' );

		$current = get_option( 'trf_cargo_tracking', array() );
		$update  = array(
			'not_found_message' => isset( $_POST['not_found_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['not_found_message'] ) ) : '',
			'code_label'        => isset( $_POST['code_label'] ) ? sanitize_text_field( wp_unslash( $_POST['code_label'] ) ) : '',
			'button_label'      => isset( $_POST['button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['button_label'] ) ) : '',
		);

		update_option( 'trf_cargo_tracking', array_merge( is_array( $current ) ? $current : array(), $update ) );

		wp_safe_redirect( add_query_arg( 'trf_saved', 1, admin_url( 'admin.php?page=trf-cargo-tracking-settings' ) ) );
		exit;
	}

	/**
	 * List or edit screen.
	 */
	public static function render_list() {
		$action = isset( $_GET['action'] ) ? sanitize_key( wp_unslash( $_GET['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'edit' === $action ) {
			self::render_form( absint( isset( $_GET['id'] ) ? $_GET['id'] : 0 ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		require_once TRF_TRACK_DIR . 'includes/class-list-table.php';
		$table = new TRF_Cargo_Tracking_List_Table();
		$table->prepare_items();

		include TRF_TRACK_DIR . 'templates/admin/list.php';
	}

	/**
	 * Add screen.
	 */
	public static function render_add() {
		self::render_form( 0 );
	}

	/**
	 * Add/edit form.
	 *
	 * @param int $id Row ID, 0 for new.
	 */
	public static function render_form( $id ) {
		$row     = $id ? TRF_Cargo_Tracking_Repository::find( $id ) : null;
		$gallery = $row ? TRF_Cargo_Tracking_Gallery::from_row( $row ) : array();
		include TRF_TRACK_DIR . 'templates/admin/form.php';
	}

	/**
	 * Import screen.
	 */
	public static function render_import() {
		include TRF_TRACK_DIR . 'templates/admin/import.php';
	}

	/**
	 * Settings screen.
	 */
	public static function render_settings() {
		$options = wp_parse_args(
			get_option( 'trf_cargo_tracking', array() ),
			array(
				'not_found_message' => 'کد رهگیری یافت نشد.',
				'code_label'        => 'کد رهگیری',
				'button_label'      => 'بررسی وضعیت بار',
			)
		);
		include TRF_TRACK_DIR . 'templates/admin/settings.php';
	}

	/**
	 * Admin notice from query args.
	 */
	public static function notices() {
		if ( ! empty( $_GET['trf_saved'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'ذخیره شد.', 'trf-cargo-tracking' ) . '</p></div>';
		}
		if ( ! empty( $_GET['deleted'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'موارد انتخاب‌شده حذف شد.', 'trf-cargo-tracking' ) . '</p></div>';
		}
		if ( ! empty( $_GET['trf_error'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( sanitize_text_field( wp_unslash( $_GET['trf_error'] ) ) ) . '</p></div>'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}
}
