<?php
echo $this->Html->css('../js/plugins/datepicker/datepicker', array('inline' => false));
echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
echo $this->Html->script('event.datepicker', array('inline' => false));
echo $this->Html->script("plugins/datepicker/locales/$lang.js", array('inline' => false));

$this->extend('/Layouts/portlet');

$this->assign('contentHeader', $contentHeader);

$this->start('portletHeader');
	echo $this->Icon->get('calendar');
	echo $this->Label->get('event.title');
$this->end();

$this->start('portletBody');
?>

<div class="row">
	<div class="col-md-9">
		<h4 class="heading">
			<span><?php echo $this->Label->get('event.title'); ?></span>
		</h4>
		<?php if (isset($data) && !empty($data)) { ?>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered table-highlight">
				<thead>
					<tr>
						<th><?php echo $this->Label->get('general.title'); ?></th>
						<th><?php echo $this->Label->get('date.start_date'); ?></th>
						<th><?php echo $this->Label->get('date.end_date'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data as $obj) : ?>
					<tr>
						<td><?php echo $this->Html->link($obj[$model]['name'], array('action' => 'view', $obj[$model]['id'])); ?></td>
						<td><?php echo $obj[$model]['start_date'] . ' ' . $obj[$model]['start_time']; ?></td>
						<td><?php echo $obj[$model]['end_date'] . ' ' . $obj[$model]['end_time']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
	<div class="col-md-3">
		<button class="btn btn-primary" style="margin-bottom: 5px; width: 220px" onclick="today()"><?php echo $this->Label->get('date.today'); ?></button>
		<div id="event-calendar" data-date-format="yyyy-mm-dd" data-date="<?php echo $date; ?>" data-url="Events/getEventDates"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
	$('#event-calendar').datepicker({ language: '<?php echo $lang; ?>' })
    .on('changeDate', function(e) {
		var date = e.date.getDate();
		var mth = e.date.getMonth() + 1;
		var yr = e.date.getFullYear();
		var param = yr + "-" + mth + "-" + date;
		window.location.href = getRootURL() + 'Events/index/' + param;
    });

	DatePicker.initHighlight('#event-calendar');
	DatePicker.highlightDates('#event-calendar');
});

function today() {
	window.location.href = getRootURL() + 'Events/';
}
</script>

<?php $this->end(); ?>
