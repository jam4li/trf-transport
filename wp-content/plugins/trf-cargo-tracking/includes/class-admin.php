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
			wp_enqueue_style(
				'trf-jalalidatepicker',
				TRF_TRACK_URL . 'assets/vendor/jalalidatepicker/jalalidatepicker.min.css',
				array(),
				TRF_TRACK_VERSION
			);
			wp_enqueue_script(
				'trf-jalalidatepicker',
				TRF_TRACK_URL . 'assets/vendor/jalalidatepicker/jalalidatepicker.min.js',
				array(),
				TRF_TRACK_VERSION,
				true
			);
			wp_enqueue_media();
			wp_enqueue_script(
				'trf-cargo-tracking-admin',
				TRF_TRACK_URL . 'assets/js/admin.js',
				array( 'jquery', 'trf-jalalidatepicker' ),
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

		$raw   = isset( $_REQUEST['shipment'] ) ? wp_unslash( $_REQUEST['shipment'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$codes = array_filter( array_map( 'sanitize_text_field', (array) $raw ) );
		if ( empty( $codes ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		$ok    = wp_verify_nonce( $nonce, 'bulk-trf_shipments' );
		if ( ! $ok && 1 === count( $codes ) ) {
			$ok = wp_verify_nonce( $nonce, self::delete_nonce_action( reset( $codes ) ) );
		}
		if ( ! $ok ) {
			return;
		}

		$deleted = TRF_Cargo_Tracking_Repository::delete_many_codes( $codes );
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

		$code   = isset( $_POST['validation'] ) ? sanitize_text_field( wp_unslash( $_POST['validation'] ) ) : '';
		$is_new = empty( $_POST['is_edit'] );
		$result = TRF_Cargo_Tracking_Repository::sync_shipment( $code, self::entries_from_request(), $is_new );

		$target = $is_new
			? admin_url( 'admin.php?page=trf-cargo-tracking-add' )
			: add_query_arg(
				array(
					'page'   => 'trf-cargo-tracking',
					'action' => 'edit',
					'code'   => $code,
				),
				admin_url( 'admin.php' )
			);

		if ( is_wp_error( $result ) ) {
			$target = add_query_arg(
				array(
					'trf_error' => rawurlencode( $result->get_error_message() ),
				),
				$target
			);
		} else {
			$saved_code = is_string( $result ) ? $result : $code;
			$target     = add_query_arg(
				array(
					'page'      => 'trf-cargo-tracking',
					'action'    => 'edit',
					'code'      => $saved_code,
					'trf_saved' => 1,
				),
				admin_url( 'admin.php' )
			);
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
			$code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( '' === $code && ! empty( $_GET['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$row  = TRF_Cargo_Tracking_Repository::find( absint( $_GET['id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$code = $row ? $row->validation : '';
			}
			self::render_form( $code );
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
		self::render_form( '' );
	}

	/**
	 * Add/edit form.
	 *
	 * @param string $code Tracking code, empty for new.
	 */
	public static function render_form( $code ) {
		$code     = sanitize_text_field( $code );
		$entries  = '' !== $code ? TRF_Cargo_Tracking_Repository::find_all_by_code( $code ) : array();
		$is_edit  = ! empty( $entries );
		$notfound = ( '' !== $code && ! $is_edit && isset( $_GET['action'] ) && 'edit' === sanitize_key( wp_unslash( $_GET['action'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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

	/**
	 * Nonce action for deleting a tracking code.
	 *
	 * @param string $code Tracking code.
	 * @return string
	 */
	public static function delete_nonce_action( $code ) {
		return 'trf_track_delete_' . md5( (string) $code );
	}

	/**
	 * Parse repeater rows from POST.
	 *
	 * @return array
	 */
	private static function entries_from_request() {
		$raw = array();
		if ( isset( $_POST['entries'] ) && is_array( $_POST['entries'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$raw = wp_unslash( $_POST['entries'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		$entries = array();
		foreach ( $raw as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$urls = array();
			if ( isset( $row['gallery_urls'] ) && is_array( $row['gallery_urls'] ) ) {
				$urls = TRF_Cargo_Tracking_Gallery::normalize_urls( $row['gallery_urls'] );
			}

			$entries[] = array(
				'id'          => isset( $row['id'] ) ? absint( $row['id'] ) : 0,
				'description' => isset( $row['description'] ) ? sanitize_textarea_field( $row['description'] ) : '',
				'event_date'  => isset( $row['event_date'] ) ? sanitize_text_field( $row['event_date'] ) : '',
				'urls'        => $urls,
			);
		}

		return $entries;
	}

	/**
	 * Markup for one admin update row.
	 *
	 * @param int         $index   Repeater index.
	 * @param object|null $entry   Existing row.
	 * @param bool        $is_tpl  Whether this is the JS template.
	 */
	public static function render_entry_row( $index, $entry = null, $is_tpl = false ) {
		$exists      = $entry instanceof stdClass;
		$id          = $exists ? (int) $entry->id : 0;
		$description = $exists ? $entry->description : '';
		$event_date  = ( $exists && ! empty( $entry->event_date ) ) ? TRF_Cargo_Tracking_Jalali::from_gregorian_date( $entry->event_date ) : '';
		$created_at  = ( $exists && ! empty( $entry->created_at ) ) ? TRF_Cargo_Tracking_Jalali::from_gregorian_datetime( $entry->created_at ) : '';
		$gallery     = $exists ? TRF_Cargo_Tracking_Gallery::from_row( $entry ) : array();
		$prefix      = 'entries[' . $index . ']';
		?>
		<div class="trf-track-entry" data-trf-entry>
			<div class="trf-track-entry__head">
				<strong><?php esc_html_e( 'ردیف به‌روزرسانی', 'trf-cargo-tracking' ); ?></strong>
				<button type="button" class="button-link trf-track-entry__remove" data-trf-entry-remove><?php esc_html_e( 'حذف ردیف', 'trf-cargo-tracking' ); ?></button>
			</div>
			<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[id]" value="<?php echo $is_tpl ? '' : (int) $id; ?>">
			<p>
				<label>
					<?php esc_html_e( 'توضیحات / وضعیت بار', 'trf-cargo-tracking' ); ?>
					<textarea name="<?php echo esc_attr( $prefix ); ?>[description]" class="large-text" rows="4" <?php echo $is_tpl ? '' : 'required'; ?>><?php echo esc_textarea( $description ); ?></textarea>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'تاریخ نمایش (هجری شمسی)', 'trf-cargo-tracking' ); ?>
					<input
						type="text"
						name="<?php echo esc_attr( $prefix ); ?>[event_date]"
						class="regular-text trf-track-jalali"
						data-jdp
						data-jdp-only-date
						value="<?php echo esc_attr( $event_date ); ?>"
						autocomplete="off"
						<?php echo $is_tpl ? '' : 'required'; ?>
					>
				</label>
			</p>
			<p class="trf-track-entry__recorded">
				<span><?php esc_html_e( 'تاریخ ثبت (فقط مدیریت):', 'trf-cargo-tracking' ); ?></span>
				<?php if ( $created_at ) : ?>
					<strong><?php echo esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( $created_at ) ); ?></strong>
				<?php else : ?>
					<em><?php esc_html_e( 'پس از ذخیره پر می‌شود', 'trf-cargo-tracking' ); ?></em>
				<?php endif; ?>
			</p>
			<?php TRF_Cargo_Tracking_Gallery::render_admin_field( $gallery, $prefix . '[gallery_urls][]' ); ?>
		</div>
		<?php
	}
}
