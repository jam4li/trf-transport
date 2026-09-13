<?php
$license_status = get_option('wpwv_license_status', 'invalid');
$flag = 'red';
$txt = 'غیرفعال!';
if ($license_status === 'valid') {
    $flag = '#46b450';
    $txt = 'فعال!';
}

//  get number of currently registered warranty codes
$warranty_check_serials = $wpdb->get_results(
    "SELECT id FROM {$wpdb->prefix}wpwv_serials"
);
$warrantyCount = count($warranty_check_serials);

//  get number of currently registered validation codes
$warranty_check_validations = $wpdb->get_results(
    "SELECT id FROM {$wpdb->prefix}wpwv_validations"
);
$validationCount = count($warranty_check_validations);

?>
<script>
    const handleSanitize = () => {
        if (confirm('از پاکسازی دیتابیس اطمینان دارید؟')) {
            let sanitizeHandle = document.createElement('A')
            sanitizeHandle.href = '<?php echo add_query_arg(['action' => 'sanitize_db']); ?>'
            sanitizeHandle.click()
        }
    }
    const handleMigrate = () => {
        if (confirm('تنها در صورتی که قبلا نسخه 1 افزونه را نصب کرده اید و اکنون آپدیت کرده اید این گزینه را اجرا کنید. آیا اطمینان دارید؟')) {
            let Handle = document.createElement('A')
            Handle.href = '<?php echo add_query_arg(['action' => 'migrate_db']); ?>'
            Handle.click()
        }
    }
</script>
<div class="overlay" style="position: absolute;
    width: 100%;
    height: 100%;
    display: none;
    z-index: 10;
    background: rgb(223 223 223 / 50%);
    backdrop-filter: blur(3px);
    text-align: center;
    inset: 0;
    padding: 50px 0;">
    <h2>لطفا ابتدا تنظیمات را ذخیره کنید!</h2>
</div>
<div class="activation_box" style="border-right: 4px solid <?php echo $flag; ?>">
    <h4>لایسنس افزونه</h4>
    <span>وضعیت: <?php echo $txt; ?></span>
    <br>
    <?php if ($license_status !== 'valid') : ?>
        <span>برای فعال سازی و استفاده از افزونه لطفا سریال دریافتی از ژاکت را وارد نمایید.</span>
    <?php endif; ?>
    <div style="display: flex;">
        <input style="flex: 3" name="license_token" id="license_token" type="text" placeholder="سریال محصول" value="<?php echo $tokens['wpwv_lic_token']; ?>">
        <button style="flex: 1" name="saveTokens" id="saveTokens" class="button-secondary">فعال سازی</button>
    </div>
</div>
<h3>تنظیمات افزونه گارانتی وردپرس</h3>
<hr>
<div>
    <h3>حالت کارکرد افزونه</h3>
    <div method="post" style="display:flex; flex-flow: column wrap; gap: 30px">

        <div style="display: flex; flex-flow: row wrap; gap: 30px">
            <div>
                <input type="checkbox" name="is_warranty_active" id="is_warranty_active" <?php
                                                                                            if (isset($options) && $options['is_warranty_active'] == true) {
                                                                                                echo "checked";
                                                                                            }
                                                                                            ?> />
                <label for="is_warranty_active">فعال بودن بخش ثبت و بررسی گارانتی</label>
            </div>
            <div>
                <input type="checkbox" name="is_validation_active" id="is_validation_active" <?php
                                                                                                if (isset($options) && $options['is_validation_active'] == true) {
                                                                                                    echo "checked";
                                                                                                }
                                                                                                ?> />
                <label for="is_validation_active">فعال بودن بخش اعتبارسنجی اصالت محصول یا خدمات</label>
            </div>
        </div>
        <div style="display: flex; flex-flow: column wrap; gap: 10px;">
            <h3>تنظیمات بخش گارانتی</h3>
            <div>
                <input type="checkbox" name="is_agent_active" id="is_agent_active" <?php
                                                                                    if (isset($options) && $options['is_agent_active'] == true) {
                                                                                        echo "checked";
                                                                                    }
                                                                                    ?> />
                <label for="is_agent_active">فعال کردن گزینه های مربوط به نمایندگی فروش/گارانتی هنگام ثبت کد</label>
            </div>
            <div>
                <input type="checkbox" name="is_sms_active" id="is_sms_active" <?php
                                                                                if (isset($options) && $options['is_sms_active'] == true) {
                                                                                    echo "checked";
                                                                                }
                                                                                ?> />
                <label for="is_sms_active">فعال کردن قابلیت ارسال پیامک</label>
            </div>
            <div>
                <input type="checkbox" name="is_progress_bar_active" id="is_progress_bar_active" <?php
                                                                                                    if (isset($options) && $options['is_progress_bar_active'] == true) {
                                                                                                        echo "checked";
                                                                                                    }
                                                                                                    ?> />
                <label for="is_progress_bar_active">فعال کردن نوار پیشرفت گارانتی</label>
            </div>
            <div>
                <label for="date_settings">تنظیمات تاریخ</label>
                <select name="date_settings" id="date_settings">
                    <option value="jalali" <?php echo $options['date_settings'] == 'jalali' ? 'selected' : ''; ?>>شمسی</option>
                    <option value="gregorian" <?php echo $options['date_settings'] == 'gregorian' ? 'selected' : ''; ?>>میلادی</option>
                </select>
            </div>
            <div>
                <label for="trust_forms_version">نسخه فرم گارانتی</label>
                <select name="trust_forms_version" id="trust_forms_version">
                    <option value="1" <?php echo $options['trust_forms_version'] == '1' ? 'selected' : ''; ?>>نسخه 1</option>
                    <option value="2" <?php echo $options['trust_forms_version'] == '2' ? 'selected' : ''; ?>>نسخه 2</option>
                </select>
            </div>
        </div>
        <div style="display: flex; flex-flow: column wrap; gap: 10px;">
            <h3>تنظیمات بخش اعتبارسنجی</h3>
            <div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <h4>تصاویر بندانگشتی</h4>
                <div>
                    <label for="thumbnail_width">عرض تصاویر (px)</label>
                    <input type="text" pattern="[0-9]+" name="thumbnail_width" id="thumbnail_width" placeholder="300" value="<?php echo $options['thumbnail_width'] ? $options['thumbnail_width'] : '300'; ?>" style="max-width: 60px;" />
                </div>
                <div>
                    <label for="thumbnail_width">طول تصاویر (px)</label>
                    <input type="text" pattern="[0-9]+" name="thumbnail_height" id="thumbnail_height" placeholder="300" value="<?php echo $options['thumbnail_height'] ? $options['thumbnail_height'] : '300'; ?>" style="max-width: 60px;" />
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="metabox-holder" style="display: flex; flex-flow: row wrap; gap: 25px; margin: 50px 0">
        <div class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <h2 class="hndle ui-sortable-handle"><span>آمار افزونه گارانتی</span></h2>
                    <div class="inside">
                        <div class="main">
                            <ul>
                                <li class="page-count">
                                    <a href="<?php echo add_query_arg(['page' => 'wpw_overview']); ?>"><?php echo $warrantyCount; ?> کد گارانتی</a>
                                </li>
                                <li class="page-count">
                                    <a href="<?php echo add_query_arg(['page' => 'wpv_overview']); ?>"><?php echo $validationCount; ?> کد اعتبارسنجی</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="postbox-container">
            <div class="meta-box-sortables ui-sortable">
                <div id="dashboard_right_now" class="postbox">
                    <h2 class="hndle ui-sortable-handle"><span>گزینه های پیشرفته</span></h2>
                    <div class="inside">
                        <div class="main">
                            <p style="color: #666;">عملیات های دیتابیس</p>
                            <p style="color: red; font-size: 10px; margin: 5px 0;">لطفا با احتیاط از این گزینه ها استفاده کنید!</p>
                            <input onclick="javascript:handleMigrate()" type="submit" name="migrate" id="migrate" value="مهاجرت" class="button button-primary" <?php
                                                                                                                                                                global $wpwv_db_version;
                                                                                                                                                                if (!is_null(get_option('wpwv_db_version'))) echo 'disabled ';
                                                                                                                                                                if (get_option('wpwv_db_version') == $wpwv_db_version) echo 'disabled '; ?> />
                            <input onclick="javascript:handleSanitize()" type="submit" name="sanitize" id="sanitize" value="پاکسازی" class="button-secondary" style="border-color: red; color: red;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>