<?php
/**
 * Template Name: Cargo Tracking
 * Description: صفحه استعلام وضعیت بار.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page trf-tracking">
	<?php
	while ( have_posts() ) :
		the_post();
		$trf_lead = has_excerpt()
			? get_the_excerpt()
			: __( 'کد رهگیری بارنامه را وارد کنید تا وضعیت محموله و تصاویر مرتبط را ببینید.', 'trf-theme' );
		?>
		<header class="trf-tracking__hero">
			<div class="trf-container">
				<p class="trf-eyebrow"><?php esc_html_e( 'استعلام بار', 'trf-theme' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<p class="trf-tracking__lead"><?php echo esc_html( $trf_lead ); ?></p>
			</div>
		</header>

		<div class="trf-container trf-tracking__grid">
			<section class="trf-tracking__panel" id="tracking-lookup" aria-labelledby="trf-tracking-form-title">
				<h2 id="trf-tracking-form-title"><?php esc_html_e( 'پیگیری محموله', 'trf-theme' ); ?></h2>
				<p><?php esc_html_e( 'کد رهگیری را دقیقاً مطابق بارنامه وارد کنید.', 'trf-theme' ); ?></p>
				<?php echo do_shortcode( '[trf_cargo_tracking]' ); ?>
			</section>

			<?php get_template_part( 'template-parts/tracking-aside' ); ?>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
