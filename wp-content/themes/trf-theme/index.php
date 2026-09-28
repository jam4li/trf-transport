<?php
/**
 * Fallback index template.
 *
 * Inner pages still use Elementor content until later migration phases.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page">
	<div class="trf-container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article <?php post_class( 'trf-page__article' ); ?>>
					<?php if ( ! is_front_page() ) : ?>
						<header class="trf-page__header">
							<h1><?php the_title(); ?></h1>
						</header>
					<?php endif; ?>
					<div class="trf-page__content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
