<?php 
echo $this->Html->script('app.table', array('inline' => false));
echo $this->element('admin/header');
?>

<div class="tab-content">
	<h4 class="heading">
		<span><?php echo $contentHeader; ?></span>
	</h4>
	
	<?php
	echo $this->Form->create($model, array('url' => array('controller' => $this->params['controller'], 'action' => $page)));
	echo $this->Form->hidden('sortBy', array('name' => 'sortBy', 'value' => key($sortBy)));
	echo $this->Form->hidden('sortOrder', array('name' => 'sortOrder', 'value' => current($sortBy)));
	echo $this->Form->hidden('pageNo', array('name' => 'pageNo', 'value' => $pageNo));
	?>
	<div class="form-group option-filter">
		<div class="col-md-3">
			<div class="input-group">
				<?php
				echo $this->Form->input('search', array(
					'label' => false,
					'div' => false,
					'class' => 'form-control',
					'value' => $searchValue
				));
				?>
				<div class="input-group-btn">
					<button class="btn btn-tertiary" tabindex="-1" type="submit" name="clear" value="1">&times;</button>
					<button class="btn btn-tertiary" tabindex="-1" type="submit"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
	
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight table-clickable">
			<thead>
				<tr>
					<?php
					echo $this->FormUtility->getSort($sortBy, 'SecurityUser.username');
					echo $this->FormUtility->getSort($sortBy, 'SecurityUser.first_name');
					echo $this->FormUtility->getSort($sortBy, 'SecurityUser.last_name');
					echo '<th>' . $this->Label->get('general.status') . '</th>';
					echo $this->FormUtility->getSort($sortBy, 'SecurityUser.last_login');
					?>
				</tr>
			</thead>
			<tbody action="<?php echo $this->params['controller'] . '/user_view/'; ?>">
				<?php foreach($data as $obj) : ?>
				<tr row-id="<?php echo $obj[$model]['id']; ?>">
					<td><?php echo $this->Utility->highlight($searchValue, $obj[$model]['username']); ?></td>
					<td><?php echo $this->Utility->highlight($searchValue, $obj[$model]['first_name']); ?></td>
					<td><?php echo $this->Utility->highlight($searchValue, $obj[$model]['last_name']); ?></td>
					<td><?php echo $this->FormUtility->getStatus($obj[$model]['status']); ?></td>
					<td><?php echo $obj[$model]['last_login']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->element('layout/pagination'); ?>
	</div>
</div>

<?php echo $this->element('admin/footer'); ?>
