function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        $('.mem-title').on('click', 'li', function() {
            var that = $(this);
            var active = $(this).attr('data-type');
            that.addClass('current').siblings().removeClass('current');
            if (active == 'doc') {
                $('.mem-' + active).addClass('current').siblings('.mem-title-cont').removeClass('current');
            } else if (active == 'coin') {
                $('.mem-' + active).addClass('current').siblings('.mem-title-cont').removeClass('current');
            }
        });
    }
}

$(function() {
    new Index();
});
