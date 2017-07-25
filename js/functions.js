;(function($, window, document, undefined) {
	// Variables for the current scope
	var $win = $(window);
	var $doc = $(document);
	var leftPos = -220;
	var clicks = 0;
	
	$doc.ready(function() {

		var $mobileMenus = $('.mobile-menus');

		$('.burger').on('click', function() {
			$('body').toggleClass('show-nav');

			$mobileMenus.find('.mobile-menus-wrapper').css('left', 0);
			$mobileMenus.find('.current').removeClass('current');
			clicks = 0;
		});

		$('.mobile-menus').height($('body').height());
		
		// Header Menu
		var $mainMenu = $('.desktop-menus .main-dropdown-menu');

		$mainMenu.each(function() {
			var $menu = $(this);

			$menu.find('.dropdown-menu-lvl2').each(function(i) {
				var $this = $(this);
				var $parent = $this.parent();

				$this.addClass($parent.attr('id'));
				
				if (i === 0) {
					$this.addClass('current');
					$this.prev().parent().addClass('current');
				};

				$menu.find('.dropdown-menu-body').append($this);
			});
		});

		$('.dropdown-menu-lvl2').each(function() {
			var $this = $(this);

			var $li = $this.find('> li').equalizeHeight();
		});

		$mainMenu.find('> .dropdown-menu > li > a').on('click', function(event) {
			event.preventDefault();

			var $link = $(this);
			var $parent = $link.parent();
			var $mainMenu = $link.closest('.main-dropdown-menu');
			var id = $parent.attr('id');
			var $dropdownMenu = $mainMenu.find('.dropdown-menu-body');

			$mainMenu.find('.menu-item').removeClass('current');
			$parent.addClass('current');

			$dropdownMenu.find('.dropdown-menu').removeClass('current');
			$dropdownMenu.find('.' + id).addClass('current').find('> li').attr('style', '').equalizeHeight();
		});

		// Mobile Menu
		$mobileMenus.find('a').on('click', function(event) {
			var $link = $(this);

			if ($link.parent('li').hasClass('menu-item-has-children') || $link.hasClass('back-btn')) {
				event.preventDefault();
			};

			if ($link.hasClass('back-btn')) {
				clicks--;
				$link.closest('.menu-item-has-children').removeClass('current');
			} else if ($link.parent('li').hasClass('menu-item-has-children')) {
				clicks++;
				$link.closest('.menu-item-has-children').addClass('current');
			};

			$mobileMenus.find('.mobile-menus-wrapper').css('left', leftPos * clicks);
		});

		$mobileMenus.find('.menu > li > a').each(function() {
			var $link = $(this);
			var linkCloning = $link.clone();
			$link.next().prepend(linkCloning).find('a:first').append('<i class="ico-arrow-right"></i>');
			$link.next().prepend(linkCloning).find('a:first').wrap('<li></li>');
		});
	});

	$.fn.equalizeHeight = function() {
		var maxHeight = 0, itemHeight;
	 
		for (var i = 0; i < this.length; i++) {
			itemHeight = $(this[i]).height();
			if (maxHeight < itemHeight) {
				maxHeight = itemHeight;
			}
		}

		return this.height(maxHeight);
	}

})(jQuery, window, document);