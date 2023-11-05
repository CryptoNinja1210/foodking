"use strict";

$(document).on('change', '.paymentMethod', function () {
    let paymentMethod = $(this).val();
    for (let item in gateway) {
        if (item === paymentMethod) {
            if (gateway[item]) {
                $('#' + item + '_div').show();
            }
        } else {
            $('#' + item + '_div').hide();
        }
    }
});

$(document).on('click', '.close-button', function() {
    $(this).parent().hide();
});

let form = document.getElementById('paymentForm');
form.addEventListener('submit', function (event) {
    event.preventDefault();
    let paymentMethod = $('input[name=paymentMethod]:checked', '#paymentForm').val()
    let submit = false;
    for (let item in submitGateway) {
        if (item === paymentMethod) {
            submit = true;
            window[paymentMethod + '_payment']();
            break;
        }
    }

    if (submit === false) {
        form.submit();
    }
});
