<?php
echo $this->Html->css('table', 'stylesheet', array('inline' => false));
echo $this->Html->css('pagination', 'stylesheet', array('inline' => false));
echo $this->Html->css('search', 'stylesheet', array('inline' => false));
echo $this->Html->script('search', false); 
echo $this->Html->script('Translations.app.translation',false);

$this->extend('/Layouts/tabs');

$this->assign('contentHeader', $contentHeader);
$this->assign('portletHeader', $portletHeader);
$this->assign('tabHeader', $contentHeader);

$this->start('tabActions');
	//echo $this->FormUtility->link('add', array('action' => $model, 'add'));
	if (true) {//$_add
		echo $this->FormUtility->link('add', array('action' => 'add'), array('class' => 'divider'));
	}
	if (true) {//$_execute
		//$this->Message->getLabel('general.general')
		// echo $this->Html->link('compile', array(), array('url' => $this->params['controller'] . '/compile' ,'class' => 'divider void', 'onclick' => 'Translation.compileFile(this)'));
	}
	echo $this->FormUtility->link('modal', array('type' => 'compile'));
	//echo $this->FormUtility->link('compileModal');
	//echo $this->FormUtility->link('deleteModal');

$this->end();

$this->prepend('portletBody');
$this->end();

$this->start('tabBody');
?>

<?php
echo $this->Form->input('language', array(
	'label' => false,
	'between' => '<div class="col-md-4">',
	'after' => '</div>',
	'div' => 'row select_row page-controls',
	'options' => $languageOptions,
	'value' => $selectedLang,
	'url' => $this->params['controller'] . '/' . $this->params['action'],
	'onchange' => 'Form.change(this)',
	'class' => 'form-control',
));
?>
	<?php echo $this->Form->create('Translation', array('url' => array('action'=>'../../Translations/index',$selectedLang),'plugin'=>false)); ?>
	<div class="row">
	  <div class="col-lg-6">
	    <div class="input-group">
			<?php echo $this->Form->input('SearchField', array(
			'id' => 'SearchField',
			'value' => $searchKey,
			'placeholder' => __("Search"),
			'class' => 'form-control',
			'div' => false,
			'label' => false));?>
	      <span class="input-group-btn">
	        <input id="searchbutton"class="btn btn-default" type="submit" value="Search">
	      </span>
	    </div><!-- /input-group -->
	  </div><!-- /.col-lg-6 -->
	</div><!-- /.row -->
	<?php echo $this->Form->end(); ?>

	<?php if (isset($data) && !empty($data)) { ?>
	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered table-highlight">
			<thead url="InstitutionSites/index">
				<tr>
					<th>
						<span class="left"><?php echo 'English'; ?></span>
					</th>
					<th>
						<span class="left"><?php echo $languageOptions[$selectedLang]; ?></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($data as $arrItems):
					$id = $arrItems['Translation']['id'];
					//$code = $arrItems['Translation']['code'];//$this->Utility->highlight($searchField,$arrItems['Translation']['code']);
					$name = $arrItems['Translation']['eng']; //$this->Utility->highlight($searchField,$arrItems['Translation']['english'].((isset($arrItems['InstitutionSiteHistory']['name']))?'<br>'.$arrItems['InstitutionSiteHistory']['name']:''));
					?>
					<tr row-id="<?php echo $id ?>">
						<td><?php echo $this->Html->link($name, array('action' => 'view', $id), array('escape' => false)); ?></td>
						<td><?php echo $arrItems['Translation'][$selectedLang]; ?></td>                   
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
	echo $this->element('layout/pagination');
	?>
	<?php } ?>

<?php 
echo $this->Js->writeBuffer(); ?>
<?php $this->end(); ?>

<?php
$this->start('modalBody');
	$url = array('action' => 'compile');
	echo $this->element('layout/compileModal', array('url' => $url, 'model' => $model));
$this->end();
?>
