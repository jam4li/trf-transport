<?php

namespace Trust\Woo\Hook;

require_once TRUST_INC . 'index.php';
require_once TRUST_WOO . 'helper.php';

use Trust\Woo\Utils\Status as Helper;
use Trust\INC\DateUtils\DateGenerator as DateUtils;

final class RegisterOrder
{
    private static $instance;
    public static function init()
    {
        if ((!self::$instance instanceof self) && Helper::is_woo_active()) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_action('woocommerce_order_status_completed', [$this, 'create_warranty_instance']);
    }

    public function create_warranty_instance($order_id)
    {

        //  wc_get_order return WC_Order object
        $order = wc_get_order($order_id);

        // prevent re-creation of warranty instance
        if (get_post_meta($order_id, 'has_trust_warranty', true)) return;

        //  $items[] is an array of type WC_Order_Item
        //  WC_Order_Item extends WC_Data class
        $items = [];

        //  get_items returns []<WC_Order_Item>
        foreach ($order->get_items() as $item) {
            $product = wc_get_product($item['product_id']);
            $product->meta_exists('is_warranty_enabled')
                && $product->get_meta('is_warranty_enabled') == "1"
                ? $items = array_merge($items, array_fill(0, $item->get_quantity(), $product))
                : null;
        }

        for ($i = 0; $i < count($items); $i++) {
            $product = $items[$i];
            $serial = $order_id . '-' . $items[$i]->get_id() . '-' . $i + 1;
            $product_name = $items[$i]->get_name();
            $period = intval($items[$i]->get_meta('warranty_period'));
            $period_unit = strval($items[$i]->get_meta('period_unit'));
            $begin_period = $items[$i]->get_meta('begin_period');


            switch ($begin_period) {
                case 'on_reg':
                    self::create_on_reg($serial, $product_name, $period, $period_unit, $order_id);
                    $data['product'] = $product_name;
                    $data['serial'] = $serial;
                    if (get_option('wpwv_options')['is_sms_active']) {
                        do_action('wpwv_send_sms', get_option('wpwv_options')['on_reg_sms_pattern'], $data);
                        // Admin order note
                        $order->add_order_note(sprintf("کد گارانتی ایجاد و پیامک به شماره %s ارسال شد.", $order->get_billing_phone()));
                        // Cusomer order note
                        $order->add_order_note(sprintf("گارانتی با کد رهگیری <span dir='ltr'>%s</span> برای سفارش شما ایجاد گردید.", $serial), 1);
                    }
                    break;
                case 'on_buy':
                    $data = self::create_on_buy($order, $serial, $product_name, $period, $period_unit, $order_id);
                    $data['product'] = $product_name;
                    $data['serial'] = $serial;
                    if (get_option('wpwv_options')['is_sms_active']) {
                        do_action('wpwv_send_sms', get_option('wpwv_options')['on_buy_sms_pattern'], $data);
                        // Admin order note
                        $order->add_order_note(sprintf("کد گارانتی ایجاد و فعال گردید و پیامک به شماره %s ارسال شد.", $order->get_billing_phone()));
                        // Cusomer order note
                        $order->add_order_note(sprintf("گارانتی با کد رهگیری <span dir='ltr'>%s</span> برای سفارش شما ایجاد گردید.", $serial), 1);
                    }
                    break;
                default:
                    wp_send_json(['message' => 'operation failed!'], 400);
            }
        }

        // setting a meta data to prevent re-creation of warranty instance
        add_post_meta($order_id, 'has_trust_warranty', true, true);
    }

    public static function create_on_reg($serial = '', $product_name = '', $period = 0, $period_unit = 'm', $order_id = 0)
    {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'wpwv_serials',
            [
                'serial' => $serial,
                'product' => $product_name,
                'period' => $period,
                'period_unit' => $period_unit,
                'order_id' => $order_id
            ]
        );
    }

    /**
     * Creats a warranty instance according to woocommerce order metadata
     * 
     * @param object $order WC_Order object
     * @param string $serial Product serial
     * @param string $product_name Product name
     * @param int $period Warranty period
     * @param string $period_unit Warranty period unit; m for month, d for day
     * @param int $order_id WC_Order ID 
     * 
     * @return string[]
     */
    public static function create_on_buy($order, $serial = '', $product_name = '', $period = 0, $period_unit = 'm', $order_id = 0)
    {
        global $wpdb;

        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $customer_phone = $order->get_billing_phone();

        if (get_option('wpwv_options')['date_settings'] == 'gregorian') {
            $start_at = DateUtils::generate_g_start_date();
            $end_at = DateUtils::generate_g_end_date($period, $period_unit);
        } else {
            $start_at = DateUtils::generate_j_start_date();
            $end_at = DateUtils::generate_j_end_date($period, $period_unit);
        }

        $wpdb->insert(
            $wpdb->prefix . 'wpwv_serials',
            [
                'serial' => $serial,
                'is_registered' => 'yes',
                'product' => $product_name,
                'period' => $period,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'period_unit' => $period_unit,
                'order_id' => $order_id
            ]
        );

        return ['name' => $customer_name, 'receiver' => $customer_phone, 'start_at' => $start_at, 'end_at' => $end_at];
    }
}
