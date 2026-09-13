<?php

namespace Trust\INC\Database;

use Trust\INC\Database\DatabaseStruct as DBStruct;

require_once TRUST_INC . 'Database/class-db-structure.php';

class DatabaseActivate
{
    private static $instance;

    public const initOptions = [
        'is_agent_active'         => false,
        'is_warranty_active'      => true,
        'is_validation_active'    => true,
        'is_sms_active'           => false,
        'date_settings'           => 'jalali',
        'on_buy_sms_pattern'      => 'سلام {name} عزیز!
محصول شما ثبت گارانتی شد.
برای مشاهده وضعیت به آدرس زیر مراجعه کنید.
کد گارانتی:
        {product} - {serial}',
        'on_reg_sms_pattern'      => 'سلام {name} عزیز!
محصول شما ثبت گارانتی شد.
برای مشاهده وضعیت به آدرس زیر مراجعه کنید.
کد گارانتی:
        {product} - {serial}',
        'register_form_msg'       => "سریال {serial} معتبر است. لطفا اطلاعات خود را برای ثبت محصول وارد نمایید.",
        'register_success_msg'    => "محصول شما ثبت گارانتی شده و از تاریخ {start_at} تا {end_at} معتبر خواهد.",
        'u_register_success_msg'  => "محصول شما از تاریخ {start_at} ثبت گارانتی شد.",
        'register_err_msg'        => "ثبت کد گارانتی شما موفق بود! لطفا دوباره امتحان کنید یا با ما تماس بگیرید.",
        'invalid_serial_msg'      => "کد گارانتی {serial} نامعتبر است.",
        'invalid_validation_msg'  => "کد اعتبارسنجی نامعتبر است.",
        'back_button_field'       => "بازگشت",
        'serial_field'            => "سریال",
        'check_button_field'      => "بررسی",
        'name_field'              => "نام کامل",
        'mobile_field'            => "شماره موبایل",
        'email_field'             => "ایمیل",
        'agent_field'             => "نمایندگی گارانتی",
        'agent_address_field'     => "آدرس نمایندگی",
        'agent_code_field'        => "کد نمایندگی",
        'register_button_field'   => "ثبت",
        'show_hour'               => false,
        'product_field'           => "محصول",
        'buyer_field'             => "خریدار",
        'start_date_field'        => "تاریخ شروع گارانتی",
        'end_date_field'          => "تاریخ پایان گارانتی",
        'validation_field'        => "کد اعتبارسنجی",
        'validation_desc_field'   => "توضیحات",
        'validation_button_field' => "بررسی",
        'trust_forms_version'     => 1,
        'is_progress_bar_active'  => false,
        'remaining_warranty_time' => "زمان باقی مانده از گارانتی",
        'register_success_title'  => "ثبت موفق",
        'register_err_title'      => "ثبت نا موفق",
        'thumbnail_width'         => '300',
        'thumbnail_height'        => '300',
        'active_sms_tool'         => 'p_woo_sms',
    ];

    public const tokensArray = [
        'wpwv_lic_token'  => '',
        'wpwv_prod_token' => ''
    ];

    public function __construct()
    {
        global $wpwv_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta([
            DBStruct::instance()->get_serial_sql(),
            DBStruct::instance()->get_validation_sql()
        ]);
        update_option('wpwv_db_version', $wpwv_db_version);
    }

    public static function init()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        if (!get_option('wpwv_options')) update_option('wpwv_options', self::initOptions);
        if (!get_option('wpwv_tokens')) update_option('wpwv_tokens', self::tokensArray);
    }
}
