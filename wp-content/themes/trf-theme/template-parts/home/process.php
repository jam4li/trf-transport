<?php
/**
 * Homepage process steps.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $process Steps.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$process = isset( $args['process'] ) ? $args['process'] : array();
if ( ! $process ) {
	return;
}
?>
<section class="trf-section trf-process" id="process" aria-labelledby="trf-process-title">
	<div class="trf-container">
		<header class="trf-section__head">
			<p class="trf-eyebrow">مسیر همکاری</p>
			<h2 id="trf-process-title">از استعلام تا تحویل بار</h2>
			<p>فرآیند شفاف، بدون پیچیدگی اضافی — مناسب بازرگانان و ارسال‌های شخصی.</p>
		</header>
		<ol class="trf-process__list">
			<?php foreach ( $process as $index => $step ) : ?>
				<li class="trf-process__item">
					<span class="trf-process__index"><?php echo esc_html( $index + 1 ); ?></span>
					<span class="trf-process__icon"><?php echo trf_icon( $step['icon'] ); ?></span>
					<h3><?php echo esc_html( $step['title'] ); ?></h3>
					<p><?php echo esc_html( $step['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
