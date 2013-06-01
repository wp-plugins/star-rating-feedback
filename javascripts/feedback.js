jQuery(document).ready(function(){
    
    jQuery('#star').raty({
        click: function(score, evt) {
            jQuery('#rating').val(jQuery('#star').raty('score'));
        } 
    });
    
    jQuery('#dateRange1').datepick();
    jQuery('#dateRange2').datepick();
    
    /*$('.btn-back').click(function()
    {
        $(this).attr('href', function(index, attr) {
            return attr + location.search;
        });
    });
    
    $('.button-link').click(function()
    {
        $(this).attr('href', function(index, attr) {
            return attr + location.search;
        });
    });*/
    
    //validate form using JQuery plugin
    jQuery("#feedback").validate(
    );    
});