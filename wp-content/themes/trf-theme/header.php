<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'trf-theme' ); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'پرش به محتوا', 'trf-theme' ); ?></a>

<header class="trf-header" data-trf-header>
	<div class="trf-container trf-header__inner">
		<?php trf_the_logo(); ?>

		<nav id="trf-primary-nav" class="trf-nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'trf-theme' ); ?>" data-trf-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'trf-nav__list',
					'depth'          => 2,
					'fallback_cb'    => false,
				)
			);
			?>
			<a class="trf-btn trf-btn--primary trf-nav__cta" href="<?php echo esc_url( trf_quote_cta_url() ); ?>" data-trf-quote-open>
				<?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?>
			</a>
		</nav>

		<a class="trf-header__phone" href="<?php echo esc_url( 'tel:' . trf_mod( 'trf_phone_tel' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: phone number */ __( 'تماس: %s', 'trf-theme' ), trf_mod( 'trf_phone' ) ) ); ?>">
			<?php echo trf_icon( 'phone' ); ?>
			<span class="trf-header__phone-num"><?php echo esc_html( trf_mod( 'trf_phone' ) ); ?></span>
		</a>

		<a class="trf-btn trf-btn--primary trf-header__cta" href="<?php echo esc_url( trf_quote_cta_url() ); ?>" data-trf-quote-open>
			<?php esc_html_e( 'استعلام قیمت حمل', 'trf-theme' ); ?>
		</a>

		<button class="trf-nav-toggle" type="button" aria-expanded="false" aria-controls="trf-primary-nav" data-trf-nav-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'منو', 'trf-theme' ); ?></span>
			<span class="trf-nav-toggle__bar" aria-hidden="true"></span>
			<span class="trf-nav-toggle__bar" aria-hidden="true"></span>
			<span class="trf-nav-toggle__bar" aria-hidden="true"></span>
		</button>
	</div>
</header>
