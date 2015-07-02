function lightbox(lparams) {
	var lbox = {};
	lbox.params = lparams;

	lbox.initialize = function() {

		this.lboxWrapper = jQuery('#lboxWrapper');

		//detect our wrapper
		if (!this.lboxWrapper || this.lboxWrapper.length == 0) {
			//append it to the page
			
			jQuery('body').append('<div id="lboxWrapper"></div>');
			this.lboxWrapper = jQuery('#lboxWrapper');
		}

		//generate a unique id for this lightbox
		this.uid = Math.floor(Math.random() * 10000000);
		this.closeCallback = this.params.closeCallback;
		this.disableClose = !!this.params.disableClose;

		var html = "<div id='lboxWrapper-" + this.uid + "' class='lboxWrapper'>";
		html += '<div class="lboxOverlay" id="lboxOverlay' + this.uid + '"></div>';
		html += '<div class="lboxFrame" id="lboxFrame-' + this.uid + '">';
		html += '<div class="lboxClose" id="lboxClose-' + this.uid + '">X</div>';
		html += '<div class="lboxContent" id="lboxContent-' + this.uid + '">' + this.params.content + '</div></div></div>';

		this.lboxWrapper.append(html);

		this.wrapper = this.lboxWrapper.find('.lboxWrapper');;
		this.overlay = this.wrapper.find('.lboxOverlay');
		this.frame = this.wrapper.find('.lboxFrame');

		var self = this;

		if (!this.disableClose) {
			this.overlay.click(function(e) {
				self.closeBox(e);
			});
		}

		//the close button
		if (!this.disableClose) {
			this.close = this.wrapper.find('.lboxClose');
			this.close.click(function(e) {
				self.closeBox(e);
			});
		}

		//add a content div
		this.container = this.wrapper.find('.lboxContent');
		this.openBox();
	};

	lbox.refreshScroll = function() {
		//this.scrollingBox = new ScrollArea(this.scrollContent, this.scrollbar);
	};

	lbox.closeBox = function(e) {
		var dismissThyself = true;

		if (this.closeCallback) {
			dismissThyself = this.closeCallback();
		}

		if (dismissThyself != false) { //backwards compatible shizzle
			this.frame.removeClass('visible');
			this.overlay.removeClass('visible');
			this.wrapper.removeClass('visible');

			var self = this;

			setTimeout(function() {
				//detect any other lightboxes open
				if (jQuery('#lboxWrapper .visible').length === 0) {
					jQuery('body').css('overflow', '');

					self.lboxWrapper.removeClass('visible');
				}

				self.removeFromDom();
			}, 500);
		}
	};

	lbox.openBox = function() {
		jQuery('body').css('overflow', 'hidden');
		var self = this;
		setTimeout(function() {
			self.frame.css('top', '50%');
			self.frame.css('margin-top', -(self.frame.height() / 2));

			self.frame.addClass('visible');
			self.overlay.addClass('visible');
			self.wrapper.addClass('visible');

			self.lboxWrapper.addClass('visible');
		}, 50);

		this.refreshScroll();
	};

	lbox.resize = function() {
		this.frame.css('top', '50%');
		this.frame.css('margin-top', -(this.frame.height() / 2));
		this.frame.css('left', '50%');
		this.frame.css('margin-left', -(this.frame.width() / 2));
	};

	lbox.removeFromDom = function() {
		this.wrapper.remove();
	};

	lbox.initialize();

	return lbox;
}
