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
    }
}

$(function() {
   new Index();
});
