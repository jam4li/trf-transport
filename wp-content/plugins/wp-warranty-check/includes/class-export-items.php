<?php

namespace Trust\INC;

class Exporter {
    private static $insatance;
    public $registered_items;
    public $unregistered_items;
    public $all_items;

    public $filename;
    public $file_obj;
    public $records;

    public function __construct() {
        $this->get_registered_items();
        $this->get_unregistered_items();
        $this->get_all_items();
    }

    public static function instance() {
        if (!self::$insatance instanceof self) {
            self::$insatance = new self;
        }
        return self::$insatance;
    }

    public function get_registered_items() {
        global $wpdb;
        $this->registered_items = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}wpwv_serials WHERE `is_registered` LIKE 'yes'"
        );
    }

    public function get_unregistered_items() {
        global $wpdb;
        $this->unregistered_items = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}wpwv_serials WHERE `is_registered` LIKE 'no'"
        );
    }

    public function get_all_items() {
        global $wpdb;
        $this->all_items = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}wpwv_serials"
        );
    }

    public function read_requested_data($export_type = 'all') {
        switch ($export_type) {
            case 'registered':
                $this->records = $this->registered_items;
                break;
            case 'unregistered':
                $this->records = $this->unregistered_items;
                break;
            default:
                $this->records = $this->all_items;
        }
    }

    public function make_export($export_type = 'all') {
        $this->read_requested_data($export_type);
        $this->init_file();
        $this->write_to_file();
        $this->download_file();
    }

    public function init_file() {
        // Init file name
        $this->filename = "trust_export_" . date("y-m-d_H-i-s") . ".csv";

        // Create file object using file_name and in write mode
        $this->file_obj = fopen(get_temp_dir() . $this->filename, "a+") or die('افزونه قادر به تولید فایل نیست. لطفا دسترسی های وردپرس را بررسی و اصلاح کنید!');

        // Clean object
        ob_end_clean();

        // Add BOM to csv file
        fputs($this->file_obj, chr(0xEF) . chr(0xBB) . chr(0xBF));
    }

    public function write_to_file() {
        // Iterate over records and write to file object
        foreach ($this->records as $record) {
            $data = [
                "serial"         => $record->serial,
                "product"        => $record->product,
                "customer_name"  => $record->customer_name,
                "customer_phone" => $record->customer_phone,
                "start_at"       => $record->start_at,
                "end_at"         => $record->end_at,
                "agent"          => $record->agent,
                "agent_address"  => $record->agent_address,
                "agent_code"     => $record->agent_code,
            ];

            // Write to file object
            fputcsv($this->file_obj, $data);

        }
        // Close file object after write
        fclose($this->file_obj);
    }

    public function download_file() {
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $this->filename);
        header("Content-Type: application/csv; charset=utf-8");
        readfile(get_temp_dir() . $this->filename);
        exit;
    }
}
