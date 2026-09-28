<?php
/**
 * Quote request form (footer and contact page).
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type string $suffix Optional id suffix when two forms are on the page.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_quote_status = trf_quote_status();
$trf_quote_suffix = ( isset( $args['suffix'] ) && is_string( $args['suffix'] ) ) ? $args['suffix'] : '';
$trf_id           = $trf_quote_suffix ? '-' . $trf_quote_suffix : '';
?>
<?php if ( 'sent' === $trf_quote_status ) : ?>
	<p class="trf-quote-notice trf-quote-notice--success" role="status"><?php esc_html_e( 'درخواست شما ارسال شد. به‌زودی با شما تماس می‌گیریم.', 'trf-theme' ); ?></p>
<?php elseif ( 'error' === $trf_quote_status ) : ?>
	<p class="trf-quote-notice trf-quote-notice--error" role="alert"><?php esc_html_e( 'ارسال ناموفق بود. لطفاً دوباره تلاش کنید.', 'trf-theme' ); ?></p>
<?php endif; ?>

<form class="trf-quote-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="trf_quote_submit">
	<?php wp_nonce_field( 'trf_quote_submit', 'trf_quote_nonce' ); ?>

	<p class="trf-honeypot" aria-hidden="true">
		<label for="<?php echo esc_attr( 'trf-website' . $trf_id ); ?>"><?php esc_html_e( 'Website', 'trf-theme' ); ?></label>
		<input id="<?php echo esc_attr( 'trf-website' . $trf_id ); ?>" type="text" name="trf_website" value="" tabindex="-1" autocomplete="off">
	</p>

	<label class="screen-reader-text" for="<?php echo esc_attr( 'trf-quote-name' . $trf_id ); ?>"><?php esc_html_e( 'نام', 'trf-theme' ); ?></label>
	<input id="<?php echo esc_attr( 'trf-quote-name' . $trf_id ); ?>" type="text" name="trf_name" placeholder="<?php esc_attr_e( 'نام و نام خانوادگی', 'trf-theme' ); ?>" required maxlength="120" autocomplete="name">

	<label class="screen-reader-text" for="<?php echo esc_attr( 'trf-quote-phone' . $trf_id ); ?>"><?php esc_html_e( 'تلفن', 'trf-theme' ); ?></label>
	<input id="<?php echo esc_attr( 'trf-quote-phone' . $trf_id ); ?>" type="tel" name="trf_phone" placeholder="<?php esc_attr_e( 'شماره تماس', 'trf-theme' ); ?>" required maxlength="40" autocomplete="tel">

	<label class="screen-reader-text" for="<?php echo esc_attr( 'trf-quote-msg' . $trf_id ); ?>"><?php esc_html_e( 'پیام', 'trf-theme' ); ?></label>
	<textarea id="<?php echo esc_attr( 'trf-quote-msg' . $trf_id ); ?>" name="trf_message" rows="3" maxlength="2000" placeholder="<?php esc_attr_e( 'توضیحات محموله / مبدا و مقصد', 'trf-theme' ); ?>"></textarea>

	<button class="trf-btn trf-btn--primary" type="submit"><?php esc_html_e( 'ارسال', 'trf-theme' ); ?></button>
</form>
