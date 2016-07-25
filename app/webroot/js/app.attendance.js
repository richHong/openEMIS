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

	//For Attendance Index
	if($('#AttendanceAttendaceType').length > 0 && $('#AttendanceAttendaceType').val() == 'day'){
		Attendance.autoComplete();
	}
	
	if($('#time').length > 0){
		$('#time').timepicker();
	}
	
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
					if(Attendance.isDate(pathArr[i])){
						//alert('is date');	
						insertDate = false;
						urlPath += 	finalDate+"/";
					}
					else{
						urlPath += 	pathArr[i]+"/";
					}
				}
			}
			//alert(curURL+ "\n" +rootURL+ "\n" + rootURL.toLowerCase().indexOf(curURL));
			//alert(curURL+ "\n" +rootURL+ "\n" + curURL.search(rootURL));
			/*if (curURL.toLowerCase().indexOf(rootURL) != -1){
				alert('Matched');
			}*/
			if(insertDate){
				urlPath += 	finalDate+"/";
			}
			window.location = urlPath;
		});
/*
		var checkin = $('#datepickerStart').datetimepicker({
			maskInput: true,   
			pickTime: false,
			language: 'en',
			format: 'yyyy-MM-dd',
				
		}).on('changeDate', function(ev) {
			var newDate = new Date(ev.date)
			
			newDate.setDate(newDate.getDate() );
			var month = newDate.getMonth()+1;
			month = (month < 10)? '0'+month:month;
			var year = newDate.getFullYear();
			var date= newDate.getDate();
			date = (date < 10)? '0'+date:date;
			
			var urlPath = getRootURL() + $('#datepickerForm').attr('url') ;
			
			var finalDate = year+'-'+month+'-'+date;
			window.location = urlPath +"/"+finalDate;
		});//.data('datetimepicker');*/
	}
});

/*
var ClassAttendance = {
	search: function(obj) {
		var day = $('#AttendanceDayAttendanceDateDay').val();
		var mth = $('#AttendanceDayAttendanceDateMonth').val();
		var yr = $('#AttendanceDayAttendanceDateYear').val();
		var date = yr + '-' + mth + '-' + day;
		window.location.href = getRootURL() + $(obj).attr('url') + date;
	},
	searchPeriod: function(obj) {
		var subjects = $('#subjects').val();
		var period = $('#period').val();
		window.location.href = getRootURL() + $(obj).attr('url')+ subjects +"/"+ period;
	},
	getLessonPriod : function (){
		if($("#period").length > 0){
			var url =  getRootURL()+$("#period").attr('url');
			$.ajax({
				type: "POST",
				url: url,
				data: ({edu_grade_sub_id:$('#subjects').val()}),
				success: function (data){
					$("#period").html(data);
				}
			});
		}
	}
}*/

var Attendance = {
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
	searchSubjectList: function(obj) {
		var subject_id;
		if($('#SubjectList').val() == ''){
			subjectID = 0;	
		} else {
			subjectID = $('#SubjectList').val();
		}
		window.location.href = getRootURL() + $(obj).closest( "form" ).attr('link') + '/index/' + $(obj).val() +'/'+$('#datepickerStart').find('input').val();
	},
	searchAttendanceType: function(obj,type) {
		var url = $(obj).closest( "form" ).attr('link') +'/';
		if(type == 'lesson'){
			var pathArr = url.split("/");
			url = pathArr[0]+'/'+pathArr[1]+'/index/';
			if($('#SubjectList').val()  != ''){
				url += $('#SubjectList').val() +'/';
			}
			
			if($('#StudentAttendanceType').val()  != ''){
				url += $('#StudentAttendanceType').val() +'/';
			}
		}
		else{
			if( $(obj).val() != ''){
				url += $(obj).val() +'/';
			}
		}
		
		//alert($('#datepickerStart').find('input').val());
		url = getRootURL()+ url;
		window.location = url+$('#datepickerStart').find('input').val();
	},
	autoComplete : function (){
		var element = '#AttendanceName';
		var url = getRootURL() + $(element).attr('url');//+"/"+$(element).val();

		Form.attachAutoComplete(element, url, null);
	},
	getTodayDate : function(obj){
		window.location = getRootURL() + $(obj).closest( "form" ).attr('link');
	},
	getClassList : function(obj, append_div_id){
		var maskId;
		var parent = $('#AttendanceIndexForm');
		var url =  getRootURL()+$(obj).attr('url')+"/"+$(obj).val();
		
		var success = function(data, status) {
			//alert(data.toSource());
			var callback = function() {
				$('#'+append_div_id).html(data).attr('disabled', false);
			};
			$.unmask({id: maskId, callback: callback});
		}
		
		$.ajax({
			type: "POST",
			url: url,
			beforeSend: function (jqXHR) { maskId = $.mask({parent: parent}); },
			success: success
		});
	},
	markAllAs : function (id){
		$(".AttendanceTypeDM").val(id);
	},
	
}