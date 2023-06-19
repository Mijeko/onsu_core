function UmPagination(el){
	var t = this;
	t.$items = $(el).find('.pagination-item');
	t.$pages = t.$items.not('.to-start, .to-end, .arrow');
	t.active = t.$pages.index( t.$pages.filter('.active') );

	// .to-start и .to-end - кнопки "В начало" и "В конец"
	// .arrow.left и .arrow.right - стрелочки влево и вправо
	// Параметры функции:
	// object = 'start' / 'end' / 'both'
	// state = 'disable' или 'enable'
	t.stateToggle = function(object, state){
		var objectsToChange;
		if (object == 'both') objectsToChange = t.$items.filter('.to-start, .to-end, .arrow');
		else if (object == 'start') objectsToChange = t.$items.filter('.to-start, .arrow.prev');
		else if (object == 'end') objectsToChange = t.$items.filter('.to-end, .arrow.next');
		else return false;

		if (state == 'disable'){
			objectsToChange.addClass('disabled');
		} else if (state == 'enable') objectsToChange.removeClass('disabled');
		else return false;
	}

	t.setActive = function(target){
		// не даем выйти за пределы диапазона страниц
		if (target < 0) target = 0;
		else if ( target > t.$pages.length-1 ) target = t.$pages.length-1;

		// переключаем класс active на указанный элемент
		t.$pages.eq(t.active).removeClass('active');
		t.$pages.eq(t.active = target).addClass('active');

		// по умолчанию включаем крайние кнопки
		t.stateToggle('both', 'enable');
		// выключаем соответствующие крайние кнопки, если активный элемент - крайний
		if (t.active === 0){
			t.stateToggle('start', 'disable');
		} // здесь не ставим else, ибо может быть всего одна страница, она же первая и последняя 
		if (t.active === t.$pages.length-1 ){
			t.stateToggle('end', 'disable');
		}
	}

	$(el).on('click', '.pagination-item:not(.active):not(.disabled)', function(e){
		e.preventDefault();
		var $t = $(this);
		if ( $t.hasClass('to-start') ){ 
			t.setActive(0);
		} else if ( $t.hasClass('to-end') ){
			t.setActive(t.$pages.length-1);
		} else if ( $t.hasClass('prev') ){ 
			t.setActive(t.active-1);
		} else if ( $t.hasClass('next') ){
			t.setActive(t.active+1);
		} else {
			var clicked = $.inArray(this, t.$pages);
			t.setActive(clicked);
		}
	})	

	if ( -1 === t.active ) t.setActive(0);
}