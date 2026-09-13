<?php

namespace Trust\INC\Database;

use Trust\INC\Database\DatabaseStruct as DBStruct;

require_once TRUST_INC . 'Database/class-db-structure.php';

class MigrateDB {
    private static $instance;
    public $serial_table;
    public $serial_sql;
    public $validation_table;
    public $validation_sql;

    public function __construct() {}
    public function run_migration() {
        global $wpwv_db_version;

        // Enable different warranty period units
        if (version_compare(get_option('wpwv_db_version'), '2.0', '<')) {
            $this->serial_sql = DBStruct::instance()->serial_sql;
            $this->serial_table = DBStruct::instance()->serial_table;
            $serial_update_records_sql = "UPDATE $this->serial_table SET period_unit='m'";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta([$this->serial_sql, $serial_update_records_sql]);
        }

        // Enable thumbnails
        if (version_compare(get_option('wpwv_db_version'), '3.0', '<')) {
            $this->validation_sql = DBStruct::instance()->validation_sql;
            $this->validation_table = DBStruct::instance()->validation_table;
            $validation_update_records_sql = "UPDATE $this->validation_table SET thumbnail_url=NULL";

            $this->serial_sql = DBStruct::instance()->serial_sql;
            $this->serial_table = DBStruct::instance()->serial_table;
            // $this->serial_update_records_sql = "UPDATE $this->serial_table SET customer_email=NULL";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta([$this->validation_sql, $validation_update_records_sql]);
            dbDelta([$this->serial_sql]);
        }

        // Update date types into datetime
        if (version_compare(get_option('wpwv_db_version'), '3.1', '<')) {
            $this->validation_sql = DBStruct::instance()->validation_sql;
            $this->validation_table = DBStruct::instance()->validation_table;
            
            $this->serial_sql = DBStruct::instance()->serial_sql;
            $this->serial_table = DBStruct::instance()->serial_table;

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta([$this->validation_sql, $this->serial_sql]);
        }


        // Update is_registered fields values in database
        if (version_compare(get_option('wpwv_db_version'), '3.2', '<')) {
            $this->validation_sql = DBStruct::instance()->validation_sql;
            $this->validation_table = DBStruct::instance()->validation_table;
            
            $this->serial_sql = DBStruct::instance()->serial_sql;
            $this->serial_table = DBStruct::instance()->serial_table;
            $serial_update_records_sql1 = "UPDATE $this->serial_table SET is_registered='yes' WHERE is_registered LIKE 'بله'";
            $serial_update_records_sql2 = "UPDATE $this->serial_table SET is_registered='no' WHERE is_registered LIKE 'خیر'";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta([$this->validation_sql]);
            dbDelta([$this->serial_sql, $serial_update_records_sql1, $serial_update_records_sql2]);
        }
        
        update_option('wpwv_db_version', $wpwv_db_version);

    }

    public static function instance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function sanitize_db() {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpwv_serials");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpwv_validations");

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta(DBStruct::instance()->get_serial_drop_sql());
        dbDelta(DBStruct::instance()->get_validation_drop_sql());
    }
}
