/*
OpenEMIS School
Open School Management Information System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more information please email contact@openemis.org.
*/

$(document).ready(function() {	
	Form.init();
	$( ":button" ).bind( "click", function() {
		$( this ).toggleClass( "disabled" );
	});
});

var Form = {
	init: function() {
		$('input[type="number"]').keypress(function(evt) {
			//return utility.integerCheck(evt);
		});
		this.linkVoid();
		$('button.btn-back').each(function() {
			$(this).click(Form.back);
		});
	},
	
	back: function() {
		var parent = $('.fa-arrow-left').parent();
		if(parent.prop('tagName')=='A') {
			window.location.href = parent.attr('href');
		}
	},
	
	change: function(obj) {
		window.location.href = getRootURL() + $(obj).attr('url') + '/' + $(obj).val();
	},
	
	linkVoid: function(id) {
		var element = id!=undefined ? id + ' a.void' : 'a.void';
		$(element).each(function() {
			$(this).attr('href', 'javascript: void(0)');
		});
	},
	
	attachAutoComplete: function(element, url, callback) {
		$(element).autocomplete({
			source: url,
			minLength: 2,
			select: callback
		});
	},
	
	toggleSelect: function(obj) {
		var table = $(obj).closest('table');
		table.find('tbody input[type="checkbox"]').each(function() {
			var row = $(this).closest('tr');
			if(obj.checked) {
				if($(this).attr('disabled') == undefined){
					$(this).attr('checked','checked');
					if(row.hasClass('inactive')) {
						row.removeClass('inactive');
					}
				}
			} else {
				$(this).removeAttr('checked');
				if(!row.hasClass('inactive')) {
					row.addClass('inactive');
				}
			}
		});
	}
};