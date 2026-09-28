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
			<h2>شرکت حمل و نقل بین المللی تراف</h2>
			<p>خدماتی که ما ارائه می‌دهیم</p>
		</header>
		<div class="trf-services__grid">
			<?php foreach ( $services as $service ) : ?>
				<a class="trf-service-card" href="<?php echo esc_url( $service['url'] ); ?>">
					<span class="trf-service-card__bg" style="background-image:url('<?php echo esc_url( $service['image'] ); ?>')"></span>
					<span class="trf-service-card__body">
						<h3><?php echo esc_html( $service['title'] ); ?></h3>
						<p><?php echo esc_html( $service['text'] ); ?></p>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
