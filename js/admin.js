const $ = require('jquery');

console.log(acf);

$('.attachment-preview').on('click', function() {
    console.log('test');
    
});

// console.log(image_id);

var field = acf.getField('focus_point');
field.$el.addClass('my-class');

// console.log(field);