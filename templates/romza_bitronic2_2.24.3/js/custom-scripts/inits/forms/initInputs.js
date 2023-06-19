b2.init.inputs = function(target){
	// disabling the disabled.
	// because pointer-events: none is not supported in old browsers
	$(target).find('.checkbox-styled.disabled').click(function(){
		return false;
	});
	
	// for selecting input content on focus
	var focusedElement;
	// inputs content select on focus
	$(target).find('input').on('focus', function(){
		if (focusedElement == $(this)) return;
		// ^ if input is already being edited, we don't need to
		// select its full content again. Otherwise it's impossible
		// to place cursor in the desired place
		focusedElement = $(this);
		setTimeout(function () { focusedElement.select(); }, 100); 
		// ^ timeout is a hack for Chrome. Without it select doesn't work.
	});

	// switching password display on/off
	$(target).find('.btn-password-toggle').on('click', function(e){
		e.stopPropagation();
		e.preventDefault();
		return false;
	});

    $(target).find('input[type="file"]').bind({
        change: function() {
            // display of chosen files and thumbnail creation
            var imageType = /image.*/;

            // clear the list of previously selected images
            $('.chosen-file-preview').html('');

            $.each(this.files, function(i, file) {
                // do not sift pictures
                if ( !file.type.match(imageType) ) {
                    //console.log(BX.message('file-ots') + '`' + file.name + '` (' + BX.message('file-type') + file.type + ')');
                    return true;
                }

                // create the img element and place it in miniature
                var img = $('<img/>').appendTo('.chosen-file-preview');

                // create a FileReader object and after reading the file, display the thumbnail
                var reader = new FileReader();
                reader.onload = (function(aImg) {
                    return function(e) {
                        aImg.attr('src', e.target.result);
                        aImg.attr('width', 150);
                    }
                })(img);

                reader.readAsDataURL(file);
            });
        }
    });

	$(target).find('.btn-password-toggle').on('mousedown', function(e){
		var field = $(this).closest('.textinput-icons').siblings('input');
		if (field.attr('type') == 'text'){
			field.get(0).type = 'password';
			//^ don't use jQuery 'cause it won't allow us to change type
			// for some IE compatibility reasons
		} else if (field.attr('type') == 'password'){
			field.get(0).type = 'text';
		}
		return false;
	});

	$(target).find('input[type="file"]').on('change', function(){
		var t = $(this);
		var fileName = t.val().replace(/.*(\/|\\)/, '');
		var fileNameElement = t.closest('.fileinput-styled').children('.chosen-file');
		if (!fileName) {
			fileNameElement.html('*Файл не выбран');	
		} else { 
			fileNameElement.html(fileName);
		}
	})

	$(target).find('.checkbox-content').keydown(function(e){
		if ( 32 === e.keyCode ){
			var chBox = $(this).siblings('input[type="checkbox"]');
			chBox.prop("checked", !chBox.prop("checked"));
		}
	})
}