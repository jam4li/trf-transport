<?php
/**
 * Template Name: Contact
 * Description: صفحه تماس با فرم پیام.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page trf-contact">
	<?php
	while ( have_posts() ) :
		the_post();
		$trf_lead     = has_excerpt() ? get_the_excerpt() : __( 'برای پرسش، هماهنگی مسیر یا پیگیری بار، پیام بگذارید یا مستقیم با ما تماس بگیرید.', 'trf-theme' );
		$trf_body     = get_the_content();
		$trf_has_body = '' !== trim( wp_strip_all_tags( $trf_body ) );
		$trf_details  = trf_contact_parse_details( $trf_body );
		?>
		<header class="trf-contact__hero">
			<div class="trf-container">
				<p class="trf-eyebrow"><?php esc_html_e( 'ارتباط با تراف', 'trf-theme' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<p class="trf-contact__lead"><?php echo esc_html( $trf_lead ); ?></p>
			</div>
		</header>

		<div class="trf-container trf-contact__grid">
			<section class="trf-contact__panel" id="contact-form" aria-labelledby="trf-contact-form-title">
				<h2 id="trf-contact-form-title"><?php esc_html_e( 'ارسال پیام', 'trf-theme' ); ?></h2>
				<p><?php esc_html_e( 'فرم را تکمیل کنید تا کارشناسان تراف در اسرع وقت پاسخ دهند.', 'trf-theme' ); ?></p>
				<?php get_template_part( 'template-parts/contact-form' ); ?>
			</section>

			<?php
			get_template_part(
				'template-parts/contact-aside',
				null,
				array(
					'offices'  => $trf_details['offices'],
					'email'    => $trf_details['email'],
					'has_body' => $trf_has_body,
				)
			);
			?>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
