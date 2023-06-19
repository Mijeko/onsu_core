b2.init.buyClick = function(){
	b2.el.$basket = $('#basket');
	
	var ttTimeout;
	$(document).find('button.buy, a.buy').on('click', function(e){
		e.preventDefault();
		switch ( b2.s.actionOnBuy ){
			case 'animation-n-popup':

				var _ = $(this);
				if ( _.hasClass('disabled') ) return;

				var root = _.closest('.catalog-item');
				var price;
				var img;

				if ( !root.length ){
					root = _.closest('.buy-block-content');
					if ( !root.length ){
						root = _.closest('.slider-item');

						if ( !root.length ) break;
						price = root.find('.price-value').html();
					}
					img = root.find('.product-main-photo>img');
					price = root.find('.price>.value').html();

				} else {
					price = root.find('.price-value').html();
				}
				if ( !price ) break;
				price = formatRub.from(price);
				var quantity = parseFloat(root.find('.quantity-input').val());
				if ( !quantity ) quantity = 1;
				var totalPrice = quantity * price;
				if ( !img ) img = root.find('.photo img');
				var tt = _.next('.tooltip');
				
				fly(img, b2.el.$basket);
				addToBasket(b2.el.$basket, totalPrice, 1);
				// 1 for adding only one goods item. Quantity IS NOT EQUAL to items.
				// we add 1 item, but different quantity (may be 10 kg of meat, as
				// an example. Meat = goods item. 10 kg = quantity)
				
				if (isHover) {
					if ( tt.length === 0 ){
						_.tooltip({
							trigger: 'focus',
							html: true,
							title: '<i class="flaticon-43"></i>\
									<div class="text">Товар добавлен в корзину</div>\
									<a href="basket-big.php" class="link">\
										<span class="text">Оформить заказ</span\
									</a>',
							template: '\
							<div class="tooltip buy-popup" role="tooltip">\
								<div class="tooltip-arrow"></div>\
								<div class="tooltip-inner"></div>\
							</div>',
						}).one('shown.bs.tooltip', function(){
							tt = _.next('.tooltip');
							tt.hover(function(){
								clearTimeout(ttTimeout);
							}, function(){
								ttTimeout = setTimeout(function(){
									_.tooltip('destroy');
								}, 4000);
							})
						}).one('hidden.bs.tooltip',function(){
							clearTimeout(ttTimeout);
							_.tooltip('destroy');
						}).tooltip('show').one('keyup', function(e){
							if ( 27 === e.keyCode ){
								clearTimeout(ttTimeout);
								e.stopImmediatePropagation();
								_.tooltip('destroy');
							}
						})
						ttTimeout = setTimeout(function(){
							_.tooltip('destroy');
						}, 4000);
					} else {
						clearTimeout(ttTimeout);
						ttTimeout = setTimeout(function(){
							_.tooltip('destroy');
						}, 4000);
					}
				}
				
			break;
			case 'open-modal-basket':
				$('#modal_basket').modal('show');
			break;
			case 'go-to-big-basket':
				window.location.href = 'basket-big.php';
			break;
			default:
				console.log('problems in button.buy click');
		}
	})
}