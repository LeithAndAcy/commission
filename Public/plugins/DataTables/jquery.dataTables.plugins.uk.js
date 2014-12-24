//DD/MM/YYYY style sorting

jQuery.fn.dataTableExt.aTypes.unshift(function(sData) {
	if (sData !== null && sData.match(/^(0\d|[12]\d|3[01])\/(0\d|1[012])\/(00|19|20|21)\d\d$/)) {
		return 'uk_date';
	}
	return null;
});

$.extend(jQuery.fn.dataTableExt.oSort, {
	"uk_date-pre" : function(d) {
		return parseInt(d.substr(6, 4) + d.substr(3, 2) + d.substr(0, 2), 10);
	},

	"uk_date-asc" : function(x, y) {
		return ((x < y) ? -1 : ((x > y) ? 1 : 0));
	},

	"uk_date-desc" : function(x, y) {
		return ((x < y) ? 1 : ((x > y) ? -1 : 0));
	}
});

// * DD/MM/YYYY - HH:MM:SS style sorting

jQuery.fn.dataTableExt.aTypes.unshift(function(sData) {
	if (sData !== null && sData.match(/^(0\d|[12]\d|3[01])\/(0\d|1[012])\/(00|19|20|21)\d\d - ([012]\d:[0-5]\d:[0-5]\d)$/)) {
		return 'uk_full_date';
	}
	return null;
});

$.extend(jQuery.fn.dataTableExt.oSort, {
	"uk_full_date-pre" : function(d) {
		return parseInt(d.substr(6, 4) + d.substr(3, 2) + d.substr(0, 2) + d.substr(13, 2) + d.substr(16, 2) + d.substr(19, 2), 10);
	},

	"uk_full_date-asc" : function(x, y) {
		return ((x < y) ? -1 : ((x > y) ? 1 : 0));
	},

	"uk_full_date-desc" : function(x, y) {
		return ((x < y) ? 1 : ((x > y) ? -1 : 0));
	}
}); 