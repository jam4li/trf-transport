<?php
/**
 * 404 template.
 *
 * @package trf-theme
 */

get_header();
?>

<main id="content" class="trf-page">
	<div class="trf-container">
		<article class="trf-page__article trf-404">
			<header class="trf-page__header">
				<h1><?php esc_html_e( 'صفحه پیدا نشد', 'trf-theme' ); ?></h1>
			</header>
			<div class="trf-page__content">
				<p><?php esc_html_e( 'آدرس واردشده وجود ندارد یا جابه‌جا شده است.', 'trf-theme' ); ?></p>
				<p>
					<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'بازگشت به صفحه اصلی', 'trf-theme' ); ?>
					</a>
				</p>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
