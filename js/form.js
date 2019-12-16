const $ = require('jquery');
window.globals = window.globals || {};
window.globals.jQuery = window.globals.jQuery || $;

(function($) {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
            $(window).tabsection({}).data('instance').initOnContentChange();
        }, false);
    });
})(window.globals.jQuery);