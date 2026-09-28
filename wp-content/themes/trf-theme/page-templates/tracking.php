<?php
/**
 * Template Name: Cargo Tracking
 * Description: صفحه استعلام وضعیت بار.
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
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
