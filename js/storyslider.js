const $              = require('jquery');
const Flickity       = require('flickity');

var interval = $( '.story-slider__container' ).data( "autoplay" );
var flickity = new Flickity('.story-slider__container', {
    wrapAround: true,
    cellAlign: 'left',
    prevNextButtons  : false,
    draggable: true,
    setGallerySize: false,
    autoPlay: interval,
    pauseAutoPlayOnHover: false,
    on: {
        ready: function() {
            $('.progress__bar').addClass('active1');
        },
        change: function() {
            $('.progress__bar').toggleClass('active1');
            $('.progress__bar').toggleClass('active2');
        }
    }
});