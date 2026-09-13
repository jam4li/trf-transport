<?php

namespace Trust\Templates;

require_once TRUST_INC . 'class-export-items.php';

use Trust\INC\Exporter;

$exporter = Exporter::instance();

?>
<div class="wrap">
    <script>
        const $ = jQuery
        $(document).ready(function() {
            $("#makeExport1").on("click", function(e) {
                e.preventDefault() //  Prevent from reload
                const target = document.createElement('a');
                target.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?page=wpvi_export&action=export_trust_registered_items"
                target.click()
            });
        });
        $(document).ready(function() {
            $("#makeExport2").on("click", function(e) {
                e.preventDefault() //  Prevent from reload
                const target = document.createElement('a');
                target.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?page=wpvi_export&action=export_trust_unregistered_items"
                target.click()
            });
        });
        $(document).ready(function() {
            $("#makeExport3").on("click", function(e) {
                e.preventDefault() //  Prevent from reload
                const target = document.createElement('a');
                target.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?page=wpvi_export&action=export_trust_all_items"
                target.click()
            });
        });
    </script>
    <div style="display: flex; flex-flow: column wrap; line-height: 32px;">
        <h1>برون بری آیتم های گارانتی افزونه Trust</h1>
        <span style="color: #777; font-size: 12px;">میتوانید آیتم های موجود در افزونه را به تفکیک ثبت شده - ثبت نشده - همه موارد به صورت فایل csv برون بری کنید.</span>
        <span style="color: #777; font-size: 12px;">فایل csv برون بری شده را باید به فرمت <b>utf-8</b> در نرم افزار اکسل باز کنید تا دچار به هم ریختگی حروف فارسی نگردد.</span>
    </div>
    <hr>
    <?php
    if (isset($_error)) {
        echo "<p style='background-color: white; padding: 10px; border-radius: 5px; border-right: 5px solid red; margin: 10px 0;'>$_error</p>";
    } ?>
    <div style="display: flex; flex-flow: row wrap; gap: 15px; justify-content: center;">
        <div class="details" style="flex: 1 1 250px; max-width: 280px; border-right: 3px solid orangered;">
            <h2>برون بری موارد ثبت شده</h2>
            <p>موارد موجود برای خروجی گرفتن: <span style="background-color: orangered; color: white; padding: 4px 6px; border-radius: 2px; "><?php echo count($exporter->registered_items); ?> مورد</span>
            </p>
            <form id="exportForm" method="GET" action="" onsubmit="return false">
                <button class="button button-primary" style="padding: 5px 10px; border: 1px solid #3858e9; box-shadow: 1px 1px 7px 1px gray;" id="makeExport1" type="submit">دریافت خروجی
                </button>
            </form>
        </div>
        <div class="details" style="flex: 1 1 250px; max-width: 280px; border-right: 3px solid orange;">
            <h2>برون بری موارد ثبت نشده</h2>
            <p>موارد موجود برای خروجی گرفتن: <span style="background-color: orange; color: white; padding: 4px 6px; border-radius: 2px; "><?php echo count($exporter->unregistered_items); ?> مورد</span>
            </p>
            <form id="exportForm" method="GET" action="" onsubmit="return false">
                <button class="button button-primary" style="padding: 5px 10px; border: 1px solid #3858e9; box-shadow: 1px 1px 7px 1px gray;" id="makeExport2" type="submit">دریافت خروجی
                </button>
            </form>
        </div>
        <div class="details" style="flex: 1 1 250px; max-width: 280px; border-right: 3px solid green;">
            <h2>برون بری تمام موارد</h2>
            <p>موارد موجود برای خروجی گرفتن: <span style="background-color: green; color: white; padding: 4px 6px; border-radius: 2px; "><?php echo count($exporter->all_items); ?> مورد</span>
            </p>
            <form id="exportForm" method="GET" action="" onsubmit="return false">
                <button class="button button-primary" style="padding: 5px 10px; border: 1px solid #3858e9; box-shadow: 1px 1px 7px 1px gray;" id="makeExport3" type="submit">دریافت خروجی
                </button>
            </form>
        </div>
    </div>
</div>