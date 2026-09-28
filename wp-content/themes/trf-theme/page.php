<?php
/**
 * Page template — keeps Elementor `the_content()` working on inner pages.
 *
 * @package trf-theme
 */

get_header();

$is_elementor = trf_is_elementor_page();
$page_class   = 'trf-page' . ( $is_elementor ? ' trf-page--elementor' : '' );
?>

<main id="content" class="<?php echo esc_attr( $page_class ); ?>">
	<?php if ( $is_elementor ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php else : ?>
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
	<?php endif; ?>
</main>

<?php
get_footer();
