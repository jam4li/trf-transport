<div class="widefat">
    <div class="wrap">
        <h2>افزودن کد اعتبارسنجی جدید</h2>
        <hr>
        <?php if (isset($msg)) : ?>
            <div class="<?php echo $status; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <?php
        //  load form if license status is valid
        if (get_option('wpwv_license_status', '') === 'valid') :
        ?>
            <form method="post" style="display: flex; flex-flow: column wrap; gap: 10px; max-width: 320px; margin: 0 auto">
                <label for="validation">کد اعتبارسنجی</label>
                <input type="text" name="validation" id="validation" placeholder="****-******-****" dir="ltr" required />
                <label for="description">توضیحات</label>
                <textarea name="description" id="description" style="height: 120px" placeholder="هرگونه توضیحات مرتبط با کد اعتبارسنجی. مثلا: گوشی موبایل Galaxy A70 تولید ویتنام - فاقد گارانتی" required></textarea>
                <label for="thumbnail">تصویر</label>
                <input type="url" name="thumbnail_url" id="thumbnail_url" placeholder="https://imgcdn.com/my/image.png" pattern="/^(?:https?:\/\/)(.*?)\/(.+?)(?:\/|\?|\#|$|\n)\w*(.jpg|.jpeg|.png)$/gs"/>
                <input type="submit" name="saveData" id="saveData" value="افزودن" class="button button-primary" />
            </form>
        <?php
        else :
        ?>
            <div class="update-nag">
                <h3>لطفا ابتدا لایسنس افزونه را فعال کنید!</h3>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>