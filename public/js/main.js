;(function ($) {

    "use strict";

    var selectImages = function () {
        if ($(".image-select").length > 0) {
            const selectIMG = $(".image-select");
            selectIMG.find("option").each((idx, elem) => {
                const selectOption = $(elem);
                const imgURL = selectOption.attr("data-thumbnail");
                if (imgURL) {
                    selectOption.attr(
                        "data-content",
                        "<img src='%i'/> %s"
                            .replace(/%i/, imgURL)
                            .replace(/%s/, selectOption.text())
                    );
                }
            });
            selectIMG.selectpicker();
        }
    };

    var menuleft = function () {
        if ($('div').hasClass('section-menu-left')) {
            var bt = $(".section-menu-left").find(".has-children");
            bt.on("click", function () {
                var args = {duration: 200};
                if ($(this).hasClass("active")) {
                    $(this).children(".sub-menu").slideUp(args);
                    $(this).removeClass("active");
                } else {
                    $(".sub-menu").slideUp(args);
                    $(this).children(".sub-menu").slideDown(args);
                    $(".menu-item.has-children").removeClass("active");
                    $(this).addClass("active");
                }
            });
            $('.sub-menu-item').on('click', function (event) {
                event.stopPropagation();
            });
        }
    };

    var tabs = function () {
        $('.widget-tabs').each(function () {
            $(this).find('.widget-content-tab').children().hide();
            $(this).find('.widget-content-tab').children(".active").show();
            $(this).find('.widget-menu-tab').find('li').on('click', function () {
                var liActive = $(this).index();
                var contentActive = $(this).siblings().removeClass('active').parents('.widget-tabs').find('.widget-content-tab').children().eq(liActive);
                contentActive.addClass('active').fadeIn("slow");
                contentActive.siblings().removeClass('active');
                $(this).addClass('active').parents('.widget-tabs').find('.widget-content-tab').children().eq(liActive).siblings().hide();
            });
        });
    };

    $('ul.dropdown-menu.has-content').on('click', function (event) {
        event.stopPropagation();
    });
    $('.button-close-dropdown').on('click', function () {
        $(this).closest('.dropdown').find('.dropdown-toggle').removeClass('show');
        $(this).closest('.dropdown').find('.dropdown-menu').removeClass('show');
    });

    var progresslevel = function () {
        if ($('div').hasClass('progress-level-bar')) {
            var bars = document.querySelectorAll('.progress-level-bar > span');
            setInterval(function () {
                bars.forEach(function (bar) {
                    var t1 = parseFloat(bar.dataset.progress);
                    var t2 = parseFloat(bar.dataset.max);
                    var getWidth = (t1 / t2) * 100;
                    bar.style.width = getWidth + '%';
                });
            }, 500);
        }
    }

    var collapse_menu = function () {
        $(".button-show-hide").on("click", function () {
            $('.layout-wrap').toggleClass('full-width');
        })
    }

    var showpass = function () {
        $(".show-pass").on("click", function () {
            $(this).toggleClass("active");
            var input = $(this).parents(".password").find(".password-input");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else if (input.attr("type") === "text") {
                input.attr("type", "password");
            }
        });
    }

    var gallery = function () {
        $(".button-list-style").on("click", function () {
            $(".wrap-gallery-item").addClass("list");
        });
        $(".button-grid-style").on("click", function () {
            $(".wrap-gallery-item").removeClass("list");
        });
    }

    var clipboardcopy = function () {
        $(".clipboard_copy").on("click", function () {
            var targetEl = $(this).data("target");
            copyToClipboard(targetEl);
        });

        function copyToClipboard(targetEl) {
            var copyText = document.querySelector(targetEl).innerText;
            navigator.clipboard.writeText(copyText).then(function () {
                //console.log('Text copied to clipboard');
            }).catch(function (err) {
                console.error('Error in copying text: ', err);
            });
        }
    }


    document.addEventListener('DOMContentLoaded', function () {
        const elements = document.querySelectorAll('.clipboard_copy');

        elements.forEach(element => {
            element.addEventListener('click', function () {

                if (element.querySelector('.clipboard_copy_message')) {
                    return;
                }

                const textSpan = document.createElement('span');
                textSpan.innerText = 'Copied to clipboard';
                textSpan.classList.add('mt-1', 'fs-4', 'error', 'text-success', 'd-block', 'clipboard_copy_message');

                textSpan.style.display = 'inline';
                textSpan.style.marginLeft = '5px';
                element.appendChild(textSpan);

                setTimeout(function () {
                    textSpan.remove();
                }, 5000);
            });
        });
    });


    var select_colors_theme = function () {
        if ($('div').hasClass("select-colors-theme")) {
            $(".select-colors-theme .item").on("click", function (e) {
                $(this).parents(".select-colors-theme").find(".active").removeClass("active");
                $(this).toggleClass("active");
            })
        }
    }

    var icon_function = function () {
        if ($('div').hasClass("list-icon-function")) {
            $(".list-icon-function .trash").on("click", function (e) {
                $(this).parents(".product-item").remove();
                $(this).parents(".attribute-item").remove();
                $(this).parents(".countries-item").remove();
                $(this).parents(".user-item").remove();
                $(this).parents(".roles-item").remove();
            })
        }
    }

    var box_search = function () {

        $(document).on('click', function (e) {
            var clickID = e.target.id;
            if ((clickID !== 's')) {
                $('.box-content-search').removeClass('active');
            }
        });
        $(document).on('click', function (e) {
            var clickID = e.target.class;
            if ((clickID !== 'a111')) {
                $('.show-search').removeClass('active');
            }
        });

        $('.show-search').on('click', function (event) {
                event.stopPropagation();
            }
        );
        $('.search-form').on('click', function (event) {
                event.stopPropagation();
            }
        );
        var input = $('.header-dashboard').find('.form-search').find('input');
        input.on('input', function () {
            if ($(this).val().trim() !== '') {
                $('.box-content-search').addClass('active');
            } else {
                $('.box-content-search').removeClass('active');
            }
        });

    }

    var retinaLogos = function () {
        var retina = window.devicePixelRatio > 1 ? true : false;
        if (retina) {
            /*   if ($(".dark-theme").length > 0) {
                 $('#logo_header').attr({src:'logo-white.png',width:'154px',height:'52px'});
               } else {
                 $('#logo_header').attr({src:'logo-main.png',width:'154px',height:'52px'});
               }*/
        }
    };

    var preloader = function () {
        setTimeout(function () {
            $("#preload").fadeIn("slow", function () {
                $(this).remove();
            });
        }, 500);
    };


    // Dom Ready
    $(function () {
        selectImages();
        menuleft();
        tabs();
        progresslevel();
        collapse_menu();
        showpass();
        gallery();
        clipboardcopy();
        select_colors_theme();
        icon_function();
        box_search();
        retinaLogos();
        preloader();

    });

})(jQuery);
