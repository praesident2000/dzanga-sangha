const $              = require('jquery');
const cookieconsent = require('cookieconsent');
const Cookie        = require('js-cookie');

var script_tag = document.getElementById('cc-script')
var cc_message = script_tag.getAttribute("data-message");
var cc_dismiss = script_tag.getAttribute("data-dismiss");
var cc_link = script_tag.getAttribute("data-link");
var cc_href = script_tag.getAttribute("data-href");

window.cookieconsent.initialise({
    container: document.getElementById("page-body"),
    content: {
        message: cc_message,
        dismiss: cc_dismiss,
        link: cc_link,
        href: cc_href,
    },
    cookie: {
        name: 'dzasa_cookie',
        // secure: true,
    },
    revokable:true,
    onStatusChange: function(status) {
        console.log(this.hasConsented() ? 'enable cookies' : 'disable cookies');
    },
    law: {
        regionalLaw: false,
    },
    location: true,
    elements: {
        dismiss: '<a aria-label="dismiss cookie message" tabindex="0" class="button cc-btn cc-dismiss">{{dismiss}}</a>',
    }
});
$(".cc-window").removeClass("cc-floating").addClass("cc-banner");