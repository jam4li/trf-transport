<?php
/**
 * Homepage hero.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_phone     = trf_mod( 'trf_phone' );
$trf_phone_tel = trf_mod( 'trf_phone_tel' );
$trf_tracking  = trf_tracking_url();
?>
<section class="trf-hero" aria-labelledby="trf-hero-title">
	<div class="trf-hero__media">
		<img src="<?php echo esc_url( trf_asset( 'img/about.webp' ) ); ?>" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
	</div>
	<div class="trf-container trf-hero__content">
		<p class="trf-eyebrow trf-eyebrow--light"><?php echo esc_html( trf_mod( 'trf_hero_eyebrow' ) ); ?></p>
		<h1 id="trf-hero-title"><?php echo esc_html( trf_mod( 'trf_hero_title' ) ); ?></h1>
		<p class="trf-hero__lead"><?php echo esc_html( trf_mod( 'trf_hero_lead' ) ); ?></p>
		<div class="trf-hero__actions">
			<a class="trf-btn trf-btn--primary" href="#quote" data-trf-quote-open><?php echo trf_icon( 'file' ); ?><?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?></a>
			<?php if ( $trf_tracking ) : ?>
				<a class="trf-btn trf-btn--ghost" href="<?php echo esc_url( $trf_tracking ); ?>"><?php echo trf_icon( 'package' ); ?><?php esc_html_e( 'استعلام وضعیت بار', 'trf-theme' ); ?></a>
			<?php endif; ?>
		</div>
		<ul class="trf-hero__chips">
			<li><?php echo trf_icon( 'badge' ); ?>مجوزهای قانونی</li>
			<li><?php echo trf_icon( 'shield' ); ?>پوشش بیمه</li>
			<li><?php echo trf_icon( 'pin' ); ?>نمایندگان مرزی</li>
			<?php if ( $trf_phone_tel ) : ?>
				<li>
					<a href="<?php echo esc_url( 'tel:' . $trf_phone_tel ); ?>">
						<?php echo trf_icon( 'phone' ); ?>
						<?php echo esc_html( $trf_phone ); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</section>
