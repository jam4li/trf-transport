<?php
/**
 * Homepage destination grid.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $countries Country cards.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$countries = isset( $args['countries'] ) ? $args['countries'] : array();
?>
<section class="trf-section trf-countries" id="countries">
	<div class="trf-container">
		<header class="trf-section__head">
			<p class="trf-eyebrow">مقاصد پرتردد</p>
			<h2>حمل به کشورهای مختلف</h2>
			<p>مسیرهای فعال به همسایگان، آسیای میانه، خلیج فارس و اروپا.</p>
		</header>

		<div class="trf-countries__grid">
			<?php foreach ( $countries as $country ) : ?>
				<?php
				$lead = ! empty( $country['lead'] ) ? $country['lead'] : wp_trim_words( $country['text'], 22, '…' );
				?>
				<a class="trf-destination-card" href="<?php echo esc_url( $country['url'] ); ?>">
					<span class="trf-destination-card__flag">
						<img src="<?php echo esc_url( $country['image'] ); ?>" alt="" width="96" height="64" loading="lazy" decoding="async">
					</span>
					<h3><?php echo esc_html( $country['title'] ); ?></h3>
					<p><?php echo esc_html( $lead ); ?></p>
					<span class="trf-destination-card__more">جزئیات مسیر <?php echo trf_icon( 'arrow' ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
