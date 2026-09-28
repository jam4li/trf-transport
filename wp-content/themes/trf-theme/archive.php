<?php
/**
 * Post type and blog archives.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page">
	<div class="trf-container">
		<header class="trf-page__header">
			<h1><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<p class="trf-page__lede">', '</p>' ); ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="trf-archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$excerpt = get_the_excerpt();
					?>
					<article <?php post_class( 'trf-archive-card' ); ?>>
						<a class="trf-archive-card__media" href="<?php the_permalink(); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) );
							} else {
								echo '<img src="' . esc_url( trf_asset( 'img/articles-1.webp' ) ) . '" alt="" loading="lazy">';
							}
							?>
						</a>
						<div class="trf-archive-card__body">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( $excerpt ) : ?>
								<p><?php echo esc_html( wp_trim_words( $excerpt, 22, '…' ) ); ?></p>
							<?php endif; ?>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'class'     => 'trf-pagination',
					'mid_size'  => 1,
					'prev_text' => '‹',
					'next_text' => '›',
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'محتوایی پیدا نشد', 'trf-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
