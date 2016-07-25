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

$(function() {
	if($('#datepickerStart').length > 0){
		$('#datepickerStart').datepicker()
		.on('changeDate', function(ev) {
			var newDate = new Date(ev.date)
			
			newDate.setDate(newDate.getDate() );
			var month = newDate.getMonth()+1;
			month = (month < 10)? '0'+month:month;
			var year = newDate.getFullYear();
			var date= newDate.getDate();
			date = (date < 10)? '0'+date:date;
			var finalDate = year+'-'+month+'-'+date;
			var urlPath = getRootURL() + $('#datepickerForm').attr('link') ;
			
			if(urlPath.charAt(urlPath.length -1) !=  '/'){
					urlPath +="/";
			}
			//alert(urlPath);
			var rootURL = $('#datepickerForm').attr('link').toLowerCase();
			var curURL = window.location.pathname.toLowerCase();
			var startIndex = curURL.search(rootURL) + rootURL.length;
			var endIndex = curURL.length ;
			
			var dataURL = curURL.substring(startIndex, endIndex);
			var pathArr = dataURL.split("/");
			var insertDate = true;
			for(var i = 0; i < pathArr.length; i++){
				if(pathArr[i] != ''){
					//alert(pathArr[i]);
					if(ClassLesson.isDate(pathArr[i])){
						//alert('is date');	
						insertDate = false;
						urlPath += 	finalDate+"/";
					}
					else{
						urlPath += 	pathArr[i]+"/";
					}
				}
			}
			if(insertDate){
				urlPath += 	finalDate+"/";
			}
			
			window.location = urlPath;
		});
	}
});

var ClassLesson = {
	isDate : function(txtDate)
	{
	  var currVal = txtDate;
	  if(currVal == '')
		return false;
	  
	  //Declare Regex  
	  var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/; 
	  var dtArray = currVal.match(rxDatePattern); // is format OK?
	
	  if (dtArray == null)
		 return false;
	 
	  //Checks for mm/dd/yyyy format.
	  dtMonth = dtArray[7];
	  dtDay= dtArray[5];
	  dtYear = dtArray[1];
	
	  if (dtMonth < 1 || dtMonth > 12)
		  return false;
	  else if (dtDay < 1 || dtDay> 31)
		  return false;
	  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
		  return false;
	  else if (dtMonth == 2)
	  {
		 var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
		 if (dtDay> 29 || (dtDay ==29 && !isleap))
			  return false;
	  }
	  return true;
	},
	filterChange: function(obj) {
		//alert('filterChange'+getRootURL('url'));
		var subjectID;
		var staffID;
		var url;
		if($('#subject_id').val() == ''){
			subjectID = 0;	
		}
		else{
			subjectID = $('#subject_id').val();
		}
		
		if($('#staff_id').val() == ''){
			staffID = 0;	
		}
		else{
			staffID = $('#staff_id').val();
		}
		
		if(subjectID == 0 && staffID == 0){
			url = getRootURL() + $(obj).parents('form').attr('link') + '/' +$('input[id="datepickerStart"]').val();
		}
		else{
			url = getRootURL() + $(obj).parents('form').attr('link') + '/' +$('input[id="datepickerStart"]').val() + '/' + subjectID + '/' + staffID;
		}
                window.location.href = url;
	},
}