<?php

namespace Trust\INC;

class Shortcodes
{
    private $forms_active_version;

    public function __construct()
    {
        $this->forms_active_version = get_option('wpwv_options')['trust_forms_version'];
    }

    public function warranty_check_form()
    {
        if ($this->forms_active_version == 1) {
            ob_start();
?>
            <div class="container" id="trust-warranty-react-app"></div>
        <?php
            do_action('wpwv_enqueue_scripts');
            return ob_get_clean();
        } elseif ($this->forms_active_version == 2) {
            ob_start();
            include_once TRUST_TPL . 'frontend/trust-app-v2/warranty/template-warranty-v2.php';
            do_action('wpwv_enqueue_warranty_scripts');
            return ob_get_clean();
        }
    }

    public function validation_check_form() {
        if ($this->forms_active_version == 1) {
            ob_start();
        ?>
            <div class="container" id="trust-validation-react-app"></div>
        <?php
            do_action('wpwv_enqueue_scripts');
            return ob_get_clean();
        } elseif ($this->forms_active_version == 2) {
            ob_start();
            include_once TRUST_TPL . 'frontend/trust-app-v2/validation/template-validation-v2.php';
            do_action('wpwv_enqueue_validation_scripts');
            return ob_get_clean();
        }
    }
}
