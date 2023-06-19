function initGenInfoToggle(target, textDefault)
{
	if (typeof textDefault == "undefined") {
		textDefault = b2.s.detailTextDefault;
	}
	// check if function is needed at all
	var genInfoCollection = $(target).find('.general-info');
	//if (!genInfo || !genInfo.length) return;

	genInfoCollection.each(function(){
	//======================= VARIABLES ==========================
		var that = this,
			genInfo = $(this),
			genDesc = genInfo.find('.desc'),
			genInfoToggle = genInfo.find('.pseudolink'),
			infoHeightLimit, genInfoScrollHeight, isToggleable;

	//======================= METHODS ============================
		this.rz = {};
		this.rz.update = function(){
			if (!genDesc || !genDesc.length) return;
			// reset, just in case of something.
			genDesc.css('max-height', '');
			genInfo.removeClass('opened');

			infoHeightLimit = parseInt(genDesc.css('max-height'));
			genInfoScrollHeight = genDesc.get(0).scrollHeight;

			// button is added only if content is higher than limit by more than 20px
			// otherwise, open content fully
			if ( genInfoScrollHeight - infoHeightLimit > 20 ){
				genInfo.removeClass('opened');
				genInfoToggle.show();
				isToggleable = true;
			} else {
				genInfo.addClass('opened');
				genInfoToggle.hide();
				isToggleable = false;
			}

			if (textDefault === 'open' && isToggleable) that.rz.open();
		};
		this.rz.open = function(){
			genDesc.velocity({
				'max-height': genInfoScrollHeight
			}, 250, function(){
				genInfo.addClass('opened');
			})
		};
		this.rz.close = function(){
			genDesc.velocity({
				'max-height': infoHeightLimit
			}, 250, function(){
				genInfo.removeClass('opened');
			});
		};

	//======================= EVENTS =============================
		genInfoToggle.off('click.genInfoToggle').on('click.genInfoToggle', function(e){
			if (!genInfo || !genInfo.length) return;

			genInfo.hasClass('opened') ? that.rz.close() : that.rz.open();			
		});
		genDesc.find('img').off('load.genInfoToggle').on('load.genInfoToggle', function(){
			that.rz.update();
		});
	//======================= INIT ===============================
		this.rz.update();
	});

	genInfoCollection.update = function(){
		return this.each(function(){
			this.rz.update();
		});
	}

	return genInfoCollection;
}