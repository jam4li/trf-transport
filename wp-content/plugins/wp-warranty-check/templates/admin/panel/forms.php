<div style="display: flex; flex-flow: column wrap; gap: 8px;">
    <h2>تنظیمات نسخه <?php echo $options['trust_forms_version']; ?> فرم ها</h2>
    <h3>فرم ثبت و بررسی گارانتی</h3>
    <div>
        <label for="back_button_field">فیلد دکمه بازگشت</label>
        <input type="text" name="back_button_field" id="back_button_field" value="<?php echo $options['back_button_field'] ? $options['back_button_field'] : null; ?>" />
    </div>

    <h4>مرحله بررسی سریال</h4>
    <div>
        <label for="serial_field">فیلد سریال</label>
        <input type="text" name="serial_field" id="serial_field" value="<?php echo $options['serial_field'] ? $options['serial_field'] : null; ?>" />
    </div>
    <div>
        <label for="check_button_field">دکمه بررسی سریال</label>
        <input type="text" name="check_button_field" id="check_button_field" value="<?php echo $options['check_button_field'] ? $options['check_button_field'] : null; ?>" />
    </div>
    <h4>مرحله ثبت سریال</h4>
    <div>
        <label for="name_field">فیلد نام</label>
        <input type="text" name="name_field" id="name_field" value="<?php echo $options['name_field'] ? $options['name_field'] : null; ?>" />
    </div>
    <div>
        <label for="mobile_field">فیلد شماره موبایل</label>
        <input type="text" name="mobile_field" id="mobile_field" value="<?php echo $options['mobile_field'] ? $options['mobile_field'] : null; ?>" />
    </div>
    <div>
        <label for="email_field">فیلد ایمیل</label>
        <input type="text" name="email_field" id="email_field" value="<?php echo $options['email_field'] ? $options['email_field'] : null; ?>" />
    </div>
    <div>
        <label for="agent_field">فیلد نمایندگی</label>
        <input type="text" name="agent_field" id="agent_field" value="<?php echo $options['agent_field'] ? $options['agent_field'] : null; ?>" />
    </div>
    <div>
        <label for="agent_address_field">فیلد آدرس نمایندگی</label>
        <input type="text" name="agent_address_field" id="agent_address_field" value="<?php echo $options['agent_address_field'] ? $options['agent_address_field'] : null; ?>" />
    </div>
    <div>
        <label for="agent_code_field">فیلد کد نمایندگی</label>
        <input type="text" name="agent_code_field" id="agent_code_field" value="<?php echo $options['agent_code_field'] ? $options['agent_code_field'] : null; ?>" />
    </div>
    <div>
        <label for="register_button_field">دکمه ثبت سریال</label>
        <input type="text" name="register_button_field" id="register_button_field" value="<?php echo $options['register_button_field'] ? $options['register_button_field'] : null; ?>" />
    </div>

    <h4>فرم نتیجه گارانتی ثبت شده</h4>
    <?php if ($options['trust_forms_version'] == '2') { ?>
        <div>
            <label for="register_success_title">عنوان ثبت موفق</label>
            <input type="text" name="register_success_title" id="register_success_title" value="<?php echo $options['register_success_title'] ? $options['register_success_title'] : null; ?>" />
        </div>
        <div>
            <label for=" register_err_title">عنوان ثبت ناموفق</label>
            <input type="text" name="register_err_title" id="register_err_title" value="<?php echo $options['register_err_title'] ? $options['register_err_title'] : null; ?>" />
        </div>
    <?php } ?>
    <div>
        <label for="show_hour">نمایش ساعت در کنار تاریخ</label>
        <input type="checkbox" name="show_hour" id="show_hour" <?php echo $options['show_hour'] ? 'checked' : null; ?> />
    </div>
    <div>
        <label for="product_field">فیلد نام محصول</label>
        <input type="text" name="product_field" id="product_field" value="<?php echo $options['product_field'] ? $options['product_field'] : null; ?>" />
    </div>
    <div>
        <label for="buyer_field">فیلد نام خریدار</label>
        <input type="text" name="buyer_field" id="buyer_field" value="<?php echo $options['buyer_field'] ? $options['buyer_field'] : null; ?>" />
    </div>
    <div>
        <label for="start_date_field">فیلد تاریخ شروع گارانتی</label>
        <input type="text" name="start_date_field" id="start_date_field" value="<?php echo $options['start_date_field'] ? $options['start_date_field'] : null; ?>" />
    </div>
    <div>
        <label for="end_date_field">فیلد تاریخ پایان گارانتی</label>
        <input type="text" name="end_date_field" id="end_date_field" value="<?php echo $options['end_date_field'] ? $options['end_date_field'] : null; ?>" />
    </div>
    <div>
        <label for="remaining_warranty_time">فیلد زمان باقی مانده از گارانتی</label>
        <input type="text" name="remaining_warranty_time" id="remaining_warranty_time" value="<?php echo $options['remaining_warranty_time'] ? $options['remaining_warranty_time'] : null; ?>" />
    </div>

    <hr />
    <h3>فرم اعتبارسنجی</h3>

    <div>
        <label for="validation_field">فیلد کد اعتبارسنجی</label>
        <input type="text" name="validation_field" id="validation_field" value="<?php echo $options['validation_field'] ? $options['validation_field'] : null; ?>" />
    </div>
    <div>
        <label for="validation_button_field">فیلد دکمه اعتبارسنجی</label>
        <input type="text" name="validation_button_field" id="validation_button_field" value="<?php echo $options['validation_button_field'] ? $options['validation_button_field'] : null; ?>" />
    </div>
    <div>
        <label for="validation_desc_field">فیلد توضیحات اعتبارسنجی</label>
        <input type="text" name="validation_desc_field" id="validation_desc_field" value="<?php echo $options['validation_desc_field'] ? $options['validation_desc_field'] : null; ?>" />
    </div>
</div>

<style>
    #forms div>div>div {
        display: flex;
        flex-flow: row wrap;
        gap: 30px;
        align-items: center;
        justify-content: space-between;
    }

    .wrap div input[type="text"] {
        flex: 1;
        max-width: 480px;
    }
</style>