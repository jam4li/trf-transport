<?php

namespace Trust\INC;

class Importer {
    public static $insatance;
    public $filename;
    public $file_path;
    public $ext;

    public $file_obj;

    public $error;
    public $rows_inserted;
    public $duplicate_rows;

    public function __construct($file_info) {
        $this->filename = $file_info['name'];
        $this->file_path = $file_info['tmp_name'];
        $this->ext = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
    }

    public static function instance($file_info) {
        if (!self::$insatance instanceof self) {
            self::$insatance = new self($file_info);
        }
        return self::$insatance;
    }

    public function make_import($import_type) {
        $this->read_file_and_insert($import_type);
    }

    public function init_file() {
        if (!is_null($this->filename) && $this->ext == 'csv') $this->file_obj = fopen($this->file_path, 'r');
        else {
            $this->error = 'فرمت یا نام فایل ارسال شده غیر مجاز است!';
            return false;
        }
    }

    public function read_file_and_insert($import_type) {
        $this->init_file();

        if (!is_null($this->file_obj)) {
            if ($import_type == 'warranty') $this->insert_warranty();
            if ($import_type == 'validation') $this->insert_validation();

            fclose($this->file_obj);
        }
    }

    public function insert_warranty() {
        global $wpdb;
        while ($row = fgetcsv($this->file_obj)) {
            $data_len = count($row);
            if (!($data_len == 4)) continue;

            $serial       = trim($row[0]);
            $product_name = trim($row[1]);
            $period       = trim($row[2]);
            $period_unit  = trim($row[3]);

            //  check if data is correct
            if (!empty($serial) && !empty($product_name) && !empty($period_unit) && is_numeric($period)) {
                //  check if record does not exist already
                $check_result = $wpdb->get_results(
                    "SELECT id FROM {$wpdb->prefix}wpwv_serials WHERE serial = '$serial'"
                );
                if (!isset($check_result[0]->id)) {
                    $wpdb->insert(
                        $wpdb->prefix . "wpwv_serials",
                        [
                            'serial'      => $serial,
                            'product'     => $product_name,
                            'period'      => $period,
                            'period_unit' => $period_unit
                        ]
                    );
                    $this->rows_inserted++;
                } else $this->duplicate_rows++;
            }
        }
    }

    public function insert_validation() {
        global $wpdb;
        while ($row = fgetcsv($this->file_obj)) {
            $data_len = count($row);
            if (!($data_len == 3 || $data_len == 2)) continue;

            $validation     = trim($row[0]);
            $description    = trim($row[1]);
            $images_raw     = isset($row[2]) ? trim($row[2]) : '';
            // Multiple images: separate with | in the 3rd CSV column
            $gallery_urls   = ValidationGallery::normalize_urls(array_filter(array_map('trim', explode('|', $images_raw))));
            $gallery_fields = ValidationGallery::db_fields_from_urls($gallery_urls);

            //  check if data is correct
            if (!empty($validation) && !empty($description)) {
                //  check if record does not exist already
                $check_result = $wpdb->get_results(
                    "SELECT id FROM {$wpdb->prefix}wpwv_validations WHERE validation = '$validation'"
                );
                if (!isset($check_result[0]->id)) {
                    $wpdb->insert(
                        $wpdb->prefix . "wpwv_validations",
                        array_merge(
                            [
                                'validation'    => $validation,
                                'description'   => $description,
                            ],
                            $gallery_fields
                        )
                    );
                    $this->rows_inserted++;
                } else $this->duplicate_rows++;
            }
        }
    }
}
