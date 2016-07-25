<?php echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false)); ?>
<?php echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false)); ?>
<?php echo $this->Html->css('form', array('inline' => false)); ?>
<?php echo $this->Html->script('app.table', array('inline' => false)); ?>
<?php echo $this->Html->script('app.attendance', array('inline' => false)); ?>
<?php //$obj = $data['SecurityUser'];  ?>

<?php echo $this->element('classes/header'); ?>

<div class="tab-content">
    <h4 class="heading">
        <span><?php echo $this->Label->get('attendance.attendance'); ?></span>
    </h4>
    <?php echo $this->element('layout/alert'); ?>
    <?php
    $classLessonDM = $this->Form->input('SubjectList', array(
        'options' => $subjectsOptions,
        //'selected' => $selectedGradeSubject,
        'div' => 'col-md-2',
        'class' => "form-control",
        'label' => false,
        //'empty' => 'All',
        // 'empty' => 'Subjects',
        'onchange' => 'Attendance.searchSubjectList(this)'
            )
    );
    $urlPath = $this->params['controller'] . "/" . $this->params['action'] . '/' . $classId . '/' . $selectedSubject;

    echo $this->element('attendance/datepicker', array('urlPath' => $urlPath, 'formElements' => array($classLessonDM)));
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <div class="form-group option-filter">
                <?php
                foreach ($attendanceType as $item) {
                    echo $item['StudentAttendanceType']['short_form'] . " = " . $item['StudentAttendanceType']['name'] . "; ";
                }
                ?>
            </div>
            <thead>

                <tr class="multiple-line">
                    <th rowspan="3"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
                    <th rowspan="3"><?php echo $this->Label->get('first_name'); ?></th>
                    <th rowspan="3"><?php echo $this->Label->get('last_name'); ?></th>
                    <?php
                    //for($i = 0; $i < count($tableHeaderData); $i ++){
                    //pr($tableHeaderData);
                    foreach ($tableHeaderData as $thKey => $tableHeaderItem) {
                        echo '<th colspan="' . $tableHeaderItem . '">' . date('D ' . "<b\\r/>" . 'j/n', strtotime($thKey)) . "</th>";
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($period as $periodInfo) {
                        echo "<td><span class='less-imp'>" . $periodInfo['time'] . "</span></td>";
                    }
                    ?>
                </tr>  
                <tr>

                    <?php
                    foreach ($period as $periodInfo) {
                        $lessonTime = explode("-", $periodInfo['time']);

                        $tableDate = strtotime($periodInfo['date'] . " " . trim($lessonTime[0]));
                        $link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => $this->params['controller'], 'action' => 'attendance_class_edit', $classId, $selectedSubject, $tableDate), array('target' => '_self', 'escape' => false));
                        echo '<td>' . $link . '</td>';
                    }
                    ?>
                </tr> 

            </thead>
            <tbody>

                <?php
                foreach ($data as $student) {
                    //	pr($attendanceData);
                    $studentAttendanceData = !empty($attendanceData) ? $attendanceData['StudentAttendanceLesson'][$student['Student']['id']] : array();
                    //	pr($studentAttendanceData);
                    ?>
                    <tr>
                        <td><?php echo $this->Html->link($student['SecurityUser']['openemisid'], '/Students/view/' . $student['Student']['id'], array('target' => '_blank')); ?></td>
                        <td><?php echo $student['SecurityUser']['first_name']?></td>
                        <td><?php echo $student['SecurityUser']['last_name'] ?></td>

                            <?php
                            foreach ($period as $periodInfo) {
                                $lessonTime = explode("-", $periodInfo['time']);
                                $curDate = date('Y-m-d H:i:s', strtotime($periodInfo['date'] . " " . trim($lessonTime[0])));

                                $displayData = "-";
                                foreach ($studentAttendanceData as $selectedAttendance) {
                                    if ($selectedAttendance['datetime'] == $curDate && $selectedAttendance['student_id'] == $student['Student']['id']) {
                                        $displayData = $selectedAttendance['short_form'] . (!empty($selectedAttendance['remarks']) ? '<br /><span class="less-imp">' . $selectedAttendance['remarks'] . '</span>' : "");
                                        break;
                                    }
                                }

                                echo "<td>" . $displayData . "</td>";
                            }
                            /* for($i = 0; $i < $dateDiff; $i ++){
                              $curDate = date('Y-m-d', strtotime($startDate." +".$i." day"));

                              for($s = 0; $s < $numOfSegment; $s ++){
                              $displayData = "-";
                              foreach($studentAttendanceData as $selectedAttendance){
                              if($selectedAttendance['attendance_date'] == $curDate && $selectedAttendance['session'] == ($s+1)){
                              $displayData = $selectedAttendance['short_form'].(!empty($selectedAttendance['remarks'])?'<br /><span class="less-imp">'.$selectedAttendance['remarks'].'</span>':"");
                              break;
                              }
                              }

                              echo "<td>".$displayData."</td>";
                              }
                              } */
                            ?>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>

    </div>
</div>
<?php echo $this->element('classes/footer'); ?>