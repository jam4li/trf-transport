<?php
/**
 * Template Name: Contact
 * Description: صفحه تماس با فرم استعلام قیمت.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page">
	<div class="trf-container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'trf-page__article' ); ?>>
				<header class="trf-page__header">
					<h1><?php the_title(); ?></h1>
				</header>
				<div class="trf-page__content">
					<?php the_content(); ?>
				</div>
				<div class="trf-page__quote">
					<h2><?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?></h2>
					<p><?php esc_html_e( 'برای دریافت بهترین قیمت حمل فرم زیر را تکمیل کنید تا کارشناسان ما در اسرع وقت با شما تماس بگیرند', 'trf-theme' ); ?></p>
					<?php get_template_part( 'template-parts/quote-form', null, array( 'suffix' => 'contact' ) ); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
