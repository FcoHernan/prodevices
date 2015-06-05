jQuery(document).ready( function($){
    "use strict";

    var $body = $('body'),
        content_width   = $('.content').width(),
        container_width = $('.container').width();


    /*************************
     * TABS
     *************************/

    $.fn.yiw_tabs = function(options) {
        // valori di default
        var config = {
            'tabNav': 'ul.tabs',
            'tabDivs': '.containers',
            'currentClass': 'current'
        };

        if (options) $.extend(config, options);

        this.each(function() {
            var tabNav = $(config.tabNav, this);
            var tabDivs = $(config.tabDivs, this);
            var activeTab;
            var maxHeight = 0;

            tabDivs.children('div').hide();

            if ( $('li.'+config.currentClass+' a', tabNav).length > 0 )
                activeTab = '#' + $('li.'+config.currentClass+' a', tabNav).data('tab');
            else
                activeTab = '#' + $('li:first-child a', tabNav).data('tab');

            $(activeTab).show().addClass('showing').trigger('yit_tabopened');
            $('li:first-child a', tabNav).parents('li').addClass(config.currentClass);

            $('a', tabNav).click(function(){
                if ( ! $(this).parents('li').hasClass('current') ) {

                    var id = '#' + $(this).data('tab');
                    var thisLink = $(this);

                    $('li.'+config.currentClass, tabNav).removeClass(config.currentClass);
                    $(this).parents('li').addClass(config.currentClass);

                    $('.showing', tabDivs).fadeOut(200, function(){
                        $(this).removeClass('showing').trigger('yit_tabclosed');
                        $(id).fadeIn(200).addClass('showing').trigger('yit_tabopened');
                    });
                }

                return false;
            });


        });
    };

    $('.tabs-container').yiw_tabs({
        tabNav  : 'ul.tabs',
        tabDivs : '.border-box'
    });

    /*************************
     * IMAGE STYLED
     *************************/

    $(window).on('load', function () {
        if ($.fn.prettyPhoto) {
            $(".image-styled .img_frame a[rel^='prettyPhoto']").prettyPhoto({
                social_tools: ''
            });


        }
    });


    /*************************
     * FAQ
     *************************/

    $('#faqs-container').yit_faq();

});