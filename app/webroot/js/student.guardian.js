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
  StudentGuardians.init();
});

var StudentGuardians = {
    init: function() {
        var table = $('.tab-content');
        var element = '#autocomplete';
        var url = getRootURL() + 'Students/StudentGuardian/guardian_search/'; // was able.attr('url') which is == Students/StudentGuardian/guardian_search/

        // PHPSM-50: Fix the [object Object] bug by introducing 1 extra event handler (i.e. choiceFocus)
        StudentGuardians.attachAutoComplete(element, url, StudentGuardians.selectField, StudentGuardians.choiceFocus);
        // End PHPSM-50
    },

    selectField: function(event, ui) {
        var val = ui.item.data;

        $('#SecurityUserId').val(val['id']);
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