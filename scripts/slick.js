$(document).ready(function() {
    $('.section_slick-slider').slick({
        slidesToShow: 4,
        infinite: false,
        responsive: [
            {
                breakpoint: 905,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});