<?php
if ($this->Session->check('Security.accessViewType')) {
	$accessViewType = $this->Session->read('Security.accessViewType');
} else {
	// maybe want to kill the operation as the person is an unidentified user
}

switch ($accessViewType) {
	case 1:
		$navigations = array(

			// $this->Label->get('user.myProfile') => array(
			// 	'controller' => 'Administrator', 'action' => 'view/'.$_userId, 'icon' => 'icon icon-profile', 'highlight' => "MyProfile"
			// ),

			$this->Label->get('event.title') => array(
				'controller' => 'Events', 'action' => 'index', 'icon' => 'icon icon-events',
				'sub' => array(
					$this->Label->get('event.list') => array('controller' => 'Events', 'action' => 'index', 'icon' => 'fa fa-list'),
					$this->Label->get('event.add') => array('controller' => 'Events', 'action' => 'add', 'icon' => 'fa fa-plus-square')
				)
			),

			$this->Label->get('SClass.title') => array(
				'controller' => 'Classes', 'action' => 'index', 'icon' => 'icon icon-classes',
				'sub' => array(
					$this->Label->get('SClass.list') => array('controller' => 'Classes', 'action' => 'index', 'icon' => 'fa fa-list'),
					$this->Label->get('SClass.add') => array('controller' => 'Classes', 'action' => 'add', 'icon' => 'fa fa-plus-square')
				)
			),

			$this->Label->get('student.title') => array(
				'controller' => 'Students', 'action' => 'index', 'icon' => 'icon icon-students',
				'sub' => array(
					$this->Label->get('student.list') => array('controller' => 'Students', 'action' => 'listing', 'icon' => 'fa fa-list'),
					$this->Label->get('general.search') => array('controller' => 'Students', 'action' => 'index', 'icon' => 'fa fa-search'),
					$this->Label->get('student.add') => array('controller' => 'Students', 'action' => 'add', 'icon' => 'fa fa-plus-square')
				)
			),

			$this->Label->get('staff.title') => array(
				'selected' => array('Staff', 'Attendance'),
				'controller' => 'Staff', 'action' => 'index', 'icon' => 'icon icon-staff',
				'sub' => array(
					$this->Label->get('staff.list') => array('controller' => 'Staff', 'action' => 'listing', 'icon' => 'fa fa-list'),
					$this->Label->get('general.search') => array('controller' => 'Staff', 'action' => 'index', 'icon' => 'fa fa-search'),
					$this->Label->get('staff.add') => array('controller' => 'Staff', 'action' => 'add', 'icon' => 'fa fa-plus-square'),
					$this->Label->get('Attendance.title') => array('controller' => 'Staff', 'action' => 'StaffAttendanceDay/staff_list', 'icon' => 'fa fa-pencil-square')
				)
			),

			$this->Label->get('guardian.title') => array(
				'controller' => 'Guardians', 'action' => 'index', 'icon' => 'icon icon-guardians',
				'sub' => array(
					$this->Label->get('guardian.list') => array('controller' => 'Guardians', 'action' => 'listing', 'icon' => 'fa fa-list'),
					$this->Label->get('general.search') => array('controller' => 'Guardians', 'action' => 'index', 'icon' => 'fa fa-search'),
					$this->Label->get('guardian.add') => array('controller' => 'Guardians', 'action' => 'add', 'icon' => 'fa fa-plus-square')
				)
			),

			$this->Label->get('Administrator.name') => array(
				'controller' => 'Administrator', 'action' => 'index', 'icon' => 'icon icon-administrators', 'highlight' => "NotMyProfile",
				'sub' => array(
					$this->Label->get('Administrator.list') => array('controller' => 'Administrator', 'action' => 'listing', 'icon' => 'fa fa-list'),
					$this->Label->get('Administrator.addAdmin') => array('controller' => 'Administrator', 'action' => 'add', 'icon' => 'fa fa-plus-square')
				)
			),

			$this->Label->get('admin.title') => array('controller' => 'Admin', 'action' => 'index', 'icon' => 'fa fa-cogs', 'otherControllers' => array('Education', 'Finance', 'Assessment', 'FieldOptions', 'Translations', 'CustomField'))
		);
		break;
	case 2:
		$navigations = array(
			// Admin
			//'Dashboard' => array('controller' => 'Dashboard', 'action' => 'index', 'icon' => 'fa fa-dashboard'),
			// $this->Label->get('user.myProfile') => array(
			// 	'selected' => array('Staff'),
			// 	'controller' => 'Staff', 'action' => 'view', 'icon' => 'icon icon-profile'
			// ),

			$this->Label->get('event.title') => array(
				'controller' => 'Events', 'action' => 'index', 'icon' => 'icon icon-events'
			),

			$this->Label->get('SClass.title') => array(
				'controller' => 'Classes', 'action' => 'index', 'icon' => 'icon icon-classes'
			),

			$this->Label->get('student.title') => array(
				'controller' => 'Students', 'action' => 'search', 'icon' => 'icon icon-students'
			)

		);
		break;
	case 3:
		// Students
		$navigations = array(
			// $this->Label->get('user.myProfile') => array(
			// 	'controller' => 'Students', 'action' => 'view', 'icon' => 'icon icon-profile',
			// ),
			$this->Label->get('event.title') => array(
				'controller' => 'Events', 'action' => 'index', 'icon' => 'icon icon-events'
			)			
		);
		break;

	case 4:
		// Guardians
		$navigations = array(
			// $this->Label->get('user.myProfile') => array(
			// 	'controller' => 'Guardians', 'action' => 'view', 'icon' => 'icon icon-profile'
			// ),
			$this->Label->get('event.title') => array(
				'controller' => 'Events', 'action' => 'index', 'icon' => 'icon icon-events'
			),
			$this->Label->get('student.title') => array(
				'controller' => 'Students', 'action' => 'search', 'icon' => 'icon icon-students'
			)
		);
		break;

	default: 
		$navigations = array(); 
		break;
}

// $navigations = array(
// 	//'Dashboard' => array('controller' => 'Dashboard', 'action' => 'index', 'icon' => 'fa fa-dashboard'),
// 	$this->Label->get('event.title') => array(
// 		'controller' => 'Events', 'action' => 'index', 'icon' => 'icon icon-events',
// 		'sub' => array(
// 			$this->Label->get('event.list') => array('controller' => 'Events', 'action' => 'index', 'icon' => 'fa fa-list'),
// 			$this->Label->get('event.add') => array('controller' => 'Events', 'action' => 'add', 'icon' => 'fa fa-plus-square')
// 		)
// 	),

// 	$this->Label->get('SClass.title') => array(
// 		'controller' => 'Classes', 'action' => 'index', 'icon' => 'icon icon-classes',
// 		'sub' => array(
// 			$this->Label->get('SClass.list') => array('controller' => 'Classes', 'action' => 'index', 'icon' => 'fa fa-list'),
// 			$this->Label->get('SClass.add') => array('controller' => 'Classes', 'action' => 'add', 'icon' => 'fa fa-plus-square')
// 		)
// 	),

// 	$this->Label->get('student.title') => array(
// 		'controller' => 'Students', 'action' => 'index', 'icon' => 'icon icon-students',
// 		'sub' => array(
// 			$this->Label->get('general.search') => array('controller' => 'Students', 'action' => 'index', 'icon' => 'fa fa-search'),
// 			$this->Label->get('student.add') => array('controller' => 'Students', 'action' => 'add', 'icon' => 'fa fa-plus-square')
// 		)
// 	),

// 	$this->Label->get('guardian.title') => array(
// 		'controller' => 'Guardians', 'action' => 'index', 'icon' => 'icon icon-guardians',
// 		'sub' => array(
// 			$this->Label->get('general.search') => array('controller' => 'Guardians', 'action' => 'index', 'icon' => 'fa fa-search'),
// 			$this->Label->get('guardian.add') => array('controller' => 'Guardians', 'action' => 'add', 'icon' => 'fa fa-plus-square')
// 		)
// 	),

// 	$this->Label->get('staff.title') => array(
// 		'selected' => array('Staff', 'Attendance'),
// 		'controller' => 'Staff', 'action' => 'index', 'icon' => 'icon icon-staff',
// 		'sub' => array(
// 			$this->Label->get('general.search') => array('controller' => 'Staff', 'action' => 'index', 'icon' => 'fa fa-search'),
// 			$this->Label->get('staff.add') => array('controller' => 'Staff', 'action' => 'add', 'icon' => 'fa fa-plus-square'),
// 			$this->Label->get('Attendance.title') => array('controller' => 'Attendance', 'action' => 'StaffAttendanceDay/staff_list', 'icon' => 'fa fa-pencil-square')
// 		)
// 	),

// 	$this->Label->get('admin.title') => array('controller' => 'Admin', 'action' => 'index', 'icon' => 'fa fa-cogs')
// );
?>

<nav id="sidebar">
	<ul id="main-nav" class="open-active">
		<?php
		$controller = $this->params['controller'];
		$icon = '<i class="%s"></i>';
		foreach($navigations as $label => $item) {
			$class = $item['icon'];
			if(isset($item['sub'])) {
				$liClass = 'dropdown';
				if(isset($item['selected'])) {
					if(in_array($controller, $item['selected'])) {
						$liClass = 'active dropdown';
					}
				} else {
					if($controller == $item['controller']) {
						$liClass = 'active dropdown';
						// there is no elegant way of doing this... cos labeling is tied to current controller.. handling for admin and my profile navs
						if (isset($isCurrentUser) && array_key_exists('highlight', $item)) {
						if ($item['highlight'] == 'MyProfile') {
							$liClass = ($isCurrentUser)? 'active': '';
						} 
						if ($item['highlight'] == 'NotMyProfile') {
							$liClass = ($isCurrentUser)? 'dropdown': 'active dropdown';
						}
					}
					if (array_key_exists('otherControllers', $item)) {
						if ($liClass!='active') {
							$liClass = (in_array($controller, $item['otherControllers']))? 'active dropdown': 'dropdown';
						}
					}

					}
				}
				echo '<li class="' . $liClass . '">';
				echo '<a href="javascript:;">';
				echo sprintf($icon, $class);
				echo __($label);
				echo '<span class="caret"></span>';
				echo '</a>';
				echo '<ul class="sub-nav">';
				foreach($item['sub'] as $sub => $options) {
					$url = array('controller' => $options['controller'], 'action' => $options['action']);
					$url['plugin'] = array_key_exists('plugin', $options) ? $options['plugin'] : false;
					$a = $this->Html->link(sprintf($icon, strtolower($options['icon'])) . __($sub), $url, array('escape' => false));
					echo '<li>' . $a . '</li>';
				}
				echo '</ul>'; 
				echo '</li>';
			} else {
				$liClass = $controller == $item['controller'] ? 'active' : '';
				// there is no elegant way of doing this... cos labeling is tied to current controller.. handling for admin and my profile navs
				if (isset($isCurrentUser) && array_key_exists('highlight', $item)) {
					if ($item['highlight'] == 'MyProfile') {
						$liClass = ($isCurrentUser)? 'active': '';
					} 
					if ($item['highlight'] == 'NotMyProfile') {
						$liClass = ($isCurrentUser)? '': 'active';
					}
				}
				if (array_key_exists('otherControllers', $item)) {
					if ($liClass!='active') {
						$liClass = (in_array($controller, $item['otherControllers']))? 'active': '';
					}
				}
				echo '<li class="' . $liClass . '">';
				$url = array('controller' => $item['controller'], 'action' => $item['action']);
				$url['plugin'] = array_key_exists('plugin', $item) ? $item['plugin'] : false;
				echo $this->Html->link(sprintf($icon, strtolower($item['icon'])) . __($label), $url, array('escape' => false));
				echo '</li>';
			}
		}
		?>
	</ul>
</nav>