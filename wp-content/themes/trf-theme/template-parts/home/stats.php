<?php
/**
 * Homepage trust metrics.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $stats Metric items.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats = isset( $args['stats'] ) ? $args['stats'] : array();
if ( ! $stats ) {
	return;
}
?>
<section class="trf-stats" aria-label="<?php esc_attr_e( 'آمار شرکت', 'trf-theme' ); ?>">
	<div class="trf-container">
		<ul class="trf-stats__list">
			<?php foreach ( $stats as $stat ) : ?>
				<li class="trf-stats__item">
					<strong><?php echo esc_html( $stat['value'] ); ?></strong>
					<span><?php echo esc_html( $stat['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
