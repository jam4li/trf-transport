<?php
/**
 * Homepage about section.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $benefits Benefit cards.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$benefits = isset( $args['benefits'] ) ? $args['benefits'] : array();
?>
<section class="trf-section trf-about" id="about">
	<div class="trf-container trf-about__grid">
		<div class="trf-about__media">
			<img src="<?php echo esc_url( trf_asset( 'img/about.webp' ) ); ?>" width="640" height="427" alt="درباره شرکت حمل و نقل بین المللی تراف" loading="lazy" decoding="async">
			<p class="trf-about__badge">۲۰+ سال تجربه عملیاتی</p>
		</div>
		<div class="trf-about__copy">
			<p class="trf-eyebrow">چرا تراف</p>
			<h2>درباره شرکت حمل و نقل بین‌المللی تراف</h2>
			<p>تراف با بیش از ۲۰ سال سابقه در لجستیک بین‌المللی، حمل هوایی، زمینی، ریلی و دریایی را برای شرکت‌ها، بازرگانان و ارسال‌های شخصی ساده می‌کند — از خاورمیانه و آسیای میانه تا خاور دور و اروپا.</p>
			<p>خدمات ما شامل ترخیص کالا، صادرات و واردات و حمل کانتینری است. با مجوزهای قانونی لازم، سلامت محموله تا لحظه تحویل تضمین می‌شود.</p>
			<?php if ( $benefits ) : ?>
				<ul class="trf-about__benefits">
					<?php foreach ( $benefits as $benefit ) : ?>
						<li>
							<span class="trf-about__benefit-icon"><?php echo trf_icon( $benefit['icon'] ); ?></span>
							<div>
								<h3><?php echo esc_html( $benefit['title'] ); ?></h3>
								<p><?php echo esc_html( $benefit['text'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="trf-about__actions">
				<?php if ( trf_contact_url() ) : ?>
					<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( trf_contact_url() ); ?>">اطلاعات تماس</a>
				<?php endif; ?>
				<a class="trf-btn trf-btn--outline" href="#quote" data-trf-quote-open>استعلام قیمت</a>
			</div>
		</div>
	</div>
</section>
