// switch some class (usually 'active') between a set of elements
// these elements must be descendants of el and data-switch should
// contain valid jquery selector to find them, starting from el
// if data-switch is empty, all children of el (> *) are targeted
// optional data-class attribute allows to define class to switch
function makeSwitch(el){
	var $el = $(el);
	var target = $el.attr('data-switch') || '> *';
	var tarClass = $el.attr('data-class') || 'active';
	$el.on('click', target, function(){
		var t = $(this);
		if ( t.hasClass(tarClass) ) return;
		$el.find(target+'.'+tarClass).removeClass(tarClass);
		t.addClass(tarClass);
	})
}