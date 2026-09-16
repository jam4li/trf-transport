<?php

namespace Trust\INC;

require_once TRUST_INC . 'License/class-license.php';

class Functions
{
    protected $options;
    protected $w_localized_variants;
    protected $v_localized_variants;

    public function __construct()
    {
        $this->options = get_option('wpwv_options');
        $this->w_localized_variants = [
            'handle_uri'             => admin_url('admin-ajax.php'),
            'is_agent_active'        => $this->options['is_agent_active'],
            'register_form_msg'      => $this->options['register_form_msg'],
            'register_err_msg'       => $this->options['register_err_msg'],
            'invalid_serial_msg'     => $this->options['invalid_serial_msg'],
            'back_button_field'      => $this->options['back_button_field'],
            'serial_field'           => $this->options['serial_field'],
            'check_button_field'     => $this->options['check_button_field'],
            'name_field'             => $this->options['name_field'],
            'mobile_field'           => $this->options['mobile_field'],
            'email_field'            => $this->options['email_field'],
            'agent_field'            => $this->options['agent_field'],
            'agent_address_field'    => $this->options['agent_address_field'],
            'agent_code_field'       => $this->options['agent_code_field'],
            'register_button_field'  => $this->options['register_button_field'],
            'show_hour'              => $this->options['show_hour'],
            'product_field'          => $this->options['product_field'],
            'buyer_field'            => $this->options['buyer_field'],
            'start_date_field'       => $this->options['start_date_field'],
            'end_date_field'         => $this->options['end_date_field'],
            'is_progress_bar_active' => $this->options['is_progress_bar_active'],
            'remaining_warranty_time' => $this->options['remaining_warranty_time'],
            'register_success_title' => $this->options['register_success_title'],
            'register_err_title'     => $this->options['register_err_title'],
        ];

        $this->v_localized_variants = [
            'handle_uri'               => admin_url('admin-ajax.php'),
            'back_button_field'       => $this->options['back_button_field'],
            'validation_field'        => $this->options['validation_field'],
            'validation_desc_field'   => $this->options['validation_desc_field'],
            'validation_button_field' => $this->options['validation_button_field'],
            'invalid_validation_msg'  => $this->options['invalid_validation_msg'],
            'thumbnail_width'         => $this->options['thumbnail_width'],
            'thumbnail_height'        => $this->options['thumbnail_height'],
        ];
    }

    private function load_warranty_app_assets()
    {
        wp_register_style('wpwv_warranty', TRUST_URL . 'assets/css/style.css');
        wp_enqueue_style('wpwv_warranty');
    }

    private function load_validation_app_assets()
    {
        wp_register_style('wpwv_validation', TRUST_URL . 'assets/css/style.css');
        wp_enqueue_style('wpwv_validation');
    }

    /**
     * Since v1.0.0
     * load forms v1 scripts
     */
    public function load_frontend_assets()
    {
        wp_enqueue_style('trust-styles', TRUST_URL . 'assets/css/style.css');
        wp_enqueue_script('trust-bundle', TRUST_URL . 'templates/frontend/trust-app-v1/trust-bundle.js', [], '1.1.0', true);


        if ($this->options['is_warranty_active']) {
            $this->load_warranty_app_assets();
            wp_localize_script('trust-bundle', 'wck_app', $this->w_localized_variants);
        }

        if ($this->options['is_validation_active']) {
            $this->load_validation_app_assets();
            wp_localize_script('trust-bundle', 'vck_app', $this->v_localized_variants);
        }
    }

    /**
     * Since v4.0.0
     * load compiled warranty next.js app chunks from warranty/dist/ directory
     */
    public function load_warranty_frontend_assets()
    {
        wp_enqueue_script('trust-w-v2-webpack', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/webpack-36d12a75f0098f30.js', [], '2.0.0', true);
        wp_enqueue_script('trust-w-v2-framework', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/framework-305cb810cde7afac.js', [], '2.0.0', true);
        wp_enqueue_script('trust-w-v2-main', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/main-bfbd70c9b9a5a25b.js', [], '2.0.0', true);
        wp_enqueue_script('trust-w-v2-app', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/pages/_app-5fbdfbcdfb555d2f.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-w-v2-217', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/217-a134b4ffdb4c4645.js', [], '2.0.0', true);
        wp_enqueue_script('trust-w-v2-index', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/chunks/pages/index-645b1cbc4143622f.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-w-v2-buildManifest', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/UlYY_9Tj5WNIe7zPqI0gw/_buildManifest.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-w-v2-ssgManifest', TRUST_URL . 'templates/frontend/trust-app-v2/warranty/dist/_next/static/UlYY_9Tj5WNIe7zPqI0gw/_ssgManifest.js', [], '2.0.0', true); //

        wp_localize_script('trust-w-v2-index', 'wck_app', $this->w_localized_variants);
    }

    /**
     * Since v4.0.0
     * load compiled validation next.js app chunks from validation/dist/ directory
     */
    public function load_validation_frontend_assets()
    {
        wp_enqueue_script('trust-v-v2-polyfill', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/polyfills-78c92fac7aa8fdd8.js', [], '2.0.0', true);
        wp_enqueue_script('trust-v-v2-webpack', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/webpack-36d12a75f0098f30.js', [], '2.0.0', true);
        wp_enqueue_script('trust-v-v2-framework', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/framework-305cb810cde7afac.js', [], '2.0.0', true);
        wp_enqueue_script('trust-v-v2-main', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/main-d849ca0355e35509.js', [], '2.0.0', true);
        wp_enqueue_script('trust-v-v2-app', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/pages/_app-5fbdfbcdfb555d2f.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-v-v2-961', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/961-71b146edfd9debf3.js', [], '2.0.0', true);
        wp_enqueue_script('trust-v-v2-index', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/chunks/pages/index-f269e4808342a575.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-v-v2-buildManifest', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/JErqU9oaPMMFSjaFtWNOD/_buildManifest.js', [], '2.0.0', true); //
        wp_enqueue_script('trust-v-v2-ssgManifest', TRUST_URL . 'templates/frontend/trust-app-v2/validation/dist/_next/static/JErqU9oaPMMFSjaFtWNOD/_ssgManifest.js', [], '2.0.0', true); //

        wp_localize_script('trust-v-v2-index', 'vck_app', $this->v_localized_variants);
    }

    public function load_admin_assets()
    {
        wp_register_style('wpwv-admin', TRUST_URL . 'assets/css/admin.css');
        wp_enqueue_style('wpwv-admin');
        wp_register_script('wpwv-admin-script', TRUST_URL . 'assets/js/admin/panel.js', [], '1.0.0', true);
        wp_enqueue_script('wpwv-admin-script');
    }

    public function make_query(string $k, string $v)
    {
        $_query = remove_query_arg(['page', 'page_number']);
        return get_site_url() . $_query  . "?{$k}={$v}";
    }

    public function warranty_lookup()
    {
        AJAXCallsProxy::handle("warranty")->lookup();
    }

    public function warranty_register()
    {
        AJAXCallsProxy::handle("warranty")->register();
    }

    public function validation_lookup()
    {
        AJAXCallsProxy::handle("validation")->lookup();
    }

    public function delete_single_validation()
    {
        if (is_admin()) AJAXCallsProxy::handle("validation")->delete_single_record();
    }

    public function delete_multiple_validations()
    {
        if (is_admin()) AJAXCallsProxy::handle("validation")->delete_multiple_records();
    }

    public function delete_single_warranty()
    {
        if (is_admin()) AJAXCallsProxy::handle("warranty")->delete_single_record();
    }

    public function delete_multiple_warranties()
    {
        if (is_admin()) AJAXCallsProxy::handle("warranty")->delete_multiple_records();
    }

    public function get_validation_items()
    {
        global $wpdb;

        $i = intval(sanitize_text_field($_POST['i']));

        //  create a range of 120 items
        if (!is_null($_POST['filter_by']) && $_POST['term'] != '') {
            switch ($_POST['filter_by']) {
                case 'validation':
                    $validations = $wpdb->get_results("SELECT id,validation,description FROM {$wpdb->prefix}wpwv_validations WHERE validation LIKE '%{$_POST['term']}%' LIMIT {$i}, 120");
                    break;
                case 'description':
                    $validations = $wpdb->get_results("SELECT id,validation,description FROM {$wpdb->prefix}wpwv_validations WHERE description LIKE '%{$_POST['term']}%' LIMIT {$i}, 120");
                    break;
                default:
                    wp_die();
                    break;
            }
        } else $validations = $wpdb->get_results("SELECT id,validation,description FROM {$wpdb->prefix}wpwv_validations LIMIT {$i}, 120");
        wp_send_json(['data' => $validations], 200);
    }

    public function get_warranty_items()
    {
        global $wpdb;

        $i = intval(sanitize_text_field($_POST['i']));

        //  create a range of 120 items
        if (!is_null($_POST['filter_by']) && $_POST['term'] != '') {
            switch ($_POST['filter_by']) {
                case 'serial':
                    $warranties = $wpdb->get_results("SELECT id,serial,product,is_registered,order_id FROM {$wpdb->prefix}wpwv_serials WHERE serial LIKE '%{$_POST['term']}%' LIMIT {$i}, 120");
                    break;
                case 'product':
                    $warranties = $wpdb->get_results("SELECT id,serial,product,is_registered,order_id FROM {$wpdb->prefix}wpwv_serials WHERE product LIKE '%{$_POST['term']}%' LIMIT {$i}, 120");
                    break;
                default:
                    wp_die();
                    break;
            }
        } else $warranties = $wpdb->get_results("SELECT id,serial,product,is_registered,order_id FROM {$wpdb->prefix}wpwv_serials LIMIT {$i}, 120");
        wp_send_json(['data' => $warranties], 200);
    }
}
