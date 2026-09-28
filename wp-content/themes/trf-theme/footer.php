<?php
$trf_use_theme_footer = is_front_page()
	|| ! function_exists( 'elementor_theme_do_location' )
	|| ! elementor_theme_do_location( 'footer' );

if ( $trf_use_theme_footer ) :
	?>
<footer class="trf-footer">
	<div class="trf-container trf-footer__top">
		<a class="trf-footer__phone" href="tel:05137762626">۰۵۱-۳۷۷۶۲۶۲۶</a>
		<div class="trf-footer__social" aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی', 'trf-theme' ); ?>">
			<a href="https://t.me/" target="_blank" rel="noopener noreferrer">Telegram</a>
			<a href="https://twitter.com/" target="_blank" rel="noopener noreferrer">Twitter</a>
			<a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer">Youtube</a>
		</div>
	</div>

	<div class="trf-container trf-footer__grid">
		<div class="trf-footer__brand">
			<a class="trf-logo trf-logo--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( trf_asset( 'img/logo.png' ) ); ?>" width="160" height="50" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>
			<p>شرکت باربری و حمل‌ونقل تراف با بیش از ۲۰ سال سابقه تخصصی در زمینه لجستیک؛ حمل بار هوایی، زمینی و دریایی فعالیت می‌کند.</p>
		</div>

		<div class="trf-footer__col">
			<h2>خدمات ما</h2>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/road-transport/' ) ); ?>">حمل و نقل جاده ای</a></li>
				<li><a href="<?php echo esc_url( home_url( '/sea-transport/' ) ); ?>">حمل و نقل دریایی</a></li>
				<li><a href="<?php echo esc_url( home_url( '/rail-transport/' ) ); ?>">حمل و نقل ریلی</a></li>
				<li><a href="<?php echo esc_url( home_url( '/air-transport/' ) ); ?>">حمل و نقل هوایی</a></li>
			</ul>
		</div>

		<div class="trf-footer__col">
			<h2>دسترسی سریع</h2>
			<ul>
				<li><a href="https://www.irica.ir/" target="_blank" rel="noopener noreferrer">گمرک جمهوری اسلامی ایران</a></li>
				<li><a href="https://www.itca-kh.com/" target="_blank" rel="noopener noreferrer">انجمن حمل و نقل خراسان</a></li>
				<li><a href="https://www.rmto.ir/" target="_blank" rel="noopener noreferrer">سازمان راهداری و حمل و نقل جاده ای</a></li>
				<li><a href="https://www.mimt.gov.ir/" target="_blank" rel="noopener noreferrer">وزارت صنعت، معدن و تجارت</a></li>
			</ul>
		</div>

		<div class="trf-footer__col trf-footer__quote" id="quote">
			<h2>استعلام قیمت حمل</h2>
			<p>برای دریافت بهترین قیمت حمل فرم زیر را تکمیل کنید تا کارشناسان ما در اسرع وقت با شما تماس بگیرند</p>
			<form class="trf-quote-form" action="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" method="get">
				<label class="screen-reader-text" for="trf-quote-name"><?php esc_html_e( 'نام', 'trf-theme' ); ?></label>
				<input id="trf-quote-name" type="text" name="name" placeholder="<?php esc_attr_e( 'نام و نام خانوادگی', 'trf-theme' ); ?>" required>

				<label class="screen-reader-text" for="trf-quote-phone"><?php esc_html_e( 'تلفن', 'trf-theme' ); ?></label>
				<input id="trf-quote-phone" type="tel" name="phone" placeholder="<?php esc_attr_e( 'شماره تماس', 'trf-theme' ); ?>" required>

				<label class="screen-reader-text" for="trf-quote-msg"><?php esc_html_e( 'پیام', 'trf-theme' ); ?></label>
				<textarea id="trf-quote-msg" name="message" rows="3" placeholder="<?php esc_attr_e( 'توضیحات محموله / مبدا و مقصد', 'trf-theme' ); ?>"></textarea>

				<button class="trf-btn trf-btn--primary" type="submit"><?php esc_html_e( 'ارسال', 'trf-theme' ); ?></button>
			</form>
		</div>
	</div>

	<div class="trf-footer__bottom">
		<div class="trf-container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		</div>
	</div>
</footer>
	<?php
endif;
?>

<?php wp_footer(); ?>
</body>
</html>
