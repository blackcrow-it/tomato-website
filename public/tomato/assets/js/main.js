(function ($) {
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * [isMobile description]
     * @type {Object}
     */

    window.isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }
    window.isIE = /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent);
    window.windowHeight = window.innerHeight;
    window.windowWidth = window.innerWidth;


    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };


    // format price
    function formatPrice(amount, decimalCount = 0, decimal = ".", thousands = ".") {
        decimalCount = Math.abs(decimalCount)
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount
        const negativeSign = amount < 0 ? "-" : ""
        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString()
        let j = (i.length > 3) ? i.length % 3 : 0
        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "")
    }


    // function random
    function getRandomIntInclusive(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min; //The maximum is inclusive and the minimum is inclusive
    }


    function headerJs() {
        var header = $('.header');
        var mobilescroll = $('.menu-mobile__nav')[0];
        if (header.length) {
            var headeroom = new Headroom(document.querySelector("header"), {
                tolerance: 4,
                offset: 100,
                classes: {
                    pinned: "header-pin",
                    unpinned: "header-unpin"
                },
                onPin: function () {

                },
                onUnpin: function () {},
            });
            headeroom.init();
        }

        function toggleMenu() {
            var toggle = $('.header__iconmenu .f-wrap');

            toggle.on('click', function (e) {
                e.stopPropagation();
                $('.menu-mobile').toggleClass('menu-mobile--active');
                $(this).closest('.header').toggleClass('header-show-mobile');

                if ($(this).closest('.header').hasClass('header-show-mobile')) {
                    bodyScrollLock.disableBodyScroll(mobilescroll);

                } else {
                    bodyScrollLock.enableBodyScroll(mobilescroll);
                }
            });
        }

        function toggleSearch() {
            $('.header__iconSearch').on('click', function (e) {
                e.preventDefault();
                $('.header-search').addClass('header-search__active');
            });

            $('.header-search__close').on('click', function () {
                $('.header-search').removeClass('header-search__active');
            });
        }

        function toggleCart() {
            $('.header__cart').on('click', function (e) {
                e.preventDefault();
                $('.cartbox').addClass('cartbox--show');
            });
        }

        toggleMenu();
        toggleSearch();
        toggleCart();
    }


    function headerMobile() {
        $('.menu-mobile__nav .menu-list li.menu-has-children').on('click', '> a', function (e) {
            e.preventDefault();
            $(this).next().slideToggle();
            $(this).toggleClass('active');
        });

        $('.menu-mobile__bg').on('click', function () {
            $('.header__iconmenu .f-wrap').trigger('click');
        })
    }


    function owlSlideJs() {
        function fixAutoplaySlide(item, time) {
            item.on('mouseenter', function (e) {
                item.trigger('stop.owl.autoplay');
            })

            item.on('mouseleave', function (e) {
                item.trigger('play.owl.autoplay', [time]);
            })
        }

        function dataSlideOneItem() {

            $('[data-slide-one-item]').each(function () {
                let self = $(this);
                let timeRandom = getRandomIntInclusive(5, 10) * 1000;

                self.owlCarousel({
                    items: 1,
                    dots: true,
                    margin: 18,
                    loop: true,
                    smartSpeed: 2000,
                    autoplay: true,
                    autoplayTimeout: timeRandom,
                    autoplayHoverPause: true,
                    responsive: {
                        768: {
                            margin: 30,
                        },
                    }
                });

                fixAutoplaySlide(self, timeRandom);
            });
        }

        function dataSlideThreeItem() {
            $('[data-slide-three-item]').each(function () {
                let self = $(this);
                let timeRandom = getRandomIntInclusive(5, 10) * 1000;

                self.owlCarousel({
                    items: 2,
                    dots: true,
                    margin: 18,
                    smartSpeed: 1000,
                    autoplay: true,
                    autoplayTimeout: timeRandom,
                    autoplayHoverPause: true,
                    nav: true,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                    responsive: {
                        768: {
                            margin: 30,
                        },
                        991: {
                            items: 3,
                            margin: 30,
                        },
                    }
                });

                fixAutoplaySlide(self, timeRandom);
            })
        }

        function dataSlideFourItem() {
            $('[data-slide-four-item]').each(function () {
                let self = $(this);
                let timeRandom = getRandomIntInclusive(5, 10) * 1000;

                self.owlCarousel({
                    items: 2,
                    margin: 18,
                    smartSpeed: 1000,
                    autoplay: true,
                    autoplayTimeout: timeRandom,
                    autoplayHoverPause: true,
                    dots: true,
                    nav: true,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],

                    responsive: {
                        768: {
                            items: 3,
                            margin: 30
                        },
                        991: {
                            items: 4,
                            margin: 30
                        },
                    }
                });

                fixAutoplaySlide(self, timeRandom);
            })
        }

        function teamSlideMobile() {
            var ww = $(window).width();

            if (ww < 991) {
                $('.team-slide-mobile .owl-carousel').owlCarousel({
                    items: 1,
                    dots: true,
                    margin: 18,
                    loop: true,
                    smartSpeed: 1000,
                    nav: true,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                    responsive: {
                        768: {
                            items: 2,
                            margin: 30
                        },
                    }
                });
            }
        }

        function slideTestimonial() {
            var wrap = $('.testimonial-slide'),
                owlItem = $('.owl-carousel', wrap),
                owlDot = $('.owl-dot-custom', wrap);

            owlItem.owlCarousel({
                items: 1,
                center: true,
                loop: true,
                dots: true,
                dotsContainer: '.testimonial-slide .owl-dot-custom',
                margin: 18,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                smartSpeed: 1000,
                nav: true,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            });

            owlDot.find('.owl-dot').on('click', function () {
                owlItem.trigger('to.owl.carousel', [$(this).index(), 1000]);
            });

            fixAutoplaySlide(owlItem, 6000);
        }

        function slideImgBookDetail() {
            var slide = $('.book-detail-img .owl-carousel'),
                customDot = $('.book-detail-img .owl-dot-custom');

            var owl = slide.owlCarousel({
                items: 1,
                center: true,
                loop: true,
                dots: true,
                dotsContainer: '.book-detail-img .owl-dot-custom',
                margin: 30,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                smartSpeed: 1000,
                nav: true,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            });

            customDot.find('.owl-dot').on('click', function () {
                owl.trigger('to.owl.carousel', [$(this).index(), 1000]);
            });

            fixAutoplaySlide(slide, 6000);
        }

        function widgetBookSlide() {
            $('.widget--book .owl-carousel').each(function () {
                let self = $(this);
                let timeRandom = getRandomIntInclusive(5, 10) * 1000;

                self.owlCarousel({
                    items: 1,
                    dots: true,
                    margin: 18,
                    loop: true,
                    smartSpeed: 2000,
                    autoplay: true,
                    autoplayTimeout: 10000,
                    autoplayHoverPause: true,
                    responsive: {
                        768: {
                            items: 3,
                            margin: 18
                        },
                        991: {
                            items: 4
                        },
                        1200: {
                            items: 1
                        }
                    }
                });
            });
        }

        function postRelatedCourse() {
            $('.post-detail__relatedCourse .owl-carousel').owlCarousel({
                items: 2,
                dots: true,
                margin: 18,
                smartSpeed: 1000,
                autoplayHoverPause: true,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                responsive: {
                    768: {
                        items: 3,
                        margin: 30,
                    },
                }
            });
        }

        function lessonboxRelated() {
            $('.lessonbox-related-slide').each(function () {
                let self = $(this);
                let timeRandom = getRandomIntInclusive(5, 10) * 1000;

                self.owlCarousel({
                    items: 2,
                    margin: 18,
                    smartSpeed: 1000,
                    autoplayHoverPause: true,
                    dots: true,
                    nav: true,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],

                    responsive: {
                        768: {
                            items: 3,
                            margin: 30
                        },
                        1200: {
                            items: 4,
                        }
                    }
                });
            })
        }

        dataSlideOneItem();
        dataSlideThreeItem();
        dataSlideFourItem();
        teamSlideMobile();
        slideTestimonial();
        slideImgBookDetail();
        widgetBookSlide();
        postRelatedCourse();
        lessonboxRelated();
    }


    function popupJs() {
        var magnificPopupDefault = {
            type: 'image',
            overflowY: 'scroll',
            fixedContentPos: true,
            image: {
                markup: `<div class="mfp-figure">
                            <div class="mfp-close"><svg x="0px" y="0px" width="39px" height="38px" viewBox="0 0 39 38"><path id="Line-6" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" d="M1.738,1.425l35.355,35.354"/><path id="Line-7" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="square" d="M1.031,36.779L36.387,1.425"/></svg></div>
                            <div class="mfp-image">
                                <div class="mfp-img"></div>
                            </div>
                            <div class="mfp-bottom-bar">
                                <div class="mfp-title"></div>
                            </div>
                        </div>`,
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                tCounter: false,
                titleSrc: function (item) {
                    return item.el.attr('title');
                }
            },
            removalDelay: 500,
            showCloseBtn: true,
            closeOnContentClick: false,
            closeBtnInside: true,
            callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = 'mfp-zoom-in';
                    $('body').addClass('body-fix-scroll');
                },

                close: function () {
                    $('body').removeClass('body-fix-scroll');
                }
            },
            midClick: true
        }

        $('[data-init="magnificPopup"]').each(function (index, el) {
            var $el = $(this);

            $el.magnificPopup(magnificPopupDefault);
        });


        $('[data-init="magnificPopupVideo"]').each(function (index, el) {
            var $el = $(this);
            var option = {
                type: 'iframe',
                disableOn: 500,
            }

            // Merge settings.
            var settings = $.extend(true, magnificPopupDefault, option);

            $el.magnificPopup(settings);
        });


        $('[data-init="magnificPopupInline"]').magnificPopup({
            type: 'inline',
            overflowY: 'scroll',
            fixedContentPos: true,
            preloader: false,
            showCloseBtn: true,
            closeOnContentClick: false,
            closeBtnInside: true,
            removalDelay: 500,
            callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = 'mfp-zoom-in';
                }
            }
        });
    }


    function stickyJs() {
        var height = $('.stickyJs.no-marginTop').length ? 0 : $('.header').outerHeight() + 10;

        $('.stickyJs .sticky').theiaStickySidebar({
            additionalMarginTop: 10,
            minWidth: 768
        });
    }


    function backtotop() {
        if ($('#backtotop').length) {
            var fix = function () {
                var scrolltop = Math.floor($(window).scrollTop() + $(window).height()),
                    offset = Math.floor($('.footer').offset().top);

                if (scrolltop >= offset) {
                    $('#backtotop').addClass('show-btn');
                } else {
                    $('#backtotop').removeClass('show-btn');
                }
            }
            fix();
            $(window).on('scroll', fix);

            $('#backtotop').on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 700);
            });
        }
    }

    function btnscrollOffset() {
        $('.btn-scroll-form').on('click', function (e) {
            e.preventDefault();
            var self = $(this),
                id = self.attr('href'),
                offset = $(id).offset().top;

            $('html,body').animate({
                scrollTop: offset
            }, 1000);
        })
    }

    function btnAddToCart() {
        $('.btn-add-to-cart').on('click', function (e) {
            e.preventDefault();
            var self = $(this);
            self.addClass('loading');

            var form = $(self.data('form'));
            $.post(form.attr('action'), form.serialize()).done(function () {
                self.removeClass('loading');
                self.addClass('added');
                $('.cartbox').addClass('cartbox--show');
                vueCartbox.getData();
            }).fail(function (err) {
                var msg = Object.values(err.responseJSON.errors).map(x => x.join("\n")).join("\n");
                alert(msg);
                self.removeClass('loading');
            });
        });

        $('.btn-buy-and').on('click', function (e) {
            e.preventDefault();
            setTimeout(function () {
                $('.cartbox').addClass('cartbox--show');
            }, 300);
        });

        $('.btn-buy-now[type="button"]').on('click', function (e) {
            e.preventDefault();
            var self = $(this);
            self.prop('disabled', true);

            var form = $(self.data('form'));
            if (form.length == 0) {
                location.href = self.data('redirect');
                return;
            }

            $.post(form.attr('action'), form.serialize()).done(function () {
                location.href = self.data('redirect');
            }).fail(function (err) {
                var msg = Object.values(err.responseJSON.errors).map(x => x.join("\n")).join("\n");
                alert(msg);
                self.prop('disabled', false);
            });
        });
    }


    function alertboxClose() {
        $(document).on('click', '.alertbox__close', function () {
            const self = $(this);
            self.parent().slideUp(400);
            setTimeout(function () {
                self.parent().remove();
            }, 600);
        });
    }


    function validateForm() {
        $(".consultationForm__form").validate({
            rules: {
                name: "required",
                phone: {
                    required: true,
                    minlength: 9,
                    number: true
                },
                email: {
                    required: true,
                    email: true
                },
                course: {
                    required: true
                }
            },
            messages: {
                name: "<b>Họ và tên</b> không được để trống",
                phone: {
                    required: "<b>Số điện thoại</b> không được để trống",
                    minlength: "<b>Số điện thoại</b> không đúng",
                    number: "<b>Số điện thoại</b> không phải dạng số"
                },
                email: {
                    required: "<b>Email</b> không được để trống",
                    email: '<b>Email</b> không đúng định dạng',
                },
                select: {
                    required: "<b>Khoá học</b> không được để trống",
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == element[0].name)
                    $(element).closest('.input-item__inner').after(error);
            },
            submitHandler: function (form) {
                var item = $(form),
                    name = item.find('[name="name"]').val(),
                    course = item.find('[name="course"]').val();

                $('#consultationForm-modal').find('.show-name').text(name);
                $('#consultationForm-modal').find('.show-course').text(course);
            }
        });
    }


    function lessonboxPlayVideo() {
        var wrap = $('.embed-responsive');

        wrap.each(function () {
            var self = $(this),
                btn = $('.btn-play', self),
                iframe = $('iframe', self);

            btn.on('click', function (e) {
                e.preventDefault();
                jQuery(iframe)[0].src += "&autoplay=1";
                setTimeout(function () {
                    self.addClass('active');
                }, 500);
            });
        })
    }


    function checkValidate(item) {
        item.keypress(function (e) {
            var key = window.event ? event.keyCode : event.which;

            if (event.keyCode === 8 || event.keyCode === 46) {
                return true;
            } else if (key < 48 || key > 57) {
                return false;
            } else {
                return true;
            }
        });
    }


    function inputQuantity() {
        $('.input-quantity').each(function () {
            var self = $(this),
                input = $('.input-quantity-text', self),
                btn = $('.input-quantity-number', self),
                max = input.data('max'),
                min = input.data('min'),
                value = input.val();

            checkValidate(input);

            function clickButton() {
                if (value > max) {
                    input.val(max);


                } else if (value < min) {
                    input.val(min);
                } else {

                }

                btn.on('click', function () {
                    var el = $(this),
                        value = input.val();

                    if (el.hasClass('input-quantity-up')) {
                        var newVal = parseFloat(value) + 1;

                        if (newVal > max) {
                            el.addClass('disable');
                            return;
                        } else {
                            el.removeClass('disable');
                        }
                    } else {
                        if (value > 1) {
                            var newVal = parseFloat(value) - 1;
                        } else {
                            newVal = 0;
                        }

                        if (newVal < min) return;
                    }
                    input.val(newVal);
                });
            }
            clickButton();
        });
    }


    function checkShowLogin() {
        $('.show-popup-login').on('click', function (e) {
            e.preventDefault();

            let id = $(this).attr('href');
            $(id).modal('show');
        })
    }


    function checkLearningLessonPage() {
        if ($('.fix-header-top').length) {
            $('.header').addClass('header--fixtop');
        }
    }


    function percentJs() {
        var value = 0,
            totalLength = 144;

        $('.percent').each(function () {
            var self = $(this),
                totalLength = self.find('.circle').attr('r') * 2 * Math.PI,
                percent = self.attr('data-percent'),
                svg = self.find('.circle'),
                showNumber = $('.percent__number', self);

            value = Math.floor(totalLength - (totalLength * (percent / 100)));
            showNumber.text(percent + '%');
            svg.css({
                'stroke-dasharray': totalLength,
                'stroke-dashoffset': value
            });
        });
    }


    // function quizJs() {
    //     var stopTime = false;

    //     function timedCount() {
    //         var wrap = $('.timeCounterJs'),
    //             total_seconds = wrap.attr('data-time') * 60,
    //             c_minutes = parseInt(total_seconds / 60),
    //             c_seconds = parseInt(total_seconds % 60),
    //             htmlM = $('.minutes > span', wrap),
    //             htmlS = $('.seconds > span', wrap),
    //             htmlNotifi = $('.notify', wrap),
    //             timer;

    //         htmlNotifi.text('Bắt đầu làm bài');

    //         function CheckTime() {
    //             htmlM.html(c_minutes);
    //             htmlS.html(c_seconds);


    //             if (stopTime === true) {
    //                 clearTimeout(timer);
    //                 htmlNotifi.text('Đã nộp bài');
    //                 return
    //             }

    //             if (total_seconds <= 180) {
    //                 htmlNotifi.text('Sắp hết thời gian');
    //             }

    //             if (total_seconds <= 0) {
    //                 clearTimeout(timer);
    //                 htmlNotifi.text('Đã nộp bài');
    //                 $('.quiz-wrap__footer .btn-showResult').trigger('click');

    //             } else {
    //                 total_seconds = total_seconds - 1;
    //                 c_minutes = parseInt(total_seconds / 60);
    //                 c_seconds = parseInt(total_seconds % 60);
    //                 timer = setTimeout(CheckTime, 1000);
    //             }
    //         }
    //         timer = setTimeout(CheckTime, 1000);

    //         // https://stackoverflow.com/questions/55150449/javascript-quiz-timer
    //     }

    //     function openMp3() {
    //         var playMp3 = null;
    //         var linkUrl = 'assets/audio/'

    //         $('.quiz-wrap__content .quiz__list .item__title .audio').on('click', function (e) {
    //             e.preventDefault();
    //             var self = $(this),
    //                 audio = self.attr('data-link');

    //             audio = audio.replace(/(<([^>]+)>)/gi, "");
    //             audio = audio.replace(/\s+/gi, '');
    //             /* console.log("chuỗi sau xóa dấu cách-"+ audio + "-"); */


    //             if (playMp3 == null) {
    //                 playMp3 = new Audio(linkUrl + audio);
    //             } else {
    //                 playMp3.pause();
    //                 playMp3.currentTime = 0;
    //                 playMp3 = new Audio(linkUrl + audio);
    //             }
    //             playMp3.play();
    //         })


    //         // function listenMp3(audio){
    //         //     var link = 'assets/audio/';

    //         //     audio = audio.replace(/(<([^>]+)>)/gi,"");
    //         //     audio = audio.replace(/\s+/gi, '');
    //         //     /* console.log("chuỗi sau xóa dấu cách-"+ audio + "-"); */

    //         //     if(playMp3 == null) playMp3 = new Audio(+ audio);
    //         //     else{
    //         //         playMp3.pause();
    //         //         playMp3.currentTime = 0;
    //         //         playMp3 = new Audio(listenMp3+ audio);
    //         //     }
    //         //     playMp3.play();
    //         // }

    //         // //tắt mp3 khi đóng popup đáp án
    //         // function turnOffMp3(){
    //         //     playMp3.pause();
    //         //     playMp3.currentTime = 0;
    //         // }
    //     }

    //     function startQuizJs() {
    //         $('.quiz-wrap__start .btn').on('click', function (e) {
    //             e.preventDefault();
    //             $(this).closest('.quiz-wrap').removeClass('show-start');
    //             $('.widget--infoQuiz').removeClass('pointerEventsNone');
    //             timedCount();
    //         });
    //     }

    //     function showResult() {
    //         $('.quiz-wrap__footer .btn-showResult').on('click', function (e) {
    //             e.preventDefault();
    //             var self = $(this);

    //             if (!self.hasClass('no-popup')) {
    //                 var time = $('.timer-inner .minutes span').text();

    //                 if (time > (time / 3)) {
    //                     $('#alertbox-popup').modal('show');
    //                 }

    //                 $('#alertbox-popup .btn-agree').on('click', function (e) {
    //                     e.preventDefault();
    //                     var self2 = $(this);
    //                     console.log('đã chọn đồng ý');
    //                     stopTime = true;
    //                     $('#alertbox-popup').modal('hide');
    //                     self.closest('.layout-content').find('.quiz-wrap__inner').addClass('pointerEventsNone');
    //                     setTimeout(function () {
    //                         self.closest('.layout-content').find('.quiz-reslut').slideDown();
    //                     }, 100);
    //                     self.hide();
    //                 });
    //             } else {
    //                 self.closest('.layout-content').find('.quiz-wrap__inner').addClass('pointerEventsNone');
    //                 setTimeout(function () {
    //                     self.closest('.layout-content').find('.quiz-reslut').slideDown();
    //                 }, 100);
    //                 self.hide();
    //             }
    //         });
    //     }

    //     function btnViewAnswer() {
    //         $('.btn-view-answer').on('click', function (e) {
    //             e.preventDefault();
    //             var offset = $('.quiz-wrap').offset().top;
    //             $('html,body').animate({
    //                 scrollTop: offset
    //             }, 700);
    //         });
    //     }

    //     openMp3();
    //     startQuizJs();
    //     showResult();
    //     btnViewAnswer();
    // }


    function cartboxJs() {
        $('.cartbox__close, .cartbox__clickout').on('click', function () {
            $(this).closest('.cartbox').removeClass('cartbox--show');
        });
    }


    function popupCheckAddressPrice() {
        var item = $('#form-address-modal'),
            input = $('.checkbox-item input', item),
            content = $('.choose-form__content', item);

        function showContent() {
            input.on('change', function () {
                content.removeClass('show');
                $(this).closest('.choose-form__item').find('.choose-form__content').addClass('show');
            });
        }

        item.on('show.bs.modal', function (e) {
            var dataId = $(e.relatedTarget).parent().attr('id');
            item.attr('data-id', dataId);
            bodyScrollLock.disableBodyScroll(item[0]);
        });

        function closePopup() {
            item.on('hidden.bs.modal', function (e) {
                var getValue = $('.checkbox-item input:checked', item).val();
                var getId = '#' + item.attr('data-id');
                $(getId).find('p').html('<b>+' + formatPrice(getValue) + 'đ</b>');
                bodyScrollLock.enableBodyScroll(item[0]);
            });
        }

        showContent();
        closePopup();
    }


    function scrollUpShow(item) {
        var prevScrollpos = $(window).scrollTop();

        var fix = function () {
            var currentScrollPos = window.pageYOffset;

            if (prevScrollpos > currentScrollPos) {
                item.addClass('show');
            } else {
                item.removeClass('show');
            }

            prevScrollpos = currentScrollPos;
        }

        fix();
        $(window).on('scroll', fix);
    }


    function filterMobile() {
        var ww = $(window).width();
        var filter = $('.widget--filterbox');

        var htmlMobile = function (item) {
            return `
                <div class="mobile-fliter">
                    <div class="fixheight"></div>
                    <div class="widget widget--filterbox">
                        <span class="mobile-fliter__close"><i class="fa fa-angle-left"></i></span>
                        ${item}
                    </div>
                    <div class="mobile-fliter__btn">
                        <span><i class="fa fa-filter"></i>Bộ lọc</span>
                    </div>
                </div>`
        }


        if (ww < 1200 && filter.length) {
            var filterHltm = filter.html();
            $('body').append(htmlMobile(filterHltm));
            filter.remove();

            var fixscroll = $('.mobile-fliter .form-filter')[0];
            $('.mobile-fliter__btn').on('click', function () {
                $('.mobile-fliter').addClass('mobile-fliter--active');
                bodyScrollLock.disableBodyScroll(fixscroll);
            });

            $('.mobile-fliter__close').on('click', function () {
                $('.mobile-fliter').removeClass('mobile-fliter--active');
                bodyScrollLock.enableBodyScroll(fixscroll);
            });
        }
    }


    function mobileLearningProcess() {
        var ww = $(window).width();
        var domHtml = $('.learning-process');
        var text;


        var htmlMobile = function (getHtml, text) {
            return `
                <div class="mobile-learningProcess">
                    <div class="f-fixheight"></div>
                    <div class="f-text-active">${text}</div>
                    <div class="f-content">
                        <span class="f-content__close"><i class="fa fa-angle-left"></i></span>
                        <div class="f-content__inner">
                            ${getHtml}
                        </div>
                    </div>
                </div>`
        }

        if (ww < 1200 && domHtml.length) {
            var checkactive = domHtml.find('li.current');
            if (checkactive.length > 0) {
                text = domHtml.find('li.current').html();
            } else {
                text = domHtml.find('.learning-process__header').html();
            }

            var getHtml = domHtml[0].outerHTML;

            $('body').append(htmlMobile(getHtml, text));
            domHtml.remove();
            percentJs();

            var fixscroll = $('.mobile-learningProcess .learning-process__list')[0];


            $('.mobile-learningProcess .f-text-active').on('click', function () {
                $('.mobile-learningProcess .f-content').addClass('f-content--active');
                bodyScrollLock.disableBodyScroll(fixscroll);

            });

            $('.mobile-learningProcess .f-content__close').on('click', function () {
                $('.mobile-learningProcess .f-content').removeClass('f-content--active');
                bodyScrollLock.enableBodyScroll(fixscroll);
            });
        }
    }


    function mobileQuiz() {
        var ww = $(window).width();
        var domHtml = $('.widget--infoQuiz .infoQuiz__wrap');
        var text;

        var htmlMobile = function (getHtml) {
            return `
                <div class="mobile-timeCounter">
                    <div class="fixheight"></div>
                    ${getHtml}
                </div>`
        }

        if (ww < 1200 && domHtml.length) {
            var getHtml = domHtml[0].outerHTML;
            $('body').append(htmlMobile(getHtml));
            domHtml.remove();
        }
    }


    function mobileCategoryCourse() {
        var wrap = $('.sec-hero__sidebar'),
            submenu = $('.submenu', wrap);

        wrap.find('li.menu-has-children').on('click', '> a', function (e) {
            if ($(window).width() >= 1260) return;
            e.preventDefault();
            $(this).next().slideToggle();
            $(this).toggleClass('active');
        });
    }


    function popupNotifiWebsite() {
        var wrap = $('#popup-notifiWebsite');
        if (wrap.length) {
            wrap.modal('show');
        }
    }


    function wowJs() {
        var wow = new WOW({
            boxClass: 'wow', // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 0, // distance to the element when triggering the animation (default is 0)
            mobile: true, // trigger animations on mobile devices (default is true)
            live: true, // act on asynchronously loaded content (default is true)
            callback: function (box) {
                $(box).addClass('effect');
            },
            scrollContainer: null // optional scroll container selector, otherwise use window
        });
        wow.init();
    }



    owlSlideJs();
    headerMobile();
    popupJs();
    validateForm();
    btnAddToCart();
    alertboxClose();
    btnscrollOffset();
    lessonboxPlayVideo();
    inputQuantity();
    checkShowLogin();
    //quizJs();
    percentJs();
    cartboxJs();
    mobileCategoryCourse();
    mobileQuiz();
    $('[data-toggle="tooltip"]').tooltip();
    popupNotifiWebsite();

    $(window).on('load', function () {
        headerJs();
        stickyJs();
        checkLearningLessonPage();
        backtotop();
        filterMobile();
        mobileLearningProcess();
        wowJs();
        $('body').addClass('load-done');
    });
})(jQuery);
