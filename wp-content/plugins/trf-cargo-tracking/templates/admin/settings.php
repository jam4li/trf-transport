<?php
/**
 * Settings screen.
 *
 * @package TRF_Cargo_Tracking
 *
 * @var array $options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'تنظیمات ردیابی بار', 'trf-cargo-tracking' ); ?></h1>
	<?php TRF_Cargo_Tracking_Admin::notices(); ?>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'trf_track_settings' ); ?>
		<input type="hidden" name="action" value="trf_track_settings">

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="code_label"><?php esc_html_e( 'برچسب فیلد کد', 'trf-cargo-tracking' ); ?></label></th>
				<td><input name="code_label" id="code_label" type="text" class="regular-text" value="<?php echo esc_attr( $options['code_label'] ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="button_label"><?php esc_html_e( 'متن دکمه بررسی', 'trf-cargo-tracking' ); ?></label></th>
				<td><input name="button_label" id="button_label" type="text" class="regular-text" value="<?php echo esc_attr( $options['button_label'] ); ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="not_found_message"><?php esc_html_e( 'پیام کد نامعتبر', 'trf-cargo-tracking' ); ?></label></th>
				<td><textarea name="not_found_message" id="not_found_message" class="large-text" rows="4"><?php echo esc_textarea( $options['not_found_message'] ); ?></textarea></td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>

	<p class="description">
		<?php esc_html_e( 'شورت‌کد فرم استعلام: [trf_cargo_tracking] — شورت‌کد قبلی Trust یعنی [validation_check_lookup] همچنان کار می‌کند.', 'trf-cargo-tracking' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'رکوردها در همان جدول wpwv_validations ذخیره می‌شوند. جدول گارانتی wpwv_serials دست نخورده می‌ماند.', 'trf-cargo-tracking' ); ?>
	</p>
</div>
