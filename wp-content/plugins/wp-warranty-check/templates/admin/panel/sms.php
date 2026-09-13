<?php
$gateway = null;
$line = null;
$error = null;
$active_sms_tool = $options['active_sms_tool'];
switch ($active_sms_tool) {
    case 'wp_sms_pro':
        if (class_exists('WP_SMS')) {
            $gateway = wp_sms_get_option('gateway_name');
            $line = wp_sms_get_option('gateway_sender_id');
        }
        break;
    case 'p_woo_sms':
        if (class_exists('WoocommerceIR_SMS_Helper')) {
            $gateway = \PWooSms()->Options('sms_gateway');
        }
        break;
    case 'sms_ir_app':
        if (class_exists('SMSIRAppClass')) {
            $gateway = get_option('sms_ir_info_api_key') ? 'SMS.ir' : 'none';
            $line = get_option('sms_ir_info_number');
        }
        break;
    default:
        $error = "هیچ افزونه ی سازگاری برای ارسال پیامک روی سایت شما نصب نشده است!";
}

?>
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
<h3>تنظیمات پیامک</h3>
<div class="gate_section <?php if ($gateway == 'none' || $gateway == null)  echo 'unconfigured'; ?>">
    <div>
        <label for="active_sms_tool">ابزار ارسال پیامک</label>
        <select name="active_sms_tool" id="active_sms_tool">
            <option value="p_woo_sms" <?php echo $options['active_sms_tool'] == 'p_woo_sms' ? 'selected' : ''; ?>>پیامک حرفه ای ووکامرس</option>
            <option value="wp_sms_pro" <?php echo $options['active_sms_tool'] == 'wp_sms_pro' ? 'selected' : ''; ?>>WP SMS / WP SMS Pro</option>
            <option value="sms_ir_app" <?php echo $options['active_sms_tool'] == 'sms_ir_app' ? 'selected' : ''; ?>>SMS.ir (ایده پردازان)</option>
        </select>
    </div>
    <?php
    if ($gateway == null) echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>افزونه پیامکی انتخاب شده روی وردپرس شما نصب یا فعال نیست!</p>";
    else {
        switch ($active_sms_tool) {
            case 'wp_sms_pro':
                if ($gateway == '') echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>هیچ درگاهی در افزونه WP SMS تنظیم نشده است!</p>";
                else {
                    echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#46b450'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd' /></svg>درگاه انتخابی: {$gateway}</p>";
                    if ($line !== '') echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#46b450'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd' /></svg>خط انتخابی: {$line}</p>";
                    else echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>خط پیامکی را وارد نکرده اید!</p>";
                }
                break;
            case 'p_woo_sms':
                if ($gateway == 'none') echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>هیچ درگاهی در افزونه پیامک تنظیم نشده است!</p>";
                else echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#46b450'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd' /></svg>درگاه انتخابی: {$gateway}</p>";
                break;
            case 'sms_ir_app':
                if ($gateway == 'none') echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>کلید API تنظیم نشده است!</p>";
                if ($line) echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#46b450'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd' /></svg>خط انتخابی: {$line}</p>";
                else echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>خط پیامکی را وارد نکرده اید!</p>";
                break;
            default:
                echo "<p><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='#b44646'><path fill-rule='evenodd' d='M6.707 4.879A3 3 0 018.828 4H15a3 3 0 013 3v6a3 3 0 01-3 3H8.828a3 3 0 01-2.12-.879l-4.415-4.414a1 1 0 010-1.414l4.414-4.414zm4 2.414a1 1 0 00-1.414 1.414L10.586 10l-1.293 1.293a1 1 0 101.414 1.414L12 11.414l1.293 1.293a1 1 0 001.414-1.414L13.414 10l1.293-1.293a1 1 0 00-1.414-1.414L12 8.586l-1.293-1.293z' clip-rule='evenodd' /></svg>{$error}</p>";
        }
    }
    ?>
</div>
<div style="display: block; margin: 20px 0;">
    <label for="on_buy_sms_pattern">قالب پیامک برای محصولات با ثبت زمان گارانتی هنگام خرید (مختص محصولات ووکامرس)</label>
    <textarea style="width: 100%; padding: 8px; line-height: 1.8rem;" name="on_buy_sms_pattern" id="on_buy_sms_pattern" cols="90" rows="7" placeholder="سلام {name} عزیز!&NewLine;محصول شما ثبت گارانتی شد.&NewLine;برای مشاهده وضعیت به آدرس زیر مراجعه کنید.&NewLine;کد گارانتی:&NewLine;{product} - {serial}"><?php if (!is_null($options['on_buy_sms_pattern'])) echo $options['on_buy_sms_pattern']; ?></textarea>
</div>
<div style="display: block;">
    <label for="on_reg_sms_pattern">قالب پیامک برای محصولاتی توسط خود مشتری ثبت خواهند شد (محصولات ووکامرس + محصولات ثبت شده به صورت دستی یا اکسل)</label>
    <textarea style="width: 100%; padding: 8px; line-height: 1.8rem;" name="on_reg_sms_pattern" id="on_reg_sms_pattern" cols="90" rows="7" placeholder="سلام {name} عزیز!&NewLine;محصول شما ثبت گارانتی شد.&NewLine;برای فعال سازی به آدرس زیر مراجعه کنید.&NewLine;کد گارانتی:&NewLine;{product} - {serial}"><?php if (!is_null($options['on_reg_sms_pattern'])) echo $options['on_reg_sms_pattern']; ?></textarea>
</div>
<div style="background: #eee;padding: 10px;margin: 10px 0;">
    <span>راهنما</span>
    <code>
        <ul>
            <ol><b>{name}</b>:&Tab;نام کامل مشتری</ol>
            <ol><b>{product}</b>:&Tab;نام محصول</ol>
            <ol><b>{serial}</b>:&Tab;کد گارانتی محصولات خریداری شده</ol>
            <ol><b>{start_at}</b>:&Tab;تاریخ شروع گارانتی</ol>
            <ol><b>{end_at}</b>:&Tab;تاریخ اتمام گارانتی</ol>
        </ul>
    </code>
</div>