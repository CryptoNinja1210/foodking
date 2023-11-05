"use strict";

$(document).on('click', '.modal-show', function(e) {
    $('#installer-modal').addClass('active');
});

$(document).on('click', '.modal-close', function(e) {
    $('#installer-modal').removeClass('active');
});
