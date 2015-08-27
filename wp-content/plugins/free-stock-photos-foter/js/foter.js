jQuery(document).ready(function() {
    var winWidth = jQuery(window).width(),winHeight = jQuery(window).height();
    jQuery(".thumb img").each(function() {
        var tip = jQuery(this).closest('li').find('.tooltip');
        tip.css({'display':'none','position':'absolute','left':'0','top':'0'});
        jQuery(this).mouseover(function() {
            jQuery('.tooltip').css({'display':'none'});
            tip.css({'display':'block'});
        }).mousemove(function(e) {
            var tw = tip.width(),th=tip.height();
            var X = e.pageX, Y = e.pageY,off=8,woff=15;
            if ((X+tw+woff) > winWidth) X = X-tw-off;else X+=off;
            if ((Y+th+woff) > winHeight) Y = winHeight-th-(woff-off);else Y+=off;
            tip.css({'display':'block','left':X,'top':Y});
        }).mouseout(function() {jQuery('.tooltip').css({'display':'none'});});
        tip.mouseout(function() {tip.css({'display':'none'});});
    });
});