<?php
/**
 * Tracking page help aside.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone     = trf_mod( 'trf_phone' );
$phone_tel = trf_mod( 'trf_phone_tel' );
if ( '' === $phone_tel && $phone ) {
	$phone_tel = trf_tel_href( $phone );
}
?>
<aside class="trf-tracking__aside" aria-labelledby="trf-tracking-aside-title">
	<header class="trf-tracking__aside-head">
		<p class="trf-eyebrow"><?php esc_html_e( 'راهنما', 'trf-theme' ); ?></p>
		<h2 id="trf-tracking-aside-title"><?php esc_html_e( 'کد رهگیری را کجا پیدا کنم؟', 'trf-theme' ); ?></h2>
	</header>

	<ul class="trf-tracking__tips">
		<li>
			<span class="trf-tracking__tip-icon" aria-hidden="true"><?php echo trf_icon( 'file' ); ?></span>
			<span><?php esc_html_e( 'کد روی بارنامه یا رسید تحویل چاپ شده است.', 'trf-theme' ); ?></span>
		</li>
		<li>
			<span class="trf-tracking__tip-icon" aria-hidden="true"><?php echo trf_icon( 'mail' ); ?></span>
			<span><?php esc_html_e( 'در پیامک یا ایمیل تأیید ارسال، همان کد را جستجو کنید.', 'trf-theme' ); ?></span>
		</li>
		<li>
			<span class="trf-tracking__tip-icon" aria-hidden="true"><?php echo trf_icon( 'package' ); ?></span>
			<span><?php esc_html_e( 'کد را بدون فاصله و دقیقاً مطابق مدرک وارد کنید.', 'trf-theme' ); ?></span>
		</li>
	</ul>

	<div class="trf-tracking__aside-actions">
		<?php if ( $phone && $phone_tel ) : ?>
			<a class="trf-btn trf-btn--outline" href="<?php echo esc_url( 'tel:' . $phone_tel ); ?>">
				<?php echo trf_icon( 'phone' ); ?>
				<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
			</a>
		<?php endif; ?>
		<?php if ( trf_contact_url() ) : ?>
			<a class="trf-tracking__support-link" href="<?php echo esc_url( trf_contact_url() ); ?>">
				<?php esc_html_e( 'تماس با پشتیبانی', 'trf-theme' ); ?>
				<?php echo trf_icon( 'arrow' ); ?>
			</a>
		<?php endif; ?>
	</div>
</aside>
