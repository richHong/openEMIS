<?php
echo $this->Html->css('table', 'stylesheet', array('inline' => false));
?>

<?php


 // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers

     $prevMonth = $month-1;
     $prevYear = $year;
     if($prevMonth == 0){
          $prevMonth = 12;
          $prevYear = $year - 1;
     }

     $nextMonth = $month+1;
     $nextYear = $year;
     if($nextMonth == 13){
          $nextMonth = 1;
          $nextYear = $year + 1;
     }


     $calendar = "<table class='calendar'>";
     $calendar .= "<caption>$monthName $year</caption>";
     $calendar .= "<tr>";
     $calendar .= "<td align='left'>". $this->Html->link(__('<'), array('controller' => 'Events', 'action' => 'calendar', $prevMonth, $prevYear)) . "</td>";
     $calendar .= "<td colspan='5'></td>";
     $calendar .= "<td align='right'>". $this->Html->link(__('>'), array('controller' => 'Events', 'action' => 'calendar', $nextMonth, $nextYear)) . "</td>";
     $calendar .= "</tr>";

     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th width='150px'>$day</th>";
     } 

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) { 
          $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>"; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";

          $details = "";
         

          foreach($events as $event){
               if($date >= date('Y-m-d', strtotime($event['Event']['start_date'])) && $date<= date('Y-m-d', strtotime($event['Event']['end_date']))){
                    $spanColor = 'yellow';
                    if($event['Event']['type']=='2'){
                         $spanColor = '#2E9AFE';
                    }
                    $details .= '<div style="background-color:' . $spanColor . ';"> - ' .  $this->Html->link(__($event['Event']['name']), array('controller' => 'Events', 'action' => 'view', $event['Event']['id']));
                    $details .= '<br/>' . '(' . date('h:ia', strtotime($event['Event']['start_date'])) .  ' - ' . date('h:ia', strtotime($event['Event']['end_date'])) . ')</div>';
               }
          }
    

          if ($dayOfWeek == 7) {
               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          $color = "#FFFFFF";
          if($date==date('Y-m-d')){
               $color = "#cdcdcd";
          }
          
        
          $calendar .= "<td class='day' height='100px' valign='top' rel='$date' bgcolor='" . $color . "'>$currentDay.$details</td>";

          // Increment counters
 
          $currentDay++;
          $dayOfWeek++;

     }
     
     

     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

     }
     
     $calendar .= "</tr>";

     $calendar .= "</table>";

     echo $calendar;
  ?>

  <div style="background-color:yellow;"><?php echo $this->Label->get('event.schoolEvent');?></div>
  <div style="background-color:#2E9AFE;"><?php echo $this->Label->get('event.classEvent');?></div>