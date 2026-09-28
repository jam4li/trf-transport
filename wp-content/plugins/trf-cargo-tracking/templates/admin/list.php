<?php
/**
 * Admin list.
 *
 * @package TRF_Cargo_Tracking
 *
 * @var TRF_Cargo_Tracking_List_Table $table
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'ردیابی بار', 'trf-cargo-tracking' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=trf-cargo-tracking-add' ) ); ?>" class="page-title-action"><?php esc_html_e( 'افزودن محموله', 'trf-cargo-tracking' ); ?></a>
	<hr class="wp-header-end">

	<?php TRF_Cargo_Tracking_Admin::notices(); ?>

	<form method="get">
		<input type="hidden" name="page" value="trf-cargo-tracking">
		<?php
		$table->search_box( __( 'جستجو', 'trf-cargo-tracking' ), 'trf-track' );
		$table->display();
		?>
	</form>
</div>
