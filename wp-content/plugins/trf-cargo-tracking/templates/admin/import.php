<?php
/**
 * CSV import screen.
 *
 * @package TRF_Cargo_Tracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$inserted   = isset( $_GET['inserted'] ) ? absint( $_GET['inserted'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$duplicates = isset( $_GET['duplicates'] ) ? absint( $_GET['duplicates'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$skipped    = isset( $_GET['skipped'] ) ? absint( $_GET['skipped'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<div class="wrap">
	<h1><?php esc_html_e( 'درون‌ریزی محموله‌ها', 'trf-cargo-tracking' ); ?></h1>
	<?php TRF_Cargo_Tracking_Admin::notices(); ?>

	<?php if ( $inserted || $duplicates || $skipped ) : ?>
		<div class="notice notice-info">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: inserted, 2: duplicates, 3: skipped */
						__( '%1$d مورد افزوده شد، %2$d تکراری نادیده گرفته شد، %3$d ردیف نامعتبر رد شد.', 'trf-cargo-tracking' ),
						$inserted,
						$duplicates,
						$skipped
					)
				);
				?>
			</p>
		</div>
	<?php endif; ?>

	<p><?php esc_html_e( 'فایل CSV باید با همان ساختار قبلی Trust برای اعتبارسنجی باشد تا داده‌های موجود قابل درون‌ریزی بمانند:', 'trf-cargo-tracking' ); ?></p>
	<ol>
		<li><?php esc_html_e( 'ستون ۱: کد رهگیری (validation)', 'trf-cargo-tracking' ); ?></li>
		<li><?php esc_html_e( 'ستون ۲: توضیحات', 'trf-cargo-tracking' ); ?></li>
		<li><?php esc_html_e( 'ستون ۳ (اختیاری): آدرس تصاویر یا PDF، جدا شده با |', 'trf-cargo-tracking' ); ?></li>
	</ol>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
		<?php wp_nonce_field( 'trf_track_import' ); ?>
		<input type="hidden" name="action" value="trf_track_import">
		<p>
			<input type="file" name="csv_import" accept=".csv,text/csv" required>
		</p>
		<?php submit_button( __( 'درون‌ریزی', 'trf-cargo-tracking' ) ); ?>
	</form>
</div>
