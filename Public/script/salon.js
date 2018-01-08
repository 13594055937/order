function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        $('img.lazy').lazyLoad();
        $('.list-rank')
            .on('mouseover', '.item-inner', function() {
                $(this).addClass('current').siblings('.item-inner').removeClass('current');
            });

        // 轮播
        $(".banner").owlCarousel({
            navigation: true, // Show next and prev buttons
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true,
            stopOnHover: true,
            navigation: false
        });
    }
}

$(function() {
    new Index();
});
