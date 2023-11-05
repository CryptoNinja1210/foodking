"use strict";

/* Top bar scrolling start */
$(document).ready(function () {
    let headerSize = $('.db-main').width();
    if (headerSize < 736) {
        if ($('div').hasClass('sub-header')) {
            $('.sub-header').css('top', $(".db-header").prop("scrollHeight") + 'px');
        }
        $('.db-main').scroll(function (e) {
            let scroll = $('.db-main').scrollTop();
            if (scroll === 0) {
                $('.sub-header').show();
            } else {
                $('.sub-header').hide();
            }
        });
    }
});
/* Top bar scrolling close */

/* Frontend header fixed start */
$(window).on('scroll', function () {
    if (this.scrollY > 0) {
        $('.ff-header').addClass('active');
    } else {
        $('.ff-header').removeClass('active');
    }
});
/* Frontend header fixed close */

/* table filter open & closing */
$(document).on('click', '.table-filter-btn', function () {
    const height = $('.table-filter-div').prop("scrollHeight");
    const check = $('.table-filter-div').hasClass('active')
    if (!check) {
        $('.table-filter-div').addClass('active').css('height', height + 'px');
    } else {
        $('.table-filter-div').removeClass('active').css('height', '0px');
    }
});
/* table filter open & closing */

/* Profile stat */
$(document).on('click', '.profile-tabBtn', function () {
    $('.profile-tabBtn').each(function (i, obj) {
        obj.classList.remove('active');
        if (obj.attributes[1].value) {
            $(obj.attributes[1].value).removeClass('active');
        }
    });

    const dataTab = $(this).addClass('active').attr('data-tab');
    if (dataTab) {
        $(dataTab).addClass('active');
    }
});
/* Profile end */

/* Variation start */
$(document).on('click', '.size-tabs label', function () {
    $('.size-tabs label').each(function (i, obj) {
        obj.classList.remove('active');
    });
    $(this).addClass('active');
})

$(document).on('click', '.extra-swiper .extra', function () {
    if ($(this).find('input').prop('checked')) {
        $(this).find('input').prop('checked', false);
        $(this).removeClass('active');
    } else {
        $(this).find('input').prop('checked', true);
        $(this).addClass('active');
    }
});

$(document).on('click', '.addon-swiper .addon', function () {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $(this).addClass('active');
    }
});
/* Variation end */

/* Other button tab stat */
$(document).on('click', '.other-tabBtn', function () {
    $('.other-tabBtn').each(function (i, obj) {
        obj.classList.remove('active');
        if (obj.attributes[1].value) {
            $(obj.attributes[1].value).removeClass('active');
        }
    });

    const dataTab = $(this).addClass('active').attr('data-tab');
    if (dataTab) {
        $(dataTab).addClass('active');
    }
});
/* Other button tab end */

/* Sidebar toggle start */
$(document).on('click', '.db-header-nav', function () {
    if (!$(this).hasClass('fa-bars')) {
        $(this).removeClass('fa-align-left').addClass('fa-bars');
        $('.db-sidebar').addClass('active');
        $('.db-main').addClass('expand');
    } else {
        $(this).removeClass('fa-bars').addClass('fa-align-left');
        $('.db-sidebar').removeClass('active');
        $('.db-main').removeClass('expand');
    }
});

$(document).on('click', '.close-db-menu', function () {
    $('.db-header-nav').removeClass('fa-bars').addClass('fa-align-left');
    $('.db-sidebar').removeClass('active');
    $('.db-main').removeClass('expand');
});
/* Sidebar toggle close */

/* Web cart, Mobile cart, profile toggle start */
$(document).ready(function () {
    $.fn.openClose = function (selectorName, activeClass, closeClass) {
        $(selectorName).addClass(activeClass);
        document.body.style.overflowY = "hidden";

        $(document).on('click', closeClass, function () {
            $(selectorName).removeClass(activeClass);
            document.body.style.overflowY = "auto";
        });
    }

    $(document).on('click', ".webcart", function () {
        $.fn.openClose('#cart', 'active', '.xmark-btn');
    });

    $(document).on('click', ".mobcart", function () {
        $.fn.openClose('#cart', 'active', '.xmark-btn');
    });

    $(document).on('click', ".user-profile-dropdown-box", function () {
        $.fn.openClose('#user-profile-dropdown-box', 'active', '.xmark-btn');
    });
});
/* Web cart, Mobile cart, profile toggle close */

/* Tab start */
$(document).on('click', '.db-tabBtn', function () {
    $('.db-tabBtn').each(function (i, obj) {
        obj.classList.remove('active');
        if (obj.attributes[2].value) {
            $(obj.attributes[2].value).removeClass('active');
        }
    });

    const dataTab = $(this).addClass('active').attr('data-tab');
    if (dataTab) {
        $(dataTab).addClass('active');
    }
});
/* Tab close */

/* button switch start */
$(document).on('change', '.custom-switch input', function () {
    let isChecked = $(this).prop('checked');
    if (isChecked) {
        $(this).next().text('on');
    } else {
        $(this).next().text('off');
    }
});
/* button switch close */

/* POS responsive start */
$(document).on('click', '.db-pos-cartBtn', function () {
    if (!$('.db-pos-cartDiv').hasClass('active')) {
        $('.db-pos-cartDiv').addClass('active');
    } else {
        $('.db-pos-cartDiv').removeClass('active');
    }
});

$(document).on('click', '.db-pos-cartCls', function () {
    $('.db-pos-cartDiv').removeClass('active');
});
/* POS responsive close */


/* Message code start */
$(document).on('change', '#media', function () {
    let fileName = $(this).val().split('\\').pop();
    let fileList = $(".chat-footer-data-list").removeClass('hidden');
    let fileItem = `<li class="chat-footer-data-item">
                     <i class="fa-solid fa-file-lines"></i>
                     <span>${fileName}</span>
                     <button class="fa-solid fa-circle-xmark close-the-image-file" type="button"></button>
                 </li>`;
    fileList.append(fileItem);
});
$(document).on('click', '.close-the-image-file', function () {
    $(this).parent().remove();
    $(".chat-footer-data-list").addClass('hidden');
})
/* Message code end */


/* Active side bar menu start */
$('.db-sidebar-nav-menu').each(function (i, obj) {
    if (obj.classList.contains('router-link-exact-active')) {
        obj.parentElement.classList.add('active');
    }
});

$(document).on('click', '.db-sidebar-nav-menu', function () {
    $('.db-sidebar-nav-menu').each(function (i, obj) {
        if (obj.parentElement.classList.contains('active')) {
            obj.parentElement.classList.remove('active')
        }
    });
    $(this).parent().addClass('active');
});

$(document).on('click', '.db-breadcrumb-item', function () {
    $('.db-sidebar-nav-menu').each(function (i, obj) {
        if (obj.parentElement.classList.contains('active')) {
            obj.parentElement.classList.remove('active')
        }
    });

    $('.db-sidebar-nav-menu').each(function (i, obj) {
        if (obj.classList.contains('router-link-exact-active')) {
            obj.parentElement.classList.add('active');
        }
    });
});
/* Active side bar menu end */

/* Installer menu active code start */
const handleLinkForInstaller = (param) => {
    window.location.replace(param);
}
$(document).on('click', '.close-alert-button', function() {
    $(this).parent().remove();
})
/* Installer menu active code close */


/* Setting menu code start */
$(function() {
    let toggleValue = false;
    $(document).on('click', '.settings-btn', function() {
        toggleValue = !toggleValue
        const pixel = $(this).siblings().prop('scrollHeight');
        if(toggleValue) {
            $(this).siblings().css('height', `${pixel}px`);
            $(this).children().last().removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            $(this).siblings().css('height', `0px`);
            $(this).children().last().removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    })
})
/* Setting menu code end */


