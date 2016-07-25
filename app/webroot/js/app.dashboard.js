/*
OpenEMIS School
Open School Management InDashboardation System

This program is free software: you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or any later version. This program is distributed in the hope 
that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should 
have received a copy of the GNU General Public License along with this program.  If not, see 
<http://www.gnu.org/licenses/>.  For more inDashboardation please email contact@openemis.org.
*/

$(document).ready(function() {	
	Dashboard.init();
});

var Dashboard = {
	init: function() {
	},

	showChartEnrollment: function(attendanceDivName, chartData, options) {
		var title = options['title'];
		var year = options['year'];
		var xName = options['xName'];

		// convert str to number
		for (var chartDataIndex in chartData) {
			for (var k in chartData[chartDataIndex]['data']) {
				chartData[chartDataIndex]['data'][k] = Number(chartData[chartDataIndex]['data'][k]);
			}
		}; 

		if (year != '') {
			if (attendanceDivName.length != 0) {
				$('#enrollmentBarChart').highcharts({
					chart: {
						type: 'column'
					},
					title: {
						text: null
					},
					xAxis: {
						// the year
						categories: attendanceDivName
					},
					yAxis: {
						min: 0,
						allowDecimals: false,
						title: {
							text: xName
						}
					},
					plotOptions: {
						series: {
							// stacking: 'normal',
							dataLabels: {
								enabled: true,
								color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
								style: {
									textShadow: '0 0 3px black, 0 0 3px black'
								}
							}
						}
					},
					series: chartData,
					credits: {
						enabled: false
					}
				});
			}
		}
	},

	showChartAttendance: function(attendanceDivName, chartData, options) {
		var dataObj = new Object();
		var currAttendanceDate;
		var title = options['title'];
		var yName = options['yName'];
		var xName = options['xName'];
		var model = options['model'];

		for (i = 0; i < chartData.length; ++i) {
			currAttendanceDate = chartData[i][model]['attendance_date'];
			currAttendanceTypeId = chartData[i]['StudentAttendanceType']['id'];
			currAttendanceTypeName = chartData[i]['StudentAttendanceType']['name'];

			if (jQuery.isEmptyObject(dataObj[currAttendanceTypeName])) {
				dataObj[currAttendanceTypeName] = new Array();
			}
			dataObj[currAttendanceTypeName].push(new Array(new Date(currAttendanceDate).getDate(), Number(chartData[i][0]['count'])));
		}

		var daysInMonth = this.getDaysInMonth();
		var currDay;
		var currMonth;
		var daysInMonthAxis = [];
		daysInMonth.forEach(function(currDate) {
			currDay = currDate.getDate();
			currMonth = currDate.getMonth();
			daysInMonthAxis.push(currDay);
		});
		// form the series
		var currSeries = [];
		var tMonth = [];
		for (var key in dataObj) {
			currSeries.push(
				{name:key, data:dataObj[key]});
		}

		var currSeries2 = [];
		var tDate;
		for (var key in currSeries) {
			currSeries[key].pdata = [];
			for (var key2 in daysInMonth) {
				currSeries[key].pdata.push(this.dataByDate(Number(key2)+1, currSeries[key].data))
			}
			currSeries[key].data = currSeries[key].pdata
		}
		// console.log(daysInMonthAxis);
		// console.log(currSeries);
		if (chartData.length != 0) {
			$('#attendanceLineChart').highcharts({
				chart: {
					type: 'area'
				},
				title: {
					text: null
				},
				xAxis: {
					allowDecimals: false,
					categories: daysInMonthAxis,
					title: {
						text: xName
					}
				},
				yAxis: {
					allowDecimals: false,
					title: {
						text: yName
					}
				},
				series: currSeries,
				credits: {
					enabled:false
				}
			});
		}
	},

	dataByDate: function(needle, haystack) {
		var found = null;
		for (var key in haystack) {
			if (haystack[key][0] == needle) {
				found = haystack[key][1];
				break;
			}
		}
		return found;
	},

	getDaysInMonth: function() {
		var d = new Date();
		var month = d.getMonth();
		var year = d.getFullYear();

		var date = new Date(year, month, 1);
		var days = [];
		while (date.getMonth() === month) {
			days.push(new Date(date));
			date.setDate(date.getDate() + 1);
		}
		return days;
	}


};