const $              = require('jquery');
const InfiniteScroll = require('infinite-scroll');
const lozad          = require('lozad');

const observer = lozad();

$('.load-more__button').on('click', function(e) {
    e.preventDefault();
    var button = $(this);
    var loader = $(document.createElement('div'));
    var container = button.closest('.load-more');
    var buttoncontainer = button.parent();
    var grid = container.find('.load-more__items');
    var next_page = button.data('target');
    var post_type = button.data('type');
    button.remove();
    loader.addClass('spinner').text('loading ...');
    buttoncontainer.append(loader);
    var loadcontainer = $('<div />');
    loadcontainer.load(
        next_page + ' .load-more__items--' + post_type,
        function () {
            loader.remove();
            grid.append(loadcontainer.first().children().html());
            observer.observe();
        }
    );
});

$('#categoryfilter').change(function(e){
    var filter = $('#filter');
    $.ajax({
        url:filter.attr('action'),
        data:filter.serialize(), // form data
        type:filter.attr('method'), // POST
        success:function(data){
            $('#response').html(data); // insert data
            observer.observe();
        }
    });    
    return false;
});