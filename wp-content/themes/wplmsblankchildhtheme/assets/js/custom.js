(function($){
    "use strict";
    
    $(document).ready( function() {
        if( document.getElementById('mobility-disclaimer-form') !== null ) {
            $('#mobility-disclaimer-form form input[name="accept"]').on( 'change', function(e) {
                e.preventDefault();
                var $value = $(this).val();
    
                if( $(this).is(':checked') ) {
                    window.location.href = 'https://samyeinstitute.org/unit/mobility-meditators-full-30-minute-sequence/?id=23278';
                }
            });
        }
    });

    $(document).ready(function() {
        if ($('[data-background]').length > 0) {
            $('[data-background]').each(function() {
            var $background, $backgroundmobile, $this;
            $this = $(this);
            $background = $(this).attr('data-background');
            $backgroundmobile = $(this).attr('data-background-mobile');
            if ($this.attr('data-background').substr(0, 1) === '#') {
                return $this.css('background-color', $background);
            } else if ($this.attr('data-background-mobile') && device.mobile()) {
                return $this.css('background-image', 'url(' + $backgroundmobile + ')');
            } else {
                return $this.css('background-image', 'url(' + $background + ')');
            }
            });
        }
    });
    // $(document).ready(function(){
    //     $('.ct-slick-homepage').slick({
    //     autoplay: true,
    //     autoplaySpeed: 3000,
    //     });
    // });

    // $('.carousel').carousel();

    /* For a given date, get the ISO week number
    *
    * Based on information at:
    *
    *    http://www.merlyn.demon.co.uk/weekcalc.htm#WNR
    *
    * Algorithm is to find nearest thursday, it's year
    * is the year of the week number. Then get weeks
    * between that date and the first day of that year.
    *
    * Note that dates in one year can be weeks of previous
    * or next year, overlap is up to 3 days.
    *
    * e.g. 2014/12/29 is Monday in week  1 of 2015
    *      2012/1/1   is Sunday in week 52 of 2011
    */
    function getWeekNumber(d) {
        // Copy date so don't modify original
        d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
        // Set to nearest Thursday: current date + 4 - current day number
        // Make Sunday's day number 7
        d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
        // Get first day of year
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
        // Calculate full weeks to nearest Thursday
        var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
        // Return array of year and week number
        return [d.getUTCFullYear(), weekNo];
    }

    var result = getWeekNumber(new Date());
    document.getElementById("weekly-reflection").innerHTML = 'It\'s currently week ' + result[1] + ' of ' + result[0];
    
})(jQuery);