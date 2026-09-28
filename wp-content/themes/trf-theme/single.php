<?php
/**
 * Single post template.
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
					<?php if ( is_singular( 'post' ) ) : ?>
						<time class="trf-page__meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php endif; ?>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="trf-page__thumb">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>
				<div class="trf-page__content">
					<?php
					the_content();
					if ( ! get_the_content() && has_excerpt() ) {
						echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
					}
					?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
