//Main Spec
RZB2.ajax.MainSpecTab = function(params){
	this.tab = '';
	this.tabId = '';
	this.contentId = '';
	this.spinners = [];
	this.isLoaded = false;
	this.$tab = false;
	this.$content = false;
	this.editOn = false;
	if (typeof params == 'object') {
		this.tab = params.tab || this.tab;
		this.tabId = params.tabId || this.tabId;
		this.contentId = params.contentId || this.contentId;
		this.editOn = params.edit_mode == 'Y';
	}
};
RZB2.ajax.MainSpecTab.prototype.Init = function() {
	var _ = this;
	if (!!_.tabId) {
		_.$tab = $('#'+_.tabId);
		_.$tab.on('b2SblockTabOpen', BX.delegate(_.Load, _));
		if (_.$tab.hasClass('shown')) {
			setTimeout(BX.delegate(_.Load, _), 10);
		}
		//_.spinners[_.spinners.length] = RZB2.ajax.spinner(_.$tab.find('.combo-header'));
		_.spinners[_.spinners.length] = RZB2.ajax.spinner($('.combo-links [href=#'+_.tabId+']'));
	}
	if (!!_.contentId) {
		_.$content = $('#'+_.contentId);
		_.spinners[_.spinners.length] = RZB2.ajax.spinner(_.$content);
	}
};
RZB2.ajax.MainSpecTab.prototype.Load = function() {
	var _ = this;
	if (_.isLoaded) return;

	_.isLoaded = true;
	_.SendRequest();
};
RZB2.ajax.MainSpecTab.prototype.SendRequest = function() {
	var _ = this;
	_.spinners.forEach(function(spinner){
		spinner.Start();
	});
	$.ajax({
		url: SITE_DIR + 'ajax/main_spec.php',
		type: "POST",
		data: {rz_ajax: 'y', tab: _.tab},
		error: function() {
			_.isLoaded = false;
			_.StopSpinners();
		},
		success: function(res){
			_.editOn ? _.$content.html($(res).find('.conteiner-items')) : _.$content.html(res);
			_.StopSpinners();
			initSpecialBlocks(_.$tab);
			initPhotoThumbs(_.$content);
			initToggles(_.$content.parent());
			b2.init.ratingStars(_.$content);
			b2.init.tooltips(_.$content);
			RZB2.ajax.BasketSmall.RefreshButtons();
			RZB2.ajax.Compare.RefreshButtons();
			RZB2.ajax.Favorite.RefreshButtons();
            RZB2.utils.initLazy(_.$content);
			if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted(_.$content[0]);
		}
	});
};
RZB2.ajax.MainSpecTab.prototype.StopSpinners = function() {
	this.spinners.forEach(function(spinner){
		spinner.Stop();
	});
}
