<?php
/**
 * Theme footer (homepage always; inner pages if Elementor Theme Builder has no footer).
 *
 * @package trf-theme
 */

$trf_use_theme_footer = is_front_page()
	|| ! function_exists( 'elementor_theme_do_location' )
	|| ! elementor_theme_do_location( 'footer' );

if ( $trf_use_theme_footer ) :
	$trf_phone       = trf_mod( 'trf_phone' );
	$trf_phone_tel   = trf_mod( 'trf_phone_tel' );
	$trf_socials     = array(
		array( 'label' => 'Telegram', 'url' => trf_mod( 'trf_social_telegram' ) ),
		array( 'label' => 'Twitter', 'url' => trf_mod( 'trf_social_twitter' ) ),
		array( 'label' => 'Youtube', 'url' => trf_mod( 'trf_social_youtube' ) ),
	);
	$trf_quote_status = trf_quote_status();
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

		<div class="trf-footer__col trf-footer__quote" id="quote">
			<h2>استعلام قیمت حمل</h2>
			<p>برای دریافت بهترین قیمت حمل فرم زیر را تکمیل کنید تا کارشناسان ما در اسرع وقت با شما تماس بگیرند</p>

			<?php if ( 'sent' === $trf_quote_status ) : ?>
				<p class="trf-quote-notice trf-quote-notice--success" role="status"><?php esc_html_e( 'درخواست شما ارسال شد. به‌زودی با شما تماس می‌گیریم.', 'trf-theme' ); ?></p>
			<?php elseif ( 'error' === $trf_quote_status ) : ?>
				<p class="trf-quote-notice trf-quote-notice--error" role="alert"><?php esc_html_e( 'ارسال ناموفق بود. لطفاً دوباره تلاش کنید.', 'trf-theme' ); ?></p>
			<?php endif; ?>

			<form class="trf-quote-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="trf_quote_submit">
				<?php wp_nonce_field( 'trf_quote_submit', 'trf_quote_nonce' ); ?>

				<p class="trf-honeypot" aria-hidden="true">
					<label for="trf-website"><?php esc_html_e( 'Website', 'trf-theme' ); ?></label>
					<input id="trf-website" type="text" name="trf_website" value="" tabindex="-1" autocomplete="off">
				</p>

				<label class="screen-reader-text" for="trf-quote-name"><?php esc_html_e( 'نام', 'trf-theme' ); ?></label>
				<input id="trf-quote-name" type="text" name="trf_name" placeholder="<?php esc_attr_e( 'نام و نام خانوادگی', 'trf-theme' ); ?>" required maxlength="120" autocomplete="name">

				<label class="screen-reader-text" for="trf-quote-phone"><?php esc_html_e( 'تلفن', 'trf-theme' ); ?></label>
				<input id="trf-quote-phone" type="tel" name="trf_phone" placeholder="<?php esc_attr_e( 'شماره تماس', 'trf-theme' ); ?>" required maxlength="40" autocomplete="tel">

				<label class="screen-reader-text" for="trf-quote-msg"><?php esc_html_e( 'پیام', 'trf-theme' ); ?></label>
				<textarea id="trf-quote-msg" name="trf_message" rows="3" maxlength="2000" placeholder="<?php esc_attr_e( 'توضیحات محموله / مبدا و مقصد', 'trf-theme' ); ?>"></textarea>

				<button class="trf-btn trf-btn--primary" type="submit"><?php esc_html_e( 'ارسال', 'trf-theme' ); ?></button>
			</form>
		</div>
	</div>

	<div class="trf-footer__bottom">
		<div class="trf-container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		</div>
	</div>
</footer>
	<?php
endif;
?>

<?php wp_footer(); ?>
</body>
</html>
