<?php
/**
 * Homepage closing CTA.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_phone     = trf_mod( 'trf_phone' );
$trf_phone_tel = trf_mod( 'trf_phone_tel' );
?>
<section class="trf-cta-band" aria-labelledby="trf-cta-title">
	<div class="trf-container trf-cta-band__inner">
		<div>
			<p class="trf-eyebrow trf-eyebrow--light">آماده ارسال هستید؟</p>
			<h2 id="trf-cta-title">قیمت مسیر را استعلام بگیرید یا مستقیم تماس بگیرید</h2>
			<p>کارشناسان تراف مسیر، زمان و هزینه را متناسب با محموله شما مشخص می‌کنند.</p>
		</div>
		<div class="trf-cta-band__actions">
			<a class="trf-btn trf-btn--primary" href="#quote" data-trf-quote-open><?php echo trf_icon( 'file' ); ?>استعلام قیمت حمل</a>
			<?php if ( $trf_phone_tel ) : ?>
				<a class="trf-btn trf-btn--ghost" href="<?php echo esc_url( 'tel:' . $trf_phone_tel ); ?>"><?php echo trf_icon( 'phone' ); ?><?php echo esc_html( $trf_phone ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
