<?php

namespace Trust\INC;

require_once TRUST_INC . 'class-export-items.php';

use Trust\INC\Helper as Helper;
use Trust\INC\Exporter as Exporter;

class AdminSections
{
    private static $instance;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menus']);
    }

    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function register_menus()
    {
        add_menu_page(
            'تنظیمات گارانتی و اعتبارسنجی',
            'تراست',
            'manage_options',
            'wpwv_settings',
            'Trust\INC\AdminMenuHandlers::settings'
        );

        if (get_option('wpwv_options')['is_validation_active']) {
            add_submenu_page(
                'wpwv_settings',
                'مدیریت اعتبارسنجی وردپرس',
                'همه اعتبارسنجی ها',
                'manage_options',
                'wpv_overview',
                'Trust\INC\AdminMenuHandlers::validation_overview'
            );
            add_submenu_page(
                'wpwv_settings',
                'افزودن سریال اعتبارسنجی',
                'افزودن اعتبارسنجی جدید',
                'manage_options',
                'wpv_add_validation',
                'Trust\INC\AdminMenuHandlers::add_validation'
            );
        }

        if (get_option('wpwv_options')['is_warranty_active']) {
            add_submenu_page(
                'wpwv_settings',
                'تراست | گارانتی وردپرس',
                'همه گارانتی ها',
                'manage_options',
                'wpw_overview',
                'Trust\INC\AdminMenuHandlers::warranty_overview'
            );
            add_submenu_page(
                'wpwv_settings',
                'افزودن سریال گارانتی جدید',
                'افزودن گارانتی جدید',
                'manage_options',
                'wpw_add_serial',
                'Trust\INC\AdminMenuHandlers::add_warranty'
            );
        }

        add_submenu_page(
            'wpwv_settings',
            'درون ریزی افزونه گارانتی وردپرس',
            'درون ریزی',
            'manage_options',
            'wpwv_import',
            'Trust\INC\AdminMenuHandlers::import'
        );
        add_submenu_page(
            'wpwv_settings',
            'تراست | اعتبارسنجی وردپرس',
            'برون بری',
            'manage_options',
            'wpvi_export',
            'Trust\INC\AdminMenuHandlers::export'
        );
    }
}

class AdminMenuHandlers
{
    public static function settings()
    {
        // run the manual database actions if called by user
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'sanitize_db':
                    do_action('manual_db_sanitize');
                    break;
                case 'migrate_db':
                    do_action('manual_db_migration');
                    break;
                default:
                    break;
            }
        }

        //  update tokens
        if (isset($_POST['saveTokens'])) {
            Helper::init_activation($_POST['license_token']);
        }

        //  update plugin options if ['saveData'] is present on $_POST global
        if (isset($_POST['saveData'])) {
            $optionsArray = [
                'show_hour'               => isset($_POST['show_hour']),
                'is_sms_active'           => isset($_POST['is_sms_active']),
                'is_agent_active'         => isset($_POST['is_agent_active']),
                'date_settings'           => $_POST['date_settings'] ?? 'jalali',
                'is_warranty_active'      => isset($_POST['is_warranty_active']),
                'is_validation_active'    => isset($_POST['is_validation_active']),
                'is_progress_bar_active'  => isset($_POST['is_progress_bar_active']),
                'name_field'              => isset($_POST['name_field']) ? sanitize_text_field($_POST['name_field']) : null,
                'email_field'             => isset($_POST['email_field']) ? sanitize_text_field($_POST['email_field']) : null,
                'agent_field'             => isset($_POST['agent_field']) ? sanitize_text_field($_POST['agent_field']) : null,
                'buyer_field'             => isset($_POST['buyer_field']) ? sanitize_text_field($_POST['buyer_field']) : null,
                'serial_field'            => isset($_POST['serial_field']) ? sanitize_text_field($_POST['serial_field']) : null,
                'mobile_field'            => isset($_POST['mobile_field']) ? sanitize_text_field($_POST['mobile_field']) : null,
                'product_field'           => isset($_POST['product_field']) ? sanitize_text_field($_POST['product_field']) : null,
                'end_date_field'          => isset($_POST['end_date_field']) ? sanitize_text_field($_POST['end_date_field']) : null,
                'thumbnail_width'         => isset($_POST['thumbnail_width']) ? sanitize_text_field($_POST['thumbnail_width']) : null,
                'active_sms_tool'         => isset($_POST['active_sms_tool']) ? sanitize_text_field($_POST['active_sms_tool']) : null,
                'thumbnail_height'        => isset($_POST['thumbnail_height']) ? sanitize_text_field($_POST['thumbnail_height']) : null,
                'start_date_field'        => isset($_POST['start_date_field']) ? sanitize_text_field($_POST['start_date_field']) : null,
                'validation_field'        => isset($_POST['validation_field']) ? sanitize_text_field($_POST['validation_field']) : null,
                'agent_code_field'        => isset($_POST['agent_code_field']) ? sanitize_text_field($_POST['agent_code_field']) : null,
                'register_err_msg'        => isset($_POST['register_err_msg']) ? sanitize_textarea_field($_POST['register_err_msg']) : null,
                'check_button_field'      => isset($_POST['check_button_field']) ? sanitize_text_field($_POST['check_button_field']) : null,
                'back_button_field'       => isset($_POST['back_button_field']) ? sanitize_textarea_field($_POST['back_button_field']) : null,
                'trust_forms_version'     => isset($_POST['trust_forms_version']) ? sanitize_text_field($_POST['trust_forms_version']) : null,
                'agent_address_field'     => isset($_POST['agent_address_field']) ? sanitize_text_field($_POST['agent_address_field']) : null,
                'register_form_msg'       => isset($_POST['register_form_msg']) ? sanitize_textarea_field($_POST['register_form_msg']) : null,
                'on_buy_sms_pattern'      => isset($_POST['on_buy_sms_pattern']) ? sanitize_textarea_field($_POST['on_buy_sms_pattern']) : null,
                'on_reg_sms_pattern'      => isset($_POST['on_reg_sms_pattern']) ? sanitize_textarea_field($_POST['on_reg_sms_pattern']) : null,
                'invalid_serial_msg'      => isset($_POST['invalid_serial_msg']) ? sanitize_textarea_field($_POST['invalid_serial_msg']) : null,
                'register_err_title'      => isset($_POST['register_err_title']) ? sanitize_textarea_field($_POST['register_err_title']) : null,
                'register_button_field'   => isset($_POST['register_button_field']) ? sanitize_text_field($_POST['register_button_field']) : null,
                'validation_desc_field'   => isset($_POST['validation_desc_field']) ? sanitize_text_field($_POST['validation_desc_field']) : null,
                'invalid_validation_msg'  => isset($_POST['invalid_validation_msg']) ? sanitize_text_field($_POST['invalid_validation_msg']) : null,
                'register_success_msg'    => isset($_POST['register_success_msg']) ? sanitize_textarea_field($_POST['register_success_msg']) : null,
                'validation_button_field' => isset($_POST['validation_button_field']) ? sanitize_text_field($_POST['validation_button_field']) : null,
                'remaining_warranty_time' => isset($_POST['remaining_warranty_time']) ? sanitize_text_field($_POST['remaining_warranty_time']) : null,
                'register_success_title'  => isset($_POST['register_success_title']) ? sanitize_textarea_field($_POST['register_success_title']) : null,
                'u_register_success_msg'  => isset($_POST['u_register_success_msg']) ? sanitize_textarea_field($_POST['u_register_success_msg']) : null,
            ];
            // 'is_admin_email_active'   => isset($_POST['is_admin_email_active']),
            //     'is_customer_email_active' => isset($_POST['is_customer_email_active']),
            //     'email_title'             => isset($_POST['email_title']) ? sanitize_text_field($_POST['email_title']) : null,
            //     'admin_email_pattern'     => isset($_POST['admin_email_pattern']) ? sanitize_textarea_field($_POST['admin_email_pattern']) : null,
            //     'customer_email_pattern'  => isset($_POST['customer_email_pattern']) ? sanitize_textarea_field($_POST['customer_email_pattern']) : null,
            update_option('wpwv_options', $optionsArray);
            echo '<script>location.reload()</script>';
        }

        include TRUST_TPL . 'admin/panel/settings.php';
    }

    public static function validation_overview()
    {
        global $wpdb;

        //  logic to load details of a specific validation if action param is set
        if (isset($_GET['action']) && $_GET['action'] == 'edit') {

            // alter validation record
            if (isset($_POST['saveDetails']) && isset($_GET['id'])) {
                $gallery_fields = ValidationGallery::db_fields_from_urls(ValidationGallery::urls_from_request());
                $result = $wpdb->update(
                    $wpdb->prefix . "wpwv_validations",
                    array_merge(
                        ['description' => sanitize_textarea_field($_POST['description'])],
                        $gallery_fields
                    ),
                    ['id' => $_GET['id']]
                );

                if (gettype($result) == 'integer') {
                    $msg = 'تغییرات اعمال شد!';
                    $status = 'success';
                } else {
                    $msg = 'خطایی در اعمال تغییرات رخ داد!';
                    $status = 'error';
                }
            }

            include TRUST_TPL . 'admin/validation-details.php';
            return;
        }

        //  logic to delete a specific validation if action param is set
        // if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        //     $wpdb->delete(
        //         $wpdb->prefix . 'wpwv_validations',
        //         ['id' => $_GET['id']]
        //     );
        // }

        include TRUST_TPL . 'admin/validation-overview.php';
    }

    public static function add_validation()
    {
        global $wpdb;

        // handle new validation request and throw a response
        if (isset($_POST['saveData'])) {
            $validation  = sanitize_text_field($_POST['validation']);
            $description = sanitize_textarea_field($_POST['description']);
            $gallery_fields = ValidationGallery::db_fields_from_urls(ValidationGallery::urls_from_request());
            $result      = $wpdb->insert(
                $wpdb->prefix . 'wpwv_validations',
                array_merge(
                    [
                        'validation'  => $validation,
                        'description' => $description,
                    ],
                    $gallery_fields
                )
            );

            if (gettype($result) == 'integer') {
                $msg    = 'با موفقیت اضافه شد!';
                $status = 'success';
            } else {
                $msg    = 'خطایی رخ داد!';
                $status = 'error';
            }
        }

        include TRUST_TPL . 'admin/add-validation.php';
    }

    public static function warranty_overview()
    {
        global $wpdb;

        //  logic to load details of a specific warranty if action param is set
        if (
            isset($_GET['action'])
            && $_GET['action'] == 'edit'
            && isset($_GET['id'])
        ) {
            //  alter serial record
            if (isset($_POST['saveDetails'])) {
                $is_agent_active = get_option('wpwv_options')['is_agent_active'];
                switch ($is_agent_active) {
                    case true:
                        $result = $wpdb->update(
                            $wpdb->prefix . "wpwv_serials",
                            [
                                'is_registered'  => sanitize_text_field($_POST['is_registered']),
                                'customer_name'  => sanitize_text_field($_POST['customer_name']),
                                'customer_phone' => sanitize_text_field($_POST['customer_phone']),
                                'start_at'       => sanitize_text_field($_POST['start_at']),
                                'end_at'         => sanitize_text_field($_POST['end_at']),
                                'agent'          => sanitize_text_field($_POST['agent']),
                                'agent_address'  => sanitize_text_field($_POST['agent_address']),
                                'agent_code'     => sanitize_text_field($_POST['agent_code'])
                            ],
                            [
                                'serial' => sanitize_text_field($_POST['serial'])
                            ]
                        );
                        break;
                    case false:
                        $result = $wpdb->update(
                            $wpdb->prefix . "wpwv_serials",
                            [
                                'is_registered'  => sanitize_text_field($_POST['is_registered']),
                                'customer_name'  => sanitize_text_field($_POST['customer_name']),
                                'customer_phone' => sanitize_text_field($_POST['customer_phone']),
                                'start_at'       => sanitize_text_field($_POST['start_at']),
                                'end_at'         => sanitize_text_field($_POST['end_at'])
                            ],
                            [
                                'serial' => sanitize_text_field($_POST['serial'])
                            ]
                        );
                        break;
                    default:
                        $result = false;
                }

                // throw a response based on the query result
                if (gettype($result) == 'integer') {
                    $msg    = 'تغییرات اعمال شد!';
                    $status = 'success';
                } else {
                    $msg    = 'خطایی رخ داد. اطمینان حاصل کنید که تمامی موارد به درستی وارد شده و سریال تکراری نباشد.';
                    $status = 'error';
                }
            }

            include TRUST_TPL . 'admin/serial-details.php';
            return;
        }

        //  logic to delete a specific warranty if action param is set
        if (isset($_GET['action']) && $_GET['action'] == 'delete') {
            $wpdb->delete(
                $wpdb->prefix . 'wpwv_serials',
                ['id' => $_GET['id']]
            );
        }

        include TRUST_TPL . 'admin/serials-overview.php';
    }

    public static function add_warranty()
    {
        global $wpdb;

        if (isset($_POST['saveData'])) {
            $serial      = sanitize_text_field($_POST['serial']);
            $product     = sanitize_text_field($_POST['product']);
            $period      = sanitize_text_field($_POST['period']);
            $period_unit = sanitize_text_field($_POST['period_unit']);
            $result      = $wpdb->insert(
                $wpdb->prefix . 'wpwv_serials',
                [
                    'serial'      => $serial,
                    'product'     => $product,
                    'period'      => $period,
                    'period_unit' => $period_unit
                ]
            );
            if (gettype($result) == 'integer') {
                $msg    = 'با موفقیت اضافه شد!';
                $status = 'success';
            } else {
                $msg    = 'خطایی رخ داد!';
                $status = 'error';
            }
        }

        include TRUST_TPL . 'admin/add-serial.php';
    }

    public static function import()
    {
        $options = get_option('wpwv_options');
        include TRUST_TPL . 'admin/page-import-serials.php';
    }

    public static function export()
    {
        if (isset($_GET['action'])) {
            $exporter = Exporter::instance();
            switch ($_GET['action']) {
                case 'export_trust_registered_items':
                    $exporter->make_export('registered');
                    break;
                case 'export_trust_unregistered_items':
                    $exporter->make_export('unregistered');
                    break;
                case 'export_trust_all_items':
                    $exporter->make_export('all');
                    break;
                default:
                    die();
            }
        }
        include TRUST_TPL . 'admin/page-export-items.php';
    }
}
