<?php

namespace Trust\Woo\Utils;

final class Status {
    //  public function __construct() {}
    public static function is_woo_active(): bool {
        /**
         * Check if WooCommerce is activated
         */
        if ( class_exists( 'woocommerce' ) ) {
            return true;
        }

        return false;
    }
}
