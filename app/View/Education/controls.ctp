<div class="row">
	<div class="col-md-3">
		<?php
		echo $this->Form->input('page', array(
			'label' => false,
			'options' => $options,
			'default' => $selectedPage,
			'class' => 'form-control',
			'url' => $this->params['controller'],
			'onchange' => 'Form.change(this)',
			'autocomplete' => 'off'
		));
		?>
	</div>
	
	<?php if (isset($secondaryOptions)) : ?>
	<div class="col-md-3">
		<?php
		echo $this->Form->input('secondary', array(
			'label' => false,
			'options' => $secondaryOptions,
			'default' => $selectedSecondary,
			'class' => 'form-control',
			'url' => $this->params['controller'] . '/' . $model . '/' . $action,
			'onchange' => 'Form.change(this)',
			'autocomplete' => 'off'
		));
		?>
	</div>
	<?php endif ?>
</div>
