<div id='customFieldOptionValues'>

	<div class="form-group">
		<label class="col-md-2 control-label">
			<?php echo $this->Label->get('CustomField.customOption'); ?>
		</label>
		<div class="col-md-6">
			<table class="table table-striped table-hover table-bordered table-highlight" style="margin-bottom: 2em;">
				<tr>
					<td>
						<?php 
						$index = 0;
						if (isset($customFieldOption)) {
							foreach ($customFieldOption as $key => $value) {
								$options = array();
								if (array_key_exists('id', $data[$model])) {
									$options['customFieldId'] = $data[$model]['id'];
								}
								if (array_key_exists('id', $value)) {
									$options['id'] = $value['id'];
								}
								$options['key'] = $key;
								$options['value'] = $value;
								$options['index'] = $index;
								$options['model'] = $model;
								$options['customFieldOptionModel'] = $customFieldOptionModel;
								echo $this->FormUtility->getCustomFieldOptionFormElement($options);
								$index++;
							}
						}

						for ($i=0; $i < $additionalRows; $i++) { 
							$options = array();

							if (isset($data)) {
								if (array_key_exists('id', $data[$model])) {
									$options['customFieldId'] = $data[$model]['id'];
								}
							}
							
							$options['index'] = $index;
							$options['model'] = $model;
							$options['customFieldOptionModel'] = $customFieldOptionModel;
							echo $this->FormUtility->getCustomFieldOptionFormElement($options);
							$index++;
						}

						if ($action == 'edit') {
							echo $this->FormUtility->link('add', array('action' => $action,$id,++$additionalRows,'1'));	
						} else if ($action == 'add') {
							echo $this->FormUtility->link('add', array('action' => $action,++$additionalRows,'1'));
						}
						

						?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>