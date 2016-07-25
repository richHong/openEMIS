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
	/*if ($(".start_time").length > 0) {
		$(".start_time").datepicker({ dateFormat: "yy-mm-dd" });
	}
	if ($(".end_time").length > 0) {
		$(".end_time").datepicker({ dateFormat: "yy-mm-dd" });
	}*/
	
	//to increase the height of each cell hotzone
	$('.hotzone').each(function(i, obj) {
		//alert();
		var id = $(this).parent().parent().attr('id');
		$(this).height($('#'+id).parent().height());
	});
});
var timetable = {
	deselect_all_cells: function (){
		$('[id^=timetable_event_]').remove();
		//$('[id^=timetable_event_]').hide();
		
		$('[id^=rc_]').removeClass('selected');
		
		// $("#"+cell_id).removeClass('selected');
		// $("#"+popup_id).remove();
	}, 

	cell_click : function (cell_id, timetable_id, editable){
		var cell = $("#"+cell_id);
		//alert('clicked');
		if($.trim($("#"+cell_id+ " .container .entry-wrapper").html()) == ''){
			timetable.deselect_all_cells();
			//alert(getRootURL() + $('.timetable-container').attr('url'));
			cell.addClass('selected');
			var arrCellInfo = cell.attr('id').split('_');
			var row = arrCellInfo[1];
			var column = arrCellInfo[2];
			
			var popup_id = 'timetable_event_'+row+'_'+column;
			
			if($('#'+popup_id).length == 0){
			
				var popupTop = cell.position().top + 45;
				var popupLeft = cell.position().left - 1;
				//alert(cell.offset().top);
				var date = {
					fullday : $('#col_'+column).attr('full-day'),
					day : $('#col_'+column).attr('day'),
					start_time : $('#row_'+row+ "_start_time").val(),
					end_time : $('#row_'+row+ "_end_time").val(),
					day_of_week : $('#col_'+column).attr('dayOfWeek')
				};
				
				//alert($('.timetable-container').attr('url'));
				$.ajax({
					type: "POST",
					url: getRootURL() + $('.timetable-container').attr('url'),
					data: ({date:date, cell_id:cell.attr('id'), popup_id:popup_id, editable:editable, timetable_id:timetable_id}),
					success: function (data){
						//cell.parent().append(data);
						//alert(data);
						$('.timetable-container').append(data);
						$('#timetable_event_'+row+'_'+column).css('top', popupTop).css('left',popupLeft);
					}
				});
			}
		}
		
	},
	entry_click :function (self, editable){
		var cell = $(self).parent().parent().parent();
		timetable.deselect_all_cells();
		cell.addClass('selected');
		
		var arrEntryInfo = $(self).attr('id').split('_');
		var arrCellInfo = cell.attr('id').split('_');
		var row = arrCellInfo[1];
		var column = arrCellInfo[2];
		
		var popup_id = 'timetable_event_'+row+'_'+column;
		
		if($('#'+popup_id).length > 0){
			$('#'+popup_id).remove();
		}
		
		/*var popupTop = $(self).offset().top + $(self).outerHeight();
		var popupLeft = cell.offset().left - 1;
			*/
		var popupTop = $(self).parent().parent().parent().position().top + 45;
		var popupLeft = $(self).parent().parent().parent().position().left - 1;
		
		var date = {
			fullday : $('#col_'+column).attr('full-day'),
			day : $('#col_'+column).attr('day'),
			start_time : $('#row_'+row+ "_start_time").val(),
			end_time : $('#row_'+row+ "_end_time").val(),
			day_of_week : $('#col_'+column).attr('dayOfWeek')
		};

		$.ajax({
			type: "POST",
			url: getRootURL() +$('.timetable-container').attr('url'),
			data: ({date:date, cell_id:cell.attr('id'), popup_id:popup_id, entry_id:arrEntryInfo[1], editable:editable}),
			success: function (data){
				$('.timetable-container').append(data);
				$('#timetable_event_'+row+'_'+column).css('top', popupTop).css('left',popupLeft);
			}
		});
	},
	save_event : function (popup_id,cell_id, editable){
		var urla  = $('#'+popup_id+" #TimetablesTimetableForm").attr('action');
		var data = $('#'+popup_id+" #TimetablesTimetableForm").serialize();

		$.ajax({
            type: "POST",
            url: urla ,
			dataType: 'JSON',
           	data: data,
            success: function (data){
				
				if(data['success']){
					
					var entry_id = 'entry_'+$('#'+popup_id+' #TimetableEntryId').val();
					
					if($("#"+entry_id).length == 0){
						$('#'+cell_id+" .container .entry-wrapper").append(data['data']);	
					}
					else{
						$("#"+entry_id).replaceWith(data['data']);
					}
					
					timetable.close_event_popup(popup_id,cell_id);
					timetable.show_action_panel(cell_id);
				}
				else{
					
					var alertOpt = {
						parent: '#'+popup_id,
						title: data['errorMessage'],
						text: data['errorMessage'],
						type: alertType.error, // alertType.info or alertType.warn or alertType.ok
					////	position: 'top',
					//	css: {}, // positioning of your alert, or other css property like width, eg. {top: '-10px', left: '-20px'}
						autoFadeOut: true,
					}
					
					$.alert(alertOpt);
				}
            }
        });
	},
	delete_event: function (popup_id,cell_id, self){
		var urla  = getRootURL() + $(self).attr('url');
		//alert(urla);
		$.ajax({
            type: "POST",
            url: urla ,
			data: {timetable_id:$('#TimetableEntryTimetableId').val()},
            success: function (data){
				//alert(data);
				if(data != ""){
					var entry_id = 'entry_'+$('#'+popup_id+' #TimetableEntryId').val();
					
					if($("#"+entry_id).length > 0){
						
						$("#"+entry_id).remove();
					}
					
					timetable.close_event_popup(popup_id,cell_id);
					//timetable.show_action_panel(cell_id);
				}
            }
        });
	},
	save_lesson : function (popup_id,cell_id){
		var urla  =  $('#'+popup_id+" #ClassesSaveLessonForm").attr('action');//'/openemis_school/timetables/saveEvent';//
		
		//alert(urla);
		$.ajax({
            type: "POST",
            url: urla ,
           	data: $('#'+popup_id+" #ClassesSaveLessonForm").serialize(),

            success: function (data){
			//	alert(data);
				if(data != ""){
					var entry_id = 'entry_'+$('#'+popup_id+' #TimetableEntryId').val();
					
					if($("#"+entry_id).length == 0){
						$('#'+cell_id+" .container .entry-wrapper").replaceWith(data);	
					}
					else{
						$("#"+entry_id).replaceWith(data);
					}
					
					timetable.close_event_popup(popup_id,cell_id);
					timetable.show_action_panel(cell_id);
				}
            }
        });
	},
	close_event_popup : function (popup_id, cell_id){
		$("#"+cell_id).removeClass('selected');
		$("#"+popup_id).remove();
	},
	show_action_panel : function (cell_id){
		$("#"+cell_id+" .action-wrapper").removeClass('hide');
	},
	/*setupPopupView : function (cell_id){
		
	}*/
	search : function (obj){
		window.location.href = getRootURL() + $(obj).attr('url')+'index/'+$(obj).val();
	}
}