(function ($) {

	$.fn.abc = function (e) {
		$(this).each(function () {
			return new Plugin($(this), $(e));
		});
	};


	var Plugin = function (a, b) {
		this.$tab = a;
		this.$content = b;
		this._init();
		this.build();
	};

	Plugin.prototype = {
		_init: function () {
			this.tabCount = this.$tab.length;
			this.contentCount = this.$content.length;
		},
		build: function () {
			if (this.tabCount != this.contentCount) {
				console.log('123123123');
				return;
			}
			this.trigger();
		},
		trigger: function () {
			var that = this;
			this.$tab.click(function () {
				that.tabTo($(this).index());
			});
		}
	};

	$('.tab').abc('.content');

})(jQuery);