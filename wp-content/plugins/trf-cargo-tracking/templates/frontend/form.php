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
$media = ( $ok && ! empty( $result['gallery'] ) && is_array( $result['gallery'] ) ) ? $result['gallery'] : array();
if ( '' === $label ) {
	$label = __( 'کد رهگیری', 'trf-cargo-tracking' );
}
if ( '' === $button ) {
	$button = __( 'بررسی وضعیت بار', 'trf-cargo-tracking' );
}
?><div class="trf-track" data-trf-track>
	<form class="trf-track__form" method="get" data-trf-track-form>
		<div class="trf-track__row">
			<input
				class="trf-track__input"
				id="trf-track-code"
				name="trf_track"
				type="text"
				dir="ltr"
				inputmode="text"
				autocomplete="off"
				spellcheck="false"
				required
				aria-label="<?php echo esc_attr( $label ); ?>"
				value="<?php echo esc_attr( $code ); ?>"
				placeholder="TRF-12345"
			>
			<button class="trf-btn trf-btn--primary trf-track__submit" type="submit" data-trf-track-submit>
				<span data-trf-track-submit-label><?php echo esc_html( $button ); ?></span>
			</button>
		</div>
	</form>

	<div class="trf-track__result" data-trf-track-result aria-live="polite">
		<?php if ( $fail ) : ?>
			<div class="trf-track__notice trf-track__notice--error" role="alert">
				<p><?php echo esc_html( $result['description'] ); ?></p>
			</div>
		<?php elseif ( $ok ) : ?>
			<article class="trf-track__card">
				<header class="trf-track__card-head">
					<p class="trf-track__badge"><?php esc_html_e( 'یافت شد', 'trf-cargo-tracking' ); ?></p>
					<p class="trf-track__code">
						<span class="trf-track__code-label"><?php echo esc_html( $label ); ?></span>
						<code class="trf-track__code-value" dir="ltr"><?php echo esc_html( $result['validation'] ); ?></code>
					</p>
				</header>
				<div class="trf-track__description"><?php echo nl2br( esc_html( $result['description'] ) ); ?></div>
				<?php if ( $media ) : ?>
					<section class="trf-track__gallery" aria-label="<?php esc_attr_e( 'مدارک محموله', 'trf-cargo-tracking' ); ?>">
						<h3 class="trf-track__gallery-title"><?php esc_html_e( 'مدارک محموله', 'trf-cargo-tracking' ); ?></h3>
						<div class="trf-track__gallery-grid">
							<?php foreach ( $media as $index => $url ) : ?>
								<?php if ( TRF_Cargo_Tracking_Gallery::is_pdf( $url ) ) : ?>
									<?php
									$pdf_label = sprintf(
										/* translators: 1: tracking code, 2: file number */
										__( 'فایل PDF %2$d محموله %1$s', 'trf-cargo-tracking' ),
										$result['validation'],
										$index + 1
									);
									?>
									<a
										class="trf-track__file"
										href="<?php echo esc_url( $url ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										aria-label="<?php echo esc_attr( $pdf_label ); ?>"
									>
										<span class="trf-track__file-badge">PDF</span>
										<span class="trf-track__file-name"><?php echo esc_html( TRF_Cargo_Tracking_Gallery::basename_from_url( $url ) ); ?></span>
										<span class="trf-track__file-hint"><?php esc_html_e( 'مشاهده / دانلود', 'trf-cargo-tracking' ); ?></span>
									</a>
								<?php else : ?>
									<?php
									$alt = sprintf(
										/* translators: 1: tracking code, 2: image number */
										__( 'تصویر %2$d محموله %1$s', 'trf-cargo-tracking' ),
										$result['validation'],
										$index + 1
									);
									?>
									<button
										type="button"
										class="trf-track__thumb"
										data-trf-lightbox-src="<?php echo esc_url( $url ); ?>"
										data-trf-lightbox-alt="<?php echo esc_attr( $alt ); ?>"
									>
										<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
									</button>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</article>
		<?php else : ?>
			<div class="trf-track__notice trf-track__notice--idle">
				<p><?php esc_html_e( 'پس از وارد کردن کد، وضعیت و مدارک محموله اینجا نمایش داده می‌شود.', 'trf-cargo-tracking' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
