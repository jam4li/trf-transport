<h3>تنظیمات ایمیل</h3>
<div style="margin: 20px 0;">
    <div>
        <input type="checkbox" name="is_admin_email_active" id="is_admin_email_active" <?php echo $options['is_admin_email_active'] ? 'checked' : ''; ?>>
        <label for="is_admin_email_active">ارسال ایمیل برای مدیر سایت</label>
    </div>
    <div>
        <input type="checkbox" name="is_customer_email_active" id="is_customer_email_active" <?php echo $options['is_customer_email_active'] ? 'checked' : ''; ?>>
        <label for="is_customer_email_active">ارسال ایمیل برای کاربر</label>
    </div>
</div>
<div style="display: flex; flex-flow: column wrap; gap: 8px; margin: 10px 0;">
    <label for="email_title">عنوان ایمیل</label>
    <input type="text" name="email_title" id="email_title" value="<?php echo isset($options['email_title']) ? $options['email_title'] : ''; ?>" placeholder="ثبت گارانتی جدید"/>
</div>
<div style="display: flex; flex-flow: column wrap; gap: 8px; margin: 10px 0;">
    <label for="admin_email_pattern">قالب ایمیل مدیر</label>
    <textarea
        name="admin_email_pattern"
        id="admin_email_pattern"
        cols="60" rows="10"
        placeholder="سلام (name) عزیز!&NewLine;محصول شما ثبت گارانتی شد.&NewLine;برای فعال سازی به آدرس زیر مراجعه کنید.&NewLine;کد گارانتی:&NewLine;(product) - (serial)"><?php if (!is_null($options['admin_email_pattern'])) echo $options['admin_email_pattern']; ?></textarea>
</div>
<div style="display: flex; flex-flow: column wrap; gap: 8px; margin: 10px 0;">
    <label for="customer_email_pattern">قالب ایمیل مشتری</label>
    <textarea
        name="customer_email_pattern"
        id="customer_email_pattern"
        cols="60" rows="10"
        placeholder="سلام (name) عزیز!&NewLine;محصول شما ثبت گارانتی شد.&NewLine;برای فعال سازی به آدرس زیر مراجعه کنید.&NewLine;کد گارانتی:&NewLine;(product) - (serial)"><?php if (!is_null($options['customer_email_pattern'])) echo $options['customer_email_pattern']; ?></textarea>
</div>
<div style="background: #eee;padding: 10px;margin: 10px 0;">
    <span>راهنما</span>
    <code>
        <ul>
            <ol><b>(name)</b>:&Tab;نام کامل مشتری</ol>
            <ol><b>(product)</b>:&Tab;نام محصول</ol>
            <ol><b>(serial)</b>:&Tab;کد گارانتی محصولات خریداری شده</ol>
        </ul>
    </code>
</div>