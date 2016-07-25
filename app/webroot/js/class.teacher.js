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
    ClassTeachers.init();
});

var ClassTeachers = {

    init: function() {
        var table = $('.tab-content');
        var element = '#searchId';
        var url = getRootURL() + table.attr('url');
        ClassTeachers.attachAutoComplete(element, url, ClassTeachers.selectField, ClassTeachers.choiceFocus);
    },

    selectField: function(event, ui) {
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
    
    // PHPSM-50: Introduce extra event handler to resolve the [Object object] bug
    choiceFocus: function(event, ui) {
    	var str = ui.item.label.split(' ');
        this.value = str[0];
       	event.preventDefault(); // Prevent the default focus behavior.
    },
    // End PHPSM-50

    attachAutoComplete: function(element, url, callback, focusCallback) {
        $(element).autocomplete({
            source: url,
            minLength: 2,
            select: callback,
            // PHPSM-50: Introduce extra event handler
            focus: focusCallback
			// End PHPSM-50
        });
        $(element).change(function() {
            ClassTeachers.inputStaffDetails(element);
        });
        $(element).keypress(function(event){
            if(event.keyCode == 13)
            {
                 ClassTeachers.inputStaffDetails(element);
                 event.preventDefault();
                 return false
            }
        });
    },

    inputStaffDetails: function(element) {
        var table = $('.tab-content');
        var url = getRootURL() + table.attr('url');
        
        $('.openemisid').val("");
        $('.staff-id').val("");
        $('.first-name').val("");
        $('.last-name').val("");

        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: url,
            data: "term="+ $(element).val(),
            success: function (data, textStatus) {
                for(var i in data){
                    val = data[i]['value'];
                    for(var j in val) {
                        ele = $('.' + j);
                        if(ele.get(0).tagName.toUpperCase() === 'INPUT') {
                            ele.val(val[j]);
                        } else {
                            ele.html(val[j]);
                        }
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            } 
        });


    }


}
