<?php
/**
 * Quote request modal.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dialog class="trf-modal" id="quote" data-trf-quote-modal aria-labelledby="trf-quote-title" aria-describedby="trf-quote-desc">
	<div class="trf-modal__panel">
		<button type="button" class="trf-modal__close" data-trf-quote-close>
			<span class="screen-reader-text"><?php esc_html_e( 'بستن', 'trf-theme' ); ?></span>
			<span aria-hidden="true">&times;</span>
		</button>
		<header class="trf-modal__head">
			<h2 id="trf-quote-title"><?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?></h2>
			<p id="trf-quote-desc"><?php esc_html_e( 'برای دریافت بهترین قیمت حمل فرم زیر را تکمیل کنید تا کارشناسان ما در اسرع وقت با شما تماس بگیرند.', 'trf-theme' ); ?></p>
		</header>
		<?php get_template_part( 'template-parts/quote-form' ); ?>
	</div>
</dialog>
