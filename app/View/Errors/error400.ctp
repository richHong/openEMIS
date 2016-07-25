<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

<!DOCTYPE html>
<html>
<body>
	<div id="container">
		<div id="content">
			<div class="col-md-11" style="margin-top:15px">
				<h5>
					<?php 
						echo sprintf(
							$this->Label->get('general.urlNotFound'),'<strong>"'.$url.'"</strong>'
						);
					?>
				</h5>
				<p>
					<?php 
        			$redirectUrl = $this->request->referer();
        			if ($this->request->referer() != '/') {
        				echo '<a href="'.$this->request->referer().'">'.$this->Label->get('general.backPrevPage').'</a>';
        			} else {
        				echo '<a href="'.$this->webroot.'">'.$this->Label->get('general.backPrevPage').'</a>';
        			}
        			?>
				</p>
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>