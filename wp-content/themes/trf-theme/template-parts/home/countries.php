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
				$lead  = ! empty( $country['lead'] ) ? $country['lead'] : wp_trim_words( $country['text'], 22, '…' );
				$url   = ! empty( $country['url'] ) ? $country['url'] : '';
				$tag   = $url ? 'a' : 'div';
				$href  = $url ? ' href="' . esc_url( $url ) . '"' : '';
				$title = isset( $country['title'] ) ? $country['title'] : '';
				?>
				<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static a|div. ?> class="trf-destination-card"<?php echo $href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
					<span class="trf-destination-card__flag">
						<img src="<?php echo esc_url( $country['image'] ); ?>" alt="<?php echo esc_attr( $title ); ?>" width="96" height="64" loading="lazy" decoding="async">
					</span>
					<h3><?php echo esc_html( $title ); ?></h3>
					<p><?php echo esc_html( $lead ); ?></p>
					<?php if ( $url ) : ?>
						<span class="trf-destination-card__more">جزئیات مسیر <?php echo trf_icon( 'arrow' ); ?></span>
					<?php endif; ?>
				</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static a|div. ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
