<?php

namespace Trust;

use Trust\INC\Functions as Functions;
use Trust\INC\Shortcodes as Shortcodes;
use Trust\INC\TrustLoader as TrustLoader;
use Trust\INC\AdminSections as AdminSections;
use Trust\INC\Database\MigrateDB as MigrateDB;
use Trust\INC\Database\DatabaseActivate as ActivateDB;
use Trust\INC\Database\DatabaseStruct;
// use Trust\Email\InitTrustEmailIntegration;
use Trust\SMS\InitTrustSMSIntegration;
use Trust\Woo\InitTrustWooIntegration;

/**
 *   Plugin Name: افزونه گارانتی Trust
 *   Plugin URI: https://www.zhaket.com/web/trust-wordpress-plugin
 *   Description: تراست جهت استعلام گارانتی و لایسنس تحت سیستم وردپرس طراحی شده است!
 *   Author: AxiosIO
 *   Author URI: https://www.zhaket.com/store/web/mojtabakh
 *   Text domain: wpwv
 *   Domain Path: /languages
 *   Version: 4.0.3
 */

defined('ABSPATH') || exit;

define('TRUST_DIR', plugin_dir_path(__FILE__));
define('TRUST_URL', plugin_dir_url(__FILE__));
define('TRUST_INC', TRUST_DIR . 'includes/');
define('TRUST_TPL', TRUST_DIR . 'templates/');
define('TRUST_WOO', TRUST_DIR . 'woocommerce/');
define('TRUST_SMS', TRUST_DIR . 'sms/');
// deprectaed current email funtionality as it's not async and blocks processes
// define('TRUST_EMAIL', TRUST_DIR . 'email/');

include_once __DIR__.'/ion-checker.php';
if (!empty($ioncube_error_checker)){
    add_action('admin_notices', function () use ($ioncube_error_checker){printf('<div class="notice notice-error notice-alt"> <p>%s</p> </div>',implode('<hr>',$ioncube_error_checker));},1);
    return;
}

class TrustWarrantyPlugin
{
    protected $plugin_name;
    protected $version;
    protected $db_version;
    protected $loader;
    protected $functions;
    protected $shortcodes;

    private static $instance;

    public function get_version()
    {
        return $this->version;
    }

    public function get_db_version()
    {
        return $this->db_version;
    }

    public static function instance()
    {
        return self::$instance;
    }

    public function utils()
    {
        return $this->functions;
    }

    public function run_activation_hook()
    {
        ActivateDB::init();
    }

    public function run_upgrade_hook()
    {
        global $wpwv_db_version;

        if (is_null(get_option('wpwv_db_version')) || get_site_option('wpwv_db_version') != $wpwv_db_version) {
            MigrateDB::instance()->run_migration();
        }
    }

    public static function make()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function init()
    {
        global $wpwv_db_version;
        $this->plugin_name = 'افزونه گارانتی Trust';
        $this->version = '4.0.3';
        $this->db_version = $wpwv_db_version;

        $this->loader = new TrustLoader();
        $this->functions = new Functions();
        DatabaseStruct::instance();

        register_activation_hook(__FILE__, [$this, 'run_activation_hook']);

        // register primary hooks
        $this->loader->add_action('plugins_loaded', [$this, 'run_upgrade_hook'], 10, 3);
        $this->loader->add_action('admin_init', 'Trust\INC\Database\MigrateDB::instance', 10, 3);
        $this->loader->add_action('admin_enqueue_scripts', [$this->functions, 'load_admin_assets'], 10, 3);

        /**
         * Since v4.0.0
         * load correct scripts according to enabled form version
         */
        $options = get_option('wpwv_options');
        if ($options['trust_forms_version'] == '1') {
            $this->loader->add_action('wpwv_enqueue_scripts', [$this->functions, 'load_frontend_assets'], 10, 3);
        } else if ($options['trust_forms_version'] == '2') {
            if ($options['is_warranty_active']) {
                $this->loader->add_action('wpwv_enqueue_warranty_scripts', [$this->functions, 'load_warranty_frontend_assets'], 10, 3);
            }
            if ($options['is_validation_active']) {
                $this->loader->add_action('wpwv_enqueue_validation_scripts', [$this->functions, 'load_validation_frontend_assets'], 10, 3);
            }
        }

        // register manual hooks for db actions
        $this->loader->add_action('manual_db_migration', 'Trust\INC\Database\MigrateDB::instance', 10, 3);
        $this->loader->add_action('manual_db_sanitize', 'Trust\INC\Database\MigrateDB::sanitize_db', 10, 3);

        // register ajax call handlers for warranty
        $this->loader->add_action('wp_ajax_nopriv_wck_lookup', [$this->functions, 'warranty_lookup'], 10, 3);
        $this->loader->add_action('wp_ajax_wck_lookup', [$this->functions, 'warranty_lookup'], 10, 3);
        $this->loader->add_action('wp_ajax_nopriv_wck_register', [$this->functions, 'warranty_register'], 10, 3);
        $this->loader->add_action('wp_ajax_wck_register', [$this->functions, 'warranty_register'], 10, 3);

        // register ajax call handlers for validation
        $this->loader->add_action('wp_ajax_nopriv_vck_lookup', [$this->functions, 'validation_lookup'], 10, 3);
        $this->loader->add_action('wp_ajax_vck_lookup', [$this->functions, 'validation_lookup'], 10, 3);

        // register ajax handlers for admin actions
        $this->loader->add_action('wp_ajax_delete_single_validation', [$this->functions, 'delete_single_validation'], 10, 3);
        $this->loader->add_action('wp_ajax_delete_multiple_validations', [$this->functions, 'delete_multiple_validations'], 10, 3);
        $this->loader->add_action('wp_ajax_delete_single_warranty', [$this->functions, 'delete_single_warranty'], 10, 3);
        $this->loader->add_action('wp_ajax_delete_multiple_warranties', [$this->functions, 'delete_multiple_warranties'], 10, 3);

        $this->loader->add_action('wp_ajax_get_validation_items', [$this->functions, 'get_validation_items'], 10, 3);
        $this->loader->add_action('wp_ajax_nopriv_get_validation_items', [$this->functions, 'get_validation_items'], 10, 3);

        $this->loader->add_action('wp_ajax_get_warranty_items', [$this->functions, 'get_warranty_items'], 10, 3);
        $this->loader->add_action('wp_ajax_nopriv_get_warranty_items', [$this->functions, 'get_warranty_items'], 10, 3);

        // load plugin
        $this->loader->run();
    }

    public function load_essentials()
    {
        require_once TRUST_INC . 'functions.php';
        require_once TRUST_INC . 'class-trust-loader.php';
        require_once TRUST_INC . 'class-ajax-calls-proxy.php';
        require_once TRUST_INC . 'Database/class-db-migrate.php';
        require_once TRUST_INC . 'Database/class-db-activate.php';
    }

    public function load_dependencies()
    {
        require_once TRUST_INC . 'class-shortcodes.php';
        require_once TRUST_INC . 'class-ajax.php';

        include_once TRUST_DIR . "woocommerce/index.php";
        include_once TRUST_DIR . "sms/index.php";
        // include_once TRUST_DIR . "email/index.php";

        // init plugin integrations
        new InitTrustWooIntegration();
        new InitTrustSMSIntegration();
        // new InitTrustEmailIntegration();


        $shortcodes = new Shortcodes();
        $this->add_shortcode('warranty_check_lookup', [$shortcodes, 'warranty_check_form']);
        $this->add_shortcode('validation_check_lookup', [$shortcodes, 'validation_check_form']);
        $this->register_shortcodes();

        if (is_admin()) {
            require_once TRUST_INC . 'class-admin.php';
            AdminSections::instance();
        }
    }

    public function add_shortcode($handle, $callback)
    {
        $this->shortcodes[] = [
            'handle' => $handle,
            'callback' => $callback
        ];
    }

    public function register_shortcodes()
    {
        foreach ($this->shortcodes as $shortcode) {
            add_shortcode($shortcode['handle'], $shortcode['callback']);
        }
    }

    public function run()
    {
        $this->load_essentials();
        $this->init();
        $this->load_dependencies();
    }
}

$plugin = TrustWarrantyPlugin::make();
$plugin->run();
