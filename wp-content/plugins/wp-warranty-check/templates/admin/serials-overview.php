<?php

namespace Trust\Templates;

use Trust\Templates\LocalQueries as LocalQueries;

require_once TRUST_TPL . '/admin/query.php';

//  get all warranty serials added to database
$page_number = 1;
$pages_count = 1;
if (isset($_GET['page_number']) && is_numeric($_GET['page_number'])) {
    $page_number = $_GET['page_number'];
}

//  create a range of 120 items
$i = 120 * (intval($page_number) - 1);
$serials = $wpdb->get_results(
    "SELECT id,serial,is_registered,product FROM {$wpdb->prefix}wpwv_serials LIMIT {$i},120"
);

$serials_count = count($wpdb->get_results(
    "SELECT id FROM {$wpdb->prefix}wpwv_serials"
));
$pages_count = floor($serials_count / 120);
if ($serials_count % 120 != 0) {
    $pages_count++;
}

$LQ = new LocalQueries();
?>
<div class="widefat">
    <div class="wrap">
        <h2>کدهای گارانتی</h2>
        <div style="margin: 10px 0; display: flex; gap: 5px;">
            <a class="button" style="display: flex; align-items: center; width: max-content;" href="<?php echo $LQ->make_query('page', 'wpw_add_serial'); ?>">
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                افزودن مورد جدید
            </a>
            <a class="button button-primary" style="color:white; display: flex; align-items: center; width: max-content;" href="<?php echo $LQ->make_query('page', 'wpwv_import'); ?>">
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                </svg>
                درون ریزی
            </a>
        </div>
        <hr>
        <div class="header_controls">
            <ul class="page_info">
                <li>
                    صفحه <?php echo isset($_GET['page_number']) ? $_GET['page_number'] : '1'; ?>
                </li>
                <li>
                    در حال نمایش <?php echo count($serials); ?> مورد
                </li>
                <li style="background-color: #a1ea1e;">
                    تمام گارانتی ها: <?php echo $serials_count; ?>
                </li>
            </ul>
            <form class="flexed">
                <select name="search_warranties_by" id="search_warranties_by">
                    <option value="serial">سریال</option>
                    <option value="product">توضیحات</option>
                </select>
                <div>
                    <input type="text" name="search_warranties" id="search_warranties" placeholder="جستجو..." />
                    <button name="begin_search_warranties" id="begin_search_warranties" class="button flexed">
                        <span class="dashicons dashicons-search"></span>
                    </button>
                </div>
            </form>
            <div class="flexed">
                <div class="actions">
                    <button style="color: red; border-color: red;" class="button flexed" id="bulk_delete" disabled>
                        <span class="dashicons dashicons-trash"></span>
                        حذف
                    </button>
                </div>
                <div class="pagination">
                    <?php
                    if ($pages_count > 1) {
                        if (isset($_GET['page_number']) && intval($_GET['page_number']) != 1) echo "<a href='" . add_query_arg(['page_number' => intval($_GET['page_number']) - 1]) . "' class='button button-primary flexed'><span class='dashicons dashicons-arrow-right'></span>صفحه قبل</a>";
                        if (((isset($_GET['page_number']) && intval($_GET['page_number']) != $pages_count))) echo "<a href='" . add_query_arg(['page_number' => intval($_GET['page_number']) + 1]) . "' class='button button-primary flexed'>صفحه بعد<span class='dashicons dashicons-arrow-left'></span></a>";
                        elseif (!isset($_GET['page_number'])) echo "<a href='" . add_query_arg(['page_number' => 2]) . "' class='button button-primary flexed'>صفحه بعد<span class='dashicons dashicons-arrow-left'></span></a>";
                    } ?>
                </div>
            </div>
        </div>
        <table id="ax_table">
            <thead>
                <th class="manage_column column_cb chek_column">
                    <label class="screen_reader_text" for="cb_select_all_1"></label>
                    <input type="checkbox" name="cb_select_all_1" id="cb_select_all_1" />
                </th>
                <th>ردیف/شناسه</th>
                <th>سریال</th>
                <th>محصول</th>
                <th>ثبت شده؟</th>
            </thead>
            <tbody id="results">
            </tbody>
            <tr id="loading_state_row" style="height: 50px;">
                <td id="loading_state" class="colspanchange" colspan="5" style="padding: 20px; text-align: center; font-size: 36px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="font-size: 36px; width: 36px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    <p style="color: grey;">در حال دریافت...</p>
                </td>
            </tr>
            <tr id="empty_state_row" style="height: 50px; display: none;">
                <td id="empty_state" class="colspanchange" colspan="5" style="padding: 20px; text-align: center; font-size: 36px;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="font-size: 36px; width: 36px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p style="color: grey;">موردی یافت نشد!</p>
                </td>
            </tr>
            <tfoot>
                <th class="manage_column column_cb chek_column">
                    <label class="screen_reader_text" for="cb_select_all_2"></label>
                    <input type="checkbox" name="cb_select_all_2" id="cb_select_all_2" />
                </th>
                <td>ردیف/شناسه</td>
                <td>سریال</td>
                <td>محصول</td>
                <td>ثبت شده؟</td>
            </tfoot>
        </table>
        <div class="wrap">
            <div class="pagination-footer">
                <?php
                for ($index = 1; $index <= $pages_count; $index++) {
                    if ((isset($_GET['page_number']) && $_GET['page_number'] == $index) || $index === 1 && !isset($_GET['page_number'])) {
                        echo "<a href='" . add_query_arg(['page_number' => $index]) . "' class='button button-primary'>$index</a>";
                    } else {
                        echo "<a href='" . add_query_arg(['page_number' => $index]) . "' class='button'>$index</a>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    var $ = jQuery;
    // register a handler function for single deletion
    function handleDelete(id) {
        if (confirm('از حذف این مورد اطمینان دارید؟')) {
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: {
                    action: 'delete_single_warranty',
                    uid: id,
                },
                success: function() {
                    $(`#ax_table tbody#results tr[data-uid=${id}]`).remove()
                    window.location.reload()
                },
                error: function(error) {
                    alert(error.responseText)
                },
            })
        }
    }
    $(document).ready(function() {


        // initiate a container array for the data
        let items = []

        // initiate state variables
        let total_items_in_page
        let checked_items

        // set UI state to loading
        $('#loading_state_row').show()

        // ajax query to retrieve the data according to the page number
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                nonce: '<?php echo wp_create_nonce("get_warranty_items"); ?>',
                i: '<?php echo $i; ?>',
                action: 'get_warranty_items',
                term: null,
                filter_by: null,
            },
            success: function(response) {
                response.data.forEach((item) => {
                    items.push(item)
                })
                $('#current_page_items span').text(items.length)
                total_items_in_page = items.length
                checked_items = 0

                // append the data to the table if there is any
                if (items.length > 0) {
                    items.forEach((item) => {
                        $('#ax_table tbody#results').append(`
                            <tr data-uid="${item.id}">
                                <th class="chek_column" scope="row">
                                    <label class="screen_reader_text" for="cb_select_${item.id}"></label>
                                    <input id="cb_select_${item.id}" type="checkbox" name="post[]" value="${item.id}" />
                                </th>
                                <td id="item_id">
                                    ${item.id}
                                </td>
                                <td id='idents'>
                                    <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                        ${item.serial}
                                    </a>
                                    <div>
                                        <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                            <span class="dashicons dashicons-edit"></span>
                                        </a>
                                        <a href="javascript:handleDelete(${item.id})">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </div>
                                </td>
                                <td>${item.product} ${item.order_id ? ' - <span style="background: purple;border-radius: 5px;padding: 1px 8px;color: white;box-shadow: 1px 3px 3px gray;">ووکامرس - سفارش ' + item.order_id + '</span>' : ''}</td>
                                <td>${item.is_registered == 'yes' ? '✅' : '❌'} ${item.is_registered}</td>
                            </tr>
                        `)
                    })

                    // register a handler function for single selection
                    $('#ax_table #results tr th input:checkbox[name="post[]"]').on('click', function() {
                        if (!$(this).prop('checked')) {
                            checked_items -= 1

                            if ($('#ax_table #cb_select_all_1').prop('checked')) {
                                $('#ax_table #cb_select_all_1').prop('checked', false)
                                $('#ax_table #cb_select_all_2').prop('checked', false)
                            }
                        } else if (
                            $(this).prop('checked') &&
                            !$('#ax_table #cb_select_all_1').prop('checked')
                        ) {
                            checked_items += 1

                            if (checked_items == total_items_in_page) {
                                $('#ax_table #cb_select_all_1').prop('checked', true)
                                $('#ax_table #cb_select_all_2').prop('checked', true)
                            }
                        }

                        if (checked_items > 0) $('#bulk_delete').prop('disabled', false)
                        else $('#bulk_delete').prop('disabled', true)
                    })
                } else $('#empty_state_row').show()

                // set UI state to loaded
                $('#loading_state_row').hide()
            },
            error: function(error) {
                alert('error: ' + error.responseText)
            },
        })

        // register a handler function for top bulk selection
        $('#cb_select_all_1').on('click', function() {
            if ($(this).is(':checked')) checked_items = total_items_in_page
            else checked_items = 0

            $('#ax_table #cb_select_all_2').prop(
                'checked',
                $('#cb_select_all_1').prop('checked'),
            )
            $('#ax_table input:checkbox[name="post[]"]').each(function() {
                $(this).prop('checked', $('#ax_table #cb_select_all_1').prop('checked'))
            })

            if (checked_items > 0) $('#bulk_delete').prop('disabled', false)
            else $('#bulk_delete').prop('disabled', true)
        })

        // register a handler function for bottom bulk selection
        $('#cb_select_all_2').on('click', function() {
            if ($(this).is(':checked')) checked_items = total_items_in_page
            else checked_items = 0

            $('#ax_table #cb_select_all_1').prop(
                'checked',
                $('#cb_select_all_2').prop('checked'),
            )
            $('#ax_table input:checkbox[name="post[]"]').each(function() {
                $(this).prop('checked', $('#cb_select_all_2').prop('checked'))
            })

            if (checked_items > 0) $('#bulk_delete').prop('disabled', false)
            else $('#bulk_delete').prop('disabled', true)
        })

        // register a handler function for bulk delete
        $('#bulk_delete').on('click', function() {
            if (total_items_in_page > 0 && checked_items > 0) {
                if (confirm('از حذف این مورد اطمینان دارید؟')) {
                    let ids = []
                    $('#ax_table input:checkbox[name="post[]"]:checked').each(function() {
                        ids.push($(this).val())
                    })
                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php"); ?>',
                        type: 'POST',
                        data: {
                            action: 'delete_multiple_warranties',
                            uids: ids,
                        },
                        success: function() {
                            window.location.reload()
                        },
                        error: function(error) {
                            alert(error.responseText)
                        },
                    })
                }
            }
        })

        $('#begin_search_warranties').on('click', function(e) {
            // prevent page from reloading
            e.preventDefault()

            // initiate array to hold search results
            let new_items = []

            // set UI state to loading
            $('#loading_state_row').show()

            // check if there is a term provided to perform search
            if (!$('#search_warranties').val()) {
                alert('فیلد جستجو نمیتواند خالی باشد!')
                $('#current_page_items span').text(items.length)
                if (items.length > 0) {
                    items.forEach((item) => {
                        $('#ax_table tbody#results').append(`
                            <tr data-uid="${item.id}">
                                <th class="chek_column" scope="row">
                                    <label class="screen_reader_text" for="cb_select_${item.id}"></label>
                                    <input id="cb_select_${item.id}" type="checkbox" name="post[]" value="${item.id}" />
                                </th>
                                <td id="item_id">
                                    ${item.id}
                                </td>
                                <td id='idents'>
                                    <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                        ${item.serial}
                                    </a>
                                    <div>
                                        <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                            <span class="dashicons dashicons-edit"></span>
                                        </a>
                                        <a href="javascript:handleDelete(${item.id})">
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </div>
                                </td>
                                <td>${item.product}</td>
                                <td>${item.is_registered == 'yes' ? '✅' : '❌'} ${item.is_registered}</td>
                            </tr>
                        `)
                    })
                } else $('#empty_state_row').show()

                // set UI state to loaded
                $('#loading_state_row').hide()
                return
            }

            // check search conditions match warranty mode
            if ($('#search_warranties_by option:selected').val()) {
                $('#ax_table tbody#results').empty()
                $('#loading_state_row').show()
                $('#empty_state_row').hide()

                $.ajax({
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    type: 'POST',
                    data: {
                        nonce: '<?php echo wp_create_nonce("get_warranty_items"); ?>',
                        i: '<?php echo $i; ?>',
                        action: 'get_warranty_items',
                        term: $('#search_warranties').val(),
                        filter_by: $('#search_warranties_by option:selected').val(),
                    },
                    success: function(response) {
                        $('#empty_state_row').hide()
                        response.data.forEach((item) => new_items.push(item))
                        total_items_in_page = new_items.length
                        $('#current_page_items span').text(new_items.length)

                        if (new_items.length > 0) {
                            new_items.forEach((item) => {
                                $('#ax_table tbody#results').append(`
                                    <tr data-uid="${item.id}">
                                        <th class="chek_column" scope="row">
                                            <label class="screen_reader_text" for="cb_select_${item.id}"></label>
                                            <input id="cb_select_${item.id}" type="checkbox" name="post[]" value="${item.id}" />
                                        </th>
                                        <td id="item_id">
                                            ${item.id}
                                        </td>
                                        <td id='idents'>
                                            <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                                ${item.serial}
                                            </a>
                                            <div>
                                                <a href="<?php echo admin_url('admin.php'); ?>?page=wpw_overview&action=edit&id=${item.id}">
                                                    <span class="dashicons dashicons-edit"></span>
                                                </a>
                                                <a href="javascript:handleDelete(${item.id})">
                                                    <span class="dashicons dashicons-trash"></span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>${item.product}</td>
                                        <td>${item.is_registered == 'yes' ? '✅' : '❌'} ${item.is_registered}</td>
                                    </tr>
                                `)
                            })
                        } else $('#empty_state_row').show()

                        // set UI state to loaded
                        $('#loading_state_row').hide()
                    },
                    error: function(error) {
                        alert(error.responseText)
                    },
                })
            }

        })
    })
</script>
<style>
    .page_info {
        display: flex;
        flex-flow: row wrap;
        gap: 5px;
        margin: 0;
    }

    .page_info li {
        background: white;
        padding: 4px 8px;
        border-radius: 4px;
        box-shadow: 0 2px 2px 1px #d7d7d7;
        margin: 0;
    }

    .pagination,
    .pagination-footer {
        display: flex;
        flex-flow: row wrap;
        gap: 2px;
        justify-content: center;
        height: max-content;
    }

    .pagination-footer .button,
    .pagination-footer .button-primary {
        width: 34px;
        height: 28px;
        max-width: 34px;
        text-align: center;
    }

    #empty_state_row svg,
    #empty_state_row p,
    #loading_state_row svg,
    #loading_state_row p {
        animation: blink 1.5s linear infinite;
    }

    .wrap #ax_table #idents div {
        display: flex;
        flex-flow: row wrap;
        gap: 5px;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 0.1;
            scale: (0.8);
        }

        50% {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>