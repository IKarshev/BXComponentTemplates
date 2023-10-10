$(function(){
    if (window.innerWidth < 1111) {
        $('.companyPage__slider').slick({
            arrows: false,
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            variableWidth: true
        });
    } else {
        $('.companyPage__slider').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: false,
            dots: true
        });
    };


    $(".gallery_element").on("click", function(event){
        event.preventDefault();

        var arr = [];
        var gallery_id = $(this).attr("data-fancybox");
        $(`.gallery_element[data-fancybox=${gallery_id}]`).each(function(){
            arr.push( {src: `${ $(this).attr("href") }`} );
        });

        $.fancybox.open(arr);
    });

});
