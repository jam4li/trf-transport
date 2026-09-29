<?php
/**
 * Fallback index template.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page">
	<div class="trf-container">
		<?php if ( have_posts() ) : ?>
			<?php if ( ! is_singular() ) : ?>
				<header class="trf-page__header">
					<h1><?php bloginfo( 'name' ); ?></h1>
				</header>
			<?php endif; ?>
			<?php
			while ( have_posts() ) :
				the_post();
				$heading_tag = is_singular() ? 'h1' : 'h2';
				?>
				<article <?php post_class( 'trf-page__article' ); ?>>
					<?php if ( ! is_front_page() ) : ?>
						<header class="trf-page__header">
							<<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static h1|h2. ?>><?php the_title(); ?></<?php echo $heading_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static h1|h2. ?>>
						</header>
					<?php endif; ?>
					<div class="trf-page__content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;
			?>
		<?php else : ?>
			<article class="trf-page__article">
				<header class="trf-page__header">
					<h1><?php esc_html_e( 'محتوایی پیدا نشد', 'trf-theme' ); ?></h1>
				</header>
			</article>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
