<?php
namespace Trust\Templates;
use Trust\TrustWarrantyPlugin as Trust;
?>
<form method="post">
  <div class="ax_panel">
    <!-- Tab links -->
    <div class="ax_tab">
      <button type="button" class="ax_tablinks active" onclick="openTab(event, 'general')">تنظیمات عمومی</button>
      <button type="button" class="ax_tablinks" onclick="openTab(event, 'forms')">فرم ها</button>
      <button type="button" class="ax_tablinks" onclick="openTab(event, 'messages')">پیام های فرم</button>
      <button type="button" class="ax_tablinks" onclick="openTab(event, 'sms')">پیامک</button>
      <!-- <button type="button" class="ax_tablinks" onclick="openTab(event, 'email')">ایمیل</button> -->
    </div>
    <!-- Tab content -->
    <div id="general" class="ax_tabcontent" style="display: flex;">
      <div class="wrap">
        <?php include_once TRUST_TPL . 'admin/panel/general-settings.php'; ?>
      </div>
    </div>

    <div id="forms" class="ax_tabcontent">
      <div class="wrap">
        <?php include_once TRUST_TPL . 'admin/panel/forms.php'; ?>
      </div>
    </div>

    <div id="messages" class="ax_tabcontent">
      <div class="wrap">
        <?php include_once TRUST_TPL . 'admin/panel/form-messages.php'; ?>
      </div>
    </div>

    <div id="sms" class="ax_tabcontent">
      <div class="wrap">
        <?php include_once TRUST_TPL . 'admin/panel/sms.php'; ?>
      </div>
    </div>

    <!-- <div id="email" class="ax_tabcontent">
      <div class="wrap">
        <?php //include_once TRUST_TPL . 'admin/panel/email.php'; ?>
      </div>
    </div> -->

    <aside>
      <button class="ax_save ax_btn ax_primary" name="saveData" id="submit" type="submit">ذخیره تنظیمات</button>
    </aside>
  </div>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#ax_software_info').innerHTML = "<small>نسخه افزونه: <span><?php echo Trust::instance()->get_version(); ?></span><br/>نسخه دیتابیس: <span><?php echo Trust::instance()->get_db_version(); ?></span></small>"
  });
</script>