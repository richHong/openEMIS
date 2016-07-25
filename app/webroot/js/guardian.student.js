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

$( document ).ready(function() {
  GuardianStudent.init();
});

var GuardianStudent = {
    init: function() {
        var table = $('.tab-content');
        var element = '#autocomplete';
        var url = getRootURL() + 'Guardians/GuardianStudent/student_search/'; 
        GuardianStudent.attachAutoComplete(element, url, GuardianStudent.selectField, GuardianStudent.choiceFocus);
        // End PHPSM-50
    },

    selectField: function(event, ui) {
        var val = ui.item.data;

        $('#StudentSecurityUserId').val(val['id']);
        $('#FirstName').val(val['first_name']);
        $('#LastName').val(val['last_name']);

        return false;
    },
    
    choiceFocus: function(event, ui) {
    	var str = ui.item.label.split(' ');
        this.value = str[0];
       	event.preventDefault(); // Prevent the default focus behavior.
    },

    attachAutoComplete: function(element, url, callback, focusCallback) {
        $(element).autocomplete({
            source: url, //StudentGuardians.alt_tags,
            minLength: 2,
            select: callback,
            // PHPSM-50: Fix the [object Object] bug when using keyboard up/down button to select choice
            focus: focusCallback
			// End PHPSM-50
        });
    }
}