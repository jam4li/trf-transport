<?php

namespace Trust\INC;

use Trust\INC\DateUtils\DateGenerator as DateUtils;

require_once TRUST_INC . 'DateUtils/class-date-utils.php';

class Utils {
    public static function updateRecordBySerial($serial, $data) {
        global $wpdb;
        return $wpdb->update(
            $wpdb->prefix . "wpwv_serials",
            $data,
            ['serial' => $serial]
        );
    }

    public static function makeQueryBySerialOn(string $serial, string $queries) {
        global $wpdb;
        $query = "SELECT {$queries} FROM {$wpdb->prefix}wpwv_serials WHERE serial = '{$serial}'";
        return $wpdb->get_results($query)[0];
    }

    public static function makeQueryByValidationOn(string $validation, string $queries) {
        global $wpdb;
        $query = "SELECT {$queries} FROM {$wpdb->prefix}wpwv_validations WHERE validation = '{$validation}'";
        return $wpdb->get_results($query)[0];
    }
}

interface AJAXCallsInterface {
    public function lookup();
    public function register();
    public function delete_single_record();
    public function delete_multiple_records();
}

abstract class AJAXCalls implements AJAXCallsInterface {
    protected $uid;
    protected $uids;
    protected $options;
    protected $query_result;

    public function send_response($response) {
        wp_send_json($response, 200);
    }
}

class WarrantyAJAXCalls extends AJAXCalls {
    protected $replacements;
    protected $pattern;

    public function __construct() {
        if (isset($_POST['uids'])) $this->uid = sanitize_text_field($_POST['uids']);
        $this->pattern = [
            '/\{serial\}/i',
            '/\{start_at\}/i',
            '/\{end_at\}/i'
        ];
    }
    
    public function lookup() {
        $this->options = get_option('wpwv_options');
        $this->uid = sanitize_text_field($_POST['serial']);
        $this->query_result = Utils::makeQueryBySerialOn($this->uid, '*');
        $result = $this->query_result !== null ? 'ok' : 'fail';
        $this->replacements = [
            "{$this->uid}"
        ];

        $register_form_msg = $this->options['register_form_msg'];
        $register_form_msg = preg_replace(
            $this->pattern,
            $this->replacements,
            $register_form_msg
        );

        $invalid_serial_msg = $this->options['invalid_serial_msg'];
        $invalid_serial_msg = preg_replace($this->pattern, $this->replacements, $invalid_serial_msg);

        if ($result == 'ok') {
            switch (strval($this->query_result->is_registered)) {
                case 'yes':
                    $this->send_response([
                        'status'           => $result,
                        'serial'           => "{$this->uid}",
                        'msg'              => "{$this->uid}",
                        'product'          => $this->query_result->product,
                        'start_at'         => $this->query_result->start_at,
                        'end_at'           => $this->query_result->end_at,
                        'customer_name'    => $this->query_result->customer_name,
                        'customer_phone'   => $this->query_result->customer_phone,
                        'agent'            => $this->query_result->agent,
                        'agent_address'    => $this->query_result->agent_address,
                        'agent_code'       => $this->query_result->agent_code,
                        'do_registeration' => false
                    ]);
                    break;

                case 'no':
                    $this->send_response([
                        'status'           => $result,
                        'serial'           => "{$this->uid}",
                        'msg'              => "{$register_form_msg}",
                        'do_registeration' => true
                    ]);
                    break;

                default:
                    $this->send_response([
                        'status'           => $result,
                        'msg'              => "خطایی ناشناخته رخ داده است!",
                        'do_registeration' => false
                    ]);
                    break;
            }
        } else {
            $this->send_response([
                'status'           => $result,
                'serial'           => "{$this->uid}",
                'msg'              => $invalid_serial_msg,
                'do_registeration' => false
            ]);
        }
    }

    public function register() {
        $this->options = get_option('wpwv_options');
        $is_agent_active = $this->options['is_agent_active'];

        $serial = sanitize_text_field($_POST['serial']);
        $customer_name = sanitize_text_field($_POST['customer_name']);
        $customer_phone = sanitize_text_field($_POST['customer_phone']);
        // $customer_email = sanitize_text_field($_POST['customer_email']);

        $query_result = Utils::makeQueryBySerialOn($serial, 'product,period,period_unit');
        $period = intval($query_result->period);
        $period_unit = $query_result->period_unit;
        $product = $query_result->product;

        if ($this->options['date_settings'] == 'gregorian') {
            $start_at = DateUtils::generate_g_start_date();
            $end_at = DateUtils::generate_g_end_date($period, $period_unit);
        } else {
            $start_at = DateUtils::generate_j_start_date();
            $end_at = DateUtils::generate_j_end_date($period, $period_unit);
        }

        $date_pattern = "/[-\s:]/";
        $start_date_components = preg_split($date_pattern, $start_at);
        $end_date_components = preg_split($date_pattern, $end_at);
        $start_date_reverse = "{$start_date_components[2]}-{$start_date_components[1]}-{$start_date_components[0]}";
        $end_date_reverse = "{$end_date_components[2]}-{$end_date_components[1]}-{$end_date_components[0]}";

        $this->replacements = [
            "{$this->uid}",
            "{$start_date_reverse}",
            "{$end_date_reverse}"
        ];

        if (intval($period) == 0) {
            $success_msg = $this->options['u_register_success_msg'];
            $success_msg = preg_replace($this->pattern, $this->replacements, $success_msg);
        } else {
            $success_msg = $this->options['register_success_msg'];
            $success_msg = preg_replace($this->pattern, $this->replacements, $success_msg);
        }

        switch ($is_agent_active) {
            case true:
                $result = Utils::updateRecordBySerial(
                    $serial,
                    [
                        'is_registered' => 'yes',
                        'customer_name' => $customer_name,
                        'customer_phone' => $customer_phone,
                        'start_at' => $start_at,
                        'end_at' => $end_at,
                        'agent' => sanitize_text_field($_POST['agent']),
                        'agent_address' => sanitize_text_field($_POST['agent_address']),
                        'agent_code' => sanitize_text_field($_POST['agent_code'])
                    ]
                    // 'customer_email' => $customer_email,
                );
                break;
            case false:
                $result = Utils::updateRecordBySerial(
                    $serial,
                    [
                        'is_registered' => 'yes',
                        'customer_name' => $customer_name,
                        'customer_phone' => $customer_phone,
                        'start_at' => $start_at,
                        'end_at' => $end_at
                    ]
                    // 'customer_email' => $customer_email,
                );
                break;
            default:
                $result = false;
                break;
        }

        switch (gettype($result)) {
            case "integer":
                // registration was successful
                $options = get_option('wpwv_options');

                if ($options['is_sms_active']) {
                    if (preg_match("/(0000-00-00)/", $end_at)) {
                        $end_at = "گارانتی نامحدود";
                    }
                    $data = [
                        'name' => $customer_name,
                        'product' => $product,
                        'serial' => $serial,
                        'start_at' => $start_at,
                        'end_at' => $end_at,
                        'receiver' => $customer_phone,
                    ];
                    do_action('wpwv_send_sms', get_option('wpwv_options')['on_reg_sms_pattern'], $data);
                }

                // deprecated current email funtionality as it is a blocking process
                // if ($options['is_admin_email_active']) {
                //     $data = [
                //         'name' => $customer_name,
                //         'product' => $product,
                //         'serial' => $serial
                //     ];
                //     do_action('wpwv_send_admin_email', $data);
                // }

                // if ($options['is_customer_email_active']) {
                //     $data = [
                //         'name' => $customer_name,
                //         'product' => $product,
                //         'serial' => $serial
                //     ];
                //     do_action('wpwv_send_customer_email', $data, sanitize_text_field($_POST['customer_email']));
                // }

                $this->send_response([
                    'status' => 'ok',
                    'msg' => $success_msg
                ]);
                break;

            case "boolean":
                // registration failed
                $this->send_response([
                    'status' => 'fail',
                    'msg' => 'ثبت محصول انجام نشد!'
                ]);
                break;

            default:
                // unknown error
                $this->send_response([
                    'status' => $result,
                    'msg' => 'خطایی ناشناخته رخ داد. لطفا با مدیر سایت تماس بگیرید!'
                ]);
                break;
        }
    }

    public function delete_single_record() {
        global $wpdb;
        $this->uid = sanitize_text_field($_POST['uid']);
        $result = $wpdb->delete(
            "{$wpdb->prefix}wpwv_serials",
            ['id' => $this->uid]
        );
        $this->send_response(['status' => $result]);
    }

    public function delete_multiple_records() {
        global $wpdb;
        $this->uids = $_POST['uids'];
        $result = $wpdb->query(
            "DELETE FROM {$wpdb->prefix}wpwv_serials WHERE `id` IN ('" . implode("','", $this->uids) . "')"
        );
        $this->send_response(['status' => $result]);
    }
}

class ValidationAJAXCalls extends AJAXCalls {
    public function lookup() {
        $this->uid   = sanitize_text_field($_POST['validation']);
        $this->query_result = Utils::makeQueryByValidationOn($this->uid, 'description,thumbnail_url,gallery_urls');
        $result = $this->query_result !== null ? 'ok' : 'fail';

        if ($result === 'ok') {
            $gallery = ValidationGallery::gallery_from_row($this->query_result);
            $this->send_response([
                'status'        => $result,
                'description'   => $this->query_result->description,
                'thumbnail_url' => $gallery[0] ?? '',
                'gallery'       => $gallery,
                'validation'    => $this->uid
            ]);
        } else $this->send_response([
            'status' => $result,
            'description' => get_option('wpwv_options')['invalid_validation_msg']
        ]);
    }

    public function register() {
    }

    public function delete_single_record() {
        global $wpdb;
        $this->uid = sanitize_text_field($_POST['uid']);
        $result = $wpdb->delete(
            "{$wpdb->prefix}wpwv_validations",
            ['id' => $this->uid]
        );
        $this->send_response(['status' => $result]);
    }

    public function delete_multiple_records() {
        global $wpdb;
        $this->uids = $_POST['uids'];
        $result = $wpdb->query(
            "DELETE FROM {$wpdb->prefix}wpwv_validations WHERE `id` IN ('" . implode("','", $this->uids) . "')"
        );
        $this->send_response(['status' => $result]);
    }
}
