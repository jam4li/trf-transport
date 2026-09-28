<?php
/**
 * Homepage services grid.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $services Service cards.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = isset( $args['services'] ) ? $args['services'] : array();
?>
<section class="trf-section trf-services" id="services">
	<div class="trf-container">
		<header class="trf-section__head">
			<p class="trf-eyebrow">خدمات ما</p>
			<h2>چهار شیوه حمل، یک مسیر مطمئن</h2>
			<p>بسته به زمان، هزینه و نوع محموله، مناسب‌ترین روش ارسال را انتخاب کنید.</p>
		</header>
		<div class="trf-services__grid">
			<?php foreach ( $services as $service ) : ?>
				<a class="trf-service-card" href="<?php echo esc_url( $service['url'] ); ?>">
					<span class="trf-service-card__bg" style="background-image:url('<?php echo esc_url( $service['image'] ); ?>')"></span>
					<span class="trf-service-card__body">
						<?php if ( ! empty( $service['icon'] ) ) : ?>
							<span class="trf-service-card__icon"><?php echo trf_icon( $service['icon'] ); ?></span>
						<?php endif; ?>
						<h3><?php echo esc_html( $service['title'] ); ?></h3>
						<p><?php echo esc_html( $service['text'] ); ?></p>
						<span class="trf-service-card__more">جزئیات خدمات <?php echo trf_icon( 'arrow' ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
