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
    
})(jQuery);