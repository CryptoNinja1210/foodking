"use strict";

$(document).on('click', '.dropdown-btn', function(e) {
    e.stopPropagation();
    let check = $(this).parent().children().eq(1).hasClass('active');
    if(!check) {
        $(this).parent().children().eq(1).addClass('active');
    } else {
        $(this).parent().children().eq(1).removeClass('active');
    }
});

$(document).on('click', '.dropdown-list', function() {
    let check = $(this).hasClass('active');
    if(check) {
        $(this).removeClass('active');
    }
});
