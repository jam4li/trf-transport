<?php

/**
 * Metabox
 * add meabox for woocommerce products
 * PHP version 7.2
 *
 * @category Class
 * @package  Trust
 * @author   Mojtaba Khodami <mojtabakh@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */

namespace Trust\Woo\MetaBox;

use Trust\Woo\Utils\Status as Helper;

/**
 * Initiate Woocommerce Metabox
 *
 * @category Class
 * @package  Trust
 * @author   Mojtaba Khodami <mojtabakh@hotmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */
final class RegisterMetaBox {
    private static $instance;

    /**
     * Check if warranty is enabled on the plugin settings
     * @return bool
     */
    public function is_warranty_enabled() {
        return get_post_meta(get_the_ID(), 'is_warranty_enabled', true);
    }

    public function get_period() {
        $warranty_priod = get_post_meta(get_the_ID(), 'warranty_period', true);
        if (!is_null($warranty_priod)) {
            return $warranty_priod;
        }

        return null;
    }

    public function __construct() {
        /*
         * Register meta tab
         */
        add_action('add_meta_boxes', [$this, 'register_metabox_tab']);


        /*
         * Save metabox data
         */
        add_action('woocommerce_process_product_meta', [$this, 'save_warranty_meta']);

        /*
         * Load assets
         *
         *
         * 'get_script_depends' function is passed to a proper
         * hook (admin_enqueue_scripts) to be loaded on the admin side
         * on proper time when WP core runs the action
         */
        add_action('admin_enqueue_scripts', [$this, 'get_script_depends']);

        /*
         * 'get_style_depends' will run on the call action
         * for 'admin_enqueue_scripts'
         */
        add_action('admin_enqueue_scripts', [$this, 'get_style_depends']);
    }

    public static function init(): ?RegisterMetaBox {
        if ((!self::$instance instanceof self) && Helper::is_woo_active()) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function get_style_depends(): void {
        wp_enqueue_style('wpwv-woo', TRUST_URL . 'assets/css/admin/metabox.css', [], '1.0.0');
    }

    public function get_script_depends(): void {
        wp_enqueue_script('wpwv-woo', TRUST_URL . 'assets/js/admin/metabox.js', ['jquery'], '1.0.0', true);
    }

    public function register_metabox_tab(): void {
        $screen = get_current_screen();
        if ($screen->post_type == 'product') {
            add_meta_box('product_warranty', 'گارانتی محصول', [$this, 'register_metabox_content'], $screen);
        }
    }

    public function register_metabox_content(): void {
        $begin_period     = get_post_meta(get_the_ID(), 'begin_period', true);
        $period_unit      = get_post_meta(get_the_ID(), 'period_unit', true);
        $unlimited_period = get_post_meta(get_the_ID(), 'unlimited_period', true);
?>
        <div id="warranty_data" class="panel">
            <div class="options_group">
                <form method="POST">
                    <p class="form-field">
                        <label for="warranty_enabled">فعال کردن گارانتی برای محصول</label>
                        <input type="checkbox" name="warranty_enabled" id="warranty_enabled" <?php echo $this->is_warranty_enabled() ? ' checked' : ''; ?> />
                    </p>

                    <div id="warranty_details" class="form-field">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <label for="warranty_period">مدت گارانتی</label>
                                    <input type="number" min="0" name="warranty_period" id="warranty_period" placeholder="مدت گارانتی" value="<?php echo $this->get_period(); ?>" />
                                </div>
                                <div class="col">
                                    <label for="period_unit">برحسب</label>
                                    <select name="period_unit" id="period_unit">
                                        <option value="d" <?php echo $period_unit === 'd' ? 'selected' : ''; ?>>روز
                                        </option>
                                        <option value="m" <?php echo $period_unit === 'm' ? 'selected' : ''; ?>>ماه
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="unlimited_period">گارانتی نامحدود</label>
                                <input type="checkbox" name="unlimited_period" id="unlimited_period" <?php echo $unlimited_period ? 'checked' : ''; ?> />
                            </div>
                        </div>
                        <div class="col">
                            <label for="begin_period">شروع دوره گارانتی از: </label>
                            <select name="begin_period" id="begin_period">
                                <option value="on_buy" <?php echo $begin_period == 'on_buy' ? 'selected' : ''; ?>>زمان
                                    تکمیل خرید
                                </option>
                                <option value="on_reg" <?php echo $begin_period == 'on_reg' ? 'selected' : ''; ?>>زمان
                                    ثبت توسط مشتری
                                </option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
        </div>
<?php

    }

    public function save_warranty_meta(): void {
        if ($_POST['warranty_enabled'] == 'on') {
            update_post_meta(get_the_ID(), 'is_warranty_enabled', true);
            if (!isset($_POST['unlimited_period'])) {
                update_post_meta(get_the_ID(), 'warranty_period', $_POST['warranty_period']);
                update_post_meta(get_the_ID(), 'period_unit', $_POST['period_unit']);
                update_post_meta(get_the_ID(), 'unlimited_period', false);
            } else {
                update_post_meta(get_the_ID(), 'warranty_period', 0);
                update_post_meta(get_the_ID(), 'period_unit', 'd');
                update_post_meta(get_the_ID(), 'unlimited_period', true);
            }
            update_post_meta(get_the_ID(), 'begin_period', $_POST['begin_period']);
        } else {
            update_post_meta(get_the_ID(), 'is_warranty_enabled', false, true);
        }
    }
}
