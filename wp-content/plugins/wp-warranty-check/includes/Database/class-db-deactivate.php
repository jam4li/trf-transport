<?php
namespace Trust\INC\Database;
class DeactivateDB {
    public function __construct() {
        add_action( 'admin_init', [ $this, 'deactivate_db' ] );
    }
    public static function deactivate_db() {
        include TRUST_INC . 'class-db-structure.php';
        delete_option( 'wpwv_options' );
        
        /** @var string $serial_drop_sql */
        $wpdb->query( $serial_drop_sql );
        /** @var string $validation_drop_sql */
        $wpdb->query( $validation_drop_sql );
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $serial_drop_sql );
        dbDelta( $validation_drop_sql );       
    }
}
