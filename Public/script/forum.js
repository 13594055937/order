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
        $('.list-category').on('click', 'li', function(){
        	var $parent = $('.m-forum-page');
        	var cur = $(this).attr('data-type');
        	$(this).addClass('current').siblings('li').removeClass('current');
        	$parent.find('.forum-cont-' + cur).addClass('current').siblings('.forum-cont').removeClass('current');
        })
    }
}

$(function() {
    new Index();
});
