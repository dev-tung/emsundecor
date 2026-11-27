(function () {
	'use strict';

	var toggle = document.querySelector('.menu-toggle');
	var navigation = document.querySelector('.primary-navigation');
	var categoryItems = document.querySelectorAll('.menu-item--product-categories');
	var canHover = window.matchMedia('(hover: hover) and (pointer: fine)');

	if (!toggle || !navigation) {
		return;
	}

	categoryItems.forEach(function (item) {
		var categoryToggle = item.querySelector('.product-category-toggle');

		if (!categoryToggle) {
			return;
		}

		function setOpen(open) {
			item.classList.toggle('is-open', open);
			categoryToggle.setAttribute('aria-expanded', String(open));
		}

		categoryToggle.addEventListener('click', function () {
			setOpen(categoryToggle.getAttribute('aria-expanded') !== 'true');
		});

		item.addEventListener('mouseenter', function () {
			if (canHover.matches) {
				setOpen(true);
			}
		});

		item.addEventListener('mouseleave', function () {
			if (canHover.matches && !item.contains(document.activeElement)) {
				setOpen(false);
			}
		});

		item.addEventListener('focusout', function (event) {
			if (!item.contains(event.relatedTarget) && !(canHover.matches && item.matches(':hover'))) {
				setOpen(false);
			}
		});

		item.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && categoryToggle.getAttribute('aria-expanded') === 'true') {
				event.preventDefault();
				event.stopPropagation();
				setOpen(false);
				categoryToggle.focus();
			}
		});

		document.addEventListener('click', function (event) {
			if (!item.contains(event.target)) {
				setOpen(false);
			}
		});
	});

	document.documentElement.classList.add('emsun-menu-js');

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', String(!isOpen));
		navigation.classList.toggle('is-open', !isOpen);
		if (isOpen) {
			categoryItems.forEach(function (item) {
				item.classList.remove('is-open');
				item.querySelector('.product-category-toggle').setAttribute('aria-expanded', 'false');
			});
		}
	});

	navigation.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			toggle.setAttribute('aria-expanded', 'false');
			navigation.classList.remove('is-open');
			toggle.focus();
		}
	});
}());
