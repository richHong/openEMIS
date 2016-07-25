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
	Datepicker.init();
});

var Datepicker = {
	init: function() {
		$('.bootstrap-datepicker').each(function() {
			if ($(this).attr('change') != undefined) {
				$(this).on('changeDate', function() {
					eval($(this).attr('change'));
				});
			}
		});
	},
	
	change: function(obj) {
		var val = $(obj).find('input').val();
		window.location.href = getRootURL() + $(obj).attr('url') + '/' + val;
	}
}
