<?php
/**
 * Single post template.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page trf-article">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', 'article' );
	endwhile;
	?>
</main>

<?php
get_footer();
