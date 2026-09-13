<?php

namespace Trust\SMS\Hook;

use SMSIRAppClass;

class SendSMS
{
    private static $instance;
    private $active_sms_tool;

    public static function init(): ?SendSMS
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        add_action('wpwv_send_sms', [$this, 'create_sms_instance'], 10, 2);
        $this->active_sms_tool = get_option('wpwv_options')['active_sms_tool'];
    }

    /**
     * Calls appropriate SMS tool to send the message
     * 
     * @param mixed[] $data Array with keys: name, receiver, product, serial
     * 
     * @return void
     */
    public function send_sms($data)
    {
        switch ($this->active_sms_tool) {
            case 'p_woo_sms':
                if (class_exists('WoocommerceIR_SMS_Helper')) \PWooSMS()->SendSMS($data);
                break;
            case 'wp_sms_pro':
                if (class_exists('WP_SMS')) wp_sms_send($data['mobile'], $data['message']);
                break;
            case 'sms_ir_app':
                if (class_exists('SMSIRAppClass')) SMSIRAppClass::sendBulkSMS($data['message'], [$data['mobile']]);
                break;
            default:
                return;
        }
    }

    /**
     * Create message text and replace shortcodes with actual data and
     * call Trust\SMS\Hook\SendSMS::send_sms with $data as parameter.
     * 
     * @param string $msgPattern Message pattern with shortcodes to be replaced with actual data
     * @param mixed[] $data has 4 keys: product, serial, name, receiver
     *  
     * @return void
     */
    public function create_sms_instance($msgPattern = '', $data = []): void
    {
        $pattern      = [
            '/\{name\}/i',
            '/\{product\}/i',
            '/\{serial\}/i',
            '/\{start_at\}/i',
            '/\{end_at\}/i',
        ];
        $replacements = [
            "{$data['name']}",
            "{$data['product']}",
            "{$data['serial']}",
            "{$data['start_at']}",
            "{$data['end_at']}",
        ];

        $msgPattern = preg_replace($pattern, $replacements, $msgPattern);
        $data       = [
            'message' => $msgPattern,
            'mobile'  => $data['receiver']
        ];
        $this->send_sms($data);
    }
}
