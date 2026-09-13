'use strict'
var $ = jQuery

$.when( $.ready ).then(function() {
    const toggleWarranty = $('#warranty_enabled')
    const periodInput = $('#warranty_period')
    const periodUnit = $('#period_unit')
    const isUnlimited = $('#unlimited_period')

    if (toggleWarranty.is(':checked')) {
        $('#warranty_details').css('opacity', '1')
    }
    if(isUnlimited.is(':checked')) {
        periodInput.val(0)
        periodInput.prop('disabled', true)
        periodUnit.prop('disabled', true)
    }

    toggleWarranty.on('click', function() {
        if ( !toggleWarranty.is(':checked') ) {
            $('#warranty_details').css('opacity', '0')
        } else {
            $('#warranty_details').css('opacity', '1')
        }
    })

    isUnlimited.on('click', function() {
        if(isUnlimited.prop('checked')) {
            periodInput.val(0)
            periodInput.prop('disabled', true)
            periodUnit.prop('disabled', true)
        } else {
            periodInput.prop('disabled', false)
            periodUnit.prop('disabled', false)
        }
    })
})

