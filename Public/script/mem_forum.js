function Index() {
    this.init();
}

Index.prototype = {
    init: function() {
        $('img.lazy').lazyLoad();
    }
}

$(function() {
    new Index();
});
