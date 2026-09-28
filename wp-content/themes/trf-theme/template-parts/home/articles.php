<?php
/**
 * Homepage latest articles.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type WP_Query $articles Query.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$articles = isset( $args['articles'] ) ? $args['articles'] : null;
if ( ! $articles instanceof WP_Query || ! $articles->have_posts() ) {
	return;
}
?>
<section class="trf-section trf-articles" id="articles">
	<div class="trf-container">
		<header class="trf-section__head trf-section__head--row">
			<div>
				<p class="trf-eyebrow">دانشنامه</p>
				<h2>مقالات حمل و نقل</h2>
				<p>راهنما و اخبار حوزه لجستیک، ترخیص و ارسال بین‌المللی.</p>
			</div>
			<a class="trf-btn trf-btn--outline" href="<?php echo esc_url( trf_news_url() ); ?>">همه مقالات</a>
		</header>
		<div class="trf-articles__grid">
			<?php
			while ( $articles->have_posts() ) :
				$articles->the_post();
				$excerpt = get_the_excerpt();
				?>
				<article <?php post_class( 'trf-article-card' ); ?>>
					<a class="trf-article-card__media" href="<?php the_permalink(); ?>">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) );
						} else {
							echo '<img src="' . esc_url( trf_asset( 'img/articles-1.webp' ) ) . '" alt="" loading="lazy" decoding="async">';
						}
						?>
					</a>
					<div class="trf-article-card__body">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( $excerpt, 18, '…' ) ); ?></p>
						<a class="trf-article-card__more" href="<?php the_permalink(); ?>">ادامه مطلب <?php echo trf_icon( 'arrow' ); ?></a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
