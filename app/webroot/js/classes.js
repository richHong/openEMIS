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
	Classes.init();
});

var Classes = {
	init: function(obj) {
		if($('#datepickerStart').length > 0){
			var checkin = $('#datepickerStart').datetimepicker({
				maskInput: true,   
				pickTime: false,
				language: 'en',
				format: 'yyyy-MM-dd',

		}).on('changeDate', function(ev) {
            var newDate = new Date(ev.date);
			newDate.setDate(newDate.getDate());
		});
		}
	},

	addGrade: function(obj) {
		var parent = $(obj).closest('.control-wrapper');
		var index = $('.control-dynamic').length;
		
		var maskId;

		var params = {index: index};
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
	},
	
	addStudent: function(obj) {
		var table = $('.table-content');
		var index = table.find('tr').length + $('.delete input').length;
		var maskId;
		var params = {index: index};
		
		var success = function(data, status) {
			var callback = function() {
				table.find('tbody').append(data);
				var element = '#search' + index;
				var url = getRootURL() + table.attr('url');
				Form.attachAutoComplete(element, url, Classes.selectStudent);
			};
			$.unmask({id: maskId, callback: callback});
		};
		$.ajax({
			type: 'GET',
			dataType: 'text',
			url: getRootURL() + $(obj).attr('url'),
			data: params,
			beforeSend: function (jqXHR) { maskId = $.mask({parent: table}); },
			success: success
		});
	},
	
	selectStudent: function(event, ui) {
		var val = ui.item.value;
		var element;
		for(var i in val) {
			element = $('.' + i);
			if(element.get(0).tagName.toUpperCase() === 'INPUT') {
				element.val(val[i]);
			} else {
				element.html(val[i]);
			}
		}
		return false;
	},
	
	deleteStudent: function(obj) {
		var row = $(obj).closest('tr');
		var id = row.attr('row-id');
		var controlId = $('.control-id');
		if(id != undefined) {
			var div = $('.delete');
			var index = div.find('input').length;
			var name = div.attr('name').replace('{index}', index);
			var controlId = $('.control-id');
			var input = row.find(controlId).attr({type: 'hidden', name: name});
			div.append(input);
		}
		row.remove();
	},

	addSubject: function(obj) {
		var table = $('.table-content');
		var index = table.find('tr').length + $('.delete input').length;
		var maskId;
		var params = {index: index};
		var success = function(data, status) {
			var callback = function() {
				table.find('tbody').append(data);
				var element = '#search' + index;
				var url = getRootURL() + table.attr('url');
				Form.attachAutoComplete(element, url, Classes.selectSubject);
			};
			$.unmask({id: maskId, callback: callback});
		};
		$.ajax({
			type: 'GET',
			dataType: 'text',
			url: getRootURL() + $(obj).attr('url'),
			data: params,
			beforeSend: function (jqXHR) { maskId = $.mask({parent: table}); },
			success: success
		});
	},

	selectSubject: function(event, ui) {
		var val = ui.item.value;
		var element;
		for(var i in val) {
			element = $('.' + i);
			if(element.get(0).tagName.toUpperCase() === 'INPUT') {
				element.val(val[i]);
			} else {
				element.html(val[i]);
			}
		}
		return false;
	},
	
	deleteSubject: function(obj) {
		var row = $(obj).closest('tr');
		var id = row.attr('row-id');
		if(id != undefined) {
			var div = $('.delete');
			var index = div.find('input').length;
			var name = div.attr('name').replace('{index}', index);
			var controlId = $('.control-id');
			var input = row.find(controlId).attr({type: 'hidden', name: name});
			div.append(input);
		}
		row.remove();
	},

	addTeacher: function(obj) {
		var table = $('.table-content');
		var index = table.find('tr').length + $('.delete input').length;
		var maskId;
		var params = {index: index};
		var success = function(data, status) {
			var callback = function() {
				table.find('tbody').append(data);
				var element = '#search' + index;
				var url = getRootURL() + table.attr('url');
				Form.attachAutoComplete(element, url, Classes.selectTeacher);
			};
			$.unmask({id: maskId, callback: callback});
		};
		$.ajax({
			type: 'GET',
			dataType: 'text',
			url: getRootURL() + $(obj).attr('url'),
			data: params,
			beforeSend: function (jqXHR) { maskId = $.mask({parent: table}); },
			success: success
		});
	},

	selectTeacher: function(event, ui) {
		var val = ui.item.value;
		var element;
		for(var i in val) {
			element = $('.' + i);
			if(element.get(0).tagName.toUpperCase() === 'INPUT') {
				element.val(val[i]);
			} else {
				element.html(val[i]);
			}
		}
		return false;
	},
	
	deleteTeacher: function(obj) {
		var row = $(obj).closest('tr');
		var id = row.attr('row-id');
		if(id != undefined) {
			var div = $('.delete');
			var index = div.find('input').length;
			var name = div.attr('name').replace('{index}', index);
			var controlId = $('.control-id');
			var input = row.find(controlId).attr({type: 'hidden', name: name});
			div.append(input);
		}
		row.remove();
	},



	toggleCheckbox: function(obj) {
		var checkVal = obj.checked;
		$('.toggleCheckbox').each(function(i, toggleObj) {
		    if(checkVal){
		    	toggleObj.checked = true;
		    }else{
		    	toggleObj.checked = false;
		    }
		});
	},
}