<?php $obj = $data['InstitutionSite']; ?>
<?php if(is_numeric($obj['latitude']) && is_numeric($obj['longitude']) != ''){ ?>

<div class="portlet">
	<div class="portlet-header"><h3>School Map</h3></div>
	<div class="portlet-content">
		<fieldset class="section_break" id="googlemap" style="padding-top: 10px;">
			<div>
				<iframe width=100% height=300px frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $obj['latitude']; ?>,+<?php echo $obj['longitude']; ?>+(<?php echo $obj['name']; ?>)&amp;hl=en&amp;ie=UTF8&amp;t=m&amp;ll=<?php echo $obj['latitude']; ?>,<?php echo $obj['longitude']; ?>&amp;spn=0.17081,0.44632&amp;z=11&amp;iwloc=&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?q=<?php echo $obj['latitude']; ?>,+<?php echo $obj['longitude']; ?>+(<?php echo $obj['name']; ?>)&amp;hl=en&amp;ie=UTF8&amp;t=m&amp;ll=<?php echo $obj['latitude']; ?>,<?php echo $obj['longitude']; ?>&amp;spn=0.17081,0.44632&amp;z=11&amp;iwloc=&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>
			</div>
		</fieldset>
	</div>
</div>


<?php } ?>