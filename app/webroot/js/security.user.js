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


var SecurityUser = {
	addContact: function(obj) {
		var parent = $(obj).closest('.control-wrapper');
		var index = parent.find('input').length;
		var type = $(obj).attr('type');
		
		var maskId;
		var params = {index: index, type: type};
		var success = function(data, status) {
			var callback = function() {
				var group = parent.find('.control-group');
				group.append(data);
			};
			$.unmask({id: maskId, callback: callback});
		};
		$.ajax({
			type: 'GET',
			dataType: 'text',
			url: getRootURL() + $(obj).attr('url'),
			data: params,
			beforeSend: function (jqXHR) { maskId = $.mask({parent: parent}); },
			success: success
		});
	}
}