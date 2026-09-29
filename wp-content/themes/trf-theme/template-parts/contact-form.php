<?php
/**
 * Contact page message form.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_contact_status = trf_contact_status();
?>
<?php if ( 'sent' === $trf_contact_status ) : ?>
	<p class="trf-quote-notice trf-quote-notice--success" role="status"><?php esc_html_e( 'پیام شما ارسال شد. به‌زودی با شما تماس می‌گیریم.', 'trf-theme' ); ?></p>
<?php else : ?>
	<?php if ( 'error' === $trf_contact_status ) : ?>
		<p class="trf-quote-notice trf-quote-notice--error" role="alert"><?php esc_html_e( 'ارسال ناموفق بود. لطفاً فیلدها را بررسی کنید و دوباره تلاش کنید.', 'trf-theme' ); ?></p>
	<?php endif; ?>

	<form class="trf-contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="trf_contact_submit">
		<?php wp_nonce_field( 'trf_contact_submit', 'trf_contact_nonce' ); ?>

		<p class="trf-honeypot" aria-hidden="true">
			<label for="trf-contact-website"><?php esc_html_e( 'Website', 'trf-theme' ); ?></label>
			<input id="trf-contact-website" type="text" name="trf_contact_website" value="" tabindex="-1" autocomplete="off">
		</p>

		<div class="trf-contact-fields">
			<div class="trf-contact-field">
				<label for="trf-contact-first"><?php esc_html_e( 'نام', 'trf-theme' ); ?></label>
				<input id="trf-contact-first" type="text" name="trf_first_name" required maxlength="80" autocomplete="given-name">
			</div>

			<div class="trf-contact-field">
				<label for="trf-contact-last"><?php esc_html_e( 'نام خانوادگی', 'trf-theme' ); ?></label>
				<input id="trf-contact-last" type="text" name="trf_last_name" required maxlength="80" autocomplete="family-name">
			</div>

			<div class="trf-contact-field">
				<label for="trf-contact-email"><?php esc_html_e( 'ایمیل', 'trf-theme' ); ?></label>
				<input id="trf-contact-email" type="email" name="trf_email" required maxlength="120" autocomplete="email" inputmode="email">
			</div>

			<div class="trf-contact-field">
				<label for="trf-contact-phone"><?php esc_html_e( 'شماره تماس', 'trf-theme' ); ?></label>
				<input id="trf-contact-phone" type="tel" name="trf_phone" required maxlength="40" autocomplete="tel" inputmode="tel">
			</div>

			<div class="trf-contact-field trf-contact-field--full">
				<label for="trf-contact-message"><?php esc_html_e( 'پیام', 'trf-theme' ); ?></label>
				<textarea id="trf-contact-message" name="trf_message" rows="5" required maxlength="3000"></textarea>
			</div>
		</div>

		<p class="trf-contact-form__hint"><?php esc_html_e( 'همه فیلدها الزامی است.', 'trf-theme' ); ?></p>
		<button class="trf-btn trf-btn--primary" type="submit"><?php esc_html_e( 'ارسال پیام', 'trf-theme' ); ?></button>
	</form>
<?php endif; ?>
