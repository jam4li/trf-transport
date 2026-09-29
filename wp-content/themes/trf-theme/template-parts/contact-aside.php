<?php
/**
 * Contact page details aside (offices and email).
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array  $offices Parsed offices.
 *     @type string $email   Company email.
 *     @type bool   $has_body Whether unparsed page content exists.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$offices  = isset( $args['offices'] ) && is_array( $args['offices'] ) ? $args['offices'] : array();
$email    = isset( $args['email'] ) ? (string) $args['email'] : '';
$has_body = ! empty( $args['has_body'] );
$parsed   = ! empty( $offices ) || '' !== $email;

if ( ! $parsed && ! $has_body ) {
	return;
}
?>
<aside class="trf-contact__aside" aria-labelledby="trf-contact-aside-title">
	<header class="trf-contact__aside-head">
		<p class="trf-eyebrow"><?php esc_html_e( 'اطلاعات تماس', 'trf-theme' ); ?></p>
		<h2 id="trf-contact-aside-title"><?php esc_html_e( 'شعب تراف', 'trf-theme' ); ?></h2>
	</header>

	<?php if ( $parsed ) : ?>
		<?php if ( $offices ) : ?>
			<ul class="trf-contact__offices">
				<?php foreach ( $offices as $office ) : ?>
					<?php
					$title   = isset( $office['title'] ) ? $office['title'] : '';
					$address = isset( $office['address'] ) ? $office['address'] : '';
					$phone   = isset( $office['phone'] ) ? $office['phone'] : '';
					$tel     = $phone ? trf_tel_href( $phone ) : '';
					if ( '' === $title && '' === $address && '' === $phone ) {
						continue;
					}
					?>
					<li class="trf-contact__office">
						<span class="trf-contact__office-icon" aria-hidden="true"><?php echo trf_icon( 'pin' ); ?></span>
						<div class="trf-contact__office-body">
							<?php if ( $title ) : ?>
								<h3><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
							<?php if ( $address ) : ?>
								<p><?php echo esc_html( $address ); ?></p>
							<?php endif; ?>
							<?php if ( $phone && $tel ) : ?>
								<a class="trf-contact__office-phone" href="<?php echo esc_url( 'tel:' . $tel ); ?>">
									<?php echo trf_icon( 'phone' ); ?>
									<span dir="ltr"><?php echo esc_html( $phone ); ?></span>
								</a>
							<?php elseif ( $phone ) : ?>
								<p class="trf-contact__office-phone"><?php echo esc_html( $phone ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $email ) : ?>
			<a class="trf-contact__email" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
				<span class="trf-contact__office-icon" aria-hidden="true"><?php echo trf_icon( 'mail' ); ?></span>
				<span class="trf-contact__office-body">
					<span class="trf-contact__email-label"><?php esc_html_e( 'ایمیل', 'trf-theme' ); ?></span>
					<span class="trf-contact__email-value" dir="ltr"><?php echo esc_html( $email ); ?></span>
				</span>
			</a>
		<?php endif; ?>
	<?php else : ?>
		<div class="trf-contact__prose trf-page__content">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>
</aside>
