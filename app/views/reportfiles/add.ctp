<div class="reportfiles form">
<?php echo $form->create('Reportfile');?>
	<fieldset>
 		<legend><?php __('Add Reportfile');?></legend>
	<?php
		echo $form->input('file_date');
		echo $form->input('file_name');
		echo $form->input('submitter_name');
		echo $form->input('submitter_contact_info');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Reportfiles', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('List Majoractivities', true), array('controller' => 'majoractivities', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Majoractivity', true), array('controller' => 'majoractivities', 'action' => 'add')); ?> </li>
		<li><?php echo $html->link(__('List Weeklyreports', true), array('controller' => 'weeklyreports', 'action' => 'index')); ?> </li>
		<li><?php echo $html->link(__('New Weeklyreport', true), array('controller' => 'weeklyreports', 'action' => 'add')); ?> </li>
	</ul>
</div>
