<div class="table-responsive">
			<div class="form-group option-filter" style="margin-left: 0px;">
				<?php 
					foreach($attendanceType as $item){ 
						echo $item['StudentAttendanceType']['short_form']. " = ". $item['StudentAttendanceType']['name']."; ";
					}
				?>
			</div>
            <table class="table table-striped table-hover table-highlight table-bordered">
               <thead>
                    <tr class="multiple-line">
                        <th colspan="2" rowspan="2"><?php echo __('Date'); ?></th>
                        <?php for($i = 0; $i < $attendanceSession; $i++ ){ ?>
                        <th colspan="<?php echo ($isEdit)? '3':'2';?>">
                            <?php 
                                $num = ($i + 1);
                                switch($num)
                                { 
                                    case 1: $num.="st"; break;
                                    case 2: $num.="nd"; break;
                                    case 3: $num.="rd"; break;
                                    default: $num.="th"; break;
                                }
                                echo __($num. " session"); 
                            ?>
                        </th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php for($i = 0; $i < $attendanceSession; $i++ ){ ?>
                        <td><?php echo __('Attendance'); ?></td>
                        <td><?php echo __('Remark'); ?></td>
                        <?php if($isEdit){?>
                        <td></td>
                        <?php } //edit ?>
                        
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    foreach($attendancesList as $attendanceData){
                        $content = '<td>'.date('l',strtotime($attendanceData['attendance_date'])).'</td>';
                        $content .= '<td>'.date('j M',strtotime($attendanceData['attendance_date'])).'</td>';
                        $attendanceInfo = $attendanceData['session'];
                        //pr($attendanceData);
                        $num = 0;
                        $update = 1;//1 = New Entry, 2 = Update
                        for($i = 0; $i < $attendanceSession; $i++ ){
                            if(!empty($attendanceInfo[$num]) && $attendanceInfo[$num]['session'] == $i+1){
                                $content .= '<td>'.$attendanceInfo[$num]['short_form'].'</td>';
                                $content .= '<td>'.$attendanceInfo[$num]['remarks'].'</td>';
                                if($isEdit){
                                    $update = 2;
                                    $link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=> $this->params['controller'], 'action'=> 'attendance_edit',$update,$attendanceInfo[$num]['id']), array('target' => '_self','escape' => false));
                                    $content .= '<td>'.$link.'</td>';
                                }
                                
                                $num++;
                            }
                            else{
                                $content .= '<td>-</td>';
                                $content .= '<td></td>';
                                if($isEdit){
                                    $link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=> $this->params['controller'], 'action'=> 'attendance_edit',$update,($i+1),$attendanceData['attendance_date']), array('target' => '_self','escape' => false));
                                    $content .= '<td>'.$link.'</td>';
                                }
                            }
                        }
                        echo "<tr>".$content."</tr>";
                    }
                ?>
               </tbody>
            </table>
			<?php echo $this->element('layout/alert'); ?>
        </div> <!-- here -->