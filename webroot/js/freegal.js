jQuery(document).ready(function() {
	jQuery('#slideshow').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -8000,
		timeout: 12000
	});
});
jQuery(document).ready(function() {
	jQuery('#featured_artist').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -4000,
		timeout: 12000
	});
});
jQuery(document).ready(function() {
	jQuery('#newly_added').cycle({
		fx: 'fade',
		sync: 0,
		speed: 'slow',
		delay: -2000,
		timeout: 12000
	});
});

