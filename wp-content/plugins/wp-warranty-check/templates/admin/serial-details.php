<?php
global $wpdb;
$details = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}wpwv_serials WHERE id = '{$_GET['id']}'"
)[0];
$is_agent_active = get_option('wpwv_options')['is_agent_active']
?>
<div class="wrap">
    <?php if (isset($details)) : ?>
        <script>
            const handleDelete = () => {
                if (confirm('از حذف این مورد اطمینان دارید؟')) {
                    let deleteHandle = document.createElement('A')
                    deleteHandle.href = '<?php echo add_query_arg(['action' => 'delete', 'id' => $details->id]); ?>'
                    deleteHandle.click()
                }
            }
        </script>
        <a class="back-btn" href="<?php echo remove_query_arg(['action', 'id']); ?>">
            <svg className='btn-back' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' style="font-size: 36px; width: 24px">
                <path strokeLinecap='round' strokeLinejoin='round' strokeWidth={2} d='M14 5l7 7m0 0l-7 7m7-7H3' />
            </svg>
            <span>بازگشت</span>
        </a>
        <h2>جزییات سریال <span dir="ltr"><?php echo $details->serial; ?></span></h2>
        <hr />
        <?php if (isset($msg)) : ?>
            <div class="<?php /** @var string $status */
                        echo $status; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <div class="details">
            <h3>محصول <?php echo $details->product; ?> به سریال گارانتی <span dir="ltr"><?php echo $details->serial; ?></span></h3>
            <a href="javascript:handleDelete()">
                <span class="dashicons dashicons-trash"></span>
            </a>
            <form action="" method="post">
                <?php if (isset($details->order_id)) : ?>
                    <span style="background: purple;border-radius: 5px;padding: 3px 8px;color: white;">شماره سفاش ووکامرس: <?php echo $details->order_id; ?></span>
                <?php endif; ?>
                <input type="text" name="serial" id="serial" value="<?php echo $details->serial; ?>" style="display:none;">
                <ul>
                    <li style="position: relative;">
                        <div style="display: inline-flex;align-items: center;">
                            <span>ثبت شده؟:&nbsp;</span>
                            <span style="background: #666;color:white; padding: 4px;border-radius: 4px;z-index: 10;font-size: 10px;display: flex;align-items: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px;" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg> بله - خیر</span>
                        </div>
                        <select name="is_registered" id="is_registered">
                            <option value="no" <?php echo $details->is_registered == 'no' ? 'selected' : ''; ?>>خیر
                            </option>
                            <option value="yes" <?php echo $details->is_registered == 'yes' ? 'selected' : ''; ?>>بله
                            </option>
                        </select>
                    </li>
                    <li><span>تاریخ ثبت: </span><input name="start_at" id="start_at" dir="ltr" value="<?php echo $details->start_at ?? ''; ?>" /></li>
                    <li style="position: relative;">
                        <div style="display: inline-flex;align-items: center;">
                            <span>تاریخ پایان:&nbsp;</span>
                            <span id="hint" style="display: none;color:white;background: red;padding:4px;border-radius:4px;z-index: 10;font-size: 10px;">
                            </span>
                        </div>
                        <input name="end_at" id="end_at" dir="ltr" value="<?php echo $details->end_at ?? ''; ?>" />
                    </li>
                    <li><span>خریدار: </span><input name="customer_name" id="customer_name" value="<?php echo $details->customer_name ?? ''; ?>" /></li>
                    <li><span>موبایل: </span><input name="customer_phone" id="customer_phone" value="<?php echo $details->customer_phone ?? ''; ?>" /></li>
                    <!--<li><span>ایمیل: </span><input name="customer_email" id="customer_email" value="<?php echo $details->customer_email ?? ''; ?>" /></li>-->
                    <?php if ($is_agent_active) : ?>
                        <li><span>نمایندگی: </span><input name="agent" id="agent" value="<?php echo $details->agent ?? ''; ?>" /></li>
                        <li><span>آدرس نمایندگی: </span><input name="agent_address" id="agent_address" value="<?php echo $details->agent_address ?? ''; ?>" />
                        </li>
                        <li><span>کد نمایندگی: </span><input name="agent_code" id="agent_code" value="<?php echo $details->agent_code ?? ''; ?>" /></li>
                    <?php endif; ?>
                </ul>
                <button class="button button-hero button-primary" name="saveDetails" type="submit">ویرایش</button>
            </form>
        </div>
    <?php else : ?>
        <h2>چنین موردی یافت نشد!</h2>
    <?php endif; ?>
</div>
<script>
    const $ = jQuery
    if ($('#end_at').val() == '0000-00-00') {
        $('#hint').css("display", "block")
        $('#hint').text('گارانتی نامحدود!')
    }
</script>
<style>
    .details form {
        padding: 20px 0;
    }

    .details ul {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 20px;
        -webkit-box-pack: start;
        -ms-flex-pack: start;
        justify-content: start;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-flow: row wrap;
        flex-flow: row wrap;
    }


    .details ul li {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 8px;
        max-width: 370px;
        position: relative;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .details ul li input,
    .details ul li select {
        padding: 6px;
        width: 220px !important;
        height: 36px;
        border-radius: 2px;
        border: 1px solid gray;
        -webkit-box-flex: 2;
        -ms-flex: 2;
        flex: 2;
        max-width: 220px;
    }
</style>