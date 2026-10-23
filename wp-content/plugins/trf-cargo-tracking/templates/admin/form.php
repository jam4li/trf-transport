<?php
/**
 * Add / edit tracking record.
 *
 * @package TRF_Cargo_Tracking
 *
 * @var string      $code
 * @var object[]    $entries
 * @var bool        $is_edit
 * @var bool        $notfound
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $entries ) ) {
	$entries = array( null );
}
?>
<div class="wrap">
	<h1><?php echo $is_edit ? esc_html__( 'ویرایش محموله', 'trf-cargo-tracking' ) : esc_html__( 'افزودن محموله', 'trf-cargo-tracking' ); ?></h1>
	<?php TRF_Cargo_Tracking_Admin::notices(); ?>

	<?php if ( ! empty( $notfound ) ) : ?>
		<div class="notice notice-error"><p><?php esc_html_e( 'این محموله یافت نشد.', 'trf-cargo-tracking' ); ?></p></div>
	<?php else : ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="trf-track-admin-form">
			<?php wp_nonce_field( 'trf_track_save' ); ?>
			<input type="hidden" name="action" value="trf_track_save">
			<?php if ( $is_edit ) : ?>
				<input type="hidden" name="is_edit" value="1">
			<?php endif; ?>

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
			</table>

			<h2><?php esc_html_e( 'ردیف‌های وضعیت', 'trf-cargo-tracking' ); ?></h2>
			<p class="description"><?php esc_html_e( 'هر ردیف یک توضیح، مدارک و تاریخ نمایش جداگانه دارد. تاریخ ثبت فقط در پنل مدیریت دیده می‌شود و هنگام ذخیره پر می‌شود.', 'trf-cargo-tracking' ); ?></p>

			<div class="trf-track-entries" data-trf-entries>
				<?php foreach ( $entries as $index => $entry ) : ?>
					<?php TRF_Cargo_Tracking_Admin::render_entry_row( (int) $index, $entry ); ?>
				<?php endforeach; ?>
			</div>

			<p>
				<button type="button" class="button" data-trf-entry-add><?php esc_html_e( 'افزودن ردیف', 'trf-cargo-tracking' ); ?></button>
			</p>

			<template id="trf-track-entry-template">
				<?php TRF_Cargo_Tracking_Admin::render_entry_row( '__INDEX__', null, true ); ?>
			</template>

			<?php submit_button( $is_edit ? __( 'به‌روزرسانی', 'trf-cargo-tracking' ) : __( 'افزودن', 'trf-cargo-tracking' ) ); ?>
		</form>
	<?php endif; ?>
</div>
