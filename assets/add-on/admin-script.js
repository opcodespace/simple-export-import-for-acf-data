(function ($) {	"use strict";

	jQuery(document).ready(function($){

		$(".chosen-select").chosen();

		VirtualSelect.init({
			ele: '#export_mulit_pages',
			multiple: true,
			optionHeight: 36,
			minWidth:250
		});

	});

}(jQuery));	