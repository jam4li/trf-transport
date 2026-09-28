<?php
/**
 * Add / edit tracking record.
 *
 * @package TRF_Cargo_Tracking
 *
 * @var object|null $row
 * @var int         $id
 * @var string[]    $gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_edit = $row instanceof stdClass;
$code    = $is_edit ? $row->validation : '';
$desc    = $is_edit ? $row->description : '';
?>
<div class="wrap">
	<h1><?php echo $is_edit ? esc_html__( 'ویرایش محموله', 'trf-cargo-tracking' ) : esc_html__( 'افزودن محموله', 'trf-cargo-tracking' ); ?></h1>
	<?php TRF_Cargo_Tracking_Admin::notices(); ?>

	<?php if ( $id && ! $is_edit ) : ?>
		<div class="notice notice-error"><p><?php esc_html_e( 'این محموله یافت نشد.', 'trf-cargo-tracking' ); ?></p></div>
	<?php else : ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="trf-track-admin-form">
			<?php wp_nonce_field( 'trf_track_save' ); ?>
			<input type="hidden" name="action" value="trf_track_save">
			<input type="hidden" name="id" value="<?php echo $is_edit ? (int) $row->id : 0; ?>">

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="validation"><?php esc_html_e( 'کد رهگیری', 'trf-cargo-tracking' ); ?></label></th>
					<td>
						<input name="validation" id="validation" type="text" class="regular-text ltr" dir="ltr" value="<?php echo esc_attr( $code ); ?>" <?php echo $is_edit ? 'readonly' : 'required'; ?>>
						<?php if ( $is_edit ) : ?>
							<p class="description"><?php esc_html_e( 'کد رهگیری پس از ثبت تغییر نمی‌کند تا لینک‌های استعلام قبلی معتبر بمانند.', 'trf-cargo-tracking' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="description"><?php esc_html_e( 'توضیحات / وضعیت بار', 'trf-cargo-tracking' ); ?></label></th>
					<td>
						<textarea name="description" id="description" class="large-text" rows="6" required><?php echo esc_textarea( $desc ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'تصاویر', 'trf-cargo-tracking' ); ?></th>
					<td>
						<?php TRF_Cargo_Tracking_Gallery::render_admin_field( $gallery ); ?>
					</td>
				</tr>
			</table>

			<?php submit_button( $is_edit ? __( 'به‌روزرسانی', 'trf-cargo-tracking' ) : __( 'افزودن', 'trf-cargo-tracking' ) ); ?>
		</form>
	<?php endif; ?>
</div>
