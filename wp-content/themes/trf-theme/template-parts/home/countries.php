<?php
/**
 * Homepage country tabs.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $countries Country panels.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$countries = isset( $args['countries'] ) ? $args['countries'] : array();
?>
<section class="trf-section trf-countries" id="countries" data-trf-tabs>
	<div class="trf-container">
		<header class="trf-section__head">
			<h2>حمل به کشورهای مختلف</h2>
			<p>ما بیشتر به چه کشورهایی حمل می‌کنیم؟</p>
		</header>

		<div class="trf-tabs" role="tablist" aria-label="<?php esc_attr_e( 'کشورها', 'trf-theme' ); ?>">
			<?php foreach ( $countries as $index => $country ) : ?>
				<?php
				$tab_id   = 'trf-tab-' . $country['slug'];
				$panel_id = 'trf-panel-' . $country['slug'];
				?>
				<button
					type="button"
					id="<?php echo esc_attr( $tab_id ); ?>"
					class="trf-tabs__btn<?php echo 0 === $index ? ' is-active' : ''; ?>"
					role="tab"
					aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
					aria-controls="<?php echo esc_attr( $panel_id ); ?>"
					tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
					data-trf-tab="<?php echo esc_attr( $country['slug'] ); ?>"
				><?php echo esc_html( $country['title'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<div class="trf-countries__panels">
			<?php foreach ( $countries as $index => $country ) : ?>
				<?php
				$tab_id   = 'trf-tab-' . $country['slug'];
				$panel_id = 'trf-panel-' . $country['slug'];
				?>
				<article
					id="<?php echo esc_attr( $panel_id ); ?>"
					class="trf-country-panel<?php echo 0 === $index ? ' is-active' : ''; ?>"
					role="tabpanel"
					aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
					data-trf-panel="<?php echo esc_attr( $country['slug'] ); ?>"
					<?php echo 0 === $index ? '' : 'hidden'; ?>
				>
					<div class="trf-country-panel__media">
						<img src="<?php echo esc_url( $country['image'] ); ?>" alt="<?php echo esc_attr( $country['title'] ); ?>" loading="lazy">
					</div>
					<div class="trf-country-panel__body">
						<h3><?php echo esc_html( $country['title'] ); ?></h3>
						<p><?php echo esc_html( $country['text'] ); ?></p>
						<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( $country['url'] ); ?>">اطلاعات بیشتر</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
