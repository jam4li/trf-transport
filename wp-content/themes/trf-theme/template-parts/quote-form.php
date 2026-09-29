<?php
/**
 * Quote request form (modal and contact page).
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type string $suffix Optional id suffix when two forms are on the page.
 *     @type string $anchor Hash used after submit (quote|contact-form).
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_quote_status = trf_quote_status();
$trf_quote_suffix = ( isset( $args['suffix'] ) && is_string( $args['suffix'] ) ) ? $args['suffix'] : '';
$trf_id           = $trf_quote_suffix ? '-' . $trf_quote_suffix : '';
$trf_anchor       = ( isset( $args['anchor'] ) && is_string( $args['anchor'] ) ) ? $args['anchor'] : 'quote';
if ( ! in_array( $trf_anchor, array( 'quote', 'contact-form' ), true ) ) {
	$trf_anchor = 'quote';
}

$trf_flash_at   = trf_quote_anchor_from_request();
$trf_show_flash = (bool) $trf_quote_status && ( $trf_flash_at === $trf_anchor || ( '' === $trf_flash_at && 'quote' === $trf_anchor ) );
?>
<?php if ( $trf_show_flash && 'sent' === $trf_quote_status ) : ?>
	<p class="trf-quote-notice trf-quote-notice--success" role="status"><?php esc_html_e( 'درخواست شما ارسال شد. به‌زودی با شما تماس می‌گیریم.', 'trf-theme' ); ?></p>
<?php else : ?>
	<?php if ( $trf_show_flash && 'error' === $trf_quote_status ) : ?>
		<p class="trf-quote-notice trf-quote-notice--error" role="alert"><?php esc_html_e( 'ارسال ناموفق بود. لطفاً دوباره تلاش کنید.', 'trf-theme' ); ?></p>
	<?php endif; ?>

	<form class="trf-quote-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="trf_quote_submit">
		<input type="hidden" name="trf_quote_anchor" value="<?php echo esc_attr( $trf_anchor ); ?>">
		<?php wp_nonce_field( 'trf_quote_submit', 'trf_quote_nonce' ); ?>

		<p class="trf-honeypot" aria-hidden="true">
			<label for="<?php echo esc_attr( 'trf-website' . $trf_id ); ?>"><?php esc_html_e( 'Website', 'trf-theme' ); ?></label>
			<input id="<?php echo esc_attr( 'trf-website' . $trf_id ); ?>" type="text" name="trf_website" value="" tabindex="-1" autocomplete="off">
		</p>

		<div class="trf-quote-fields">
			<div class="trf-quote-field">
				<label for="<?php echo esc_attr( 'trf-quote-name' . $trf_id ); ?>"><?php esc_html_e( 'نام و نام خانوادگی', 'trf-theme' ); ?></label>
				<input id="<?php echo esc_attr( 'trf-quote-name' . $trf_id ); ?>" type="text" name="trf_name" required maxlength="120" autocomplete="name">
			</div>

			<div class="trf-quote-field">
				<label for="<?php echo esc_attr( 'trf-quote-phone' . $trf_id ); ?>"><?php esc_html_e( 'شماره تماس', 'trf-theme' ); ?></label>
				<input id="<?php echo esc_attr( 'trf-quote-phone' . $trf_id ); ?>" type="tel" name="trf_phone" required maxlength="40" autocomplete="tel" inputmode="tel">
			</div>

			<div class="trf-quote-field trf-quote-field--full">
				<label for="<?php echo esc_attr( 'trf-quote-msg' . $trf_id ); ?>"><?php esc_html_e( 'توضیحات محموله / مبدا و مقصد', 'trf-theme' ); ?></label>
				<textarea id="<?php echo esc_attr( 'trf-quote-msg' . $trf_id ); ?>" name="trf_message" rows="4" maxlength="2000"></textarea>
			</div>
		</div>

		<button class="trf-btn trf-btn--primary" type="submit"><?php esc_html_e( 'ارسال درخواست', 'trf-theme' ); ?></button>
	</form>
<?php endif; ?>
