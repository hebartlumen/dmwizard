(function($) {
	'use strict';	
			$('#foo4').carouFredSel({
					prev: '#prev4',
					next: '#next4',
					auto: false,
					responsive: true,
					width: '100%',
					scroll: 3,
					items: {
						width: 300,
					height: 'auto',	//	optionally resize item-height
						visible: {
							min: 3,
							max: 3
						}
					}
				});
})(jQuery);