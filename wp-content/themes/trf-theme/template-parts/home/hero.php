<?php
/**
 * Homepage hero.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="trf-hero">
	<div class="trf-hero__media" style="background-image:url('<?php echo esc_url( trf_asset( 'img/about.webp' ) ); ?>')"></div>
	<div class="trf-container trf-hero__content">
		<h1>شرکت حمل و نقل بین المللی تراف</h1>
		<p class="trf-hero__lead">خدماتی که ما ارائه می‌دهیم</p>
		<div class="trf-hero__actions">
			<a class="trf-btn trf-btn--primary" href="#quote" data-trf-quote-open><?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?></a>
			<a class="trf-btn trf-btn--ghost" href="<?php echo esc_url( trf_tracking_url() ); ?>"><?php esc_html_e( 'استعلام وضعیت بار', 'trf-theme' ); ?></a>
		</div>
	</div>
</section>
