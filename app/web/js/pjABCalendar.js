/*!
 * Availability Calendar v5.0
 * https://www.phpjabbers.com/availability-calendar/
 * 
 * Copyright 2013, PHPJabbers
 * https://www.phpjabbers.com/license-agreement.php
 * https://www.phpjabbers.com/licence-explained.php
 * 
 * Date: Fri Aug 02 09:42:32 2013 +0200
 */
(function (window, undefined) {
	"use strict";
	var document = window.document;
	
	function ABCalendar(opts) {
		if (!(this instanceof ABCalendar)) {
			return new ABCalendar(opts);
		}
		
		this.selector = ".abCalendarDate, .abCalendarReservedNightsStart, .abCalendarReservedNightsEnd, .abCalendarPendingNightsStart, .abCalendarPendingNightsEnd, .abCalendarNightsReservedPending, .abCalendarNightsPendingReserved, .abCalendarNightsPendingPending, .abCalendarPartial";
		this.opts = null;
		this.$abWrapper = null;
		this.$abCalendar = null;
		this.$abLoader = null;
		this.outerHeight = null;
		this.reset.call(this);
		this.init.call(this, opts);
		
		return this;
	}
	
	function log() {
		if (window && window.console && window.console.log) {
			window.console.log.apply(window.console, arguments);
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	ABCalendar.sortByTime = function (a, b) {
		var aTime = Number(a.getAttribute("data-time")),
			bTime = Number(b.getAttribute("data-time")); 
		return ((aTime < bTime) ? -1 : ((aTime > bTime) ? 1 : 0));
	};
	
	ABCalendar.prototype = {
		reset: function () {
			this.month = null;
			this.year = null;
			this.message_type = null;
			
			return this;
		},
		init: function (opts) {
			var mid,
				self = this;
			this.opts = opts;
			this.$abWrapper = pjQ.$("#abWrapper_" + this.opts.cid);
			this.$abCalendar = pjQ.$("#abCalendar_" + this.opts.cid);
			this.$abLoader = pjQ.$("#abLoader_" + this.opts.cid);
			
			this.message_type = 'calendar';
			this.loadHandler.call(this);
			this.getCalendar.call(this, this.opts.year, this.opts.month);
			
			this.$abWrapper.on("click.ab", ".abCalendarLinkMonth", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				
				self.message_type = 'calendar';
				self.loadHandler.call(self);
				self.getCalendar.call(self, $this.data('year'), $this.data('month'));
				
				return false;
			}).on("click.ab", ".abSelectorLocale", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				$this.addClass("abLocaleFocus").parent().parent().find("a.abSelectorLocale").not(this).removeClass("abLocaleFocus");
				this.message_type = 'calendar';
				self.loadHandler.call(self);
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionLocale"].join(""), {
					"cid": self.opts.cid,
					"locale_id": $this.data("id"),
					"session_id": self.opts.session_id
				}).done(function (data) {
					var year = self.year,
						month = self.month;
					self.reset.call(self);
					self.message_type = 'calendar';
					self.loadHandler.call(self);
					self.getCalendar.call(self, year, month);
				}).fail(function () {
					log("Deferred is rejected");
				});
				return false;
			}).on("click.ab", ".abReturnToAvailability", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.message_type = 'calendars';
				self.loadHandler.call(self);
				pjQ.$.get([self.opts.folder, "index.php?controller=pjFront&action=pjActionLoadAvail&index=", self.opts.index, "&locale=", self.opts.locale, "&session_id=", self.opts.session_id].join("")).done(function (data) {
					var template = data.template;
					self.$abWrapper.replaceWith(template.replace('{MSG}', self.opts.error_msg.calendars));
					if (ABCalendarAvailability !== undefined) {
						var abName = "ABCalendarAvailability_" + self.opts.index,
						options = {
							server: self.opts.server,
							folder: self.opts.folder,
							index: self.opts.index,
							locale: self.opts.locale,
							year: self.year,
							month: self.month
						};
						window[abName] = new ABCalendarAvailability(options);
					}
				});
				return false;
			}).on("click.ab", ".abReturnToCalendar", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.start_dt = null;
				self.end_dt = null;
				self.message_type = 'calendar';
				self.loadHandler.call(self);
				self.getCalendar.call(self, self.year, self.month);
				return false;
			});
		},
		loadHandler: function () {
			var msg = '',
				error_msg = pjQ.$('.abErrorMessage').data('msg');
			if (typeof error_msg === 'undefined')
			{
				error_msg = this.opts.load_msg;
			}
			if (typeof error_msg === 'undefined')
			{
				error_msg = this.opts.error_msg;
			}
			switch (this.message_type)
			{
				case 'calendar':
					msg = error_msg.calendar;
					break;
				case 'calendars':
					msg = error_msg.calendars;
					break;
			}
			if (arguments.length !== 0) {
				msg = arguments[0];
			}
			this.$abCalendar.html("");
			this.$abLoader.find('span.abLoaderMessage').html(msg);
			this.$abLoader.show();

			return this;
		},
		_getCalendar: function (year, month) {
			var self = this;
			pjQ.$.get([this.opts.folder, "index.php?controller=pjFront&action=pjActionGetCalendar"].join(""), {
				"cid": self.opts.cid,
				"view": self.opts.view,
				"year": year,
				"month": month,
				"locale": self.opts.locale,
				"index": self.opts.index,
				"session_id": self.opts.session_id
			}).done(function (data) {
				self.$abWrapper.parent().css("height", self.outerHeight+"px");
				self.$abCalendar.html(data);
				self.$abCalendar.find('.abCalendarTable > tbody > tr').each(function(e){
					var $this = pjQ.$(this),
						empty_row = true;
					$this.find('td').each(function(event){
						if(pjQ.$(this).html() != '&nbsp;')
						{
							empty_row = false;
						}
					});
					if(empty_row == true)
					{
						$this.remove();
					}
				});
				self.$abLoader.hide();
				
				var dt = new Date();
				self.month = month || dt.getMonth() + 1;
				self.year = year || dt.getFullYear();
			}).fail(function () {
				log("Deferred is rejected");
			});
		},
		getCalendar: function (year, month) {
			var self = this;
			this._getCalendar.call(this, year, month);
		}
	};
	
	// expose
	window.ABCalendar = ABCalendar;
})(window);