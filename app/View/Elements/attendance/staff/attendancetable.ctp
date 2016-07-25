
                <!-- start -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered table-highlight table-clickable">
                    <thead>
                        <tr class="multiple-line">
                            <th rowspan="2"><?php echo $this->Label->get('SecurityUser.openemisid');  ?></th>
                            <th rowspan="2"><?php echo $this->Label->get('SecurityUser.full_name'); ?></th>
                            <?php
                                for($i = 0; $i < $dateDiff; $i ++){
                                    echo '<th>'.date('D '."<b\\r/>". 'j/n', strtotime($startDate." +".$i." day"))."</th>";
                                }
                            ?>
                        </tr>
                        <tr>
                            
                            <?php
                                for($i = 0; $i < $dateDiff; $i ++){
                                    //for($d = 0; $d < $numOfSegment; $d ++){
                                        //$attendanceSession = $d+1;
                                        $tableDate = date( 'Y-m-d', strtotime($startDate." +".$i." day"));
                                       	if (isset($_update) && $_update) {
                                       		$link = $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=> $this->params['controller'], 'action'=> $model.'/staff_edit', $tableDate), array('target' => '_self','escape' => false));
                                        	echo '<th>'.$link.'</th>';
                                       	}
                                    //}
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php 
                   
                            foreach($data as $staff){ 
								$staffAttendanceData = array();
								if(!empty($attendanceData)){
									if(isset($attendanceData[$staff['Staff']['id']]['StaffAttendanceDay'])){
										$staffAttendanceData = $attendanceData[$staff['Staff']['id']]['StaffAttendanceDay'];
						?>
                       
                        <?php
									}
								}
								
                        ?>
                        <tr>
                            <td><?php echo $this->Html->link($staff['SecurityUser']['openemisid'], '/Staff/view/'.$staff['Staff']['id'], array('target' => '_blank'))?></td>
                            <td><?php echo $staff['SecurityUser']['full_name'];?></td>
                        <?php
                                for($i = 0; $i < $dateDiff; $i ++){
                                    $curDate = date('Y-m-d', strtotime($startDate." +".$i." day"));
									$displayData = "-";
									if(!empty($staffAttendanceData)){
										if($staffAttendanceData['attendance_date'] == $curDate ){
											$displayData .= $staffAttendanceData['attendance_type_name'].(!empty($staffAttendanceData['remarks'])?'<br /><span class="less-imp">'.$staffAttendanceData['remarks'].'</span>':"");
										}
									}
									 echo "<td>".$displayData."</td>";
								}
								
						?>
                        </tr>
                        <?php } ?>
                    </tbody>
                	</table>
                </div>
                <!-- start -->
			