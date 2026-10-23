<?php
/**
 * WP_List_Table for tracking records.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Admin list of cargo tracking codes.
 */
class TRF_Cargo_Tracking_List_Table extends WP_List_Table {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'trf_shipment',
				'plural'   => 'trf_shipments',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'           => '<input type="checkbox" />',
			'validation'   => __( 'کد رهگیری', 'trf-cargo-tracking' ),
			'description'  => __( 'آخرین توضیحات', 'trf-cargo-tracking' ),
			'event_date'   => __( 'تاریخ نمایش', 'trf-cargo-tracking' ),
			'update_count' => __( 'ردیف‌ها', 'trf-cargo-tracking' ),
			'images'       => __( 'مدارک', 'trf-cargo-tracking' ),
		);
	}

	/**
	 * Sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'validation' => array( 'validation', false ),
		);
	}

	/**
	 * Bulk actions.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			'delete' => __( 'حذف', 'trf-cargo-tracking' ),
		);
	}

	/**
	 * Checkbox column.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="shipment[]" value="%s" />', esc_attr( $item->validation ) );
	}

	/**
	 * Tracking code with row actions.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_validation( $item ) {
		$edit_url = add_query_arg(
			array(
				'page'   => 'trf-cargo-tracking',
				'action' => 'edit',
				'code'   => $item->validation,
			),
			admin_url( 'admin.php' )
		);
		$delete_url = wp_nonce_url(
			add_query_arg(
				array(
					'page'     => 'trf-cargo-tracking',
					'action'   => 'delete',
					'shipment' => $item->validation,
				),
				admin_url( 'admin.php' )
			),
			TRF_Cargo_Tracking_Admin::delete_nonce_action( $item->validation )
		);

		$actions = array(
			'edit'   => '<a href="' . esc_url( $edit_url ) . '">' . esc_html__( 'ویرایش', 'trf-cargo-tracking' ) . '</a>',
			'delete' => '<a href="' . esc_url( $delete_url ) . '" class="submitdelete" onclick="return confirm(\'' . esc_js( __( 'از حذف این محموله و تمام ردیف‌های آن اطمینان دارید؟', 'trf-cargo-tracking' ) ) . '\');">' . esc_html__( 'حذف', 'trf-cargo-tracking' ) . '</a>',
		);

		return '<strong><a href="' . esc_url( $edit_url ) . '">' . esc_html( $item->validation ) . '</a></strong> ' . $this->row_actions( $actions );
	}

	/**
	 * Description excerpt.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_description( $item ) {
		return esc_html( wp_trim_words( wp_strip_all_tags( $item->description ), 18 ) );
	}

	/**
	 * Public Jalali date of the latest row.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_event_date( $item ) {
		if ( empty( $item->event_date ) ) {
			return '—';
		}

		$jalali = TRF_Cargo_Tracking_Jalali::from_gregorian_date( $item->event_date );

		return $jalali ? esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( $jalali ) ) : '—';
	}

	/**
	 * Number of update rows.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_update_count( $item ) {
		$count = isset( $item->update_count ) ? (int) $item->update_count : 1;

		return esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( (string) $count ) );
	}

	/**
	 * Image count / thumb.
	 *
	 * @param object $item Row.
	 * @return string
	 */
	protected function column_images( $item ) {
		$gallery = TRF_Cargo_Tracking_Gallery::from_row( $item );
		if ( empty( $gallery ) ) {
			return '—';
		}

		$first = TRF_Cargo_Tracking_Gallery::public_url( $gallery[0] );
		$count = count( $gallery );

		if ( TRF_Cargo_Tracking_Gallery::is_pdf( $first ) ) {
			return sprintf(
				'<span class="trf-track-list-file" title="%1$s"><span class="trf-track-list-file__badge">PDF</span></span> <span>%2$s</span>',
				esc_attr( TRF_Cargo_Tracking_Gallery::basename_from_url( $first ) ),
				esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( (string) $count ) )
			);
		}

		return sprintf(
			'<img src="%1$s" alt="" width="40" height="40" style="object-fit:cover;border-radius:4px;vertical-align:middle;" /> <span>%2$s</span>',
			esc_url( $first ),
			esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( (string) $count ) )
		);
	}

	/**
	 * Default column.
	 *
	 * @param object $item        Row.
	 * @param string $column_name Column.
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		return isset( $item->$column_name ) ? esc_html( (string) $item->$column_name ) : '';
	}

	/**
	 * Query and prepare items.
	 */
	public function prepare_items() {
		$per_page = 20;
		$paged    = $this->get_pagenum();
		$search   = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter   = isset( $_REQUEST['trf_filter'] ) ? sanitize_key( wp_unslash( $_REQUEST['trf_filter'] ) ) : 'validation'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$result = TRF_Cargo_Tracking_Repository::query(
			array(
				'search'   => $search,
				'filter'   => $filter,
				'per_page' => $per_page,
				'paged'    => $paged,
			)
		);

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns(), 'validation' );
		$this->items           = $result['items'];
		$this->set_pagination_args(
			array(
				'total_items' => $result['total'],
				'per_page'    => $per_page,
			)
		);
	}

	/**
	 * Extra table nav: search-by selector.
	 *
	 * @param string $which top|bottom.
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}

		$filter = isset( $_REQUEST['trf_filter'] ) ? sanitize_key( wp_unslash( $_REQUEST['trf_filter'] ) ) : 'validation'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
		<div class="alignleft actions">
			<label class="screen-reader-text" for="trf-filter"><?php esc_html_e( 'جستجو در', 'trf-cargo-tracking' ); ?></label>
			<select name="trf_filter" id="trf-filter">
				<option value="validation" <?php selected( $filter, 'validation' ); ?>><?php esc_html_e( 'کد رهگیری', 'trf-cargo-tracking' ); ?></option>
				<option value="description" <?php selected( $filter, 'description' ); ?>><?php esc_html_e( 'توضیحات', 'trf-cargo-tracking' ); ?></option>
			</select>
		</div>
		<?php
	}

	/**
	 * Empty state.
	 */
	public function no_items() {
		esc_html_e( 'محموله‌ای یافت نشد.', 'trf-cargo-tracking' );
	}
}
