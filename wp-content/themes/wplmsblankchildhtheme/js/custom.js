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
    $(document).ready(function(){
        $('.ct-slick-homepage').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        });
    });
    
})(jQuery);