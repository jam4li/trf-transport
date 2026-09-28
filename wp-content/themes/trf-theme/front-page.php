<?php
/**
 * Front page — coded recreation of https://trf-transport.com homepage.
 *
 * @package trf-theme
 */

get_header();

$services = array(
	array(
		'title' => 'حمل و نقل دریایی',
		'text'  => 'حمل بار دریایی به تمامی بنادر دنیا در سریع ترین زمان ممکن',
		'url'   => home_url( '/sea-transport/' ),
		'image' => trf_asset( 'img/service-sea.webp' ),
	),
	array(
		'title' => 'حمل و نقل جاده ای',
		'text'  => 'حمل بار جاده ای به کشورهای حوزه قفقاز و اروپا',
		'url'   => home_url( '/road-transport/' ),
		'image' => trf_asset( 'img/service-road.webp' ),
	),
	array(
		'title' => 'حمل و نقل ریلی',
		'text'  => 'حمل بار با قطار به کشورهای آسیایی از طریق خطوط ریلی',
		'url'   => home_url( '/rail-transport/' ),
		'image' => trf_asset( 'img/service-rail.webp' ),
	),
	array(
		'title' => 'حمل و نقل هوایی',
		'text'  => 'حمل بار سریع به تمامی فرودگاه‌های دنیا',
		'url'   => home_url( '/air-transport/' ),
		'image' => trf_asset( 'img/service-air.webp' ),
	),
);

$countries = array(
	array(
		'slug'  => 'afghanistan',
		'title' => 'افغانستان',
		'text'  => 'ما در شرکت حمل‌ونقل تراف به منظور جابه‌جایی و ارسال بار از ایران به افغانستان یا بالعکس نیز همراه شما هستیم. در امور مربوط به لجستیک، مهم‌ترین معیار انتخاب شرکت حمل‌ونقل، باتجربه و قابل اعتماد بودن شرکت مذکور است. تراف با سابقه‌ای طولانی مدت در زمینه حمل بار به افغانستان و دیگر کشورهای آسیای میانه و خاور دور، توانسته است اعتماد و نظر شمار زیادی از مشتریان خود را جلب کند.',
		'url'   => home_url( '/transport-to-afghanistan/' ),
		'image' => trf_asset( 'img/countries/afghanistan.svg' ),
	),
	array(
		'slug'  => 'turkey',
		'title' => 'ترکیه',
		'text'  => 'یکی از خدمات شرکت بین المللی حمل‌ونقل تراف، حمل بار و ارائه خدمات لجستیک به مقصد ترکیه است. تمامی شرکت‌های بین‌المللی صادرات و واردات کالا، بازرگانی‌ها، عموم افراد به‌ویژه دانشجویان و… می‌توانند ارسال بار خود به مقصد کشور ترکیه را از طریق شرکت تراف انجام دهند.',
		'url'   => home_url( '/transport-to-turkey/' ),
		'image' => trf_asset( 'img/countries/turkey.svg' ),
	),
	array(
		'slug'  => 'russia',
		'title' => 'روسیه',
		'text'  => 'در شرکت بین‌المللی تراف با تکیه بر زیرساخت‌های خود و تلاش روزافزون پرسنل مجرب این شرکت، توانسته‌ایم به یکی از بهترین شرکت‌های حمل بار هوایی به روسیه، حمل بار دریایی و… تبدیل شویم. تراف همواره سعی کرده است با کاهش هزینه‌ها و ساده‌تر کردن فرایند جابه‌جایی داخلی و خارجی بار، یک سیستم اصولی لجستیک را طراحی و پیاده‌سازی کند.',
		'url'   => home_url( '/transport-to-russia/' ),
		'image' => trf_asset( 'img/countries/russia.svg' ),
	),
	array(
		'slug'  => 'iraq',
		'title' => 'عراق',
		'text'  => 'حمل بار به عراق با قیمتی مناسب و تضمین سلامت بار از مبدا ایران به مقصد کشور عراق از سوی شرکت بین المللی حمل‌ونقل تراف انجام می‌شود. انواع جابه‌جایی مانند حمل اثاثیه منزل، خودرو، حمل و نقل کانتینری، ارسال خرده بار و… از طریق تراف امکان‌پذیر است.',
		'url'   => home_url( '/transport-to-middle-east/' ),
		'image' => trf_asset( 'img/countries/iraq.svg' ),
	),
	array(
		'slug'  => 'uae',
		'title' => 'امارات',
		'text'  => 'خدمات ارسال بار به امارات با تضمین قیمت و سلامت کالا حین تحویل را از شرکت بین‌المللی تراف دریافت کنید. ما در تراف با سابقه‌ای طولانی مدت در زمینه حمل بار دریایی و هوایی به کشور امارات، امکان حمل و نقل و ارسال بار به دو صورت فریت بار و تجاری را فراهم کرده‌ایم.',
		'url'   => home_url( '/-/emirates/' ),
		'image' => trf_asset( 'img/countries/uae.svg' ),
	),
	array(
		'slug'  => 'armenia',
		'title' => 'ارمنستان',
		'text'  => 'اگر به دنبال شرکتی معتبر در زمینه حمل بار به ارمنستان با ارائه بیمه نامه بین‌المللی هستید، شرکت حمل‌ونقل بین‌المللی تراف با قیمت مناسب یک انتخاب مناسب خواهد بود. تراف در کارنامه کاری خود سابقه خوبی در زمینه حمل بار به کشور ارمنستان و شهرهایی مانند ایروان، نخجوان و… را به ثبت رسانده است.',
		'url'   => home_url( '/transport-to-asia/' ),
		'image' => trf_asset( 'img/countries/armenia.svg' ),
	),
);

$agents_domestic = array(
	array( 'تهران', 'نماینده در شهر تهران', 'agents/tehran.webp' ),
	array( 'مشهد', 'نماینده در شهر مشهد', 'agents/mashad.webp' ),
	array( 'بندرعباس', 'نماینده در بندرعباس', 'agents/bandar-abbas.webp' ),
	array( 'دوغارون', 'نماینده در مرز دوغارون', 'agents/dogharoon.webp' ),
	array( 'زاهدان', 'نماینده در شهر زاهدان', 'agents/zahedan.webp' ),
	array( 'بازرگان', 'نماینده در مرز بازرگان', 'agents/bazargan.webp' ),
	array( 'سرخس', 'نماینده در شهر سرخس', 'agents/sarakhs.jpg' ),
	array( 'چابهار', 'نماینده در شهر چابهار', 'agents/chabahar.jpeg' ),
	array( 'مهران', 'نماینده در مرز مهران', 'agents/mehran.jpeg' ),
	array( 'لطف آباد', 'نماینده در مرز لطف آباد', 'agents/lotfabad.jpg' ),
	array( 'جلفا', 'نماینده در شهر جلفا', 'agents/jolfa.jpg' ),
	array( 'سرو', 'نماینده در مرز سرو', 'agents/sarv.jpg' ),
	array( 'ماهیرود', 'نماینده در مرز ماهیرود', 'agents/mahirood.jpg' ),
);

$agents_foreign = array(
	array( 'امارات', 'نماینده در کشور امارات', 'agents/uae.webp' ),
	array( 'افغانستان', 'نماینده در کشور افغانستان', 'agents/afghanistan.webp' ),
	array( 'ازبکستان', 'نماینده در کشور ازبکستان', 'agents/Uzbekistan.webp' ),
	array( 'پاکستان', 'نماینده در کشور پاکستان', 'agents/pakistan.webp' ),
	array( 'اروپا', 'نماینده در قاره اروپا', 'agents/europe.webp' ),
	array( 'قزاقستان', 'نماینده در کشور قزاقستان', 'agents/Kazakhstan.webp' ),
	array( 'تاجیکستان', 'نماینده در کشور تاجیکستان', 'agents/tajikestan.webp' ),
	array( 'ترکیه', 'نماینده در کشور ترکیه', 'agents/turkiye.webp' ),
);

$articles = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>

<main id="content" class="trf-home">

	<section class="trf-hero">
		<div class="trf-hero__media" style="background-image:url('<?php echo esc_url( trf_asset( 'img/about.webp' ) ); ?>')"></div>
		<div class="trf-container trf-hero__content">
			<h1>شرکت حمل و نقل بین المللی تراف</h1>
			<p class="trf-hero__lead">خدماتی که ما ارائه می‌دهیم</p>
			<div class="trf-hero__actions">
				<a class="trf-btn trf-btn--primary" href="#quote"><?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?></a>
				<a class="trf-btn trf-btn--ghost" href="<?php echo esc_url( home_url( '/tracking/' ) ); ?>"><?php esc_html_e( 'استعلام وضعیت بار', 'trf-theme' ); ?></a>
			</div>
		</div>
	</section>

	<section class="trf-section trf-services" id="services">
		<div class="trf-container">
			<header class="trf-section__head">
				<h2>شرکت حمل و نقل بین المللی تراف</h2>
				<p>خدماتی که ما ارائه می‌دهیم</p>
			</header>
			<div class="trf-services__grid">
				<?php foreach ( $services as $service ) : ?>
					<a class="trf-service-card" href="<?php echo esc_url( $service['url'] ); ?>">
						<span class="trf-service-card__bg" style="background-image:url('<?php echo esc_url( $service['image'] ); ?>')"></span>
						<span class="trf-service-card__body">
							<h3><?php echo esc_html( $service['title'] ); ?></h3>
							<p><?php echo esc_html( $service['text'] ); ?></p>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="trf-section trf-about" id="about">
		<div class="trf-container trf-about__grid">
			<div class="trf-about__media">
				<img src="<?php echo esc_url( trf_asset( 'img/about.webp' ) ); ?>" width="640" height="427" alt="درباره شرکت حمل و نقل بین المللی تراف" loading="lazy">
			</div>
			<div class="trf-about__copy">
				<h2>درباره شرکت حمل و نقل بین المللی تراف</h2>
				<p class="trf-eyebrow">چرا ما را انتخاب کنید؟</p>
				<p>شرکت حمل و نقل بین المللی تراف با بیش از ۲۰ سال سابقه تخصصی در زمینه لجستیک؛ حمل بار هوایی، زمینی و دریایی فعالیت می‌کند. این شرکت حمل بار، با تیمی مجرب، کارآزموده و پرسنل متعهد خدمات خود را با هدف ساده‌تر کردن فرایند جابه‌جایی کالا و حمل بار به تمامی نقاط خاورمیانه، آسیای میانه، خاور دور و… به عموم افراد، شرکت‌ها و ارگان‌های دولتی و خصوصی ارائه می‌کند.</p>
				<p>خدمات قابل ارائه از سوی شرکت حمل‌ و نقل بین المللی تراف طیف گسترده‌ای از خدمات لجستیک مانند حمل بار هوایی، ریلی، دریایی، جاده‌ای، ترخیص کالا، صادرات و واردات، حمل کانتینری و… را شامل می‌شود و نیاز تمامی افراد به خدمات این‌چنینی را پوشش می‌دهد. جابه‌جایی و حمل بار توسط این شرکت باربری بین المللی به تمامی کشورهای همسایه مانند ترکیه، افغانستان، عراق، ارمنستان و… صورت می‌گیرد.</p>
				<p>شرکت حمل و نقل بین المللی تراف از تمامی مجوزهای قانونی لازم برای ارسال بار هوایی، دریایی و زمینی در داخل کشور یا مقاصد خارج از کشور برخوردار است بنابراین به شما تضمین می‌دهیم که سلامت بار و کالای شما تا مقصد و لحظه تحویل کاملا حفظ خواهد شد.</p>
				<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">اطلاعات بیشتر</a>
			</div>
		</div>
	</section>

	<section class="trf-section trf-countries" id="countries" data-trf-tabs>
		<div class="trf-container">
			<header class="trf-section__head">
				<h2>حمل به کشورهای مختلف</h2>
				<p>ما بیشتر به چه کشورهایی حمل می‌کنیم؟</p>
			</header>

			<div class="trf-tabs" role="tablist" aria-label="<?php esc_attr_e( 'کشورها', 'trf-theme' ); ?>">
				<?php foreach ( $countries as $index => $country ) : ?>
					<button
						type="button"
						class="trf-tabs__btn<?php echo 0 === $index ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						data-trf-tab="<?php echo esc_attr( $country['slug'] ); ?>"
					><?php echo esc_html( $country['title'] ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="trf-countries__panels">
				<?php foreach ( $countries as $index => $country ) : ?>
					<article
						class="trf-country-panel<?php echo 0 === $index ? ' is-active' : ''; ?>"
						data-trf-panel="<?php echo esc_attr( $country['slug'] ); ?>"
						<?php echo 0 === $index ? '' : 'hidden'; ?>
					>
						<div class="trf-country-panel__media">
							<img src="<?php echo esc_url( $country['image'] ); ?>" alt="<?php echo esc_attr( $country['title'] ); ?>" loading="lazy">
						</div>
						<div class="trf-country-panel__body">
							<h3><?php echo esc_html( $country['title'] ); ?></h3>
							<p><?php echo esc_html( $country['text'] ); ?></p>
							<a class="trf-btn trf-btn--primary" href="<?php echo esc_url( $country['url'] ); ?>">اطلاعات بیشتر</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="trf-section trf-agents" id="agents" data-trf-agent-tabs>
		<div class="trf-container">
			<header class="trf-section__head">
				<h2>نمایندگان شرکت حمل و نقل بین المللی تراف</h2>
				<p>ما در تمامی شهرهای ایران و اکثر کشورها نماینده داریم</p>
			</header>

			<div class="trf-tabs trf-tabs--agents" role="tablist">
				<button type="button" class="trf-tabs__btn is-active" data-trf-agent-tab="domestic" aria-selected="true">نمایندگان داخلی</button>
				<button type="button" class="trf-tabs__btn" data-trf-agent-tab="foreign" aria-selected="false">نمایندگان خارجی</button>
			</div>

			<?php
			$agent_groups = array(
				'domestic' => $agents_domestic,
				'foreign'  => $agents_foreign,
			);
			foreach ( $agent_groups as $group => $list ) :
				?>
				<div class="trf-agents__viewport<?php echo 'domestic' === $group ? ' is-active' : ''; ?>" data-trf-agents data-trf-agent-panel="<?php echo esc_attr( $group ); ?>" <?php echo 'domestic' === $group ? '' : 'hidden'; ?>>
					<button class="trf-agents__nav trf-agents__nav--prev" type="button" data-trf-agents-prev aria-label="<?php esc_attr_e( 'قبلی', 'trf-theme' ); ?>">‹</button>
					<div class="trf-agents__track" data-trf-agents-track>
						<?php foreach ( $list as $agent ) : ?>
							<article class="trf-agent-card">
								<img src="<?php echo esc_url( trf_asset( 'img/' . $agent[2] ) ); ?>" alt="<?php echo esc_attr( $agent[0] ); ?>" loading="lazy">
								<div class="trf-agent-card__body">
									<h3><?php echo esc_html( $agent[0] ); ?></h3>
									<p><?php echo esc_html( $agent[1] ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
					<button class="trf-agents__nav trf-agents__nav--next" type="button" data-trf-agents-next aria-label="<?php esc_attr_e( 'بعدی', 'trf-theme' ); ?>">›</button>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<?php if ( $articles->have_posts() ) : ?>
		<section class="trf-section trf-articles" id="articles">
			<div class="trf-container">
				<header class="trf-section__head">
					<h2>مقالات</h2>
					<p>جدیدترین مقالات مربوط به حوزه حمل و نقل</p>
				</header>
				<div class="trf-articles__grid">
					<?php
					while ( $articles->have_posts() ) :
						$articles->the_post();
						?>
						<article <?php post_class( 'trf-article-card' ); ?>>
							<a class="trf-article-card__media" href="<?php the_permalink(); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) );
								} else {
									echo '<img src="' . esc_url( trf_asset( 'img/articles-1.webp' ) ) . '" alt="" loading="lazy">';
								}
								?>
							</a>
							<div class="trf-article-card__body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : get_the_content(), 18, '…' ) ); ?></p>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
			</div>
		</section>
		<?php
		wp_reset_postdata();
	endif;
	?>

</main>

<?php
get_footer();
