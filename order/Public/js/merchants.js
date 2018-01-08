require(['jquery', 'swiper.min', 'common', 'changeImg'], function($, swiper, common, changeImg) {
	common.common();
	changeImg.changeImg();

	$(".tab2_1").changeImg({
		'boxWidth': 1200,
		'changeLen': 4,
		'changeSpend': 200,
		'autoChange': false,
		'changeHandle': true
	});
	$(".tab2_2").changeImg({
		'boxWidth': 1200,
		'changeLen': 4,
		'changeSpend': 200,
		'autoChange': false,
		'changeHandle': true
	});

	var swiper1 = new Swiper('.company .swiper-container', {
		pagination: '.swiper-pagination',
		paginationClickable: true,
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		spaceBetween: 30,
		hashnav: true,
		hashnavWatchState: true,
		loop: true,
		autoHeight: true
	});

	var swiper4 = new Swiper('.product .swiper-container', {
		pagination: '.swiper-pagination',
		paginationClickable: true,
		nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev',
		spaceBetween: 4,
		hashnav: true,
		hashnavWatchState: true,
		autoplay: 2500,
		loop: true
	});

})