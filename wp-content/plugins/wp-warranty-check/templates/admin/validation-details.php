<?php
global $wpdb;
$details = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}wpwv_validations WHERE id = '{$_GET['id']}'"
)[0];
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
        <h2>جزییات سریال <?php echo $details->validation; ?></h2>
        <div class="details">
            <a href="javascript:handleDelete()">
                <span class="dashicons dashicons-trash"></span>
            </a>
            <ul>
                <li>
                    <form method="post" style="display: flex; flex-flow: column wrap; align-items: center; gap: 10px;">
                        <div style="display: flex; flex-flow: row wrap; flex: 1; width: 100%; align-items: center; justify-content: start; gap: 10px;">
                            <label for="description">توضیحات</label>
                            <textarea name="description" id="description" style="height: 120px; width: 80%; margin: 10px auto;"><?php echo $details->description; ?></textarea>
                        </div>
                        <div style="display: flex; flex-flow: row wrap; flex: 1; width: 100%; align-items: center; justify-content: start; gap: 10px;">
                            <label for="url">تصویر</label>
                            <input type="url" name="thumbnail_url" id="thumbnail_url" style="width: 80%;margin: 10px auto;" value="<?php echo $details->thumbnail_url; ?>" />
                        </div>
                        <button class="button button-hero button-primary" name="saveDetails" type="submit" style="width: max-content">ویرایش</button>
                    </form>
                </li>
            </ul>
        </div>
    <?php else : ?>
        <h2>چنین موردی یافت نشد!</h2>
    <?php endif; ?>
</div>