var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	"use strict";
	$(function () {
		var datagrid = ($.fn.datagrid !== undefined),
			dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			perfectScrollbar = ($.fn.perfectScrollbar !== undefined),
			$tabs = $("#tabs"),
			tabs = ($.fn.tabs !== undefined),
			$frmViewCalendar = $("#frmViewCalendar"),
			$frmCreateCalendar = $("#frmCreateCalendar");

		if ($tabs.length > 0 && tabs) {
			$tabs.tabs();
		}
		if (perfectScrollbar) {
			$(".cal-dates").perfectScrollbar();
		}
		if ($frmViewCalendar.length > 0 && validate) {
			$.validator.addMethod('sameDate',
				function (value) { 
		        	if($('input[name="start_date"]').val() == value && $('input[name="end_date"]').attr('data-mode') == 'nights')
		        	{
		        		return false;
		        	}else{
		        		return true;
		        	}
		    	}
		   );
			
			$frmViewCalendar.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				errorClass: "err",
				wrapper: "em",
				ignore: ".ignore",
				invalidHandler: function (event, validator) {
					var errors = validator.numberOfInvalids();
			        if (errors) {                    
			            validator.errorList[0].element.focus();
			        }
				}
			});			
		}
		
		if ($frmCreateCalendar.length > 0 && validate) {
			$frmCreateCalendar.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				errorClass: "err",
				wrapper: "em",
				onkeyup: false,
				ignore: ".ignore",
				invalidHandler: function (event, validator) {
					$(".pj-multilang-wrap").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).css('display','block');
						}else{
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).addClass('pj-form-langbar-item-active');
						}else{
							$(this).removeClass('pj-form-langbar-item-active');
						}
					});
				}
			});
		}
		
		$("#content").on("click", ".pj-checkbox", function () {
			var $this = $(this);
			if ($this.find("input[type='checkbox']").is(":checked")) {
				$this.addClass("pj-checkbox-checked");
			} else {
				$this.removeClass("pj-checkbox-checked");
			}
		}).on("click", ".listing-tip", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			return false;
		}).on("click", ".abCalendarLinkMonth", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			$.get("index.php?controller=pjAdminCalendars&action=pjActionGetCal", {
				"cid": $this.data("cid"),
				"year": $this.data("year"),
				"month": $this.data("month")
			}).done(function (data) {
				$("#abCalendar_" + $this.data("cid")).html(data);
			});
			return false;
		});
			
		function formatOwner(val, obj) {
			if (pjGrid.isAdmin === true) 
			{
				return ['<a href="index.php?controller=pjAdminUsers&action=pjActionUpdate&id=', obj.user_id, '">', obj.user_name, '</a>'].join("");
			}else{
				return obj.user_name;
			}
		}
		
		function formatName(val, obj) {
			return ['<a href="index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminCalendars&nextAction=pjActionView&calendar_id=', obj.id, '&nextParams=', encodeURIComponent("id=" + obj.id), '">', val, '</a>'].join("");
		}
		
		function onBeforeShow (obj) {
			if (parseInt(obj.id, 10) === pjGrid.currentCalendarId) {
				return false;
			}
			return true;
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var gridOpts = {
				buttons: [{type: "settings", title: myLabel.settings, url: "index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminOptions&nextAction=pjActionIndex&nextParams=tab%3D1&calendar_id={:id}"},
				          {type: "preview", title: myLabel.installPreview, url: "index.php?controller=pjAdminOptions&action=pjActionInstall&calendar_id={:id}"},
				          {type: "edit", title: myLabel.edit, url: "index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminCalendars&nextAction=pjActionView&calendar_id={:id}&nextParams=id%3D{:id}"},
				          {type: "delete", title: myLabel.delete, url: "index.php?controller=pjAdminCalendars&action=pjActionDeleteCalendar&id={:id}", beforeShow: onBeforeShow}],
				columns: [{text: myLabel.id, type: "text", sortable: true, editable: false},
				          {text: myLabel.calendar, type: "text", sortable: true, width: 270, renderer: formatName},
				          {text: myLabel.user, type: "text", sortable: true, editable: false, width: 150, renderer: formatOwner}
				],
				dataUrl: "index.php?controller=pjAdminCalendars&action=pjActionGetCalendar" + pjGrid.queryString,
				dataType: "json",
				fields: ['id', 'name', 'user_name'],
				paginator: {
					actions: [
						{text: myLabel.deleteSelected, url: "index.php?controller=pjAdminCalendars&action=pjActionDeleteCalendarBulk", render: true, confirmation: myLabel.deleteConfirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminCalendars&action=pjActionSaveCalendar&id={:id}",
				select: {
					field: "id",
					name: "record[]"
				}
			};
			
			var $grid = $("#grid").datagrid(gridOpts);
			
			$(document).on("submit", ".frm-filter", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = $(this),
					content = $grid.datagrid("option", "content"),
					cache = $grid.datagrid("option", "cache");
				$.extend(cache, {
					q: $this.find("input[name='q']").val()
				});
				$grid.datagrid("option", "cache", cache);
				$grid.datagrid("load", "index.php?controller=pjAdminCalendars&action=pjActionGetCalendar", "id", "ASC", content.page, content.rowCount);
				return false;
			});
		}
		
		$(document).on("focusin", ".datepicker", function (e) {
			var minDate, maxDate,
				$this = $(this),
				custom = {},
				o = {
					firstDay: $this.attr("rel"),
					dateFormat: $this.attr("rev"),
					defaultDate: $this.attr('data-format'),
					onClose: function(dateText){
						if(dateText != '')
						{
							$this.parent().parent().find('em').remove('em');
						}
					}
				};
			
			switch ($this.attr("name")) {
			case "start_date":
				if($(".datepicker[name='end_date']").val() != '')
				{
					maxDate = $(".datepicker[name='end_date']").datepicker({
						firstDay: $this.attr("rel"),
						dateFormat: $this.attr("rev")
					}).datepicker("getDate");
					$(".datepicker[name='end_date']").datepicker("destroy").removeAttr("id");
					if (maxDate !== null) {
						custom.maxDate = maxDate;
					}
				}
				break;
			case "end_date":
				if($(".datepicker[name='start_date']").val() != '')
				{
					minDate = $(".datepicker[name='start_date']").datepicker({
						firstDay: $this.attr("rel"),
						dateFormat: $this.attr("rev")
					}).datepicker("getDate");
					$(".datepicker[name='start_date']").datepicker("destroy").removeAttr("id");
					if (minDate !== null) {
						custom.minDate = minDate;
					}
				}
				break;
			}
			$this.not('.hasDatepicker').datepicker($.extend(o, custom));
		}).on("click", ".pj-form-field-icon-date", function (e) {
			var $dp = $(this).parent().siblings("input[type='text']");
			if ($dp.hasClass("hasDatepicker")) {
				$dp.datepicker("show");
			} else {
				$dp.trigger("focusin").datepicker("show");
			}
		}).delegate(".cal-prev, .cal-next", "click", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			getDashboard.apply(null, [$this.data("year"), $this.data("month")]);
			return false;
		}).on("click", ".ac-button", function (e) {
			var status = $(this).attr('data-status');
			$frmViewCalendar.find("input[name='status']").val(status);
			$frmViewCalendar.submit();
		});
		
		function getDashboard() {
			$.get("index.php?controller=pjAdminCalendars&action=pjActionGetDashboard", {
				year: arguments[0],
				month: arguments[1]
			}).done(function (data) {
				$("#boxDashboard").html(data);
			});
		}
	});
})(jQuery_1_8_2);