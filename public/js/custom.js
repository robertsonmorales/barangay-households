$(function(){
	$('[data-toggle="tooltip"]').tooltip();

	$('.btn-item-nav').on('click', function(){
		var dropdown = $('#collapse-' + $(this).attr('id'));
		var parent_nav = $(this).children();

		for (let i = 0; i < $('.btn-item-nav').length; i++) {
			var chevron = $('.btn-item-nav')[i].children[0].children[1].classList;
			chevron.remove('chevron-down');
		}

		if(dropdown.hasClass('show')){
			parent_nav[0].children[1].classList.remove('chevron-down');
		}else{
			parent_nav[0].children[1].classList.add('chevron-down');
		}

	});

	$('.btn-logout').on('click', function(){
		$("#logout-form").on('submit');
	});

	$('#btn-menu').on('click', function(){		
		if($('#sidebar').width() == 0){
			showSidebar();
		}else {
			hideSidebar();
		}
	});

	$('#btn-close').on('click', function(){
		hideSidebar();
	});

	function showSidebar(){
		$('#sidebar').animate({
			width: "320px",
			opacity: 1
		}, 400);

		$('#btn-menu').attr('data-original-title', 'Hide sidebar');
	}

	function hideSidebar(){
		$('#sidebar').animate({
			width: "0px",
			opacity: 0
		}, 400);

		$('#btn-menu').attr('data-original-title', 'Show sidebar');
	}

	function kFormatter(num) {
		return Math.abs(num) > 999 ? Math.sign(num)*((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num)*Math.abs(num)
	}
});