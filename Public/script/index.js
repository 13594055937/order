function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        $('img.lazy').lazyLoad();

        // 轮播
        $(".m-slider-index").owlCarousel({
            navigation: true, // Show next and prev buttons
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true,
            stopOnHover: true,
            navigation: false
        });

        $(".banner1").owlCarousel({
            pagination: false,
            autoPlay: true,
            stopOnHover: true,
            items: 4,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 3],
            navigation: true,
            navigationText: ["上一个", "下一个"]
        });

        $(".banner2").owlCarousel({
            pagination: false,
            autoPlay: true,
            stopOnHover: true,
            items: 4,
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [979, 3],
            navigation: true,
            navigationText: ["上一个", "下一个"]
        });

        $('.banner1, .banner2').on('click', '.item', function() {
            var self = $(this);
            var content = self.find('.m-popup-cont').html();
            $.krPopup({
                skin: 'popupCont',
                width: 1200,
                content: content,
                buttons: []
            });
            // 关闭弹层
            $('.krPopup').on('click', '.close', function() {
                $.krPopupClose();
            });

            $('#krPopupMask').on('click', function(e) {
                e.stopPropagation();
                $.krPopupClose();
            });
        });
    }
};

$(function() {
    new Index();
});
