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

$ok      = is_array( $result ) && isset( $result['status'] ) && 'ok' === $result['status'];
$fail    = is_array( $result ) && isset( $result['status'] ) && 'fail' === $result['status'];
$i18n    = TRF_Cargo_Tracking_Frontend::i18n();
$entries = $ok ? TRF_Cargo_Tracking_Frontend::entries_from_result( $result ) : array();
$latest  = $entries ? $entries[0] : array();

if ( '' === $label ) {
	$label = __( 'کد رهگیری', 'trf-cargo-tracking' );
}
if ( '' === $button ) {
	$button = __( 'بررسی وضعیت بار', 'trf-cargo-tracking' );
}
?><div class="trf-track<?php echo $ok ? ' has-result' : ''; ?>" data-trf-track>
	<form class="trf-track__form" method="get" data-trf-track-form>
		<label class="trf-track__label" for="trf-track-code"><?php echo esc_html( $label ); ?></label>
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
				placeholder="مثال: KM/1405-06-164"
			>
			<button class="trf-btn trf-btn--primary trf-track__submit" type="submit" data-trf-track-submit>
				<span data-trf-track-submit-label><?php echo esc_html( $button ); ?></span>
			</button>
		</div>
		<p class="trf-track__hint"><?php esc_html_e( 'کد را بدون فاصله و دقیقاً مطابق بارنامه وارد کنید.', 'trf-cargo-tracking' ); ?></p>
	</form>

	<div class="trf-track__result" data-trf-track-result aria-live="polite">
		<?php if ( $fail ) : ?>
			<div class="trf-track__notice trf-track__notice--error" role="alert">
				<p class="trf-track__notice-title"><?php echo esc_html( $result['description'] ); ?></p>
				<p><?php echo esc_html( $i18n['errorHint'] ); ?></p>
			</div>
		<?php elseif ( $ok ) : ?>
			<?php
			$latest_desc = isset( $latest['description'] ) ? (string) $latest['description'] : '';
			$latest_date = isset( $latest['event_date'] ) ? (string) $latest['event_date'] : '';
			$headline    = TRF_Cargo_Tracking_Frontend::headline_from_description( $latest_desc );
			$entry_count = count( $entries );
			?>
			<article class="trf-track__shipment">
				<header class="trf-track__summary">
					<div class="trf-track__summary-main">
						<p class="trf-track__kicker"><?php echo esc_html( $i18n['kicker'] ); ?></p>
						<h3 class="trf-track__headline"><?php echo esc_html( $headline ); ?></h3>
						<p class="trf-track__meta">
							<?php if ( '' !== $latest_date ) : ?>
								<time datetime="<?php echo esc_attr( $latest_date ); ?>"><?php echo esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( $latest_date ) ); ?></time>
							<?php endif; ?>
							<span><?php echo esc_html( TRF_Cargo_Tracking_Frontend::updates_label( $entry_count ) ); ?></span>
						</p>
					</div>
					<div class="trf-track__summary-code">
						<span class="trf-track__code-label"><?php echo esc_html( $label ); ?></span>
						<div class="trf-track__code-row">
							<code class="trf-track__code-value" dir="ltr"><?php echo esc_html( $result['validation'] ); ?></code>
							<button type="button" class="trf-track__copy" data-trf-copy="<?php echo esc_attr( $result['validation'] ); ?>">
								<?php echo esc_html( $i18n['copy'] ); ?>
							</button>
						</div>
					</div>
				</header>

				<h4 class="trf-track__section-title"><?php echo esc_html( $i18n['path'] ); ?></h4>
				<ol class="trf-track__timeline">
					<?php foreach ( $entries as $index => $entry ) : ?>
						<?php
						$entry_desc  = isset( $entry['description'] ) ? (string) $entry['description'] : '';
						$entry_date  = isset( $entry['event_date'] ) ? (string) $entry['event_date'] : '';
						$entry_media = ( ! empty( $entry['gallery'] ) && is_array( $entry['gallery'] ) ) ? $entry['gallery'] : array();
						$is_current  = 0 === $index;
						?>
						<li class="trf-track__event<?php echo $is_current ? ' is-current' : ''; ?>">
							<span class="trf-track__dot" aria-hidden="true"></span>
							<?php if ( '' !== $entry_date ) : ?>
								<time class="trf-track__date" datetime="<?php echo esc_attr( $entry_date ); ?>">
									<?php echo esc_html( TRF_Cargo_Tracking_Jalali::to_persian_digits( $entry_date ) ); ?>
								</time>
							<?php endif; ?>
							<div class="trf-track__event-body">
								<?php if ( $is_current && $entry_count > 1 ) : ?>
									<p class="trf-track__event-flag"><?php echo esc_html( $i18n['latest'] ); ?></p>
								<?php endif; ?>
								<?php if ( '' !== $entry_desc ) : ?>
									<div class="trf-track__description"><?php echo nl2br( esc_html( $entry_desc ) ); ?></div>
								<?php endif; ?>
								<?php if ( $entry_media ) : ?>
									<section class="trf-track__files" aria-label="<?php echo esc_attr( $i18n['documents'] ); ?>">
										<p class="trf-track__files-label"><?php echo esc_html( $i18n['documents'] ); ?></p>
										<div class="trf-track__files-grid">
											<?php foreach ( $entry_media as $media_index => $url ) : ?>
												<?php if ( TRF_Cargo_Tracking_Gallery::is_pdf( $url ) ) : ?>
													<?php
													$pdf_label = sprintf(
														/* translators: 1: tracking code, 2: file number */
														__( 'فایل PDF %2$d محموله %1$s', 'trf-cargo-tracking' ),
														$result['validation'],
														$media_index + 1
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
													</a>
												<?php else : ?>
													<?php
													$alt = sprintf(
														/* translators: 1: tracking code, 2: image number */
														__( 'تصویر %2$d محموله %1$s', 'trf-cargo-tracking' ),
														$result['validation'],
														$media_index + 1
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
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			</article>
		<?php else : ?>
			<div class="trf-track__idle">
				<span class="trf-track__idle-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8.5 12 4l9 4.5v9L12 22 3 17.5z"/><path d="M12 22V13"/><path d="M3 8.5 12 13l9-4.5"/></svg>
				</span>
				<p class="trf-track__idle-title"><?php echo esc_html( $i18n['idleTitle'] ); ?></p>
				<p><?php echo esc_html( $i18n['idle'] ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
