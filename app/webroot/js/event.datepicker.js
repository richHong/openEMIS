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


var DatePicker = {
	initHighlight: function(element) {
		var obj = $(element);
		/*obj.find('th.next:visible').click(function() { 
			DatePicker.highlightDates(element);
		});*/
		obj.find('.datepicker-switch').bind("DOMSubtreeModified",function(){
			DatePicker.highlightDates(element);
		});
	},
	
	highlightDates: function(element) {
		var obj = $(element);
		var date = obj.find('.datepicker-switch').html();
		var url = getRootURL() + obj.attr('data-url');
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: url,
			data: {date: date},
			beforeSend: function (jqXHR) { },
			success: function (data, textStatus) {
				obj.find('.day').each(function() {
					if(!$(this).hasClass('old') && !$(this).hasClass('new')) {
						if(data.indexOf($(this).html()) != -1) {
							$(this).addClass('highlight');
						}
					}
				});
			}
		});
	}
}