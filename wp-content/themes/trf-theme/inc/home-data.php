<?php
/**
 * Homepage content arrays. URLs are resolved from published slugs at render time.
 *
 * @package trf-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Attach permalink + image URL to a list of items that have slug/image keys.
 *
 * @param array<int, array<string, mixed>> $items Items.
 * @return array<int, array<string, mixed>>
 */
function trf_home_hydrate( $items ) {
	foreach ( $items as &$item ) {
		if ( isset( $item['slug'] ) ) {
			$types    = isset( $item['post_types'] ) ? $item['post_types'] : array( 'page' );
			$fallback = isset( $item['fallback'] ) ? $item['fallback'] : '';
			$item['url'] = trf_permalink_for( $item['slug'], $types, $fallback );
		}
		if ( isset( $item['image'] ) ) {
			$item['image'] = trf_asset( $item['image'] );
		}
	}
	unset( $item );

	return $items;
}

/**
 * Service cards.
 *
 * @return array<int, array<string, string>>
 */
function trf_home_services() {
	return trf_home_hydrate(
		array(
			array(
				'title' => 'حمل و نقل دریایی',
				'text'  => 'حمل بار دریایی به تمامی بنادر دنیا در سریع ترین زمان ممکن',
				'slug'  => 'sea-transport',
				'image' => 'img/service-sea.webp',
			),
			array(
				'title' => 'حمل و نقل جاده ای',
				'text'  => 'حمل بار جاده ای به کشورهای حوزه قفقاز و اروپا',
				'slug'  => 'road-transport',
				'image' => 'img/service-road.webp',
			),
			array(
				'title' => 'حمل و نقل ریلی',
				'text'  => 'حمل بار با قطار به کشورهای آسیایی از طریق خطوط ریلی',
				'slug'  => 'rail-transport',
				'image' => 'img/service-rail.webp',
			),
			array(
				'title' => 'حمل و نقل هوایی',
				'text'  => 'حمل بار سریع به تمامی فرودگاه‌های دنیا',
				'slug'  => 'air-transport',
				'image' => 'img/service-air.webp',
			),
		)
	);
}

/**
 * Country tabs.
 *
 * @return array<int, array<string, mixed>>
 */
function trf_home_countries() {
	return array(
		array(
				'slug'  => 'afghanistan',
				'title' => 'افغانستان',
				'text'  => 'ما در شرکت حمل‌ونقل تراف به منظور جابه‌جایی و ارسال بار از ایران به افغانستان یا بالعکس نیز همراه شما هستیم. در امور مربوط به لجستیک، مهم‌ترین معیار انتخاب شرکت حمل‌ونقل، باتجربه و قابل اعتماد بودن شرکت مذکور است. تراف با سابقه‌ای طولانی مدت در زمینه حمل بار به افغانستان و دیگر کشورهای آسیای میانه و خاور دور، توانسته است اعتماد و نظر شمار زیادی از مشتریان خود را جلب کند.',
				'page'  => 'transport-to-afghanistan',
				'image' => 'img/countries/afghanistan.svg',
			),
			array(
				'slug'  => 'turkey',
				'title' => 'ترکیه',
				'text'  => 'یکی از خدمات شرکت بین المللی حمل‌ونقل تراف، حمل بار و ارائه خدمات لجستیک به مقصد ترکیه است. تمامی شرکت‌های بین‌المللی صادرات و واردات کالا، بازرگانی‌ها، عموم افراد به‌ویژه دانشجویان و… می‌توانند ارسال بار خود به مقصد کشور ترکیه را از طریق شرکت تراف انجام دهند.',
				'page'  => 'transport-to-turkey',
				'image' => 'img/countries/turkey.svg',
			),
			array(
				'slug'  => 'russia',
				'title' => 'روسیه',
				'text'  => 'در شرکت بین‌المللی تراف با تکیه بر زیرساخت‌های خود و تلاش روزافزون پرسنل مجرب این شرکت، توانسته‌ایم به یکی از بهترین شرکت‌های حمل بار هوایی به روسیه، حمل بار دریایی و… تبدیل شویم. تراف همواره سعی کرده است با کاهش هزینه‌ها و ساده‌تر کردن فرایند جابه‌جایی داخلی و خارجی بار، یک سیستم اصولی لجستیک را طراحی و پیاده‌سازی کند.',
				'page'  => 'transport-to-russia',
				'image' => 'img/countries/russia.svg',
			),
			array(
				'slug'  => 'iraq',
				'title' => 'عراق',
				'text'  => 'حمل بار به عراق با قیمتی مناسب و تضمین سلامت بار از مبدا ایران به مقصد کشور عراق از سوی شرکت بین المللی حمل‌ونقل تراف انجام می‌شود. انواع جابه‌جایی مانند حمل اثاثیه منزل، خودرو، حمل و نقل کانتینری، ارسال خرده بار و… از طریق تراف امکان‌پذیر است.',
				'page'  => 'transport-to-middle-east',
				'image' => 'img/countries/iraq.svg',
			),
			array(
				'slug'       => 'uae',
				'title'      => 'امارات',
				'text'       => 'خدمات ارسال بار به امارات با تضمین قیمت و سلامت کالا حین تحویل را از شرکت بین‌المللی تراف دریافت کنید. ما در تراف با سابقه‌ای طولانی مدت در زمینه حمل بار دریایی و هوایی به کشور امارات، امکان حمل و نقل و ارسال بار به دو صورت فریت بار و تجاری را فراهم کرده‌ایم.',
				'page'       => 'emirates',
				'post_types' => array( 'foreign-agents', 'page' ),
				'fallback'   => 'foreign-agents/emirates',
				'image'      => 'img/countries/uae.svg',
			),
			array(
				'slug'  => 'armenia',
				'title' => 'ارمنستان',
				'text'  => 'اگر به دنبال شرکتی معتبر در زمینه حمل بار به ارمنستان با ارائه بیمه نامه بین‌المللی هستید، شرکت حمل‌ونقل بین‌المللی تراف با قیمت مناسب یک انتخاب مناسب خواهد بود. تراف در کارنامه کاری خود سابقه خوبی در زمینه حمل بار به کشور ارمنستان و شهرهایی مانند ایروان، نخجوان و… را به ثبت رسانده است.',
				'page'  => 'transport-to-asia',
				'image' => 'img/countries/armenia.svg',
			),
	);
}

/**
 * Resolve country permalinks using the `page` key (tab `slug` is only a UI id).
 *
 * @return array<int, array<string, mixed>>
 */
function trf_home_countries_resolved() {
	$countries = trf_home_countries();
	foreach ( $countries as &$country ) {
		$types    = isset( $country['post_types'] ) ? $country['post_types'] : array( 'page' );
		$fallback = isset( $country['fallback'] ) ? $country['fallback'] : '';
		$lookup   = isset( $country['page'] ) ? $country['page'] : $country['slug'];
		$country['url']   = trf_permalink_for( $lookup, $types, $fallback );
		$country['image'] = trf_asset( $country['image'] );
	}
	unset( $country );

	return $countries;
}

/**
 * Domestic agents.
 *
 * @return array<int, array{0:string,1:string,2:string}>
 */
function trf_home_agents_domestic() {
	return array(
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
}

/**
 * Foreign agents.
 *
 * @return array<int, array{0:string,1:string,2:string}>
 */
function trf_home_agents_foreign() {
	return array(
		array( 'امارات', 'نماینده در کشور امارات', 'agents/uae.webp' ),
		array( 'افغانستان', 'نماینده در کشور افغانستان', 'agents/afghanistan.webp' ),
		array( 'ازبکستان', 'نماینده در کشور ازبکستان', 'agents/Uzbekistan.webp' ),
		array( 'پاکستان', 'نماینده در کشور پاکستان', 'agents/pakistan.webp' ),
		array( 'اروپا', 'نماینده در قاره اروپا', 'agents/europe.webp' ),
		array( 'قزاقستان', 'نماینده در کشور قزاقستان', 'agents/Kazakhstan.webp' ),
		array( 'تاجیکستان', 'نماینده در کشور تاجیکستان', 'agents/tajikestan.webp' ),
		array( 'ترکیه', 'نماینده در کشور ترکیه', 'agents/turkiye.webp' ),
	);
}

/**
 * Latest posts for the homepage articles grid.
 *
 * @return WP_Query
 */
function trf_home_articles_query() {
	return new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Fallback items for the footer services column.
 *
 * @return array<int, array{label:string,url:string}>
 */
function trf_footer_services_fallback() {
	return array(
		array( 'label' => 'حمل و نقل جاده ای', 'url' => trf_permalink_for( 'road-transport' ) ),
		array( 'label' => 'حمل و نقل دریایی', 'url' => trf_permalink_for( 'sea-transport' ) ),
		array( 'label' => 'حمل و نقل ریلی', 'url' => trf_permalink_for( 'rail-transport' ) ),
		array( 'label' => 'حمل و نقل هوایی', 'url' => trf_permalink_for( 'air-transport' ) ),
	);
}

/**
 * Fallback items for the footer quick-links column.
 *
 * @return array<int, array{label:string,url:string,external:bool}>
 */
function trf_footer_extra_fallback() {
	return array(
		array( 'label' => 'گمرک جمهوری اسلامی ایران', 'url' => 'https://www.irica.ir/', 'external' => true ),
		array( 'label' => 'انجمن حمل و نقل خراسان', 'url' => 'https://www.itca-kh.com/', 'external' => true ),
		array( 'label' => 'سازمان راهداری و حمل و نقل جاده ای', 'url' => 'https://www.rmto.ir/', 'external' => true ),
		array( 'label' => 'وزارت صنعت، معدن و تجارت', 'url' => 'https://www.mimt.gov.ir/', 'external' => true ),
	);
}
