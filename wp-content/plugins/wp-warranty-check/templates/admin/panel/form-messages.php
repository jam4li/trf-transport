<h3>تنظیمات پیام های فرم ها</h3>
<div>
    <label for="register_form_msg">پیام فرم ثبت گارانتی</label>
    <textarea name="register_form_msg" id="register_form_msg" cols="90" rows="7" placeholder="سریال (serial) معتبر است.
لطفا اطلاعات خود را برای ثبت محصول وارد نمایید."><?php if (!is_null($options['register_form_msg'])) echo $options['register_form_msg']; ?></textarea>
</div>
<div>
    <label for="register_success_msg">پیام ثبت موفقیت آمیز کد گارانتی</label>
    <textarea name="register_success_msg" id="register_success_msg" cols="90" rows="7" placeholder="محصول شما ثبت گارانتی شده و از تاریخ {start_at} تا {end_at} معتبر خواهد."><?php if (!is_null($options['register_success_msg'])) echo $options['register_success_msg']; ?></textarea>
</div>
<div>
    <label for="u_register_success_msg">پیام ثبت موفقیت آمیز کد گارانتی بدون انقضا</label>
    <textarea name="u_register_success_msg" id="u_register_success_msg" cols="90" rows="7" placeholder="محصول شما از تاریخ {start_at} ثبت گارانتی شد."><?php if (!is_null($options['u_register_success_msg'])) echo $options['u_register_success_msg']; ?></textarea>
</div>

<div>
    <label for="register_err_msg">پیام خطای ثبت کد گارانتی</label>
    <textarea name="register_err_msg" id="register_err_msg" cols="90" rows="7" placeholder="ثبت کد گارانتی شما موفق بود! لطفا دوباره امتحان کنید یا با ما تماس بگیرید."><?php if (!is_null($options['register_err_msg'])) echo $options['register_err_msg']; ?></textarea>
</div>

<div>
    <label for="invalid_serial_msg">پیام کد گارانتی نامعتبر</label>
    <textarea name="invalid_serial_msg" id="invalid_serial_msg" cols="90" rows="7" placeholder="کد گارانتی (serial) نامعتبر است."><?php if (!is_null($options['invalid_serial_msg'])) echo $options['invalid_serial_msg']; ?></textarea>
</div>

<div>
    <label for="invalid_serial_msg">پیام کد اعتبارسنجی نامعتبر</label>
    <textarea name="invalid_validation_msg" id="invalid_validation_msg" cols="90" rows="7" placeholder="کد اعتبارسنجی وارد شده نامعتبر است."><?php if (!is_null($options['invalid_validation_msg'])) echo $options['invalid_validation_msg']; ?></textarea>
</div>

<div style="background: #eee;padding: 10px;">
    <span>راهنما</span>
    <code>
        <ul>
            <ol><b>{serial}</b>:&Tab;کد گارانتی</ol>
            <ol><b>{start_at}</b>:&Tab;تاریخ شروع گارانتی</ol>
            <ol><b>{end_at}</b>:&Tab;تاریخ پایان گارانتی</ol>
        </ul>
    </code>
</div>

<style>
    #messages div {
        display: block;
        margin: 20px 0;
    }

    #messages div textarea {
        width: 100%;
        padding: 8px;
        line-height: 1.8rem;
    }
</style>