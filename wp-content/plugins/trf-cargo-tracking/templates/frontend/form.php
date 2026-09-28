<?php
/**
 * Public tracking form.
 *
 * @package TRF_Cargo_Tracking
 *
 * @var string     $label
 * @var string     $button
 * @var string     $code
 * @var array|null $result
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ok     = is_array( $result ) && isset( $result['status'] ) && 'ok' === $result['status'];
$fail   = is_array( $result ) && isset( $result['status'] ) && 'fail' === $result['status'];
$images = ( $ok && ! empty( $result['gallery'] ) && is_array( $result['gallery'] ) ) ? $result['gallery'] : array();
if ( '' === $label ) {
	$label = 'کد رهگیری';
}
if ( '' === $button ) {
	$button = 'بررسی وضعیت بار';
}
?>
<div class="trf-track" data-trf-track>
	<form class="trf-track__form" method="get" data-trf-track-form>
		<label class="trf-track__label" for="trf-track-code"><?php echo esc_html( $label ); ?></label>
		<div class="trf-track__row">
			<input
				class="trf-track__input"
				id="trf-track-code"
				name="trf_track"
				type="text"
				dir="ltr"
				autocomplete="off"
				required
				value="<?php echo esc_attr( $code ); ?>"
				placeholder="<?php echo esc_attr( $label ); ?>"
			>
			<button class="trf-btn trf-btn--primary trf-track__submit" type="submit"><?php echo esc_html( $button ); ?></button>
		</div>
	</form>

	<div class="trf-track__result" data-trf-track-result aria-live="polite">
		<?php if ( $fail ) : ?>
			<p class="trf-track__notice trf-track__notice--error"><?php echo esc_html( $result['description'] ); ?></p>
		<?php elseif ( $ok ) : ?>
			<div class="trf-track__card">
				<p class="trf-track__code"><span><?php echo esc_html( $label ); ?>:</span> <?php echo esc_html( $result['validation'] ); ?></p>
				<div class="trf-track__description"><?php echo nl2br( esc_html( $result['description'] ) ); ?></div>
				<?php if ( $images ) : ?>
					<div class="trf-track__gallery">
						<?php foreach ( $images as $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
								<img src="<?php echo esc_url( $url ); ?>" alt="">
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
