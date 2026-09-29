<?php
/**
 * Sticky CTA aside for article / service pages.
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
<aside class="trf-article__aside" aria-labelledby="trf-article-aside-title">
	<header class="trf-article__aside-head">
		<p class="trf-eyebrow"><?php esc_html_e( 'اقدام سریع', 'trf-theme' ); ?></p>
		<h2 id="trf-article-aside-title"><?php esc_html_e( 'برای این مسیر آماده هستید؟', 'trf-theme' ); ?></h2>
		<p><?php esc_html_e( 'قیمت، زمان و روش حمل را متناسب با محموله شما مشخص می‌کنیم.', 'trf-theme' ); ?></p>
	</header>

	<div class="trf-article__aside-actions">
		<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( trf_quote_cta_url() ); ?>" data-trf-quote-open>
			<?php echo trf_icon( 'file' ); ?>
			<?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?>
		</a>
		<?php if ( $phone && $phone_tel ) : ?>
			<a class="trf-btn trf-btn--outline" href="<?php echo esc_url( 'tel:' . $phone_tel ); ?>">
				<?php echo trf_icon( 'phone' ); ?>
				<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
			</a>
		<?php endif; ?>
	</div>

	<ul class="trf-article__aside-links">
		<?php if ( trf_tracking_url() ) : ?>
			<li>
				<a href="<?php echo esc_url( trf_tracking_url() ); ?>">
					<span class="trf-article__aside-icon" aria-hidden="true"><?php echo trf_icon( 'package' ); ?></span>
					<span><?php esc_html_e( 'استعلام وضعیت بار', 'trf-theme' ); ?></span>
					<?php echo trf_icon( 'arrow' ); ?>
				</a>
			</li>
		<?php endif; ?>
		<?php if ( trf_contact_url() ) : ?>
			<li>
				<a href="<?php echo esc_url( trf_contact_url() ); ?>">
					<span class="trf-article__aside-icon" aria-hidden="true"><?php echo trf_icon( 'mail' ); ?></span>
					<span><?php esc_html_e( 'تماس با پشتیبانی', 'trf-theme' ); ?></span>
					<?php echo trf_icon( 'arrow' ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</aside>
