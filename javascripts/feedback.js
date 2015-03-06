jQuery(document).ready(function(){
    var $scope      = jQuery('.star-rating');
    var type        = $scope.attr('data-type');
    var icon        = $scope.attr('data-icon');
    var color       = $scope.attr('data-color');
    var color_on    = $scope.attr('data-color-on');
    var colors      = $scope.attr('data-colors');
    var size        = $scope.attr('data-size');
    var length      = parseInt($scope.attr('data-amount'));
    var hints       = $scope.attr('data-hints');
    var starOn      = $scope.attr('data-staron');
    var starOff     = $scope.attr('data-staroff');
    var items       = '';
    
    if(hints !== undefined){
        var hintsArray  = hints.split(',');
    }
   
    if(colors !== undefined){
        var colorsArray = colors.split(',');
    }
    
    if(type === 'image'){
        $scope.raty({
            click: function(score, evt) {
                jQuery('#rating').val($scope.raty('score'));
            },
            number: length,
            numberMax : 10,
            hints: hintsArray,
            starOff : starOff,
            starOn  : starOn
        });
    }
    if(type === 'icon'){

        $scope.html('<div class="rating">'+starAmount(length)+'</div>');

        jQuery('.'+icon+'').click(function(){
            var score = jQuery(this).attr('data-number');
            jQuery('[name="rating"]').val(score);
            setStars(score);
        });
        
        jQuery('head').append('<style type="text/css">.rating span{font-size: '+size+'}.rating span.'+icon+':before,.rating span.fa-'+icon+':before {color: '+color+';}.rating span.'+icon+':hover:before,.rating span.'+icon+':hover ~ span.'+icon+':before{color: '+color_on+';}.rating span.fa-'+icon+'[data-number="1"]:before,.rating span.fa-'+icon+'[data-number="2"]:before,.rating span.fa-'+icon+'[data-number="3"]:before,.rating span.fa-'+icon+'[data-number="4"]:before,.rating span.fa-'+icon+'[data-number="5"]:before,.rating span.fa-'+icon+'[data-number="6"]:before,.rating span.fa-'+icon+'[data-number="7"]:before,.rating span.fa-'+icon+'[data-number="8"]:before,.rating span.fa-'+icon+'[data-number="9"]:before,.rating span.fa-'+icon+'[data-number="10"]:before{color: '+color_on+';}.rating span.'+icon+':hover[data-number="1"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="1"]:before{color: '+setcolor(colorsArray[0], color_on)+';}.rating span.'+icon+':hover[data-number="2"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="2"]:before{color: '+setcolor(colorsArray[1], color_on)+';}.rating span.'+icon+':hover[data-number="3"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="3"]:before{color: '+setcolor(colorsArray[2], color_on)+';}.rating span.'+icon+':hover[data-number="4"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="4"]:before{color: '+setcolor(colorsArray[3], color_on)+';}.rating span.'+icon+':hover[data-number="5"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="5"]:before{color: '+setcolor(colorsArray[4], color_on)+';}.rating span.'+icon+':hover[data-number="6"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="6"]:before{color: '+setcolor(colorsArray[5], color_on)+';}.rating span.'+icon+':hover[data-number="7"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="7"]:before{color: '+setcolor(colorsArray[6], color_on)+';}.rating span.'+icon+':hover[data-number="8"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="8"]:before{color: '+setcolor(colorsArray[7], color_on)+';}.rating span.'+icon+':hover[data-number="9"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="9"]:before{color: '+setcolor(colorsArray[8], color_on)+';}.rating span.'+icon+':hover[data-number="10"]:before,.rating span.'+icon+':hover ~ span.'+icon+'[data-number="10"]:before{color: '+setcolor(colorsArray[9], color_on)+';}.rating span.fa-'+icon+'[data-number="1"]:before{color: '+setcolor(colorsArray[0], color_on)+';}.rating span.fa-'+icon+'[data-number="2"]:before{color: '+setcolor(colorsArray[1], color_on)+';}.rating span.fa-'+icon+'[data-number="3"]:before{color: '+setcolor(colorsArray[2], color_on)+';}.rating span.fa-'+icon+'[data-number="4"]:before{color: '+setcolor(colorsArray[3], color_on)+';}.rating span.fa-'+icon+'[data-number="5"]:before{color: '+setcolor(colorsArray[4], color_on)+';}.rating span.fa-'+icon+'[data-number="6"]:before{color: '+setcolor(colorsArray[5], color_on)+';}.rating span.fa-'+icon+'[data-number="7"]:before{color: '+setcolor(colorsArray[6], color_on)+';}.rating span.fa-'+icon+'[data-number="8"]:before{color: '+setcolor(colorsArray[7], color_on)+';}.rating span.fa-'+icon+'[data-number="9"]:before{color: '+setcolor(colorsArray[8], color_on)+';}.rating span.fa-'+icon+'[data-number="10"]:before{color: '+setcolor(colorsArray[9], color_on)+';}</style>');      
        
        function setcolor(color, defaut){
            if(color === undefined){
             color = defaut;
            } 
            return color;
        }

        function starAmount(len){
            for (i = len; i > 0; i--){
               items += '<span class="'+icon+'" data-number="'+i+'" title="'+ hintsArray[i-1]+'"></span>';    
            } 
            return items;   
        }

        function setStars(number){
            for (i = 0; i < number; i++) { 
                jQuery('.rating span[data-number="'+(i+1)+'"]').attr('class', 'fa fa-'+icon+'');
            }
        }
        
        function resetStars(){
            jQuery('.rating span').each(function(){
                jQuery(this).attr('class', icon);

            });
        }

        jQuery('.star-rating span').mouseover(function(e) {
            resetStars();
        });

        jQuery('.star-rating span').mouseout(function(e) {
            setStars(jQuery('[name="rating"]').val());
        });
    }                           

    //date range
    jQuery('#dateRange1').datepick();
    jQuery('#dateRange2').datepick();
       
    //validate form using JQuery plugin
    //jQuery("#feedback").validate();    
    
    //set width auto
    $scope.css('width', 'auto');

});