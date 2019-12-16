const $ = require('jquery');
const Rellax = require('rellax');
const Flickity = require('flickity');
const lazyframe = require('lazyframe');
const anime = require('animejs');

// const jQuery = $.noConflict();
// require('../../plugins/wp-simple-booking-calendar/js/sbc');

require('flickity-fade');
require('flickity-imagesloaded');


const lozad = require('lozad');
require('bootstrap');

class MorphingMenuBG {
    constructor(el, parent) {
        this.DOM = {};
        this.DOM.el = el;
        this.DOM.parent = parent;
        this.DOM.pathEl = this.DOM.el.querySelector('path');
        this.DOM.items = Array.from($('.menu-item'));
        this.paths = this.DOM.pathEl.getAttribute('d');
        this.morphPaths = [
            'M774.12,399.851 C920.581,537.551 1147.278,662.086 1149.188,789.611 C1151.098,917.136 927.904,1048.255 781.443,1134.469 C634.982,1220.084 561.50122,1224.68892 498.13822,1212.70992 C434.77822,1200.13692 364.467416,1186.32128 262.581416,1100.70628 C160.695416,1014.49628 58.4821272,900.909929 17.0911272,715.907929 C-23.9818728,531.507929 12.521,260.95 114.407,123.247 C216.293,-14.456 356.068,-27.029 458.91,41.818 C561.432,111.273 627.656,262.148 774.12,399.851 Z',
            'M759.12,399.851 C905.581,537.551 1132.278,662.086 1134.188,789.611 C1136.098,917.136 912.904,1048.255 766.443,1134.469 C619.982,1220.084 551.540513,1175.96061 488.177513,1163.98161 C424.817513,1151.40861 349.467416,1186.32128 247.581416,1100.70628 C145.695416,1014.49628 62.2715463,901.365701 20.8805463,716.363701 C-20.1924537,531.963701 -2.479,260.95 99.407,123.247 C201.293,-14.456 341.068,-27.029 443.91,41.818 C546.432,111.273 612.656,262.148 759.12,399.851 Z',
            'M767.12,399.851 C913.581,537.551 1140.278,662.086 1142.188,789.611 C1144.098,917.136 965.957041,1031.76349 819.496041,1117.97749 C673.035041,1203.59249 605.538383,1200.56303 560.018202,1186.4063 C514.498021,1172.24956 346.819219,1084.67777 301.880454,1023.51399 C256.941688,962.350216 56.0404551,898.321359 17.6795844,738.764336 C-20.6812862,579.207313 5.521,260.95 107.407,123.247 C209.293,-14.456 349.068,-27.029 451.91,41.818 C554.432,111.273 620.656,262.148 767.12,399.851 Z',
            'M815.12,399.851 C961.581,537.551 1188.278,662.086 1190.188,789.611 C1192.098,917.136 1005.72018,1057.03015 859.259181,1143.24415 C712.798181,1228.85915 648.113253,1242.68657 584.750253,1230.70757 C521.390253,1218.13457 398.499655,1191.30306 305.916352,1091.66205 C213.33305,992.021032 114.499747,903.205334 30.5289494,749.647022 C-53.4418485,596.08871 53.521,260.95 155.407,123.247 C257.293,-14.456 397.068,-27.029 499.91,41.818 C602.432,111.273 668.656,262.148 815.12,399.851 Z',
            'M767.12,399.851 C913.581,537.551 1140.278,662.086 1142.188,789.611 C1144.098,917.136 957.720181,1057.03015 811.259181,1143.24415 C664.798181,1228.85915 598.386011,1226.67824 535.023011,1214.69924 C471.663011,1202.12624 360.285553,1166.35339 257.916352,1091.66205 C155.547151,1016.97071 56.0404551,898.321359 17.6795844,738.764336 C-20.6812862,579.207313 5.521,260.95 107.407,123.247 C209.293,-14.456 349.068,-27.029 451.91,41.818 C554.432,111.273 620.656,262.148 767.12,399.851 Z',
            'M767.12,399.851 C913.581,537.551 1140.278,662.086 1142.188,789.611 C1144.098,917.136 965.957041,1031.76349 819.496041,1117.97749 C673.035041,1203.59249 594.627681,1207.18785 531.264681,1195.20885 C467.904681,1182.63585 325.506117,1164.74868 257.916352,1091.66205 C190.326588,1018.57541 56.0404551,898.321359 17.6795844,738.764336 C-20.6812862,579.207313 5.521,260.95 107.407,123.247 C209.293,-14.456 349.068,-27.029 451.91,41.818 C554.432,111.273 620.656,262.148 767.12,399.851 Z'
        ];
        this.randomIndex = 0;
    }
    
    init() {
        if ($(this.DOM.parent).hasClass('is-active')) {
            this.changeShapeIn(this.morphPaths[this.randomPathIndex()]);
            this.initEvents();
        } else {
            this.changeShapeOut(this.paths);
        }
    }
    
    initEvents() {
        
        this.mouseenterFn = () => {
            this.changeShapeIn(this.morphPaths[this.randomPathIndex()]);
        };
        
        this.mouseleaveFn = () => {
            this.changeShapeOut(this.paths);
        };
        
        this.DOM.items.forEach((item) => {
            item.addEventListener('mouseenter', this.mouseenterFn);
            item.addEventListener('mouseleave', this.mouseleaveFn);
        });
    }
    
    randomPathIndex() {
        var x = 0;
        do {
            x = Math.floor(Math.random() * 6);
        } while (x === this.randomIndex);
        this.randomIndex = x;
        return x;
    }
    
    changeShapeIn(path) {
        anime.remove(this.DOM.pathEl);
        anime({
            targets: this.DOM.pathEl,
            duration: 2000,
            easing: 'easeOutQuad',
            d: path,
            loop: true,
            direction: 'alternate'
        });
    }
    
    changeShapeOut(path) {
        anime.remove(this.DOM.pathEl);
        anime({
            targets: this.DOM.pathEl,
            duration: 500,
            easing: 'easeInQuad',
            d: path
        });
    }
};

var morpingMenuBg = new MorphingMenuBG(document.querySelector('svg.nav-overlay__shape'), document.querySelector('#nav-main'));

$('.nav-main__toggler .hamburger').on('click', function () {
    $('#nav-main').toggleClass("is-active");
    morpingMenuBg.init();
});

window.addEventListener("click", function (event) {
    var $target = $(event.target)
    if ( !$target.parents('#nav-main').length > 0 || $target.hasClass('nav-overlay__shape')) {
        $('#nav-main').removeClass("is-active");
    }
});

$('.nav-main__mobile-toggler').on('click', function(){
    $(this).toggleClass('is-active');
});

$('.mobile-menu .menu-item-has-children a.main-menu__link').on('click', function() {
    if( !$(this).parent().hasClass('touched')) {
        $('.mobile-menu .main-menu__item').removeClass('touched');
        $(this).parent().addClass('touched');
        return false;
    }
});

$('.nav-main .menu-item-has-children a.main-menu__link').on('touchend', function() {    
    if( !$(this).parent().hasClass('touched')) {
        $('.nav-main .main-menu__item').removeClass('touched');
        $(this).parent().addClass('touched');
        return false;
    }
});

const observer = lozad();
observer.observe();

var rellax = new Rellax('.rellax');

lazyframe('.lazyframe');

$('.read-more__button').on('click', function (e) {
    $(this).toggleClass("button--is-active");
    var container = $(this).parent();
    container.find('.read-more__text').fadeToggle();
});

// SIMPLE BOOKING CALENDAR PLUGIN
$('div.sbc-calendar-wrapper').each(function() {
    var $calendar = $(this);
    
    function getCurrentMonth() {
        return $('.sbc-navigation select[name="sbcMonth"]', $calendar).val();
    }
    
    function getCurrentYear() {
        return $('.sbc-navigation select[name="sbcYear"]', $calendar).val();
    }
    
    function ajaxCalendarUpdate(operation) {
        var ajaxUrl = $('form', $calendar).attr('action');
        var data = {
            action: 'calendarNavigation',
            operation: operation,
            month: getCurrentMonth(),
            year: getCurrentYear(),
            id: $('form input[name="sbcId"]', $calendar).val()
        };
        $('div.sbc-loader', $calendar).addClass('sbc-loader-visible');
        $.post(ajaxUrl, data, function(response) {
            $calendar.find('#sbc-calendar').replaceWith(response);
            $('.sbc-navigation select', $calendar).bind('change', changeMonthOrYear);
        });
    }
    
    // Prev/next month
    $($calendar).on('click','a.sbc-prev-month, a.sbc-next-month', function(event) {
        event.preventDefault();
        ajaxCalendarUpdate($(this).is('.sbc-prev-month') ? 'prevMonth' : 'nextMonth');
    });
    
    // Custom month/year
    function changeMonthOrYear () {
        ajaxCalendarUpdate('date');
    }
    $('.sbc-navigation select', $calendar).bind('change', changeMonthOrYear);
});
	