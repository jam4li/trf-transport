<?php

namespace Trust\Templates;

require_once TRUST_INC . 'class-import-items.php';
use Trust\INC\Importer as Importer;

if (isset($_POST) && isset($_FILES['csv_import'])) {
    $file_info = $_FILES['csv_import'];
    $importer = Importer::instance($file_info);

    if (isset($_POST['warranty'])) $importer->make_import('warranty');
    if (isset($_POST['validation'])) $importer->make_import('validation');
}

$is_validation_active = $options['is_validation_active'];
$is_warranty_active   = $options['is_warranty_active'];
if (isset($importer)) {
    $error = $importer->error;
    $rows_inserted = $importer->rows_inserted;
    $duplicate_rows = $importer->duplicate_rows;
}

?>
<h2>درون ریزی</h2>
<span style="color: #777; font-size: 12px;">فایل های csv را مطابق با توضیحات افزونه بارگزاری نمایید.</span>
<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
if (isset($rows_inserted) && $rows_inserted > 0) {
    echo "<p style='color:green'>{$rows_inserted} مورد جدید به دیتابیس افزوده شد!</p>";
}
if (isset($duplicate_rows) && $duplicate_rows > 0) {
    echo "<p style='color:red'>{$duplicate_rows} مورد تکراری در دیتابیس یافت شد. از افزودن دوباره این موارد صرف نظر شد!</p>";
}
?>
<hr>
<?php
//  load form if license status is valid
if (get_option('wpwv_license_status', '') === 'valid') :
?>
    <div class="wrap" style="display: flex; flex-flow: row wrap; justify-content: center; gap: 15px;">
        <?php if ($is_warranty_active) : ?>
            <div class="details" style="flex: 1 1 250px; padding: 20px; max-width: 350px; border-right: 3px solid blue;">
                <h2>درون ریزی کدهای گارانتی</h2>
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                    <input
                        type="file"
                        name="csv_import"
                        id="csv_import"
                        required />
                    <input
                        class="button button-primary"
                        type="submit"
                        name="warranty"
                        id="warranty"
                        value="درون ریزی"
                        style="padding: 5px 10px; border: 1px solid #3858e9; box-shadow: 1px 1px 7px 1px gray;" />
                </form>
            </div>
        <?php endif; ?>
        <?php if ($is_validation_active) : ?>
            <div class="details" style="flex: 1 1 250px; padding: 20px; max-width: 350px; border-right: 3px solid blueviolet;">
                <h2>درون ریزی کدهای اصالت</h2>
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                    <input
                        type="file"
                        name="csv_import"
                        id="csv_import"
                        required />
                    <input
                        class="button button-primary"
                        type="submit"
                        name="validation"
                        id="validation"
                        value="درون ریزی"
                        style="padding: 5px 10px; border: 1px solid #3858e9; box-shadow: 1px 1px 7px 1px gray;" />
                </form>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="update-nag">
            <h3>لطفا ابتدا لایسنس افزونه را فعال کنید!</h3>
        </div>
    <?php endif; ?>
    </div>