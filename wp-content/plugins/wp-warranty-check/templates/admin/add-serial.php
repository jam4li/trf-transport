<div class="widefat">
    <div class="wrap">
        <h2>افزودن کد گارانتی جدید</h2>
        <hr>
        <?php if ( isset( $msg ) ) : ?>
            <div class="<?php /** @var string $status */
            echo $status; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <?php
        //  load form if license status is valid
        if ( get_option( 'wpwv_license_status', '' ) === 'valid' ) :
            ?>
            <form method="post"
                  style="display: flex; flex-flow: column wrap; gap: 10px; max-width: 320px; margin: 0 auto">
                <label for="serial">سریال</label>
                <input type="text" name="serial" id="serial" placeholder="****-******-****" dir="ltr" required/>
                <label for="product">محصول</label>
                <input type="text" name="product" id="product" placeholder="Galaxy A80, MacBook Air M1,..." required/>
                <span>مدت گارانتی</span>
                <div>
                    <label for="period_unlimited">گارانتی نامحدود (بدون تاریخ پایان)</label>
                    <input type="checkbox" name="period_unlimited" id="period_unlimited" value="unlimited"/>
                </div>
                <ul>
                    <li>
                        <span style="color: #777">مقدار حتما به صورت عددی وارد گردد!</span>
                    </li>
                    <li>
                        <span style="color: #777">تعداد ماه یا روز حتما به صورت عدد صحیح باشد و از وارد کردن اعداد اعشاری خودداری کنید</span>
                    </li>
                </ul>
                <label for="period">مقدار</label>
                <input type="number" inputmode="numeric" min="1" pattern="[0-9]*" name="period" id="period"
                                                   placeholder="12" dir="ltr" required/>
                <label for="period_unit">برحسب</label>
                <select name="period_unit" id="period_unit">
                    <option value="d">روز</option>
                    <option value="m">ماه</option>
                </select>
                <input type="submit" name="saveData" id="saveData" value="افزودن" class="button button-primary"/>
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

<script>
    const $ = jQuery;
    $('#period_unlimited').on('click', function () {
        if ($('#period_unlimited').prop("checked")) {
            $('#period').attr('disabled', true)
            $('#period').val(0)
            $('#period_unit').attr('disabled', true)
            $('#period_unit').val("n/a")
        } else {
            $('#period').attr('disabled', false)
            $('#period_unit').attr('disabled', false)
        }
    })
</script>