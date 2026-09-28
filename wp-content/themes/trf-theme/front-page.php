<?php
/**
 * Front page.
 *
 * @package trf-theme
 */

get_header();

$services        = trf_home_services();
$stats           = trf_home_stats();
$process         = trf_home_process();
$benefits        = trf_home_benefits();
$countries       = trf_home_countries_resolved();
$agents_domestic = trf_home_agents_domestic();
$agents_foreign  = trf_home_agents_foreign();
$articles        = trf_home_articles_query();
?>

<main id="content" class="trf-home">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/stats', null, compact( 'stats' ) );
	get_template_part( 'template-parts/home/services', null, compact( 'services' ) );
	get_template_part( 'template-parts/home/process', null, compact( 'process' ) );
	get_template_part( 'template-parts/home/about', null, compact( 'benefits' ) );
	get_template_part( 'template-parts/home/countries', null, compact( 'countries' ) );
	get_template_part( 'template-parts/home/agents', null, compact( 'agents_domestic', 'agents_foreign' ) );
	get_template_part( 'template-parts/home/articles', null, compact( 'articles' ) );
	get_template_part( 'template-parts/home/cta' );
	?>
</main>

<?php
get_footer();
