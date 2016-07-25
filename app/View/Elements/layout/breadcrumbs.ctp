<ol class="breadcrumb">
	<?php
	if(!empty($_breadcrumbs)) {
		foreach($_breadcrumbs as $b) {
			$title = $b['title'];
			if($b['selected']) {
				echo '<li class="active">' . sprintf('<span>%s</span>', $title) . '</li>';
			} else {
				echo '<li>' . $this->Html->link($title, $b['link']['url']) . '</li>';
			}
		}
	}
	?>
</ol>
