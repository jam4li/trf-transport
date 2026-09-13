<?php

namespace Trust\INC\Database;

global $wpwv_db_version;
$wpwv_db_version = "3.2";

class DatabaseStruct {
    private static $instance;
    public $serial_sql;
    public $validation_sql;
    public $serial_table;
    public $validation_table;

    public $serial_drop_sql;
    public $validation_drop_sql;

    public function __construct() {
        global $wpdb;
        $this->serial_table = $wpdb->prefix . 'wpwv_serials';
        $this->validation_table = $wpdb->prefix . 'wpwv_validations';

        $this->serial_sql = "CREATE TABLE {$this->serial_table} (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `serial` varchar(255) NOT NULL,
            `is_registered` varchar(255) DEFAULT 'no',
            `product` varchar(255) NOT NULL,
            `period` smallint UNSIGNED,
            `start_at` datetime,
            `end_at` datetime,
            `customer_name` varchar(255),
            `customer_phone` varchar(255),
            `customer_email` varchar(255),
            `agent` varchar(255),
            `agent_address` varchar(255),
            `agent_code` varchar(255),
            `period_unit` char,
            `order_id` varchar(255),
            PRIMARY KEY (`id`),
            CONSTRAINT Warranty_serial UNIQUE (`serial`)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci";

        $this->validation_sql = "CREATE TABLE {$this->validation_table} (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `validation` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `thumbnail_url` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE (`validation`)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci";


        $this->serial_drop_sql = "DROP TABLE IF EXISTS `$this->serial_table`";
        $this->validation_drop_sql = "DROP TABLE IF EXISTS `$this->validation_table`";
    }

    public static function instance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function get_serial_drop_sql() {
        return $this->serial_drop_sql;
    }

    public function get_validation_drop_sql() {
        return $this->validation_drop_sql;
    }

    public function get_serial_sql() {
        return $this->serial_sql;
    }

    public function get_validation_sql() {
        return $this->validation_sql;
    }
}
