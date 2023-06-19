/* http://keith-wood.name/countdown.html
 * Russian initialisation for the jQuery countdown extension
 * Written by Sergey K. (xslade{at}gmail.com) June 2010. */
(function($) {
	$.countdown.regionalOptions['ru'] = {
		labels: BX.message('BITRONIC2_COUNTDOWN_LABELS'),
		labels1: BX.message('BITRONIC2_COUNTDOWN_LABELS1'),
		labels2: BX.message('BITRONIC2_COUNTDOWN_LABELS2'),
		compactLabels: BX.message('BITRONIC2_COUNTDOWN_COMPACT_LABELS'), 
		compactLabels1: BX.message('BITRONIC2_COUNTDOWN_COMPACT_LABELS1'),
		whichLabels: function(amount) {
			var units = amount % 10;
			var tens = Math.floor((amount % 100) / 10);
			return (amount == 1 ? 1 : (units >= 2 && units <= 4 && tens != 1 ? 2 :
				(units == 1 && tens != 1 ? 1 : 0)));
		},
		digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
		timeSeparator: ':', isRTL: false};
	$.countdown.setDefaults($.countdown.regionalOptions['ru']);
})(jQuery);