<div class="wrap">
	<h1>Pre Qualify Leads</h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php 
			settings_fields('PQL_settins_Option_group');
			do_settings_sections( 'pql_servay_settings' );
			submit_button();  
		?>
	</form>
</div>
