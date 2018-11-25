(function($){
    $(document).on('lazybeforeunveil lazybeforesizes', function(){
        $(window).trigger('resize');
    });
})(jQuery);