<?php
/**
 * Theme footer.
 *
 * @package trf-theme
 */

$trf_phone     = trf_mod( 'trf_phone' );
$trf_phone_tel = trf_mod( 'trf_phone_tel' );
?>
<footer class="trf-footer">
	<div class="trf-container trf-footer__grid">
		<div class="trf-footer__brand">
			<?php trf_the_logo( 'trf-logo--footer' ); ?>
			<p><?php echo esc_html( trf_mod( 'trf_footer_blurb' ) ); ?></p>
		</div>

		<nav class="trf-footer__col" aria-labelledby="trf-footer-services">
			<h2 id="trf-footer-services"><?php esc_html_e( 'خدمات ما', 'trf-theme' ); ?></h2>
			<?php trf_nav_or_fallback( 'footer', trf_footer_services_fallback() ); ?>
		</nav>

		<nav class="trf-footer__col" aria-labelledby="trf-footer-links">
			<h2 id="trf-footer-links"><?php esc_html_e( 'دسترسی سریع', 'trf-theme' ); ?></h2>
			<?php trf_nav_or_fallback( 'footer-extra', trf_footer_extra_fallback() ); ?>
		</nav>

		<div class="trf-footer__col trf-footer__contact">
			<h2 id="trf-footer-contact"><?php esc_html_e( 'ارتباط با ما', 'trf-theme' ); ?></h2>
			<?php if ( $trf_phone_tel ) : ?>
				<a class="trf-footer__phone" href="<?php echo esc_url( 'tel:' . $trf_phone_tel ); ?>">
					<span class="trf-footer__phone-label"><?php esc_html_e( 'تماس مستقیم', 'trf-theme' ); ?></span>
					<span class="trf-footer__phone-num"><?php echo trf_icon( 'phone' ); ?><?php echo esc_html( $trf_phone ); ?></span>
				</a>
			<?php endif; ?>
			<ul class="trf-footer__list">
				<li><a href="<?php echo esc_url( trf_contact_url() ); ?>"><?php esc_html_e( 'صفحه تماس', 'trf-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( trf_tracking_url() ); ?>"><?php esc_html_e( 'استعلام وضعیت بار', 'trf-theme' ); ?></a></li>
			</ul>
			<a class="trf-btn trf-btn--primary trf-footer__cta" href="<?php echo esc_url( trf_quote_cta_url() ); ?>" data-trf-quote-open>
				<?php echo trf_icon( 'file' ); ?>
				<?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?>
			</a>
		</div>
	</div>

	<div class="trf-footer__bottom">
		<div class="trf-container trf-footer__bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			<p><?php esc_html_e( 'کلیه حقوق محفوظ است', 'trf-theme' ); ?></p>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/quote-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
