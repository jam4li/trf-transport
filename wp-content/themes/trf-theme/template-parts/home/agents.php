<?php
/**
 * Homepage agent carousels.
 *
 * @package trf-theme
 *
 * @var array $args {
 *     @type array $agents_domestic Domestic agents.
 *     @type array $agents_foreign  Foreign agents.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$agents_domestic = isset( $args['agents_domestic'] ) ? $args['agents_domestic'] : array();
$agents_foreign  = isset( $args['agents_foreign'] ) ? $args['agents_foreign'] : array();
$agent_groups    = array(
	'domestic' => $agents_domestic,
	'foreign'  => $agents_foreign,
);
?>
<section class="trf-section trf-agents" id="agents" data-trf-agent-tabs>
	<div class="trf-container">
		<header class="trf-section__head">
			<p class="trf-eyebrow">شبکه نمایندگان</p>
			<h2>نمایندگان تراف در ایران و جهان</h2>
			<p>حضور در مرزها، بنادر و شهرهای کلیدی برای پیگیری سریع‌تر محموله.</p>
		</header>

		<div class="trf-tabs trf-tabs--agents" role="tablist" aria-label="<?php esc_attr_e( 'نمایندگان', 'trf-theme' ); ?>">
			<button type="button" id="trf-agent-tab-domestic" class="trf-tabs__btn is-active" role="tab" data-trf-agent-tab="domestic" aria-selected="true" aria-controls="trf-agent-panel-domestic" tabindex="0">نمایندگان داخلی</button>
			<button type="button" id="trf-agent-tab-foreign" class="trf-tabs__btn" role="tab" data-trf-agent-tab="foreign" aria-selected="false" aria-controls="trf-agent-panel-foreign" tabindex="-1">نمایندگان خارجی</button>
		</div>

		<?php foreach ( $agent_groups as $group => $list ) : ?>
			<div
				id="<?php echo esc_attr( 'trf-agent-panel-' . $group ); ?>"
				class="trf-agents__viewport<?php echo 'domestic' === $group ? ' is-active' : ''; ?>"
				role="tabpanel"
				aria-labelledby="<?php echo esc_attr( 'trf-agent-tab-' . $group ); ?>"
				data-trf-agents
				data-trf-agent-panel="<?php echo esc_attr( $group ); ?>"
				<?php echo 'domestic' === $group ? '' : 'hidden'; ?>
			>
				<button class="trf-agents__nav trf-agents__nav--prev" type="button" data-trf-agents-prev aria-label="<?php esc_attr_e( 'قبلی', 'trf-theme' ); ?>"><?php echo trf_icon( 'arrow' ); ?></button>
				<div class="trf-agents__track" data-trf-agents-track>
					<?php foreach ( $list as $agent ) : ?>
						<article class="trf-agent-card">
							<img src="<?php echo esc_url( trf_asset( 'img/' . $agent[2] ) ); ?>" alt="" width="400" height="275" loading="lazy" decoding="async">
							<div class="trf-agent-card__body">
								<h3><?php echo trf_icon( 'pin' ); ?><?php echo esc_html( $agent[0] ); ?></h3>
								<p><?php echo esc_html( $agent[1] ); ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<button class="trf-agents__nav trf-agents__nav--next" type="button" data-trf-agents-next aria-label="<?php esc_attr_e( 'بعدی', 'trf-theme' ); ?>"><?php echo trf_icon( 'arrow' ); ?></button>
			</div>
		<?php endforeach; ?>
	</div>
</section>
