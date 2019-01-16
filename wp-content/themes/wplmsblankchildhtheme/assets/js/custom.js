(function($){
    "use strict";
    
    // $(document).ready( function() {
    //     if( document.getElementById('mobility-disclaimer-form') !== null ) {
    //         $('#mobility-disclaimer-form form input[name="accept"]').on( 'change', function(e) {
    //             e.preventDefault();
    //             var $value = $(this).val();
    
    //             if( $(this).is(':checked') ) {
    //                 window.location.href = 'https://samyeinstitute.org/unit/mobility-meditators-full-30-minute-sequence/?id=23278';
    //             }
    //         });
    //     }
    // });

    // $(document).ready(function() {
    //     if ($('[data-background]').length > 0) {
    //         $('[data-background]').each(function() {
    //         var $background, $backgroundmobile, $this;
    //         $this = $(this);
    //         $background = $(this).attr('data-background');
    //         $backgroundmobile = $(this).attr('data-background-mobile');
    //         if ($this.attr('data-background').substr(0, 1) === '#') {
    //             return $this.css('background-color', $background);
    //         } else if ($this.attr('data-background-mobile') && device.mobile()) {
    //             return $this.css('background-image', 'url(' + $backgroundmobile + ')');
    //         } else {
    //             return $this.css('background-image', 'url(' + $background + ')');
    //         }
    //         });
    //     }
    // });

    // Handle read more links on Global Accumulations Page
    function readMoreLessBtn(elements) {
        var selectorArr = [];
        var descriptionElements = elements;
        //var btnSelector = '';
        // console.log(descriptionElements);
        for(var i=0; i < descriptionElements.length; i++) {
            if (descriptionElements[i].innerHTML.length > 300) {
                selectorArr.push({
                    btnSelector: 'readMore'+i,
                    readMoreSelector: 'more'+i,
                    dotsSelector: 'dots'+i
                });
                
                //btnSelector = 'readMore'+i;
                var btn = document.createElement('button');
                btn.setAttribute("id", selectorArr[i].btnSelector);
                btn.innerHTML = "Read More";
                btn.dataset.index = i;
                descriptionElements[i].parentNode.insertBefore(btn, descriptionElements[i].nextSibling);

                var readLess = descriptionElements[i].innerHTML.slice(0, 300);
                var readMore = descriptionElements[i].innerHTML.slice(300, descriptionElements[i].innerHTML.length);
                descriptionElements[i].innerHTML = readLess+ "<span id='"+selectorArr[i].dotsSelector+"'>...</span><span id='"+selectorArr[i].readMoreSelector+"'>"+readMore+"</span>";
                
                // var readMoreSelector = 'more'+i;
                // var dotsSelector = 'dots'+i;

                $('#' + selectorArr[i].readMoreSelector).css("display", "none");

                // console.log(selectorArr[i].readMoreSelector);

                $('#readMore' + i).click(function() {
                    var dataAtt = $(this).attr("data-index");
                    // console.log(this, dataAtt);

                    var dots = document.getElementById(selectorArr[parseInt(dataAtt)].dotsSelector);
                    var moreText = document.getElementById(selectorArr[parseInt(dataAtt)].readMoreSelector);
                    var btnText = document.getElementById(selectorArr[parseInt(dataAtt)].btnSelector);
                  
                    if (dots.style.display === "none") {
                      dots.style.display = "inline";
                      btnText.innerHTML = "Read more"; 
                      moreText.style.display = "none";
                    } else {
                      dots.style.display = "none";
                      btnText.innerHTML = "Read less"; 
                      moreText.style.display = "inline";
                    }
                });
            }
        };
    }

    if (document.getElementsByClassName("accumulations-description").length > 0) {
        readMoreLessBtn(document.getElementsByClassName("accumulations-description"));
    } else if (document.getElementsByClassName("term-description")[0].childNodes.length > 1){
        readMoreLessBtn([document.getElementsByClassName("term-description")[0].childNodes[1]]);
    }
    
})(jQuery);
