const $ = require('jquery');
window.globals = window.globals || {};
window.globals.jQuery = window.globals.jQuery || $;

(function($, tabsection) {
    
    $.fn[tabsection] = function(args) {
        
        var setTabHeight = function () {
            var tabContainer = $('.tab-section__tabs');
            var alltabs = $('.tab-section__tab');
            var tabsheights = [];
            alltabs.each(function () {
                var currentTab = $(this);
                tabsheights.push(currentTab.outerHeight());
                setZIndex(currentTab);
            });
            tabContainer.css({'height': Math.max(...tabsheights)});
        };
    
        var setZIndex = function (tab) {
            if (tab.hasClass('active')) {
                tab.css({'z-index': '+1'})
            } else {
                tab.css({'z-index': '-1'})
            }
        };
    
        var setActive = function (tab) {
            tab.addClass('active');
            setZIndex(tab);
        };
    
        var setInactive = function (tab) {
            tab.removeClass('active');
            setZIndex(tab);
        };
    
        return $(this).each(function() {
            var _ = $(this);
            _.data('instance', _);
            
            _.initOnLoad = function() {
                if (document.readyState == 'complete') {
                    setTabHeight();
                } else {
                    document.onreadystatechange = function () {
                        if (document.readyState === "complete") {
                            setTabHeight();
                        }
                    }
                }
    
                $(window).smartresize(function () {
                    setTabHeight();
                });
    
                $('.tab-section__button').on('click', function (e) {
                    var clickedButton = $(this);
                    if (clickedButton.hasClass('active')) {
                        return;
                    }
                    var tabcontainer = $('.tab-section__tabs');
                    var buttoncontainer = clickedButton.parent();
                    var oldActive = tabcontainer.find('.tab-section__tab.active');
                    var newActive = tabcontainer.find('.tab-section__tab--' + clickedButton.attr('data-type'));
                    buttoncontainer.find('.tab-section__button.active').removeClass('active');
                    clickedButton.addClass("active");
                    setTimeout(function () {
                        setInactive(oldActive);
                    }, 450, setActive(newActive));
                });
            };
            
            _.initOnContentChange = function () {
                setTabHeight();
            }
        });
    };
    
    $(this).tabsection({}).data('instance').initOnLoad();
    
})(window.globals.jQuery, 'tabsection');