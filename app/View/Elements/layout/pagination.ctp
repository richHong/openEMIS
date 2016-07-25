<?php
$page = $this->Paginator->counter('{:page}');
$pages = $this->Paginator->counter('{:pages}');
$total = $this->Paginator->counter('{:count}');

$pageOptions = array(
	'tag' => 'li', 
	'disabledTag' => 'a', 
	'class' => 'disabled',
	'escape' => false
);

$pageNumberOptions = array(
	'modulus' => 4,
	'first' => 2,
	'last' => 2,
	'tag' => 'li', 
	'currentTag' => 'a',
	'currentClass' => 'active',
	'separator' => '',
	'ellipsis' => false
);
?>

<div class="row dt-rb">
	<div class="col-sm-6">
		<?php if($pages > 1) : ?>
		<div class="dataTables_paginate paging_bootstrap" style="text-align: left;">
			<ul class="pagination">
				<?php
				echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), null, $pageOptions);
				echo $this->Paginator->numbers($pageNumberOptions);
				echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), null, $pageOptions);
				?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
	<div class="col-sm-6">
		<div class="dataTables_info" style="text-align: right;">
			<?php echo __('Showing') . ' ' . $page . ' - ' . $pages . ' ' . 'of' . ' ' . $total; ?>
		</div>
	</div>
</div>
