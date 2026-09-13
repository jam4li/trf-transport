<div class="wrap ax_panel-wrap">
    <?php
    global $wpdb;
    $options = get_option('wpwv_options');
    $tokens = get_option('wpwv_tokens');
    include TRUST_TPL . 'admin/panel/tabs.php';
    ?>
</div>