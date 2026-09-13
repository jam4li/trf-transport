<?php
/*** راهنمای استفاده ***/
/*
ابتدا این فایل را در پوشه اصلی قالب یا افزونه خود قرار دهید.
سپس کدهای داخل کادر پایین را در ابتدای فایل functions قالب یا ابتدای فایل اصلی افزونه (بعد از کامنت های افزونه) وارد نمایید
 ----------------------------------------------------------------------------------------------------------------
include_once __DIR__.'/ion-checker.php';
if (!empty($ioncube_error_checker)){
    add_action('admin_notices', function () use ($ioncube_error_checker){printf('<div class="notice notice-error notice-alt"> <p>%s</p> </div>',implode('<hr>',$ioncube_error_checker));},1);
    return;
}
----------------------------------------------------------------------------------------------------------------
*/

defined('ABSPATH') || exit ("no access");

$min_loader_version="12.0";
$min_php_version="7.4";
$ioncube_error_checker=[];

if (!extension_loaded('ionCube Loader')){
    $ioncube_error_checker[]=sprintf('We detect you do not have ionCube loader , please call to your host service to install ionCube loader version to upper than %s',$min_loader_version);
}elseif (!function_exists('ioncube_loader_version') || version_compare(ioncube_loader_version(),$min_loader_version,'<')){
    $ioncube_error_checker[]=sprintf('We detect your ionCube loader is too old , please call to your host service to update ionCube loader version to upper than %s',$min_loader_version);
}
if(!version_compare(phpversion(),$min_php_version,'>=')) {
    $ioncube_error_checker[] = sprintf(
        'We detect your server php version is to old, this plugin need php version %s to up.  please call to your host service to update php',
        $min_php_version
    );
}


