<?php
/**
 * Shared singular article layout (pages + posts).
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$trf_lead    = trf_article_lead();
$trf_eyebrow = is_singular( 'post' )
	? __( 'دانشنامه', 'trf-theme' )
	: __( 'خدمات و مسیرها', 'trf-theme' );
?>
<header class="trf-article__hero">
	<div class="trf-container">
		<?php trf_the_article_breadcrumb(); ?>
		<p class="trf-eyebrow"><?php echo esc_html( $trf_eyebrow ); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php if ( $trf_lead ) : ?>
			<p class="trf-article__lead"><?php echo esc_html( $trf_lead ); ?></p>
		<?php endif; ?>
		<?php if ( is_singular( 'post' ) ) : ?>
			<time class="trf-article__meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<?php endif; ?>
	</div>
</header>

<div class="trf-container trf-article__layout">
	<article <?php post_class( 'trf-article__main' ); ?>>
		<figure class="trf-article__media">
			<?php trf_the_article_image( 'large' ); ?>
		</figure>

		<div class="trf-article__content">
			<?php
			the_content();
			if ( ! get_the_content() && has_excerpt() ) {
				echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
			}
			?>
		</div>
	</article>

	<?php get_template_part( 'template-parts/article-aside' ); ?>
</div>

<?php get_template_part( 'template-parts/home/cta' ); ?>
