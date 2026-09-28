<?php
/**
 * Theme footer.
 *
 * @package trf-theme
 */

$trf_phone     = trf_mod( 'trf_phone' );
$trf_phone_tel = trf_mod( 'trf_phone_tel' );
$trf_socials   = array(
	array( 'label' => 'Telegram', 'url' => trf_mod( 'trf_social_telegram' ) ),
	array( 'label' => 'Twitter', 'url' => trf_mod( 'trf_social_twitter' ) ),
	array( 'label' => 'Youtube', 'url' => trf_mod( 'trf_social_youtube' ) ),
);
?>
<footer class="trf-footer">
	<div class="trf-container trf-footer__top">
		<a class="trf-footer__phone" href="<?php echo esc_url( 'tel:' . $trf_phone_tel ); ?>"><?php echo esc_html( $trf_phone ); ?></a>
		<div class="trf-footer__social" aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی', 'trf-theme' ); ?>">
			<?php foreach ( $trf_socials as $trf_social ) : ?>
				<?php if ( $trf_social['url'] ) : ?>
					<a href="<?php echo esc_url( $trf_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $trf_social['label'] ); ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="trf-container trf-footer__grid">
		<div class="trf-footer__brand">
			<?php trf_the_logo( 'trf-logo--footer' ); ?>
			<p><?php echo esc_html( trf_mod( 'trf_footer_blurb' ) ); ?></p>
		</div>

		<div class="trf-footer__col">
			<h2>خدمات ما</h2>
			<?php trf_nav_or_fallback( 'footer', trf_footer_services_fallback() ); ?>
		</div>

		<div class="trf-footer__col">
			<h2>دسترسی سریع</h2>
			<?php trf_nav_or_fallback( 'footer-extra', trf_footer_extra_fallback() ); ?>
		</div>
	</div>

	<div class="trf-footer__bottom">
		<div class="trf-container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/quote-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
