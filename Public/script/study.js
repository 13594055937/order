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
        $('.list-category').on('click', 'li', function() {
            var $parent = $('.m-study-page');
            var cur = $(this).attr('data-type');
            $(this).addClass('current').siblings('li').removeClass('current');
            $parent.find('.list-study-' + cur).addClass('current').siblings('.list-avatar-160').removeClass('current');
        })
    }
}

$(function() {
    new Index();
});
