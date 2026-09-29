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
				<?php
				$url   = ! empty( $service['url'] ) ? $service['url'] : '';
				$tag   = $url ? 'a' : 'div';
				$href  = $url ? ' href="' . esc_url( $url ) . '"' : '';
				$title = isset( $service['title'] ) ? $service['title'] : '';
				?>
				<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static a|div. ?> class="trf-service-card"<?php echo $href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
					<img
						class="trf-service-card__bg"
						src="<?php echo esc_url( $service['image'] ); ?>"
						alt="<?php echo esc_attr( $title ); ?>"
						width="640"
						height="400"
						loading="lazy"
						decoding="async"
					>
					<span class="trf-service-card__body">
						<?php if ( ! empty( $service['icon'] ) ) : ?>
							<span class="trf-service-card__icon"><?php echo trf_icon( $service['icon'] ); ?></span>
						<?php endif; ?>
						<h3><?php echo esc_html( $title ); ?></h3>
						<p><?php echo esc_html( $service['text'] ); ?></p>
						<?php if ( $url ) : ?>
							<span class="trf-service-card__more">جزئیات خدمات <?php echo trf_icon( 'arrow' ); ?></span>
						<?php endif; ?>
					</span>
				</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static a|div. ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
