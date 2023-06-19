$(function ($) {
	$('body').on('click', '.add_to_favorite', function (e) {
		e.preventDefault();
		var $this = $(this),
			id = $this.data('id') | 0;
		if (id > 0) {
			return $.ajax({
				url: '/bitrix/components/yenisite/favorite.list/component.php',
				dataType: 'JSON',
				data: {'ID': id, 'ACTION': 'ADD'},
				success: function (obj) {
					if(obj.result == 'success') {
						location.reload();
					} else {
						alert(obj.msg);
					}
				}
			});
		}
	})
	.on('click', '.yns_favorite-list .table-wrap .btn-delete', function (e) {
		e.preventDefault();
		var $this = $(this),
			id = $this.data('id') | 0;
		if (id > 0) {
			return $.ajax({
				url: '/bitrix/components/yenisite/favorite.list/component.php',
				dataType: 'JSON',
				data: {'ID': id, 'ACTION': 'DELETE'},
				success: function (obj) {
					if(obj.result == 'success') {
						location.reload();
					} else {
						alert(obj.msg);
					}
				}
			});
		}
	})
	.on('click', '.yns_favorite-list .popup-footer .btn-delete', function (e) {
		e.preventDefault();
		return $.ajax({
			url: '/bitrix/components/yenisite/favorite.list/component.php',
			dataType: 'JSON',
			data: {'ACTION': 'FLUSH'},
			success: function (obj) {
				if(obj.result == 'success') {
					location.reload();
				} else {
					alert(obj.msg);
				}
			}
		});
	});
});